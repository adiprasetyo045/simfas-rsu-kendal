<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Administrator sistem dengan akses penuh untuk mengelola seluruh fitur aplikasi.',
            ],
            [
                'name' => 'pelapor',
                'display_name' => 'Pelapor',
                'description' => 'Pegawai/staf rumah sakit yang dapat membuat dan memantau laporan kerusakan.',
            ],
            [
                'name' => 'teknisi',
                'display_name' => 'Teknisi',
                'description' => 'Teknisi maintenance yang bertugas memperbaiki fasilitas yang dilaporkan rusak.',
            ],
            [
                'name' => 'supervisor',
                'display_name' => 'Supervisor',
                'description' => 'Supervisor yang memantau kinerja teknisi dan melihat laporan statistik.',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
