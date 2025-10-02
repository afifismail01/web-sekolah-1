<?php
namespace App\Enums;

enum LifeStatusEnum: string
{
    case ALIVE = 'Masih hidup';
    case DECEASED = 'Sudah meninggal';
    case UNKNOWN = 'Tidak diketahui';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [$case->value => $case->value])->toArray();
    }
}
