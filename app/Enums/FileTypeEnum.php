<?php

namespace App\Enums;

enum FileTypeEnum: string
{
    case SELF_PHOTO = 'Foto calon siswa';
    case BIRTH_CERTIFICATE = 'Akte kelahiran';
    case FAMILY_CARD = 'Kartu keluarga';
    case RAPORT = 'Rapot';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    public function label(): string
    {
        return match ($this) {
            self::SELF_PHOTO => 'Foto Calon Siswa',
            self::BIRTH_CERTIFICATE => 'Scan Akta Kelahiran',
            self::FAMILY_CARD => 'Scan Kartu Keluarga',
            self::RAPORT => 'Scan Raport Terakhir',
        };
    }
}
