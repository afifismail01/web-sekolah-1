<?php
namespace App\Enums;

enum CategoryFileEnum: string
{
    case REQUIRED = 'required';
    case SUPPORT = 'support';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    public function label(): string
    {
        return match ($this) {
            self::REQUIRED => 'Wajib',
            self::SUPPORT => 'Pendukung',
        };
    }
}
