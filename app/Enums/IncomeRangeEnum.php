<?php
namespace App\Enums;

enum IncomeRangeEnum: string
{
    case BELOW_800K = 'Dibawah 800.000';
    case FROM_800K_TO_1_2M = '800.001 - 1.200.000';
    case FROM_1_2M_TO_1_8M = '1.200.001 - 1.800.000';
    case FROM_1_8M_TO_2_5M = '1.800.001 - 2.500.000';
    case FROM_2_5M_TO_3_5M = '2.500.001 - 3.500.000';
    case FROM_3_5M_TO_4_8M = '3.500.001 - 4.800.000';
    case FROM_4_8M_TO_6_5M = '4.800.001 - 6.500.000';
    case FROM_6_5M_TO_10M = '6.500.001 - 10.000.000';
    case FROM_10M_TO_20M = '10.000.001 - 20.000.000';
    case ABOVE_20M = 'Diatas 20.000.000';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [$case->value => $case->value])->toArray();
    }
}
