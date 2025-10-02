<?php

namespace App\Enums;

enum PaymentStatusEnum: string
{
    case Paid = 'paid';
    case Pending = 'pending';
    case Failed = 'failed';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [$case->value => $case->value])->toArray();
    }
}
