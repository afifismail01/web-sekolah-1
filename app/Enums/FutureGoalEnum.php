<?php
namespace App\Enums;

enum FutureGoalEnum: string
{
    case PNS = 'PNS';
    case TNI_POLRI = 'TNI/Polri';
    case DOCTOR = 'Dokter';
    case POLITICIAN = 'Politikus';
    case ENTREPRENEUR = 'Wiraswasta';
    case ARTIST = 'Seniman/Artis';
    case SCIENTIST = 'Ilmuwan';
    case CLERIC = 'Agamawan';
    case OTHER = 'Lainnya';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [$case->value => $case->value])->toArray();
    }
}
