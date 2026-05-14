<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            // ── Fiksi ──
            ['book_code' => 'FIK-001', 'isbn' => '978-602-03-1234-5', 'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata', 'publisher' => 'Bentang Pustaka', 'year' => 2005,
                'category' => 'Fiksi', 'rack' => 'A1', 'stock' => 5,
                'synopsis' => 'Novel inspiratif tentang perjuangan anak-anak di Pulau Belitong untuk meraih pendidikan. Sepuluh anak dari keluarga miskin menunjukkan bahwa semangat dan tekad bisa mengatasi segala keterbatasan.'],

            ['book_code' => 'FIK-002', 'isbn' => '978-979-22-3456-7', 'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer', 'publisher' => 'Hasta Mitra', 'year' => 1980,
                'category' => 'Fiksi', 'rack' => 'A2', 'stock' => 3,
                'synopsis' => 'Novel pertama dari Tetralogi Buru yang mengupas ketimpangan sosial dan kolonialisme di Hindia Belanda melalui mata Minke, seorang pribumi terdidik.'],

            ['book_code' => 'FIK-003', 'isbn' => '978-602-01-2345-6', 'title' => 'Anak Semua Bangsa',
                'author' => 'Pramoedya Ananta Toer', 'publisher' => 'Hasta Mitra', 'year' => 1981,
                'category' => 'Fiksi', 'rack' => 'A2', 'stock' => 2,
                'synopsis' => 'Novel kedua Tetralogi Buru yang melanjutkan perjalanan Minke dalam menemukan jati diri dan melawan penindasan kolonial.'],

            ['book_code' => 'FIK-004', 'isbn' => '978-602-8641-12-3', 'title' => 'Supernova: Ksatria, Puteri, dan Bintang Jatuh',
                'author' => 'Dee Lestari', 'publisher' => 'Gagas Media', 'year' => 2016,
                'category' => 'Fiksi', 'rack' => 'A3', 'stock' => 4,
                'synopsis' => 'Novel science fiction pertama dari serial Supernova yang menggabungkan tema kosmis dengan pengalaman manusia sehari-hari.'],

            ['book_code' => 'FIK-005', 'isbn' => '978-979-28-5678-9', 'title' => 'Minggu Ini Kamu Mati',
                'author' => 'Nina Ristyani', 'publisher' => 'Gagas Media', 'year' => 2012,
                'category' => 'Fiksi', 'rack' => 'A3', 'stock' => 3,
                'synopsis' => 'Novel young adult yang mengangkat isu kesehatan mental dengan cerita yang menyentuh tentang perjuangan seorang gadis melawan depresi.'],

            ['book_code' => 'FIK-006', 'isbn' => '978-602-03-7654-3', 'title' => 'Gajah Mada: Perang Salib',
                'author' => 'Langit Kresna Hariadi', 'publisher' => 'Bentang Pustaka', 'year' => 2015,
                'category' => 'Fiksi', 'rack' => 'A4', 'stock' => 3,
                'synopsis' => 'Novel sejarahepik yang mengisahkan perjalanan Gajah Mada dari seorang贫困 pemuda hingga menjadi panglima mahkota Majapahit.'],

            ['book_code' => 'FIK-007', 'isbn' => '978-602-04-3456-7', 'title' => 'Negeri 5 Menara',
                'author' => 'Ahmad Fuadi', 'publisher' => 'Gramedia Pustaka Utama', 'year' => 2009,
                'category' => 'Fiksi', 'rack' => 'A4', 'stock' => 4,
                'synopsis' => 'Novel tentang perjuangan enam sekawan dari berbagai daerah di pondok padat yang penuh dinamika persahabatan dan cita-cita.'],

            // ── Non-Fiksi ──
            ['book_code' => 'NFK-001', 'isbn' => '978-602-03-9876-5', 'title' => 'Pendidikan Karakter: Membangun Peradaban Bangsa',
                'author' => 'Prof. Dr. Djaelani Harono', 'publisher' => 'Retika AD', 'year' => 2021,
                'category' => 'Non-Fiksi', 'rack' => 'B1', 'stock' => 5,
                'synopsis' => 'Buku referensi lengkap tentang pendidikan karakter di sekolah, mencakup teori, implementasi, dan contoh nyata pembangunan karakter siswa.'],

            ['book_code' => 'NFK-002', 'isbn' => '978-602-04-5678-9', 'title' => 'Metode Penelitian Kualitatif untuk Bisnis dan Sosial',
                'author' => 'Prof. Dr. Sugiyono', 'publisher' => 'Alfabeta', 'year' => 2022,
                'category' => 'Non-Fiksi', 'rack' => 'B1', 'stock' => 3,
                'synopsis' => 'Buku panduan metodologi penelitian kualitatif yang lengkap dengan contoh aplikasi dalam bidang bisnis dan ilmu sosial.'],

            ['book_code' => 'NFK-003', 'isbn' => '978-602-386-123-4', 'title' => 'Public Speaking untuk Semua Kalangan',
                'author' => 'Dr. Wina Andriani, M.Si', 'publisher' => 'KPG (Kepustakaan Populer Gramedia)', 'year' => 2023,
                'category' => 'Non-Fiksi', 'rack' => 'B2', 'stock' => 4,
                'synopsis' => 'Buku panduan praktis public speaking yang mencakup teknik berbicara di depan umum, mengelola emosi, dan membangun kepercayaan diri.'],

            // ── Sains & Teknologi ──
            ['book_code' => 'SAI-001', 'isbn' => '978-602-01-8765-4', 'title' => 'Fisika untuk SMA/MA Kelas X',
                'author' => 'Mikrajuddin Abdullah', 'publisher' => 'ITB Press', 'year' => 2018,
                'category' => 'Sains & Teknologi', 'rack' => 'C1', 'stock' => 8,
                'synopsis' => 'Buku teks fisika untuk siswa SMA kelas X yang mencakup mekanika, kinematika, dinamika, dan hukum Newton dengan penjelasan detail.'],

            ['book_code' => 'SAI-002', 'isbn' => '978-602-02-3456-7', 'title' => 'Kimia Organik Dasar',
                'author' => 'Irwan Stay', 'publisher' => 'Citra Informatika', 'year' => 2020,
                'category' => 'Sains & Teknologi', 'rack' => 'C1', 'stock' => 4,
                'synopsis' => 'Buku panduan kimia organik untuk mahasiswa dan siswa SMA dengan bahasan tentang struktur molekul, gugus fungsi, dan reaksi organik.'],

            ['book_code' => 'SAI-003', 'isbn' => '978-602-432-987-6', 'title' => 'Matematika Diskrit dan Aplikasinya',
                'author' => 'Dr. Rinaldi Munir', 'publisher' => 'Informatika Bandung', 'year' => 2023,
                'category' => 'Sains & Teknologi', 'rack' => 'C2', 'stock' => 5,
                'synopsis' => 'Buku matematika diskrit yang mencakup teori himpunan, logika, relasi, fungsi, graf, dan penggunaannya dalam pemrograman.'],

            ['book_code' => 'SAI-004', 'isbn' => '978-602-8765-432-1', 'title' => 'Biologi Sel dan Molekuler',
                'author' => 'Prof. Dr. Sri Wulandari, M.Si', 'publisher' => 'UB Press', 'year' => 2021,
                'category' => 'Sains & Teknologi', 'rack' => 'C2', 'stock' => 3,
                'synopsis' => 'Buku biologi modern yang membahas tentang struktur sel, DNA, RNA, sintesis protein, dan teknologi rekayasa genetika.'],

            ['book_code' => 'SAI-005', 'isbn' => '978-602-03-2345-6', 'title' => 'Astronomi untuk Pemula',
                'author' => 'Prof. Dr. Thomas Djamaluddin', 'publisher' => 'ITB Press', 'year' => 2020,
                'category' => 'Sains & Teknologi', 'rack' => 'C3', 'stock' => 4,
                'synopsis' => 'Buku pengenalan astronomi untuk siswa dan masyarakat umum, mencakup tata surya, bintang, galaksi, dan alat pengamat.'],

            // ── Sejarah ──
            ['book_code' => 'SEJ-001', 'isbn' => '978-602-03-4567-8', 'title' => 'Sejarah Indonesia: Dari Masa Praksara hingga Reformasi',
                'author' => 'Khalid bin Jazir', 'publisher' => 'Republik Publishing', 'year' => 2018,
                'category' => 'Sejarah', 'rack' => 'D1', 'stock' => 4,
                'synopsis' => 'Kumpulan tulisan yang mengupas sejarah Indonesia dari masa praaksara hingga reformasi dengan pendekatan kritis dan berbasis fakta.'],

            ['book_code' => 'SEJ-002', 'isbn' => '978-602-04-7890-1', 'title' => 'Perang Diponegoro 1825-1830',
                'author' => 'Peter Care', 'publisher' => 'LIPI Press', 'year' => 2019,
                'category' => 'Sejarah', 'rack' => 'D1', 'stock' => 2,
                'synopsis' => 'Buku sejarah yang mengupas secara mendalam perang terbesar di Jawa pada abad ke-19 yang dipimpin Pangeran Diponegoro.'],

            ['book_code' => 'SEJ-003', 'isbn' => '978-602-386-543-0', 'title' => 'Indonesia dari Segala Sisi',
                'author' => 'Tim Historika', 'publisher' => 'KPG (Kepustakaan Populer Gramedia)', 'year' => 2022,
                'category' => 'Sejarah', 'rack' => 'D2', 'stock' => 6,
                'synopsis' => 'Buku ringan yang menyajikan fakta-fakta menarik tentang Indonesia dari sisi geografi, budaya, politik, dan ekonomi.'],

            // ── Bahasa ──
            ['book_code' => 'BAH-001', 'isbn' => '978-602-01-5678-9', 'title' => 'Bahasa Indonesia untuk Pergururan Tinggi',
                'author' => 'Prof. Dr. E. Zaenal Arifin', 'publisher' => 'Akademika Bandung', 'year' => 2020,
                'category' => 'Bahasa', 'rack' => 'E1', 'stock' => 7,
                'synopsis' => 'Buku panduan bahasa Indonesia standar untuk mahasiswa yang mencakup tata bahasa, penulisan ilmiah, dan etika berbahasa.'],

            ['book_code' => 'BAH-002', 'isbn' => '978-0-19-870025-7', 'title' => 'Oxford Advanced Learner Dictionary',
                'author' => 'A.S. Hornby', 'publisher' => 'Oxford University Press', 'year' => 2020,
                'category' => 'Bahasa', 'rack' => 'E1', 'stock' => 3,
                'synopsis' => 'Kamus bahasa Inggris paling komprehensif dengan definisi Oxford, contoh kalimat, idiom, dan fitur bahasa Inggris modern.'],

            ['book_code' => 'BAH-003', 'isbn' => '978-602-04-2345-6', 'title' => 'TOEFL Preparation: Strategi dan Latihan Soal',
                'author' => 'Dr. Hendra Halim, M.Hum', 'publisher' => 'Gramedia Pustaka Utama', 'year' => 2021,
                'category' => 'Bahasa', 'rack' => 'E2', 'stock' => 5,
                'synopsis' => 'Buku persiapan TOEFL dengan strategi mengerjakan soal reading, listening, structure, dan writing yang efektif.'],

            // ── Komputer & IT ──
            ['book_code' => 'IT-001', 'isbn' => '978-602-03-8765-4', 'title' => 'Pemrograman Web dengan Laravel 11',
                'author' => 'Ahmad Luky Ramdhoni', 'publisher' => 'Informatika Bandung', 'year' => 2024,
                'category' => 'Komputer & IT', 'rack' => 'F1', 'stock' => 4,
                'synopsis' => 'Buku panduan lengkap programming web dengan framework Laravel 11, mencakup routing, controller, database, authentication, dan deployment.'],

            ['book_code' => 'IT-002', 'isbn' => '978-602-04-5671-0', 'title' => 'JavaScript: Dari Pemula hingga Mahir',
                'author' => 'Asep Tisna Wiguna', 'publisher' => 'Elex Media Komputindo', 'year' => 2023,
                'category' => 'Komputer & IT', 'rack' => 'F1', 'stock' => 5,
                'synopsis' => 'Buku panduan JavaScript dari dasar hingga mahir, mencakup ES6+, DOM manipulation, async/await, dan framework React.'],

            ['book_code' => 'IT-003', 'isbn' => '978-602-386-234-5', 'title' => 'Basis Data dan SQL untuk Pemula',
                'author' => 'Dosen TI UPI', 'publisher' => 'Informatika Bandung', 'year' => 2022,
                'category' => 'Komputer & IT', 'rack' => 'F2', 'stock' => 6,
                'synopsis' => 'Buku pengenalan basis data dan SQL untuk pemula, mencakup desain basis data, normalisasi, query SQL, dan penggunaan MySQL.'],

            ['book_code' => 'IT-004', 'isbn' => '978-602-8765-123-4', 'title' => 'Membangun Jaringan Komputer Sekolah',
                'author' => 'Tim IT Smarter Indonesia', 'publisher' => 'Andi Publisher', 'year' => 2021,
                'category' => 'Komputer & IT', 'rack' => 'F2', 'stock' => 3,
                'synopsis' => 'Buku panduan praktis membangun jaringan komputer untuk lingkungan sekolah, mencakup konsep jaringan, perangkat, dan troubleshooting.'],

            ['book_code' => 'IT-005', 'isbn' => '978-602-04-8901-2', 'title' => 'Desain Grafis dengan Canva untuk Pemula',
                'author' => 'Rina Marlina, S.Ds', 'publisher' => 'Andi Publisher', 'year' => 2023,
                'category' => 'Komputer & IT', 'rack' => 'F3', 'stock' => 4,
                'synopsis' => 'Buku panduan desain grafis menggunakan Canva untuk membuat poster, media sosial, dan presentasi yang menarik.'],

            // ── Seni & Budaya ──
            ['book_code' => 'SEN-001', 'isbn' => '978-602-03-5678-9', 'title' => 'Seni Budaya untuk SMA/MA Kelas X',
                'author' => 'Dr. Hi. Damsar, M.Si', 'publisher' => 'PT Intan Pariwara', 'year' => 2019,
                'category' => 'Seni & Budaya', 'rack' => 'G1', 'stock' => 6,
                'synopsis' => 'Buku seni budaya untuk SMA kelas X yang mencakup seni rupa, musik, tari, dan teater dengan panduan praktikum.'],

            ['book_code' => 'SEN-002', 'isbn' => '978-602-04-8901-2', 'title' => 'Batik: Warisan Budaya Indonesia',
                'author' => 'Dr. Sri Kuharini', 'publisher' => 'Mata Padi', 'year' => 2020,
                'category' => 'Seni & Budaya', 'rack' => 'G1', 'stock' => 4,
                'synopsis' => 'Buku tentang sejarah, filosofi, dan teknik membatik Indonesia dengan berbagai motif dari berbagai daerah.'],

            // ── Agama ──
            ['book_code' => 'AGM-001', 'isbn' => '978-602-01-3456-7', 'title' => 'Akhlak dan Mulia: Pendidikan Agama Islam SMA',
                'author' => 'Dr. H. Abdul Hadis, M.Ag', 'publisher' => 'PT Intan Pariwara', 'year' => 2018,
                'category' => 'Agama', 'rack' => 'H1', 'stock' => 8,
                'synopsis' => 'Buku Pendidikan Agama Islam untuk SMA yang mencakup akhlakul karimah, tauhid, fikih, dan sejarah peradaban Islam.'],

            ['book_code' => 'AGM-002', 'isbn' => '978-602-04-2345-6', 'title' => 'Mencari Ketenangan dalam Keseharian',
                'author' => 'Ustadz Adi Hidayat, Lc., MA', 'publisher' => 'Genta Pustaka', 'year' => 2022,
                'category' => 'Agama', 'rack' => 'H1', 'stock' => 5,
                'synopsis' => 'Buku pengembangan diri berbasis nilai-nilai Islam yang membahas cara menghadapi tekanan hidup dengan tenang dan penuh makna.'],

            ['book_code' => 'AGM-003', 'isbn' => '978-602-8765-678-9', 'title' => 'Mari Membaca Al-Quran',
                'author' => 'Ustadz H. Muhammad Darwis, Lc', 'publisher' => 'Genta Pustaka', 'year' => 2019,
                'category' => 'Agama', 'rack' => 'H2', 'stock' => 6,
                'synopsis' => 'Buku panduan belajar membaca Al-Quran untuk pemula dengan metode yang mudah dan step by step.'],

            // ── Olahraga ──
            ['book_code' => 'OHR-001', 'isbn' => '978-602-03-4567-8', 'title' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan SMA Kelas X',
                'author' => 'Dr. Cony Lina Hutapea, M.Or', 'publisher' => 'PT Intan Pariwara', 'year' => 2019,
                'category' => 'Olahraga', 'rack' => 'I1', 'stock' => 10,
                'synopsis' => 'Buku PJOK untuk SMA kelas X yang mencakup konsep kebugaran jasmani, berbagai cabang olahraga, dan pola hidup sehat.'],

            ['book_code' => 'OHR-002', 'isbn' => '978-602-04-6789-0', 'title' => 'Bola Voli: Teknik, Taktik, dan Strategi',
                'author' => 'Eko Prihartanto, S.Pd', 'publisher' => 'Andi Publisher', 'year' => 2021,
                'category' => 'Olahraga', 'rack' => 'I1', 'stock' => 4,
                'synopsis' => 'Buku panduan lengkap bola voli mulai dari teknik dasar, formasi, taktik servis, passing, sampai strategi permainan.'],

            // ── Komik ──
            ['book_code' => 'KMK-001', 'isbn' => '978-602-03-9012-3', 'title' => 'Komik Sains: Eksplorasi Alam Semesta',
                'author' => 'Tim GEA', 'publisher' => 'KPG (Kepustakaan Populer Gramedia)', 'year' => 2022,
                'category' => 'Komik', 'rack' => 'J1', 'stock' => 6,
                'synopsis' => 'Komik edukasi yang menjelaskan konsep fisika luar angkasa, galaksi, dan eksplorasi alam semesta dengan bahasa visual yang menarik untuk remaja.'],

            ['book_code' => 'KMK-002', 'isbn' => '978-602-04-1234-5', 'title' => 'Peto dan Peti: Kisah Tanah Jawa',
                'author' => 'Mas Rangga', 'publisher' => 'Elex Media Komputindo', 'year' => 2020,
                'category' => 'Komik', 'rack' => 'J1', 'stock' => 5,
                'synopsis' => 'Komik young adult yang mengisahkan petualangan Peto mencari peti misterius di Tanah Jawa sambil belajar budaya dan sejarah lokal.'],
        ];

        foreach ($books as $b) {
            $category = Category::where('name', $b['category'])->first();

            $book = Book::updateOrCreate(
                ['book_code' => $b['book_code']],
                [
                    'isbn' => $b['isbn'],
                    'title' => $b['title'],
                    'author' => $b['author'],
                    'publisher' => $b['publisher'],
                    'year' => $b['year'],
                    'category_id' => $category?->id,
                    'rack_location' => $b['rack'],
                    'stock' => $b['stock'],
                    'status' => 'available',
                    'synopsis' => $b['synopsis'] ?? null,
                ]
            );

            if (!$book->qr_code) {
                $this->generateQr($book);
            }
        }
    }

    private function generateQr(Book $book): void
    {
        try {
            $fileName = 'qr/books/' . $book->book_code . '.svg';
            $qrSvg = QrCode::format('svg')
                ->size(300)
                ->margin(1)
                ->generate($book->book_code);

            Storage::disk('public')->put($fileName, $qrSvg);
            $book->updateQuietly(['qr_code' => $fileName]);
        } catch (\Throwable) {
            // Skip if service unavailable
        }
    }
}