<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $pelaporRole = Role::where('name', 'pelapor')->first();
        $teknisiRole = Role::where('name', 'teknisi')->first();
        $supervisorRole = Role::where('name', 'supervisor')->first();

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@simfas.com'],
            [
                'name' => 'Admin HMS',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,
                'phone' => '081234567890',
                'unit' => 'IT & Maintenance',
                'position' => 'System Administrator',
                'is_active' => true,
            ]
        );

        // Pelapor 1
        User::updateOrCreate(
            ['email' => 'pelapor1@simfas.com'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('pelapor123'),
                'role_id' => $pelaporRole->id,
                'phone' => '081234567891',
                'unit' => 'Rawat Inap',
                'position' => 'Perawat',
                'is_active' => true,
            ]
        );

        // Pelapor 2
        User::updateOrCreate(
            ['email' => 'pelapor2@hospital.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role_id' => $pelaporRole->id,
                'phone' => '081234567892',
                'unit' => 'IGD',
                'position' => 'Dokter Jaga',
                'is_active' => true,
            ]
        );

        // Teknisi 1
        User::updateOrCreate(
            ['email' => 'teknisi1@simfas.com'],
            [
                'name' => 'Ahmad Fadillah',
                'password' => Hash::make('teknisi123'),
                'role_id' => $teknisiRole->id,
                'phone' => '081234567893',
                'unit' => 'Maintenance',
                'position' => 'Teknisi Elektrikal',
                'is_active' => true,
            ]
        );

        // Teknisi 2
        User::updateOrCreate(
            ['email' => 'teknisi2@hospital.com'],
            [
                'name' => 'Dedi Kurniawan',
                'password' => Hash::make('password'),
                'role_id' => $teknisiRole->id,
                'phone' => '081234567894',
                'unit' => 'Maintenance',
                'position' => 'Teknisi Plumbing',
                'is_active' => true,
            ]
        );

        // Teknisi 3
        User::updateOrCreate(
            ['email' => 'teknisi3@hospital.com'],
            [
                'name' => 'Rizky Pratama',
                'password' => Hash::make('password'),
                'role_id' => $teknisiRole->id,
                'phone' => '081234567895',
                'unit' => 'Maintenance',
                'position' => 'Teknisi AC/HVAC',
                'is_active' => true,
            ]
        );

        // Supervisor
        User::updateOrCreate(
            ['email' => 'supervisor@simfas.com'],
            [
                'name' => 'Dr. Hendra Wijaya',
                'password' => Hash::make('supervisor123'),
                'role_id' => $supervisorRole->id,
                'phone' => '081234567896',
                'unit' => 'Manajemen Fasilitas',
                'position' => 'Kepala Bagian Maintenance',
                'is_active' => true,
            ]
        );
    }
}
