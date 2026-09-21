<?php

namespace App\Enums;

enum ReportPriority: string
{
    case RENDAH = 'rendah';
    case SEDANG = 'sedang';
    case TINGGI = 'tinggi';
    case DARURAT = 'darurat';

    public function label(): string
    {
        return match ($this) {
            self::RENDAH => 'Rendah',
            self::SEDANG => 'Sedang',
            self::TINGGI => 'Tinggi',
            self::DARURAT => 'Darurat',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::RENDAH => 'success',
            self::SEDANG => 'warning',
            self::TINGGI => 'orange',
            self::DARURAT => 'danger',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::RENDAH => 'bi-arrow-down-circle',
            self::SEDANG => 'bi-dash-circle',
            self::TINGGI => 'bi-arrow-up-circle',
            self::DARURAT => 'bi-exclamation-triangle-fill',
        };
    }

    public function order(): int
    {
        return match ($this) {
            self::RENDAH => 1,
            self::SEDANG => 2,
            self::TINGGI => 3,
            self::DARURAT => 4,
        };
    }
}
