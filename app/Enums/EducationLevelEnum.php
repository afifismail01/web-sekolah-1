<?php

namespace App\Enums;

enum EducationLevelEnum: string
{
    case PAUD = 'PAUD';
    case MTs = 'MTs';
    case MA = 'MA';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [$case->value => $case->value])->toArray();
    }
}
