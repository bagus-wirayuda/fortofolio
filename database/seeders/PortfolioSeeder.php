<?php

namespace Database\Seeders;

use App\Models\PortfolioEntry;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        Profile::query()->firstOrCreate(['id' => 1], [
            'name' => 'Kalfin Alloto’dang',
            'credentials' => 'S.Kom., MT',
            'headline' => 'Membangun solusi. Menggerakkan perubahan.',
            'intro' => 'Menghubungkan teknologi, pendidikan, dan kepemimpinan untuk membuka lebih banyak kemungkinan.',
            'bio' => "Saya Kalfin Alloto’dang, seorang profesional teknologi informasi, pendidik, dan penggerak komunitas yang berbasis di Makassar. Perjalanan saya mempertemukan dunia akademik, pengembangan usaha, dan organisasi.\n\nMelalui PT. Liny Jaya Informatika, BISOLPIN, dan Yayasan Solusi Pintar Indonesia, saya terus mengambil bagian dalam perjalanan teknologi dan pendidikan. Bagi saya, pengetahuan menjadi berarti ketika dibagikan dan diwujudkan dalam tindakan.",
            'location' => 'Makassar, Indonesia',
            'email' => 'celivindp02@gmail.com',
            'phone' => '082192272289',
            'website' => 'https://kalfinallottodang.id',
            'cv_path' => 'cv.pdf',
            'available' => true,
        ]);

        if (PortfolioEntry::query()->exists()) {
            return;
        }

        $records = [
            ['project', 'PT. Liny Jaya Informatika', 'Direktur', '2018 — Sekarang', 'Teknologi', 'Memimpin PT. Liny Jaya Informatika sejak 2018. Sebuah bagian dari perjalanan profesional saya di dunia teknologi informasi.'],
            ['project', 'BISOLPIN', 'Founder', '2025 — Sekarang', 'Pendidikan', 'Bimbingan Belajar Digital Solusi Pintar Indonesia. Inisiatif pendidikan digital yang saya dirikan pada tahun 2025.'],
            ['project', 'Yayasan Solusi Pintar Indonesia', 'Founder', '2026 — Sekarang', 'Sosial', 'Yayasan yang saya dirikan pada tahun 2026, melanjutkan perjalanan saya dalam pendidikan dan kontribusi bagi masyarakat.'],
            ['experience', 'Founder', 'Yayasan Solusi Pintar Indonesia', '2026 — Sekarang', 'Sosial', null],
            ['experience', 'Founder', 'Bimbingan Belajar Digital Solusi Pintar Indonesia (BISOLPIN)', '2025 — Sekarang', 'Pendidikan', null],
            ['experience', 'Direktur', 'PT. Liny Jaya Informatika', '2018 — Sekarang', 'Teknologi', null],
            ['experience', 'Dosen Ilmu Komputer', 'Universitas Dipanegara Makassar', '2009 — 2022', 'Pendidikan', null],
            ['experience', 'Tenaga Pendamping PPID', 'DISKOMINFO Sulawesi Selatan', '2018 — 2022', 'Teknologi', null],
            ['experience', 'Dosen Ilmu Komputer', 'UNIFA', '2011', 'Pendidikan', null],
            ['experience', 'Tenaga Pengajar', 'Yayasan IPEKA', '2010', 'Pendidikan', null],
            ['experience', 'Tenaga Pengajar', 'Yayasan Pelita Kasih', '2010', 'Pendidikan', null],
            ['experience', 'Staf Warehouse', 'PT. Enseval Putera Mega Trading, Tbk', '2008', 'Profesional', null],
            ['education', 'Magister Teknik Informatika', 'Universitas Hasanuddin, Makassar', 'Lulus 2016', 'S2', null],
            ['education', 'Sarjana Sistem Informasi', 'STMIK Dipanegara Makassar', 'Lulus 2007', 'S1', null],
            ['education', 'SMA Kristen Rantepao', 'Toraja Utara', 'Lulus 2001', 'SMA', null],
            ['education', 'SMP Negeri 1 Rantepao', 'Toraja Utara', 'Lulus 1998', 'SMP', null],
            ['education', 'SD Negeri Inpres 90 To’ Nangka’', 'Toraja Utara', null, 'SD', null],
            ['organization', 'Ketua Bidang Pemuda dan Olahraga', 'PMTI Sulawesi Selatan', '2022 — Sekarang', 'Komunitas', null],
            ['organization', 'Sekretaris Umum', 'IKADIPA Makassar', '2023 — Sekarang', 'Alumni', null],
            ['organization', 'Ketua 3 Bidang Pengembangan Alumni dan Kesiswaan', 'IKASMAKER', '2016 — 2020 & 2025 — Sekarang', 'Alumni', null],
            ['organization', 'Ketua Cabang Olahraga Taekwondo', 'Sulawesi Selatan', '2021 — 2025', 'Olahraga', null],
            ['organization', 'Ketua Bidang Data', 'KONI Provinsi Sulawesi Selatan', '2021 — 2025', 'Olahraga', null],
            ['organization', 'Ketua Bidang Ekraf', 'GAMKI Sulawesi Selatan', '2021 — 2025', 'Komunitas', null],
        ];

        foreach ($records as $index => [$kind, $title, $subtitle, $period, $category, $description]) {
            PortfolioEntry::query()->firstOrCreate(
                ['kind' => $kind, 'title' => $title, 'subtitle' => $subtitle],
                ['period' => $period, 'category' => $category, 'description' => $description, 'sort_order' => $index, 'is_published' => true],
            );
        }
    }
}
