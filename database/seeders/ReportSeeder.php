<?php

namespace Database\Seeders;

use App\Enums\ReportPriority;
use App\Enums\ReportStatus;
use App\Models\Assignment;
use App\Models\Category;
use App\Models\Notification;
use App\Models\ProgressHistory;
use App\Models\ProgressImage;
use App\Models\Report;
use App\Models\ReportImage;
use App\Models\Room;
use App\Models\StatusHistory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate/delete existing report tables to leave only 1 clean report
        Schema::disableForeignKeyConstraints();
        ProgressImage::truncate();
        ProgressHistory::truncate();
        Assignment::truncate();
        StatusHistory::truncate();
        ReportImage::truncate();
        Notification::truncate();
        Report::truncate();
        Schema::enableForeignKeyConstraints();

        $pelapor = User::where('email', 'pelapor1@simfas.com')->first() ?? User::first();
        $admin = User::where('email', 'admin@simfas.com')->first();
        $teknisi = User::where('email', 'teknisi1@simfas.com')->first() ?? User::first();

        $room = Room::where('name', 'IGD')->first() ?? Room::first();
        $category = Category::where('name', 'AC/HVAC')->first() ?? Category::first();

        // 1 Contoh Laporan: AC Menetes Air di IGD
        $r1 = Report::create([
            'ticket_number' => Report::generateTicketNumber(),
            'user_id' => $pelapor->id,
            'room_id' => $room->id,
            'category_id' => $category->id,
            'priority' => ReportPriority::DARURAT->value,
            'status' => ReportStatus::DALAM_PERBAIKAN->value,
            'location_detail' => 'Di dekat bed 2 IGD',
            'description' => 'AC Split IGD meneteskan air sangat deras, mengganggu penanganan pasien darurat.',
            'reported_at' => now()->subHours(3),
        ]);

        StatusHistory::create([
            'report_id' => $r1->id,
            'changed_by' => $pelapor->id,
            'old_status' => null,
            'new_status' => ReportStatus::MENUNGGU_VERIFIKASI->value,
            'notes' => 'Laporan dibuat.',
        ]);

        StatusHistory::create([
            'report_id' => $r1->id,
            'changed_by' => $admin->id,
            'old_status' => ReportStatus::MENUNGGU_VERIFIKASI->value,
            'new_status' => ReportStatus::DIPROSES->value,
            'notes' => 'Diverifikasi dan ditugaskan ke teknisi AC.',
        ]);

        StatusHistory::create([
            'report_id' => $r1->id,
            'changed_by' => $teknisi->id,
            'old_status' => ReportStatus::DIPROSES->value,
            'new_status' => ReportStatus::DALAM_PERBAIKAN->value,
            'notes' => 'Mulai pembersihan selang buangan air AC.',
        ]);

        $a1 = Assignment::create([
            'report_id' => $r1->id,
            'technician_id' => $teknisi->id,
            'assigned_by' => $admin->id,
            'assigned_at' => now()->subHours(2),
            'estimated_start' => now()->subHours(2),
            'estimated_completion' => now()->addHours(2),
            'actual_start' => now()->subHour(),
            'status' => 'dikerjakan',
            'admin_notes' => 'Tolong prioritaskan karena berada di area penanganan IGD.',
        ]);

        ProgressHistory::create([
            'assignment_id' => $a1->id,
            'technician_id' => $teknisi->id,
            'status' => 'dalam_perbaikan',
            'description' => 'Pengecekan filter dan pembersihan pipa drainase AC sedang berlangsung.',
            'progress_percentage' => 60,
        ]);
    }
}
