<?php

namespace App\Enums;

enum ReportStatus: string
{
    case MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';
    case DIPROSES = 'diproses';
    case DALAM_PERBAIKAN = 'dalam_perbaikan';
    case MENUNGGU_SPAREPART = 'menunggu_sparepart';
    case SELESAI = 'selesai';
    case DITOLAK = 'ditolak';

    public function label(): string
    {
        return match ($this) {
            self::MENUNGGU_VERIFIKASI => 'Menunggu Verifikasi',
            self::DIPROSES => 'Diproses',
            self::DALAM_PERBAIKAN => 'Dalam Perbaikan',
            self::MENUNGGU_SPAREPART => 'Menunggu Sparepart',
            self::SELESAI => 'Selesai',
            self::DITOLAK => 'Ditolak',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::MENUNGGU_VERIFIKASI => 'warning',
            self::DIPROSES => 'info',
            self::DALAM_PERBAIKAN => 'primary',
            self::MENUNGGU_SPAREPART => 'secondary',
            self::SELESAI => 'success',
            self::DITOLAK => 'danger',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::MENUNGGU_VERIFIKASI => 'bi-hourglass-split',
            self::DIPROSES => 'bi-gear-wide-connected',
            self::DALAM_PERBAIKAN => 'bi-wrench-adjustable',
            self::MENUNGGU_SPAREPART => 'bi-box-seam',
            self::SELESAI => 'bi-check-circle-fill',
            self::DITOLAK => 'bi-x-circle-fill',
        };
    }

    /**
     * Get valid next statuses from current status
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::MENUNGGU_VERIFIKASI => [self::DIPROSES, self::DITOLAK],
            self::DIPROSES => [self::DALAM_PERBAIKAN, self::DITOLAK],
            self::DALAM_PERBAIKAN => [self::MENUNGGU_SPAREPART, self::SELESAI],
            self::MENUNGGU_SPAREPART => [self::DALAM_PERBAIKAN],
            self::SELESAI => [],
            self::DITOLAK => [],
        };
    }

    public static function activeStatuses(): array
    {
        return [
            self::MENUNGGU_VERIFIKASI,
            self::DIPROSES,
            self::DALAM_PERBAIKAN,
            self::MENUNGGU_SPAREPART,
        ];
    }
}
