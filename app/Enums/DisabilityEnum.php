<?php
namespace App\Enums;

enum DisabilityEnum: string
{
    case NOTHING = 'Tidak ada';
    case TUNA_NETRA = 'Tuna netra';
    case TUNA_RUNGU = 'Tuna rungu';
    case TUNA_DAKSA = 'Tuna daksa';
    case TUNA_GRAHITA = 'Tuna grahita';
    case TUNA_LARAS = 'Tuna laras';
    case TUNA_WICARA = 'Tuna wicara';
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
