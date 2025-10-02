<?php

namespace App\Enums;

enum StageNameEnum: string
{
    case REGISTRATION = 'Input data dan Pembayaran';
    case TEST = 'Tes Ujian Masuk';
    case ANNOUNCEMENT = 'Pengumuman';

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [$case->value => $case->value])->toArray();
    }
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
