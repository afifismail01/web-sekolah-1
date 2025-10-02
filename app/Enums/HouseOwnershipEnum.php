<?php
namespace App\Enums;

enum HouseOwnershipEnum: string
{
    case ONE_OWN = 'Milik sendiri';
    case PARENTS_HOUSE = 'Rumah orang tua';
    case RELATIVE_HOUSE = 'Rumah kerabat';
    case OFFICIAL_RESIDENCE = 'Rumah dinas';
    case RENTAL_HOUSE = 'Rumah sewa/kontrak';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [$case->value => $case->value])->toArray();
    }
}
