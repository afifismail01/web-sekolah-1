<?php

namespace App\Enums;

enum AdmissionTrackEnum: string
{
    case PRESTASI = 'Jalur Prestasi';
    case MANDIRI = 'Jalur Reguler';
    case YATIM_DHUAFA = 'Jalur Beasiswa Yatim-Dhuafa';
    // case KERJASAMA = 'Jalur Kerjasama';
    // case YD_PRESTASI = 'Jalur Beasiswa Prestasi dan Yatim-Dhuafa';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [$case->value => $case->value])->toArray();
    }
}
