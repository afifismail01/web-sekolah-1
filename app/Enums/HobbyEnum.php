<?php
namespace App\Enums;

enum HobbyEnum: string
{
    case SPORT = 'Olahraga';
    case ART = 'Kesenian';
    case READ = 'Membaca';
    case WRITE = 'Menulis';
    case WALK = 'Jalan-Jalan';
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
