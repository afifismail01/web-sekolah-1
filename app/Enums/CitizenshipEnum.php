<?php
namespace App\Enums;

enum CitizenshipEnum: string
{
    case WNI = 'WNI';
    case WNA = 'WNA';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [$case->value => $case->value])->toArray();
    }
}
