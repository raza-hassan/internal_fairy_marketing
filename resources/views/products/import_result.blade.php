@extends('layouts.app', ['activePage' => 'inventory', 'titlePage' => __('Products Import')])

@section('content')

@include('products.sidebar')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    .summary-card {
        background: #fff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 22px;
        display: flex;
        align-items: center;
        gap: 18px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, .05);
        transition: all .25s ease;
        height: 100%;
    }

    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 30px rgba(0, 0, 0, .10);
    }

    .summary-card .icon {
        width: 62px;
        height: 62px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #fff;
        flex-shrink: 0;
    }

    .summary-card .content {
        flex: 1;
    }

    .summary-card .content h2 {
        margin: 0;
        font-size: 30px;
        font-weight: 700;
        color: #1f2937;
        line-height: 1;
    }

    .summary-card .content p {
        margin: 8px 0 0;
        font-size: 14px;
        color: #6b7280;
        font-weight: 600;
    }

    /* Icon Colors */
    .icon.info {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    .icon.success {
        background: linear-gradient(135deg, #22c55e, #16a34a);
    }

    .icon.warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .icon.danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .icon.secondary {
        background: linear-gradient(135deg, #6b7280, #4b5563);
    }
</style>

<div class="ps-main__wrapper">
    <section class="ps-items-listing">

        <div class="ps-section__content">

            <div class="card border-0 shadow-sm">
                <div class="card-body py-4 px-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center"
                                        style="width:60px;height:60px;">
                                        <i class="fa fa-check fa-2x"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="mb-1 mx-2 font-weight-bold">
                                        Product Import Completed
                                    </h3>
                                    <p class="text-muted mb-0 mx-2">
                                        Your file has been processed successfully. Review the summary and updated
                                        products
                                        below.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-right text-center mt-3 mt-md-0">
                            <i class="fa fa-file-excel-o fa-4x text-success mb-2"></i>
                            <br>
                            <span class="badge badge-success badge-pill px-3 py-2">
                                Completed
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 font-weight-bold">
                                Import Summary
                            </h4>
                            <small class="text-muted">
                                Overview of the imported file and applied settings.
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-primary mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Import Mode</strong>
                                <div class="text-muted mt-1">
                                    {{ $valueSource === 'file'
                                    ? 'Exact Values (No Calculation)'
                                    : 'Recalculate Values' }}
                                </div>
                            </div>
                            @if($valueSource !== 'file')
                            <div class="col-md-6 mb-3">
                                <strong>Calculation Formula</strong>
                                <div class="text-muted mt-1">
                                    Down Payment:
                                    <strong>{{ number_format($resolved['down_payment_percent'] * 100,2) }}%</strong>
                                    <br>
                                    Possession:
                                    <strong>{{ number_format($resolved['possession_percent'] * 100,2) }}%</strong>
                                    <br>
                                    Remaining:
                                    <strong>{{ number_format((1 - $resolved['down_payment_percent'] -
                                        $resolved['possession_percent']) * 100,2) }}%</strong>
                                    <br>
                                    No. Quarterly Installments:
                                    <strong>{{ $resolved['quarterly_installments_count'] }}</strong>
                                    <br>
                                    No. Monthly Installments:
                                    <strong>{{ $resolved['monthly_installments_count'] }}</strong>
                                    <br>
                                    Corner:
                                    <strong>
                                        @if($resolved['corner_amount'] !== null)
                                        Flat Rs. {{ number_format($resolved['corner_amount']) }}
                                        @elseif($resolved['corner_percent'] !== null)
                                        {{ number_format($resolved['corner_percent'] * 100,2) }}%
                                        @else
                                        Row-wise (From File)
                                        @endif
                                    </strong>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                            <div class="summary-card">
                                <div class="icon info">
                                    <i class="fa fa-list"></i>
                                </div>
                                <div class="content">
                                    <h2>{{ $summary['total_rows'] }}</h2>
                                    <p>Total Rows</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                            <div class="summary-card">
                                <div class="icon success">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                                <div class="content">
                                    <h2>{{ $summary['updated'] }}</h2>
                                    <p>Successfully Updated</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                            <div class="summary-card">
                                <div class="icon warning">
                                    <i class="fa fa-exclamation-circle"></i>
                                </div>
                                <div class="content">
                                    <h2>{{ $summary['skipped_not_available'] }}</h2>
                                    <p>Not Available</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                            <div class="summary-card">
                                <div class="icon danger">
                                    <i class="fa fa-search"></i>
                                </div>
                                <div class="content">
                                    <h2>{{ $summary['skipped_not_found'] }}</h2>
                                    <p>Products Not Found</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                            <div class="summary-card">
                                <div class="icon secondary">
                                    <i class="fa fa-ban"></i>
                                </div>
                                <div class="content">
                                    <h2>{{ $summary['skipped_invalid'] }}</h2>
                                    <p>Invalid Rows</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (!empty($summary['errors']))
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">
                                <i class="fa fa-exclamation-triangle mr-2"></i>
                                Import Errors ({{ count($summary['errors']) }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="mb-0 pl-3">
                                @foreach ($summary['errors'] as $error)
                                <li class="mb-2">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex align-items-center mt-15 mb-15">
                                <div>
                                    <h4 class="mb-1 font-weight-bold">
                                        <i class="fa fa-list-alt text-primary mr-2"></i>
                                        Updated Products
                                    </h4>
                                    <small class="text-muted">
                                        Successfully updated products from the imported file.
                                    </small>
                                </div>
                                <span class="badge badge-success px-3 py-2 mt-10">
                                    {{ $products->count() }} Updated
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Unit No</th>
                                            <th>Covered</th>
                                            <th>Price / Sft</th>
                                            <th>Corner</th>
                                            <th>Corner Amount</th>
                                            <th>Total Price</th>
                                            <th>Down Payment</th>
                                            <th>Possession</th>
                                            <th>Remaining</th>
                                            <th>Monthly</th>
                                            <th>Quarterly</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($products as $product)
                                        <tr>
                                            <td>
                                                <strong>{{ $product->unitid }}</strong>
                                            </td>
                                            <td>
                                                {{ number_format((float)$product->carea) }}
                                            </td>
                                            <td>
                                                {{ number_format((float)$product->psft) }}
                                            </td>
                                            <td class="text-center">
                                                @if($product->corner)
                                                <span class="badge bg-warning py-1 px-2">Yes</span>
                                                @else
                                                <span class="badge bg-secondary py-1 px-2">No</span>
                                                @endif
                                            </td>

                                            <td>
                                                {{ number_format((float)$product->corner_amt) }}
                                            </td>
                                            <td>
                                                <strong>
                                                    {{ number_format((float)$product->price) }}
                                                </strong>
                                            </td>
                                            <td>
                                                {{ number_format((float)$product->dpayment) }}
                                            </td>
                                            <td>
                                                {{ number_format((float)$product->posamount) }}
                                            </td>
                                            <td>
                                                {{ number_format((float)$product->rpayment) }}
                                            </td>

                                            <td>
                                                {{ number_format((float)$product->moninstallment) }}
                                            </td>
                                            <td>
                                                {{ number_format((float)$product->qtrinstallment) }}
                                            </td>

                                            <td>


                                                @if(strtolower($product->status) == 'available')
                                                <span class="badge bg-success">
                                                    {{ $product->status }}
                                                </span>
                                                @elseif(strtolower($product->status) == 'hold')
                                                <span class="badge bg-warning">
                                                    {{ $product->status }}
                                                </span>
                                                @else
                                                <span class="badge bg-secondary">
                                                    {{ $product->status }}
                                                </span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="11" class="text-center py-5">
                                                <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                                <br>
                                                <strong>No products were updated.</strong>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="text-right mt-4">
                        <a href="{{ route('import.products') }}" class="btn btn-primary btn-lg">
                            <i class="fa fa-upload mr-2"></i>
                            Import Another File
                        </a>
                    </div>
                </div>
            </div>

            <div id="importAccordion" class="mt-4">
                {{-- Not Available Products --}}
                @if(count($summary['not_available_records']))
                <div class="card shadow-sm mb-3">

                    <div class="card-header bg-warning text-white toggle-card" style="cursor:pointer;">

                        <div class="d-flex justify-content-between">

                            <span>
                                <i class="fa fa-exclamation-triangle mr-2"></i>
                                Not Available Products
                            </span>

                            <span>

                                {{ count($summary['not_available_records']) }}

                                <i class="fa fa-chevron-down ml-2"></i>

                            </span>

                        </div>

                    </div>

                    <div class="toggle-body" style="display:none;">

                        <table class="table table-bordered mb-0">

                            <thead class="thead-light">

                                <tr>
                                    <th width="120">Row</th>
                                    <th>Unit No</th>
                                    <th width="180">Status</th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach($summary['not_available_records'] as $row)

                                <tr>

                                    <td>{{ $row['row'] }}</td>

                                    <td>{{ $row['unitid'] }}</td>

                                    <td>

                                        <span class="badge badge-warning">

                                            {{ ucfirst($row['status']) }}

                                        </span>

                                    </td>

                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>
                @endif
                {{-- Products Not Found --}}
                @if(count($summary['not_found_records']))
                <div class="card shadow-sm mb-3">

                    <div class="card-header bg-danger text-white toggle-card" style="cursor:pointer;">

                        <div class="d-flex justify-content-between align-items-center">

                            <span>

                                <i class="fa fa-search mr-2"></i>

                                Products Not Found

                            </span>

                            <span>

                                <span class="badge badge-light">

                                    {{ count($summary['not_found_records']) }}

                                </span>

                                <i class="fa fa-chevron-down ml-2 toggle-icon"></i>

                            </span>

                        </div>

                    </div>

                    <div class="toggle-body" style="display:none;">

                        <div class="card-body p-0">

                            <table class="table table-striped table-hover mb-0">

                                <thead class="thead-light">

                                    <tr>

                                        <th width="120">Row</th>
                                        <th>Unit No</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($summary['not_found_records'] as $row)

                                    <tr>

                                        <td>{{ $row['row'] }}</td>

                                        <td>{{ $row['unitid'] }}</td>

                                    </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>
                @endif
            </div>

        </div>

    </section>
</div>


<script>
    $(function () {
        $('.toggle-card').on('click', function () {
            var body = $(this).next('.toggle-body');
            var icon = $(this).find('.toggle-icon');
            body.slideToggle(250);
            icon.toggleClass('fa-chevron-down fa-chevron-up');
        });
    });
</script>
@endsection