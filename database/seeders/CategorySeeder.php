<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Plumbing',
                'description' => 'Kerusakan terkait pipa, keran, wastafel, toilet, dan saluran air.',
                'icon' => 'bi-droplet-fill',
            ],
            [
                'name' => 'Elektrikal',
                'description' => 'Kerusakan terkait instalasi listrik, lampu, stop kontak, dan panel listrik.',
                'icon' => 'bi-lightning-charge-fill',
            ],
            [
                'name' => 'AC/HVAC',
                'description' => 'Kerusakan terkait AC, pendingin ruangan, ventilasi, dan sistem HVAC.',
                'icon' => 'bi-snow',
            ],
            [
                'name' => 'Furnitur',
                'description' => 'Kerusakan terkait meja, kursi, lemari, rak, dan perabotan lainnya.',
                'icon' => 'bi-easel-fill',
            ],
            [
                'name' => 'Pintu & Jendela',
                'description' => 'Kerusakan terkait pintu, jendela, kunci, engsel, dan kaca.',
                'icon' => 'bi-door-open-fill',
            ],
            [
                'name' => 'Lift/Elevator',
                'description' => 'Kerusakan dan gangguan pada lift atau elevator.',
                'icon' => 'bi-arrow-up-square-fill',
            ],
            [
                'name' => 'Jaringan & IT',
                'description' => 'Kerusakan terkait jaringan internet, WiFi, komputer, dan perangkat IT.',
                'icon' => 'bi-wifi',
            ],
            [
                'name' => 'Kebersihan & Sanitasi',
                'description' => 'Masalah kebersihan, sanitasi, tempat sampah, dan drainase.',
                'icon' => 'bi-droplet-half',
            ],
            [
                'name' => 'Alat Medis',
                'description' => 'Kerusakan atau gangguan pada peralatan dan alat medis.',
                'icon' => 'bi-heart-pulse-fill',
            ],
            [
                'name' => 'Bangunan',
                'description' => 'Kerusakan pada struktur bangunan seperti dinding, lantai, atap, plafon.',
                'icon' => 'bi-building',
            ],
            [
                'name' => 'Keamanan',
                'description' => 'Kerusakan terkait CCTV, alarm, access door, dan sistem keamanan.',
                'icon' => 'bi-shield-lock-fill',
            ],
            [
                'name' => 'Lainnya',
                'description' => 'Kerusakan fasilitas lainnya yang tidak masuk kategori di atas.',
                'icon' => 'bi-three-dots',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                array_merge($category, ['is_active' => true])
            );
        }
    }
}
