<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Facility;
use App\Models\Room;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $rooms = Room::all()->keyBy('name');
        $categories = Category::all()->keyBy('name');

        $facilities = [
            // IGD
            ['name' => 'AC Split IGD', 'code' => 'FAC-IGD-001', 'room' => 'IGD', 'category' => 'AC/HVAC'],
            ['name' => 'Lampu Utama IGD', 'code' => 'FAC-IGD-002', 'room' => 'IGD', 'category' => 'Elektrikal'],
            ['name' => 'Wastafel IGD', 'code' => 'FAC-IGD-003', 'room' => 'IGD', 'category' => 'Plumbing'],
            ['name' => 'Pintu Utama IGD', 'code' => 'FAC-IGD-004', 'room' => 'IGD', 'category' => 'Pintu & Jendela'],
            ['name' => 'Bed Pasien IGD 1', 'code' => 'FAC-IGD-005', 'room' => 'IGD', 'category' => 'Furnitur'],

            // Lobby
            ['name' => 'AC Central Lobby', 'code' => 'FAC-LBY-001', 'room' => 'Lobby Utama', 'category' => 'AC/HVAC'],
            ['name' => 'Lampu Chandelier Lobby', 'code' => 'FAC-LBY-002', 'room' => 'Lobby Utama', 'category' => 'Elektrikal'],
            ['name' => 'Kursi Tunggu Lobby', 'code' => 'FAC-LBY-003', 'room' => 'Lobby Utama', 'category' => 'Furnitur'],
            ['name' => 'TV Informasi Lobby', 'code' => 'FAC-LBY-004', 'room' => 'Lobby Utama', 'category' => 'Jaringan & IT'],

            // Poli Umum
            ['name' => 'AC Split Poli Umum', 'code' => 'FAC-PLU-001', 'room' => 'Poli Umum', 'category' => 'AC/HVAC'],
            ['name' => 'Meja Dokter Poli Umum', 'code' => 'FAC-PLU-002', 'room' => 'Poli Umum', 'category' => 'Furnitur'],
            ['name' => 'Wastafel Poli Umum', 'code' => 'FAC-PLU-003', 'room' => 'Poli Umum', 'category' => 'Plumbing'],
            ['name' => 'Komputer Poli Umum', 'code' => 'FAC-PLU-004', 'room' => 'Poli Umum', 'category' => 'Jaringan & IT'],

            // ICU
            ['name' => 'AC Central ICU', 'code' => 'FAC-ICU-001', 'room' => 'ICU', 'category' => 'AC/HVAC'],
            ['name' => 'Monitor Pasien ICU 1', 'code' => 'FAC-ICU-002', 'room' => 'ICU', 'category' => 'Alat Medis'],
            ['name' => 'Lampu Operasi ICU', 'code' => 'FAC-ICU-003', 'room' => 'ICU', 'category' => 'Elektrikal'],
            ['name' => 'Pintu Otomatis ICU', 'code' => 'FAC-ICU-004', 'room' => 'ICU', 'category' => 'Pintu & Jendela'],

            // Ruang Operasi
            ['name' => 'Lampu Operasi OR-1', 'code' => 'FAC-OR1-001', 'room' => 'Ruang Operasi 1', 'category' => 'Elektrikal'],
            ['name' => 'AC Central OR-1', 'code' => 'FAC-OR1-002', 'room' => 'Ruang Operasi 1', 'category' => 'AC/HVAC'],
            ['name' => 'Mesin Anestesi OR-1', 'code' => 'FAC-OR1-003', 'room' => 'Ruang Operasi 1', 'category' => 'Alat Medis'],

            // Rawat Inap
            ['name' => 'AC Split Kelas 1 Room A', 'code' => 'FAC-RI1-001', 'room' => 'Ruang Rawat Inap Kelas 1', 'category' => 'AC/HVAC'],
            ['name' => 'Toilet Rawat Inap Kelas 1', 'code' => 'FAC-RI1-002', 'room' => 'Ruang Rawat Inap Kelas 1', 'category' => 'Plumbing'],
            ['name' => 'TV Rawat Inap Kelas 1', 'code' => 'FAC-RI1-003', 'room' => 'Ruang Rawat Inap Kelas 1', 'category' => 'Elektrikal'],
            ['name' => 'Bed Pasien RI-2A', 'code' => 'FAC-RI2-001', 'room' => 'Ruang Rawat Inap Kelas 2', 'category' => 'Furnitur'],
            ['name' => 'Keran Air RI-2', 'code' => 'FAC-RI2-002', 'room' => 'Ruang Rawat Inap Kelas 2', 'category' => 'Plumbing'],

            // Kantor Administrasi
            ['name' => 'AC Split Kantor Admin', 'code' => 'FAC-ADM-001', 'room' => 'Kantor Administrasi', 'category' => 'AC/HVAC'],
            ['name' => 'Printer Kantor Admin', 'code' => 'FAC-ADM-002', 'room' => 'Kantor Administrasi', 'category' => 'Jaringan & IT'],
            ['name' => 'Meja Kerja Admin 1', 'code' => 'FAC-ADM-003', 'room' => 'Kantor Administrasi', 'category' => 'Furnitur'],

            // Kantin
            ['name' => 'Keran Air Kantin', 'code' => 'FAC-KNT-001', 'room' => 'Kantin', 'category' => 'Plumbing'],
            ['name' => 'Lampu Kantin', 'code' => 'FAC-KNT-002', 'room' => 'Kantin', 'category' => 'Elektrikal'],
            ['name' => 'Meja Makan Kantin 1', 'code' => 'FAC-KNT-003', 'room' => 'Kantin', 'category' => 'Furnitur'],

            // Area Luar
            ['name' => 'Lampu Parkir 1', 'code' => 'FAC-PRK-001', 'room' => 'Area Parkir', 'category' => 'Elektrikal'],
            ['name' => 'CCTV Parkir', 'code' => 'FAC-PRK-002', 'room' => 'Area Parkir', 'category' => 'Keamanan'],
            ['name' => 'Lampu Taman', 'code' => 'FAC-TMN-001', 'room' => 'Taman RS', 'category' => 'Elektrikal'],
            ['name' => 'Keran Taman', 'code' => 'FAC-TMN-002', 'room' => 'Taman RS', 'category' => 'Plumbing'],

            // Laboratorium
            ['name' => 'AC Split Lab', 'code' => 'FAC-LAB-001', 'room' => 'Laboratorium', 'category' => 'AC/HVAC'],
            ['name' => 'Wastafel Lab', 'code' => 'FAC-LAB-002', 'room' => 'Laboratorium', 'category' => 'Plumbing'],

            // Lift
            ['name' => 'Lift Utama Gedung A', 'code' => 'FAC-LFT-001', 'room' => 'Koridor Utama', 'category' => 'Lift/Elevator'],
            ['name' => 'Lift Barang Gedung A', 'code' => 'FAC-LFT-002', 'room' => 'Koridor Utama', 'category' => 'Lift/Elevator'],
        ];

        foreach ($facilities as $facility) {
            $room = $rooms->get($facility['room']);
            $category = $categories->get($facility['category']);

            if ($room && $category) {
                Facility::updateOrCreate(
                    ['code' => $facility['code']],
                    [
                        'name' => $facility['name'],
                        'room_id' => $room->id,
                        'category_id' => $category->id,
                        'condition' => 'baik',
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
