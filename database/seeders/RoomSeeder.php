<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            // Lantai 1
            ['name' => 'IGD', 'floor' => 'Lantai 1', 'building' => 'Gedung Utama', 'unit' => 'Instalasi Gawat Darurat'],
            ['name' => 'Lobby Utama', 'floor' => 'Lantai 1', 'building' => 'Gedung Utama', 'unit' => 'Umum'],
            ['name' => 'Apotek', 'floor' => 'Lantai 1', 'building' => 'Gedung Utama', 'unit' => 'Farmasi'],
            ['name' => 'Laboratorium', 'floor' => 'Lantai 1', 'building' => 'Gedung Utama', 'unit' => 'Penunjang Medis'],
            ['name' => 'Radiologi', 'floor' => 'Lantai 1', 'building' => 'Gedung Utama', 'unit' => 'Penunjang Medis'],
            ['name' => 'Pendaftaran', 'floor' => 'Lantai 1', 'building' => 'Gedung Utama', 'unit' => 'Administrasi'],

            // Lantai 2
            ['name' => 'Poli Umum', 'floor' => 'Lantai 2', 'building' => 'Gedung Utama', 'unit' => 'Rawat Jalan'],
            ['name' => 'Poli Gigi', 'floor' => 'Lantai 2', 'building' => 'Gedung Utama', 'unit' => 'Rawat Jalan'],
            ['name' => 'Poli Anak', 'floor' => 'Lantai 2', 'building' => 'Gedung Utama', 'unit' => 'Rawat Jalan'],
            ['name' => 'Poli Jantung', 'floor' => 'Lantai 2', 'building' => 'Gedung Utama', 'unit' => 'Rawat Jalan'],
            ['name' => 'Poli Mata', 'floor' => 'Lantai 2', 'building' => 'Gedung Utama', 'unit' => 'Rawat Jalan'],

            // Lantai 3
            ['name' => 'Ruang Rawat Inap Kelas 1', 'floor' => 'Lantai 3', 'building' => 'Gedung Utama', 'unit' => 'Rawat Inap'],
            ['name' => 'Ruang Rawat Inap Kelas 2', 'floor' => 'Lantai 3', 'building' => 'Gedung Utama', 'unit' => 'Rawat Inap'],
            ['name' => 'Ruang Rawat Inap Kelas 3', 'floor' => 'Lantai 3', 'building' => 'Gedung Utama', 'unit' => 'Rawat Inap'],
            ['name' => 'Nurse Station Lt.3', 'floor' => 'Lantai 3', 'building' => 'Gedung Utama', 'unit' => 'Rawat Inap'],

            // Lantai 4
            ['name' => 'ICU', 'floor' => 'Lantai 4', 'building' => 'Gedung Utama', 'unit' => 'Perawatan Intensif'],
            ['name' => 'NICU', 'floor' => 'Lantai 4', 'building' => 'Gedung Utama', 'unit' => 'Perawatan Intensif'],
            ['name' => 'Ruang Operasi 1', 'floor' => 'Lantai 4', 'building' => 'Gedung Utama', 'unit' => 'Bedah'],
            ['name' => 'Ruang Operasi 2', 'floor' => 'Lantai 4', 'building' => 'Gedung Utama', 'unit' => 'Bedah'],

            // Gedung B
            ['name' => 'Ruang Rapat', 'floor' => 'Lantai 1', 'building' => 'Gedung B', 'unit' => 'Administrasi'],
            ['name' => 'Kantor Administrasi', 'floor' => 'Lantai 1', 'building' => 'Gedung B', 'unit' => 'Administrasi'],
            ['name' => 'Kantin', 'floor' => 'Lantai 1', 'building' => 'Gedung B', 'unit' => 'Umum'],
            ['name' => 'Musholla', 'floor' => 'Lantai 1', 'building' => 'Gedung B', 'unit' => 'Umum'],
            ['name' => 'Gudang Farmasi', 'floor' => 'Lantai 2', 'building' => 'Gedung B', 'unit' => 'Farmasi'],
            ['name' => 'Ruang Laundry', 'floor' => 'Lantai 1', 'building' => 'Gedung B', 'unit' => 'Penunjang'],

            // Area Luar
            ['name' => 'Area Parkir', 'floor' => '-', 'building' => 'Area Luar', 'unit' => 'Umum'],
            ['name' => 'Taman RS', 'floor' => '-', 'building' => 'Area Luar', 'unit' => 'Umum'],
            ['name' => 'Koridor Utama', 'floor' => 'Lantai 1', 'building' => 'Gedung Utama', 'unit' => 'Umum'],
        ];

        foreach ($rooms as $room) {
            Room::updateOrCreate(
                ['name' => $room['name'], 'building' => $room['building']],
                array_merge($room, ['is_active' => true])
            );
        }
    }
}
