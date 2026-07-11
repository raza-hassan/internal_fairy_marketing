<?php

namespace App\Enums;

enum LeadFeedBackStatus: string
{
    case CONTACTED = 'contacted';
    case QUALIFIED = 'qualified';
    case SALE_CLOSED = 'sale-closed';    // using harcoded in lead.js file (sale-closed)

    // case NO_RESPONSE = 'no-response';
    // case NOT_QUALIFIED = 'not_qualified';
    case NOT_INTERESTED = 'not-interested';

    public function label(): string
    {
        return match ($this) {
            self::CONTACTED => 'Contacted',
            self::QUALIFIED => 'Qualified',
            self::SALE_CLOSED => 'Sale Closed',

            // self::NO_RESPONSE => 'No Response',
            // self::NOT_QUALIFIED => 'Not Qualified',
            self::NOT_INTERESTED => 'Not Interested',
        };
    }

    public function facebookEvent(): ?string
    {
        return match ($this) {
            self::CONTACTED => 'Contact',
            self::QUALIFIED => 'Lead',
            self::SALE_CLOSED => 'Purchase',

            // Custom but facebook not accept these events
            self::NOT_INTERESTED => 'Not Interested',
            // self::NO_RESPONSE => null,
            // self::NOT_QUALIFIED => null,
        };
    }

    public function shouldSyncToFacebook(): bool
    {
        return $this->facebookEvent() !== null;
    }

}
