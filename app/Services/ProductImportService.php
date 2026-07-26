<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImportSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductImportService
{
    public const MODE_CALCULATE = 'calculate';
    public const MODE_FILE = 'file';

    protected array $config;

    /** Resolved settings for THIS import run (after 3-tier fallback) — sirf 'calculate' mode me use hoti hain */
    protected array $resolved;

    protected string $valueSource;

    protected array $summary = [
        'total_rows'            => 0,
        'updated'               => 0,
        'skipped_not_found'     => 0,
        'skipped_not_available' => 0,
        'skipped_invalid'       => 0,
        'errors'                => [],

        'not_available_records' => [],
        'not_found_records' => [],
        'invalid_records' => [],
    ];


    protected Collection $updatedProducts;

    public function __construct()
    {
        $this->config = config('product_import');
        $this->updatedProducts = collect();
    }

    /**
     * $valueSource: 'calculate' (default) ya 'file'
     *   'calculate' => file se sirf unit_id, covered_area, price_per_sft, corner chahiye;
     *                  Amount/Total/Down/Remaining/Quarterly/Possession calculate hoti hain
     *   'file'      => file me woh sab columns bhi hona zaroori hai, seedha unhi se uthai jati hain
     *
     * $overrides — sirf 'calculate' mode me matter karta hai (sab optional/null):
     * [
     *   'down_payment_percent'         => 0.25 | null,   // decimal, NOT "25"
     *   'possession_percent'           => 0.10 | null,
     *   'quarterly_installments_count' => 24   | null,
     *   'corner_amount'                => 2000000.0 | null,
     *   'corner_percent'               => 0.10 | null,
     * ]
     */
    public function import(UploadedFile $file, string $valueSource = self::MODE_CALCULATE, array $overrides = []): array
    {
        $this->valueSource = in_array($valueSource, [self::MODE_CALCULATE, self::MODE_FILE], true)
            ? $valueSource
            : $this->config['default_value_source'];

        $this->resolved = $this->resolveSettings($overrides);

        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, false);

        if (empty($rows)) {
            throw new \RuntimeException('Unable to read the uploaded file. Please ensure the file is not empty and is in a valid CSV or Excel format.');
        }

        $rawHeaders = array_shift($rows);
        $headers = array_map(fn($h) => $this->normalizeHeader((string) $h), $rawHeaders);


        foreach ($rows as $rowIndex => $rawRow) {

            $rowIndex += 2;

            $isEmptyRow = collect($rawRow)->every(fn($v) => trim((string) $v) === '');
            if ($isEmptyRow) {
                continue;
            }

            $this->summary['total_rows']++;

            $normalizedRow = [];
            foreach ($headers as $i => $key) {
                $normalizedRow[$key] = $rawRow[$i] ?? null;
            }

            try {
                $this->processRow($normalizedRow, $rowIndex);
            } catch (\Throwable $e) {
                $this->summary['errors'][] = 'Row ' . ($rowIndex + 2) . ': ' . $e->getMessage();
            }
        }

        return [
            'summary'           => $this->summary,
            'updated_products'  => $this->updatedProducts,
            'resolved_settings' => $this->resolved,
            'value_source'      => $this->valueSource,
        ];
    }

    /**
     * "Price P/Sft" -> "pricepsft" (sirf a-z 0-9 rakha jata hai)
     */
    protected function normalizeHeader(string $header): string
    {
        return strtolower(preg_replace('/[^A-Za-z0-9]/', '_', $header));
    }

    /**
     * Priority: form override > product_import_settings (DB) > hardcoded config default
     */
    protected function resolveSettings(array $overrides): array
    {
        $db = ProductImportSetting::current();
        $defaults = $this->config['defaults'];

        $pick = function ($override, $dbValue, $default) {
            if ($override !== null && $override !== '') {
                return (float) $override;
            }
            return $dbValue !== null ? (float) $dbValue : $default;
        };

        return [
            'down_payment_percent' => $pick(
                $overrides['down_payment_percent'] ?? null,
                $db->down_payment_percent,
                $defaults['down_payment_percent']
            ),
            'possession_percent' => $pick(
                $overrides['possession_percent'] ?? null,
                $db->possession_percent,
                $defaults['possession_percent']
            ),
            'monthly_installments_count' => (int) $pick(
                $overrides['monthly_installments_count'] ?? null,
                $db->monthly_installments_count,
                $defaults['monthly_installments_count']
            ),
            'quarterly_installments_count' => (int) $pick(
                $overrides['quarterly_installments_count'] ?? null,
                $db->quarterly_installments_count,
                $defaults['quarterly_installments_count']
            ),
            // Yeh dono jaan-boojh kar null reh sakte hain — matlab "file ki apni
            // corner value use karo", is liye default (null) ko override nahi karte
            'corner_amount' => $pick(
                $overrides['corner_amount'] ?? null,
                $db->corner_amount,
                $defaults['corner_amount']
            ),
            'corner_percent' => $pick(
                $overrides['corner_percent'] ?? null,
                $db->corner_percent,
                $defaults['corner_percent']
            ),
        ];
    }

    protected function processRow(array $normalizedRow, int $rowNumber): void
    {
        $cols = $this->config['columns'];

        $unitid = $normalizedRow[$cols['unitid']] ?? null;

        if ($unitid === null || trim((string) $unitid) === '') {
            $this->summary['skipped_invalid']++;
            return;
        }
        $unitid = trim((string) $unitid);

        $coveredArea = $this->toNumber($normalizedRow[$cols['carea']] ?? null);
        $pricePerSft = $this->toNumber($normalizedRow[$cols['psft']] ?? null);
        $cornerRaw   = $this->toNumber($normalizedRow[$cols['corner']] ?? null);

        if ($coveredArea <= 0 || $pricePerSft <= 0) {
            $this->summary['skipped_invalid']++;
            $this->summary['invalid_records'][] = [
                'row' => $rowNumber,
                'reason' => 'Covered Area is missing',
            ];
            return;
        }

        // 'file' mode me extra columns bhi required hain — agar missing/invalid
        // hon to yeh row "invalid" ban jati hai (calculate nahi karte, silently 0 bhi nahi daalte)
        $fileValues = null;
        if ($this->valueSource === self::MODE_FILE) {
            $fileValues = $this->extractFileValues($normalizedRow);
            if ($fileValues === null) {
                $this->summary['skipped_invalid']++;
                return;
            }
        }

        $product = Product::where($this->config['match']['db_column'], $unitid)->first();

        if (!$product) {
            $this->summary['skipped_not_found']++;
            $this->summary['not_found_records'][] = [
                'unitid' => $unitid,
                'row'     => $rowNumber,
            ];
            return;
        }

        // If the Corner column is missing or empty in the imported file, retain the existing Corner value from the database instead of overwriting it.
        $cornerRaw = $cornerRaw > 0
            ? $cornerRaw
            : (($product->corner || $product->corner_amt) ? (float) $product->corner_amt : 0.0);

        $statusColumn   = $this->config['status_column'];
        $currentStatus  = strtolower(trim((string) $product->{$statusColumn}));
        $requiredStatus = strtolower(trim((string) $this->config['status_value_for_update']));

        if ($currentStatus !== $requiredStatus) {
            $this->summary['skipped_not_available']++;
            $this->summary['not_available_records'][] = [
                'unitid' => $unitid,
                'row'     => $rowNumber,
                'status'  => $currentStatus,
            ];

            return;
        }

        $values = $this->valueSource === self::MODE_FILE
            ? $this->buildFromFile($cornerRaw, $fileValues, $product)
            : $this->calculate($coveredArea, $pricePerSft, $cornerRaw);

        // Har row apne transaction me — kisi wajah se save beech me fail ho
        // to sirf isi row ka partial change rollback hota hai, baqi rows
        // (aur poori import) untouched rehti hai.
        DB::transaction(function () use ($product, $coveredArea, $pricePerSft, $values) {
            $product->fill(array_merge([
                'carea'  => (int)$coveredArea,
                'psft' => (int) $pricePerSft,
            ], $values));

            $product->save();
        });

        $this->summary['updated']++;
        $this->updatedProducts->push($product->fresh());
    }

    /**
     * 'file' mode k liye required extra columns file se nikalta hai.
     * Koi bhi column missing/non-numeric ho to null wapis karta hai (row invalid ban jati hai).
     */
    protected function extractFileValues(array $normalizedRow): ?array
    {
        $values = [];

        foreach ($this->config['file_value_columns'] as $dbColumn => $fileColumn) {
            if (! array_key_exists($fileColumn, $normalizedRow)) {
                return null;
            }

            $raw = $normalizedRow[$fileColumn];
            if ($raw === null || trim((string) $raw) === '') {
                return null;
            }

            $values[$dbColumn] = $this->toNumber($raw);
        }

        return $values;
    }

    /**
     * 'file' mode: koi calculation nahi — file ki values seedha DB me jati hain.
     * corner flag + corner_amount hamesha file k Corner column se (isi liye
     * 'file' mode me bhi consistent rehta hai).
     */
    protected function buildFromFile(float $cornerRawAmount, array $fileValues, Product $product): array
    {
        $isCorner = $cornerRawAmount > 0;

        $remain_amount = (int) round($fileValues['rpayment']);
        $monthly_installment = (int) round($remain_amount / $product->nummoninstallment);

        return [
            'corner'            => $isCorner ? 1 : 0,
            'corner_amt'        => (int) round($isCorner ? $cornerRawAmount : 0),
            'price'             => (int) round($fileValues['price']),
            'dpayment'          => (int) round($fileValues['dpayment']),
            'rpayment'          => $remain_amount,
            'qtrinstallment'    => (int) round($fileValues['qtrinstallment']),
            'posamount'         => (int) round($fileValues['posamount']),
            'moninstallment'    =>  $monthly_installment
        ];
    }

    /**
     * "1,420" jaisi comma-formatted Excel values bhi handle kar leta hai.
     */
    protected function toNumber($value): float
    {
        if ($value === null) {
            return 0.0;
        }

        $clean = str_replace([',', ' '], '', (string) $value);

        return is_numeric($clean) ? (float) $clean : 0.0;
    }

    /**
     * 'calculate' mode — yehi core formula hai, is method k alawa kahin aur math nahi hoti.
     *
     *   amount             = covered_area x price_per_sft
     *   corner_amount       = corner_amount (flat)   OR
     *                        base_amount x corner_percent   OR
     *                        file ki apni raw Corner value   [default]
     *   total_amount       = amount + corner_amount (agar corner hai)
     *   down_payment       = total_amount x down_payment_percent
     *   possession_amount  = total_amount x possession_percent
     *   remaining_amount   = total_amount x (1 - down% - possession%)
     *   quarterly_amount   = remaining_amount / quarterly_installments_count
     */
    protected function calculate(float $coveredArea, float $pricePerSft, float $cornerRawAmount): array
    {

        $isCorner = $cornerRawAmount > 0;

        $baseAmount = $coveredArea * $pricePerSft;

        if ($this->resolved['corner_amount'] !== null) {
            $cornerPremium = $isCorner ? (float) $this->resolved['corner_amount'] : 0.0;
        } elseif ($this->resolved['corner_percent'] !== null) {
            $cornerPremium = $isCorner ? $baseAmount * (float) $this->resolved['corner_percent'] : 0.0;
        } else {
            $cornerPremium = $isCorner ? $cornerRawAmount : 0.0;
        }

        $totalAmount = $baseAmount + $cornerPremium;

        $downPercent       = $this->resolved['down_payment_percent'];
        $possessionPercent = $this->resolved['possession_percent'];
        $remainingPercent  = max(0, 1 - $downPercent - $possessionPercent);

        $downPayment      =  (int) round($totalAmount * $downPercent);
        $possessionAmount =  (int) round($totalAmount * $possessionPercent);
        $remainingAmount  =  (int) round($totalAmount * $remainingPercent);

        $quarterlyCount = $this->resolved['quarterly_installments_count'];
        $monthlyCount = $this->resolved['monthly_installments_count'];

        $monthly_installment = (int) round($remainingAmount / $monthlyCount);
        $quarterlyInstallmentAmount = $quarterlyCount > 0 ?  (int) round($remainingAmount / $quarterlyCount) : 0;

        // dd([
        //     'downPayment' => $downPayment,
        //     'possessionAmount' => $possessionAmount,
        //     'remainingAmount' => $remainingAmount,
        //     'quarterlyCount' => $quarterlyCount,
        //     'monthlyCount' => $monthlyCount,
        //     'monthly_installment' => $monthly_installment,
        //     'quarterlyInstallmentAmoun' => $quarterlyInstallmentAmount,
        // ]);

        return [
            'corner'                        => $isCorner ? 1 : 0,
            'corner_amt'                    => round($cornerPremium, 2),
            'price'                         => round($totalAmount, 2),
            'dpayment'                      => round($downPayment, 2),
            'rpayment'                      => round($remainingAmount, 2),
            'qtrinstallment'                => round($quarterlyInstallmentAmount, 2),
            'posamount'                     => round($possessionAmount, 2),
            'moninstallment'                =>  $monthly_installment
        ];
    }
}
