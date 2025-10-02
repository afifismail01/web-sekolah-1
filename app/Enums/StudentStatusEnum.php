<?php
namespace App\Enums;
enum StudentStatusEnum: string
{
    // Status pada pengumuman
    case ACCEPTED = 'Diterima';
    case DENIED = 'Ditolak';
    case RESERVES = 'Cadangan';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [$case->value => $case->value])->toArray();
    }
}
