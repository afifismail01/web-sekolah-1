<?php

namespace App\Enums;

enum GenderEnum: string
{
    case MALE = 'Laki laki';
    case FEMALE = 'Perempuan';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [$case->value => $case->value])->toArray();
    }
}
