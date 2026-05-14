<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            ['name' => 'Rizki Ramadhan', 'nis_nim' => '2023001', 'class' => 'X IPA 1', 'major' => 'IPA', 'address' => 'Jl. Sudirman No. 10, Jakarta', 'whatsapp' => '081234567890', 'email' => 'rizki.ramadhan@sman1.sch.id'],
            ['name' => 'Siti Nurhaliza', 'nis_nim' => '2023002', 'class' => 'X IPA 1', 'major' => 'IPA', 'address' => 'Jl. Gatot Subroto No. 25, Jakarta', 'whatsapp' => '081234567891', 'email' => 'siti.nurhaliza@sman1.sch.id'],
            ['name' => 'Ahmad Fauzi', 'nis_nim' => '2023003', 'class' => 'X IPS 1', 'major' => 'IPS', 'address' => 'Jl. Thamrin No. 8, Jakarta', 'whatsapp' => '081234567892', 'email' => 'ahmad.fauzi@sman1.sch.id'],
            ['name' => 'Dewi Lestari', 'nis_nim' => '2022001', 'class' => 'XI IPA 2', 'major' => 'IPA', 'address' => 'Jl. HR Rasuna Said, Jakarta', 'whatsapp' => '081234567893', 'email' => 'dewi.lestari@sman1.sch.id'],
            ['name' => 'Budi Santoso', 'nis_nim' => '2022002', 'class' => 'XI IPS 1', 'major' => 'IPS', 'address' => 'Jl. Kebayoran Baru, Jakarta', 'whatsapp' => '081234567894', 'email' => 'budi.santoso@sman1.sch.id'],
            ['name' => 'Putri Ayu Wulandari', 'nis_nim' => '2021001', 'class' => 'XII IPA 1', 'major' => 'IPA', 'address' => 'Jl. Melawai, Jakarta', 'whatsapp' => '081234567895', 'email' => 'putri.wulandari@sman1.sch.id'],
            ['name' => 'Rizky Pratama', 'nis_nim' => '2021002', 'class' => 'XII IPS 2', 'major' => 'IPS', 'address' => 'Jl. Senayan, Jakarta', 'whatsapp' => '081234567896', 'email' => 'rizky.pratama@sman1.sch.id'],
            ['name' => 'Anisa Rahma', 'nis_nim' => '2024001', 'class' => 'X IPA 2', 'major' => 'IPA', 'address' => 'Jl. Pancoran, Jakarta', 'whatsapp' => '081234567897', 'email' => 'anisa.rahma@sman1.sch.id'],
            ['name' => 'Dimas Arya Saputra', 'nis_nim' => '2024002', 'class' => 'X IPS 2', 'major' => 'IPS', 'address' => 'Jl. Blok M, Jakarta', 'whatsapp' => '081234567898', 'email' => 'dimas.arya@sman1.sch.id'],
            ['name' => 'Maya Indriani', 'nis_nim' => '2023004', 'class' => 'X IPA 3', 'major' => 'IPA', 'address' => 'Jl. Cilandak, Jakarta', 'whatsapp' => '081234567899', 'email' => 'maya.indriani@sman1.sch.id'],
            ['name' => 'Fajar Nugroho', 'nis_nim' => '2022003', 'class' => 'XI IPA 1', 'major' => 'IPA', 'address' => 'Jl. Lebak Bulus, Jakarta', 'whatsapp' => '081234567801', 'email' => 'fajar.nugroho@sman1.sch.id'],
            ['name' => 'Lina Marlina', 'nis_nim' => '2022004', 'class' => 'XI IPS 2', 'major' => 'IPS', 'address' => 'Jl. Cipete, Jakarta', 'whatsapp' => '081234567802', 'email' => 'lina.marlina@sman1.sch.id'],
            ['name' => 'Galang Hendra', 'nis_nim' => '2021003', 'class' => 'XII IPA 2', 'major' => 'IPA', 'address' => 'Jl. Fatmawati, Jakarta', 'whatsapp' => '081234567803', 'email' => 'galang.hendra@sman1.sch.id'],
            ['name' => 'Nadia Putri', 'nis_nim' => '2023005', 'class' => 'X IPA 1', 'major' => 'IPA', 'address' => 'Jl. Antasari, Jakarta', 'whatsapp' => '081234567804', 'email' => 'nadia.putri@sman1.sch.id'],
            ['name' => 'Yoga Prasetyo', 'nis_nim' => '2024003', 'class' => 'X IPS 1', 'major' => 'IPS', 'address' => 'Jl. Permata Hijau, Jakarta', 'whatsapp' => '081234567805', 'email' => 'yoga.prasetyo@sman1.sch.id'],
        ];

        foreach ($members as $m) {
            $memberCode = 'MBR-' . strtoupper(Str::random(6));

            $member = Member::updateOrCreate(
                ['email' => $m['email']],
                [
                    'member_code' => $memberCode,
                    'name' => $m['name'],
                    'nis_nim' => $m['nis_nim'],
                    'class' => $m['class'],
                    'major' => $m['major'],
                    'address' => $m['address'],
                    'whatsapp' => $m['whatsapp'],
                    'status' => 'active',
                ]
            );

            if (!$member->qr_code) {
                $this->generateQr($member);
            }
        }
    }

    private function generateQr(Member $member): void
    {
        try {
            $fileName = 'qr/members/' . $member->member_code . '.png';
            $qrPng = QrCode::format('png')
                ->size(300)
                ->margin(1)
                ->generate($member->member_code);

            Storage::disk('public')->put($fileName, $qrPng);
            $member->updateQuietly(['qr_code' => $fileName]);
        } catch (\Throwable) {
            // Skip if service unavailable
        }
    }
}