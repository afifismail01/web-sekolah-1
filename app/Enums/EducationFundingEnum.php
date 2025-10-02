<?php
namespace App\Enums;

enum EducationFundingEnum: string
{
    case PARENTS = 'Orangtua';
    case GUARDIAN_OR_PARENTS = 'Wali/Orangtua asuh';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [$case->value => $case->value])->toArray();
    }
}
