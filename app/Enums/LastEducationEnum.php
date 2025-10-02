<?php
namespace App\Enums;

enum LastEducationEnum: string
{
    case NOT_SCHOOLED = 'Tidak bersekolah';
    case SD = 'SD/sederajat';
    case SMP = 'SMP/sederajat';
    case SMA = 'SMA/sederajat';
    case D1 = 'D1';
    case D2 = 'D2';
    case D3 = 'D3';
    case D4_S1 = 'D4/S1';
    case S2 = 'S2';
    case S3 = 'S3';
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
