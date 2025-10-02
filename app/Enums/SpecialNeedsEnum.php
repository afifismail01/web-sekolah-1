<?php
namespace App\Enums;

enum SpecialNeedsEnum: string
{
    case NOTHING = 'Tidak ada';
    case SLOW_LEARNER = 'Lamban belajar';
    case SPECIFIC_LEARNING_DIFFICULTIES = 'Kesulitan belajar spesifik';
    case COMMUNICATION_BREAKDOWN = 'Gangguan dalam komunikasi';
    case TALENTED = 'Berbakat/memiliki kecerdasan luar biasa';
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
