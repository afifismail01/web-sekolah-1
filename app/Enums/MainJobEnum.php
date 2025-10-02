<?php
namespace App\Enums;

enum MainJobEnum: string
{
    case UNEMPLOYED = 'Tidak bekerja';
    case RETIRED = 'Pensiunan';
    case CIVIL_SERVANT = 'PNS';
    case MILITARY = 'TNI/POLRI';
    case TEACHER = 'Guru/Dosen';
    case PRIVATE_EMPLOYEE = 'Pegawai Swasta';
    case ENTREPRENEUR = 'Wiraswasta';
    case LAWYER = 'Pengacara/Jaksa/Hakim/Notaris';
    case ARTIST = 'Seniman/Pelukis/Artis/Sejenisnya';
    case MEDICAL = 'Dokter/Bidan/Perawat';
    case PILOT = 'Pilot/Pramugara';
    case TRADER = 'Pedagang';
    case FARMER = 'Petani/Peternak';
    case FISHERMAN = 'Nelayan';
    case LABORER = 'Buruh (tani/pabrik/bangunan)';
    case DRIVER = 'Sopir/Masinis/Kondektur';
    case POLITICIAN = 'Politikus';
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
