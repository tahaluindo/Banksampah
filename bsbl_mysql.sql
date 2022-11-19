-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 11, 2022 at 11:47 AM
-- Server version: 5.6.51
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bsbl`
--

-- --------------------------------------------------------

--
-- Table structure for table `artikel`
--

CREATE TABLE `artikel` (
  `id` varchar(200) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` text NOT NULL,
  `thumbnail` text NOT NULL,
  `content` longtext NOT NULL,
  `id_kategori` varchar(200) NOT NULL,
  `created_at` bigint(20) NOT NULL,
  `published_at` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `artikel`
--

INSERT INTO `artikel` (`id`, `title`, `slug`, `thumbnail`, `content`, `id_kategori`, `created_at`, `published_at`) VALUES
('BA001', 'ubah sampah jadi emas, bank sampah universitas budi luhur jaksel juara umum tingkat nasional    artikel ini telah tayang di tribunjakarta.com dengan judul ubah sampah jadi emas, bank sampah universitas budi luhur jaksel juara umum tingkat nasional', 'ubah-sampah-jadi-emas,-bank-sampah-universitas-budi-luhur-jaksel-juara-umum-tingkat-nasional----artikel-ini-telah-tayang-di-tribunjakarta.com-dengan-judul-ubah-sampah-jadi-emas,-bank-sampah-universitas-budi-luhur-jaksel-juara-umum-tingkat-nasional', '6284b7200e6fa.jpeg', '<p><strong>TRIBUNJAKARTA.COM, PESANGGRAHAN -</strong> Bank Sampah <a href=\"https://jakarta.tribunnews.com/tag/universitas-budi-luhur\" target=\"_blank\">Universitas&nbsp;Budi&nbsp;Luhur</a>, Pesanggrahan, Jakarta Selatan, meraih tiga gelar juara dalam lomba yang diselenggarakan Pegadaian.</p><p>Lomba dengan tema <a href=\"https://jakarta.tribunnews.com/tag/memilah-sampah-menabung-emas\" target=\"_blank\">Memilah&nbsp;Sampah&nbsp;Menabung&nbsp;Emas</a> itu diikuti 70 <a href=\"https://jakarta.tribunnews.com/tag/bank-sampah\" target=\"_blank\">bank&nbsp;sampah</a> binaan Pegadaian di seluruh Indonesia</p><p><br></p><p>Rektor <a href=\"https://jakarta.tribunnews.com/tag/universitas-budi-luhur\" target=\"_blank\">Universitas&nbsp;Budi&nbsp;Luhur</a>, <a href=\"https://jakarta.tribunnews.com/tag/wendi-usino\" target=\"_blank\">Wendi&nbsp;Usino</a>, mengatakan, Bank Sampah Budi Luhur merupakan wadah pengelolaan sampah dan menjadi tempat sosialisasi di lingkungan masyarakat.</p><p><br></p><p>Hal itu sebagai bentuk kepedulian <a href=\"https://jakarta.tribunnews.com/tag/universitas-budi-luhur\" target=\"_blank\">Universitas&nbsp;Budi&nbsp;Luhur</a> peduli terhadap masyarakat, khususnya lingkungan hidup.</p><p>\"Bank Sampah Budi Luhur dinyatakan sebagai juara umum lomba <a href=\"https://jakarta.tribunnews.com/tag/memilah-sampah-menabung-emas\" target=\"_blank\">Memilah&nbsp;Sampah&nbsp;Menabung&nbsp;Emas</a> 2021 dan meraih hadiah mobil boks. Tetap semangat berkarya dan tidak cepat merasa puas,\" kata Wendi di <a href=\"https://jakarta.tribunnews.com/tag/universitas-budi-luhur\" target=\"_blank\">Universitas&nbsp;Budi&nbsp;Luhur</a>, Jakarta Selatan, Kamis (23/9/2021).</p><p>Koordinator Bank Sampah Budi Luhur, Umi Tutik, menjelaskan, pihaknya selalu fokus pada kegiatan lingkungan dan memberi edukasi kepada masyarakat terkait pengelolaan sampah.</p><p>\"Pencapaian yang sudah kita raih ini, kita akan banyak lagi melayani masyarakat dan jangkauan lebih luas lagi dan kita jaga lingkungan. Prestasi ini merupakan kekompakan kebersamaan dengan menjalin koordinasi dan kerja sama baik dalam segala hal,\" ujar Umi.</p><p><br></p><p>Ia mengungkapkan, pihaknya sanggup mengumpulkan dua ton sampah non organik setiap minggunya.</p><p><br></p><p>\"Jadi satu bulan bisa lima sampai tujuh ton mengirim (sampah non organik) untuk dijual,\" ucap dia.</p><p>3 gelar juara yang diraih Bank Sampah Budi Luhur berhasil yaitu kategori akumulasi sampah terbanyak, akumulasi saldo tabungan emas tertinggi, dan akumulasi jumlah partisipan terbanyak.&nbsp;</p><p>Bank Sampah Budi Luhur pun dinobatkan sebagai juara umum dan mendapatkan satu unit mobil box.</p><p><br></p>', 'KA01', 1652864887, 1652806800),
('BA002', 'kolaborasi universitas budi luhur dan pt pegadaian wujudkan ruang kreatif dan wisata edukasi daur ulang bank sampah budi luhur', 'kolaborasi-universitas-budi-luhur-dan-pt-pegadaian-wujudkan-ruang-kreatif-dan-wisata-edukasi-daur-ulang-bank-sampah-budi-luhur', '62a6e56d1bf7e.jpeg', '<p>PT. Pegadaian (Persero) telah meresmikan pembukaan The Gade Creative Lounge dan Bank Sampah Budi Luhur pada hari Kamis, 31 Maret 2021 di Universitas Budi Luhur Jakarta. Peresmian ini dihadiri oleh Rektor Universitas Budi Luhur Bapak Dr.Ir. Wendi Usino, M.Sc., M.M. ; Ketua Yayasan Pendidikan Budi Luhur Cakti, Bapak Kasih Hanggoro, MBA serta Bapak Dr. Damar Latri Setiawan selaku Direktur Jaringan, Operasi, dan Penjualan PT Pegadaian didampingi bersama Asisten Deputi Tanggung Jawab Sosial Lingkungan Kementerian BUMN, Bapak Agus Suharyono.</p><p><br></p><p><img src=\"https://www.budiluhur.ac.id/wp-content/uploads/2021/04/20210402_205327.jpg\" height=\"405\" width=\"720\" style=\"display: block; margin: auto;\"></p><p><br></p><p>Peresmian The Gade Creative Lounge dan Bank Sampah Budi Luhur ditandai dengan penandatanganan prasasti oleh Bapak Dr. Damar Latri Setiawan dan Bapak Dr.Ir. Wendi Usino, M.Sc., M.M. didampingi oleh Bapak Agus Suharyono dan Bapak Kasih Hanggoro, MBA. Peresmian ini salah satu implementasi kerja sama Universitas Budi Luhur dan PT. Pegadaian (Persero) sejak tahun 2019.</p><p><br></p><p><img src=\"https://www.budiluhur.ac.id/wp-content/uploads/2021/04/20210402_212144.jpg\" height=\"399\" width=\"698\" style=\"display: block; margin: auto;\"></p><p><br></p><p>Universitas Budi Luhur menerima hibah pembangunan The Gade Creative Lounge ini sebagai salah satu wujud kepedulian PT. Pegadaian (Persero) untuk menuangkan kreativitas mahasiswa dalam berbagai bidang dengan nuansa millennials, khususnya dalam masa pandemic Covid-19.</p><p><br></p><p><img src=\"https://www.budiluhur.ac.id/wp-content/uploads/2021/04/20210402_205245.jpg\" height=\"404\" width=\"719\" style=\"display: block; margin: auto;\"></p><p><br></p><p>Tumbuhnya kreativitas mahasiswa merupakan bagian dari membangun SDM juga termasuk tujuan BUMN yang secara konsisten membangun anak-anak bangsa.</p><p><br></p><p><img src=\"https://www.budiluhur.ac.id/wp-content/uploads/2021/04/20210402_211204.jpg\" height=\"395\" width=\"720\" style=\"display: block; margin: auto;\"></p><p><br></p><p>Sedangkan, pemberian hibah renovasi Bank Sampah Budi Luhur kepada Universitas Budi Luhur sebagai wujud kepedulian dan CSR PT. Pegadaian (Persero) terhadap lingkungan hidup khususnya pengelolaan sampah sesuai dengan sustainable development goals.</p><p><br></p><p><img src=\"https://www.budiluhur.ac.id/wp-content/uploads/2021/04/20210402_205227.jpg\" height=\"405\" width=\"720\" style=\"display: block; margin: auto;\"></p><p><br></p><p>Direktur Jaringan, Operasi dan Penjualan PT. Pegadaian (Persero) menyampaikan alasan pemilihan Universitas Budi Luhur karena selama ini diketahui bahwa Universitas Budi Luhur telah memiliki pengelolaan sampah secara organik dan anorganik sejak 8 tahun yang lalu dan telah memiliki nama besar. Ini dibuktikan dengan Bank Sampah Budi Luhur mendapat penghargaan dari Gubernur DKI Jakarta karena Bank Sampah Budi Luhur secara konsisten melakukan pemilahan sampah</p><p>Selain itu, PT Pegadaian memberikan apresiasi setinggi-tingginya terhadap Bank Sampah Budi Luhur sebagai lembaga pengelolaan sampah dengan kualitas Grade A, sehingga menjadi Bank Sampah percontohan di Indonesia dan menjadikan Bank Sampah Budi Luhur sebagai wisata edukasi daur ulang.</p><p>&nbsp;</p><p>Pada kesempatan ini pula dilakukan peninjauan ke Bank Sampah Budi Luhur yang mendemokan proses pengolahan sampah. Kegiatan akhir dalam acara hari ini adalah diadakannya webinar dengan judul Gue Millennials Melek Financials yang diikuti sekitar 1029 peserta mahasiswa Universitas Budi Luhur.</p><p><br></p><p>Rektor Universitas Budi Luhur Dr. Ir. Wendi Usino, M.Sc., MM. menyampaikan terima kasih dan penghargaan bagi PT. Pegadaian atas bantuan dan kerja sama selama ini yang sangat bermanfaat bagi mahasiswa, dosen maupun para masyarakat sekitar untuk mengelola sampah secara baik, ” Kolaborasi antara Universitas Budi luhur dan Pegadaian terhadap Bank Sampah Budi Luhur sehingga menjadikan wisata edukasi daur ulang yang diharapakan dapat bermanfaat bagi masyarakat di lingkungan hidup” Ungkap Rektor Universitas Budi Luhur.</p><p><br></p><p>Universitas Budi Luhur telah mencatatkan prestasi positif terkait pengelolaan sampah dilingkungan masyarakat serta menjadi motivator dalam hal pengelolaan di lingkungan perguruan tinggi. Hal ini sebagai bentuk konsistensi kampus dalam mendorong kepedulian masyarakat terhadap lingkungan hidup.</p>', 'KA01', 1655104877, 1655053200),
('BA003', 'bank sampah budi luhur raih juara umum dan rekor indonesia award memilah sampah', 'bank-sampah-budi-luhur-raih-juara-umum-dan-rekor-indonesia-award-memilah-sampah', '62ac6ed8b201f.jpeg', '<p><strong>Jakarta –</strong>&nbsp;Universitas Budi Luhur hari ini mendapatkan hadiah 1 unit mobil box atas prestasinya meraih juara umum dari Pegadaian dengan tema “Memilah Sampah Menabung Emas” pada Kamis, 23 September 2021.</p><p><br></p><p><img src=\"https://www.budiluhur.ac.id/wp-content/uploads/2021/09/WhatsApp-Image-2021-09-23-at-12.35.03-2.jpeg\" height=\"738\" width=\"1599\"></p><p><br></p><p>Penyerahan hadiah tersebut dari Pemimpin Wilayah IX Jakarta 2 PT Pegadaian, Hakim Setiawan diberikan ke Rektor Universitas Budi Luhur, Dr. Ir. Wendi Usino, MM, M.Sc dan Koordinator Bank Sampah Budi Luhur, Umi Tutik Asmawi. Penyerahan hadiah itu dilaksanakan di Taman Kupu-Kupu Budi Luhur dengan protokol kesehatan dan jaga jarak.</p><p><br></p><p><img src=\"https://www.budiluhur.ac.id/wp-content/uploads/2021/09/WhatsApp-Image-2021-09-23-at-12.35.02-1.jpeg\" height=\"864\" width=\"1296\"></p><p><br></p><p>Rektor Universitas Budi Luhur, Dr. Ir. Wendi Usino, MM, M.Sc mengucapkan kebanggaan terhadap Bank Sampah Budi Luhur dan berkomitmen untuk terus meningkatkan kepedulian lingkungan yang tujuan akhirnya adalah lingkungan terjaga dan memberi kenyamanan masyarakat.</p><p><br></p><p>“Pagi ini kami menerima hadiah dari pegadaian. Kami mengucapkan terima kasih atas lomba karena lomba ini bermanfaat bagi masyarakat. Universitas Budi Luhur mengutamakan kepedulian lingkungan dan peduli masyarakat. Universitas Budi Luhur sangat aktif dengan dukungan Pegadaian merupakan salah satu dan konsentrasi dalam kegiatan-kegiatan ini upaya kita ke tingkat internasional,” ujar Dr. Wendi.</p><p><br></p><p>“Bahkan Bank Sampah mendapatkan rekor mengajak masyarakat membuat karpet sampah plastik, sudah mendapatkan rekor nasional dan muri untuk jumlah dan luas lebih 100 meter persegi,” tambahnya.</p><p><br></p><p><img src=\"https://www.budiluhur.ac.id/wp-content/uploads/2021/09/WhatsApp-Image-2021-09-23-at-12.35.03.jpeg\" height=\"864\" width=\"1296\"></p><p><br></p><p>Pemimpin Wilayah IX Jakarta 2 PT Pegadaian, Hakim Setiawan mengapresiasi capian Bank Sampah Budi Luhur yang berhasil meraih juara umum dan memecahkan rekor membuat karpet dari bahan sampah plastik.</p><p><br></p><p>“Program ini dari Pegadaian pusat lomba memilah sampah yang berlangsung 3 tahun terakhir dan manfaatnya sangat besar dan ini kita akan menambah pihak melakukan pembinaan baik bank sampah dan banyak lembaga menangani kegiatan ini. </p><p><br></p><p>Karena kita membantu pemerintah mengurangi sampah dan meningkatkan lingkungan sesuai dengan visi Universitas Budi Luhur. Dan sampah kalau kita buang begitu saja kita rugi, sampah yang menghasilkan seperti Universitas Budi Luhur sudah memecahkan rekor membuat karpet dari sampah dan memiliki nilai jual itu sangat luar biasa kalau dijual,” jelasnya.</p><p><br></p><p><img src=\"https://www.budiluhur.ac.id/wp-content/uploads/2021/09/WhatsApp-Image-2021-09-23-at-14.03.08.jpeg\" height=\"864\" width=\"1296\"></p><p><br></p><p>Koordinator Bank Sampah Budi Luhur, Umi Tutik Asmawi, menjelaskan cara mendapatkan bantuan dari CSR, memenangkan lomba juara nasional dan memecahkan rekor pilah sampah. Menurutnya, kegiatan lingkungan dan masyarakat harus dengan melakukan inovasi modern.</p><p><br></p><p>“Kita banyak mitra-mitra, banyak orang mendapatkan proposal untuk CSR tapi khususnya kami di relawan Bank Budi Luhur, kita berkaitan dengan inovasi makanya CSR yang mendatangi kita. Kita membuat inovasi untuk masyarakat dan lingkungan tanpa kita minta akan ada. Kita ada pendampingan, setiap hari kita melayani ke masyarakat lingkungan,” katanya.</p><p><br></p><p><img src=\"https://www.budiluhur.ac.id/wp-content/uploads/2021/09/WhatsApp-Image-2021-09-23-at-12.35.04.jpeg\" height=\"738\" width=\"1599\"></p><p><br></p><p>Bank Sampah Budi Luhur berhasil meraih 3 kategori juara lomba Memilah Sampah Menabung Emas Tahun 2021, yaitu juara 1 untuk kategori akumulasi sampah terbanyak, juara 1 untuk kategori akumulasi saldo tabungan emas tertinggi dan juara 3 untuk kategori akumulasi jumlah partisipan terbanyak. Atas ketiga juara tersebut Bank Sampah Budi Luhur dinobatkan juara umum dan mendapatkan 1 unit mobil box.</p><p><br></p><p>Universitas Budi Luhur juga meraih penghargaan dalam acara World Clean Up Day 2021 berhasil memecahkan rekor gerakan pilah sampah dari rumah dan peserta terbanyak se-Indonesia. Penghargaan tersebut diberikan di gedung Walikota Jakarta Selatan pada 18 September 2021.</p>', 'KA01', 1655467736, 1655398800);

-- --------------------------------------------------------

--
-- Table structure for table `dompet`
--

CREATE TABLE `dompet` (
  `id` int(11) NOT NULL,
  `id_user` varchar(200) DEFAULT NULL,
  `uang` decimal(11,2) NOT NULL DEFAULT '0.00',
  `emas` decimal(65,4) NOT NULL DEFAULT '0.0000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dompet`
--

INSERT INTO `dompet` (`id`, `id_user`, `uang`, `emas`) VALUES
(11, NULL, '0.00', '0.0000'),
(14, '152260110121', '0.00', '0.0000'),
(15, '154140010021', '42000.00', '0.0000'),
(18, '154140010022', '0.00', '0.0000');

-- --------------------------------------------------------

--
-- Table structure for table `jual_sampah`
--

CREATE TABLE `jual_sampah` (
  `no` int(11) NOT NULL,
  `id_transaksi` varchar(200) NOT NULL,
  `id_sampah` varchar(200) NOT NULL,
  `jumlah_kg` decimal(65,2) NOT NULL,
  `harga_nasabah` decimal(11,2) NOT NULL,
  `jumlah_rp` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kategori_artikel`
--

CREATE TABLE `kategori_artikel` (
  `id` varchar(200) NOT NULL,
  `icon` text NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `kategori_utama` tinyint(1) NOT NULL,
  `created_at` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `kategori_artikel`
--

INSERT INTO `kategori_artikel` (`id`, `icon`, `name`, `description`, `kategori_utama`, `created_at`) VALUES
('KA01', '6284a3f5b57d6.png', 'sosialisasi dan edukasi', 'artikel mengenai kegiatan sosialisasi bank sampah budiluhur', 1, 1652859893),
('KA02', '6284a4be57be9.png', 'webinar', 'berisi kegiatan webinar yang dilakukan bank sampah budluhur', 1, 1652860094),
('KA03', '6284a4dba7714.png', 'kkn', 'kkn di bank sampah budiluhur', 1, 1652860123);

-- --------------------------------------------------------

--
-- Table structure for table `kategori_sampah`
--

CREATE TABLE `kategori_sampah` (
  `id` varchar(200) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `kategori_sampah`
--

INSERT INTO `kategori_sampah` (`id`, `name`, `created_at`) VALUES
('KS01', 'plastik', 1663061754),
('KS02', 'kertas', 1663061762),
('KS03', 'logam', 1663061767),
('KS04', 'lain-lain', 1663062026),
('KS05', 'kat xx', 1663151087),
('KS06', 'kat yy', 1663151392);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(13, '2021-11-16-023013', 'App\\Database\\Migrations\\Users', 'default', 'App', 1652608373, 1),
(14, '2021-11-16-023841', 'App\\Database\\Migrations\\KategoriArtikel', 'default', 'App', 1652608374, 1),
(15, '2021-11-16-024046', 'App\\Database\\Migrations\\Artikel', 'default', 'App', 1652608375, 1),
(16, '2021-11-16-031046', 'App\\Database\\Migrations\\KategoriSampah', 'default', 'App', 1652608377, 1),
(17, '2021-11-16-031125', 'App\\Database\\Migrations\\Sampah', 'default', 'App', 1652608377, 1),
(18, '2021-11-16-031158', 'App\\Database\\Migrations\\Transaksi', 'default', 'App', 1652608380, 1),
(19, '2021-11-16-031238', 'App\\Database\\Migrations\\SetorSampah', 'default', 'App', 1652608382, 1),
(20, '2021-11-16-031308', 'App\\Database\\Migrations\\TarikSaldo', 'default', 'App', 1652608384, 1),
(21, '2021-11-16-031352', 'App\\Database\\Migrations\\PindahSaldo', 'default', 'App', 1652608385, 1),
(22, '2021-11-16-031428', 'App\\Database\\Migrations\\JualSampah', 'default', 'App', 1652608387, 1),
(23, '2021-11-16-040233', 'App\\Database\\Migrations\\Wilayah', 'default', 'App', 1652608389, 1),
(24, '2021-11-23-225132', 'App\\Database\\Migrations\\Dompet', 'default', 'App', 1652608390, 1),
(25, '2022-04-08-054206', 'App\\Database\\Migrations\\Penghargaan', 'default', 'App', 1652608391, 1),
(26, '2022-04-08-115035', 'App\\Database\\Migrations\\Mitra', 'default', 'App', 1652608392, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mitra`
--

CREATE TABLE `mitra` (
  `id` int(11) NOT NULL,
  `icon` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mitra`
--

INSERT INTO `mitra` (`id`, `icon`, `name`, `description`) VALUES
(10, '6298a7406d4a5.png', 'pegadaian', '-'),
(11, '6298a772f0ea9.png', 'ksm nyiur', '-'),
(12, '6298a7c9102ac.png', 'budiluhur', '-'),
(13, '6298a7f722dd3.png', 'jagadtani', '-'),
(14, '6298a8258537a.png', 'dinas lingkungan hidup dki jakarta', '-'),
(15, '6298a8524ab41.png', 'dinas ppapp dki jakarta', '-');

-- --------------------------------------------------------

--
-- Table structure for table `penghargaan`
--

CREATE TABLE `penghargaan` (
  `id` int(11) NOT NULL,
  `icon` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `penghargaan`
--

INSERT INTO `penghargaan` (`id`, `icon`, `name`, `description`) VALUES
(6, '6298a8c943584.png', 'juara 3 memilah sampah menabung emas', '-'),
(7, '6298a911b4e57.jpeg', 'green prize from kagoshima university', '-'),
(8, '6298a9555e88d.jpeg', 'juara umum memilah sampah menabung emas', '-'),
(10, '6298d7d0b27d7.jpeg', 'tanda terimakasih IPB', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eligendi alias libero at quis error laborum sunt nobis magni aperiam sequi? Rerum, assumenda ut magnam inventore laboriosam aliquid dicta corporis deserunt beatae itaque repellendus facilis repudiandae cum ullam odio? Veritatis unde, optio, ad nesciunt omnis iste voluptatem vero quae tempore vel delectus doloremque facilis beatae. Autem totam ad non. Voluptatibus vel, adipisci perferendis nesciunt accusantium animi distinctio laudantium totam quaerat quod quidem molestiae! Et dolores, pariatur enim perspiciatis ad voluptatum ut rerum nam nesciunt veritatis quas quidem nulla fugit nihil. Suscipit, aspernatur! Dolore sint beatae quia illum adipisci, a vero harum.');

-- --------------------------------------------------------

--
-- Table structure for table `pindah_saldo`
--

CREATE TABLE `pindah_saldo` (
  `no` int(11) NOT NULL,
  `id_transaksi` varchar(200) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_emas` int(11) NOT NULL DEFAULT '0',
  `hasil_konversi` decimal(65,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sampah`
--

CREATE TABLE `sampah` (
  `id` varchar(200) NOT NULL,
  `id_kategori` varchar(200) NOT NULL,
  `jenis` varchar(40) NOT NULL,
  `harga` int(11) NOT NULL,
  `harga_pusat` int(11) DEFAULT '0',
  `jumlah` decimal(65,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sampah`
--

INSERT INTO `sampah` (`id`, `id_kategori`, `jenis`, `harga`, `harga_pusat`, `jumlah`) VALUES
('S001', 'KS01', 'plastik jenis a', 1200, 1300, '10.00'),
('S002', 'KS02', 'kertas jenis a', 1200, 1300, '12.00'),
('S003', 'KS03', 'logam jenis a', 1200, 1300, '8.00'),
('S004', 'KS04', 'lainnya jenis a', 1200, 1300, '5.00'),
('S005', 'KS05', 'xx jenis 1', 1000, 1200, '0.00'),
('S006', 'KS06', 'kat jenis yy', 1000, 1200, '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `setor_sampah`
--

CREATE TABLE `setor_sampah` (
  `no` int(11) NOT NULL,
  `id_transaksi` varchar(200) NOT NULL,
  `id_sampah` varchar(200) NOT NULL,
  `jumlah_kg` decimal(65,2) NOT NULL,
  `jumlah_rp` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `setor_sampah`
--

INSERT INTO `setor_sampah` (`no`, `id_transaksi`, `id_sampah`, `jumlah_kg`, `jumlah_rp`) VALUES
(22, 'TSS866458421', 'S001', '10.00', '12000.00'),
(23, 'TSS866458421', 'S002', '12.00', '14400.00'),
(24, 'TSS866458421', 'S003', '8.00', '9600.00'),
(25, 'TSS866458421', 'S004', '5.00', '6000.00');

-- --------------------------------------------------------

--
-- Table structure for table `tarik_saldo`
--

CREATE TABLE `tarik_saldo` (
  `no` int(11) NOT NULL,
  `id_transaksi` varchar(200) NOT NULL,
  `jenis_saldo` enum('uang','ubs','antam','galery24') NOT NULL,
  `jumlah_tarik` decimal(65,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `no` int(11) NOT NULL,
  `id` varchar(200) NOT NULL,
  `id_user` varchar(200) NOT NULL,
  `jenis_transaksi` varchar(50) NOT NULL,
  `date` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`no`, `id`, `id_user`, `jenis_transaksi`, `date`) VALUES
(1, 'TSS866458421', '154140010021', 'penyetoran sampah', 1663062000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(200) NOT NULL,
  `email` varchar(30) DEFAULT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(40) NOT NULL,
  `notelp` varchar(14) DEFAULT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `tgl_lahir` varchar(10) NOT NULL DEFAULT '00-00-0000',
  `kelamin` enum('laki-laki','perempuan') NOT NULL,
  `token` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_active` bigint(19) NOT NULL DEFAULT '0',
  `otp` varchar(6) DEFAULT NULL,
  `is_verify` tinyint(1) NOT NULL DEFAULT '0',
  `privilege` varchar(10) NOT NULL,
  `created_at` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `nama_lengkap`, `notelp`, `nik`, `alamat`, `tgl_lahir`, `kelamin`, `token`, `is_active`, `last_active`, `otp`, `is_verify`, `privilege`, `created_at`) VALUES
('152260110121', 'brien.degala24@gmail.com', 'briendegala', 'VsbHDe4AOaepvcU+F2vfqg==', 'brien de gala', '085157554887', '3674036405960006', 'villa bintaro regensi riau 1 blok j2/15', '24-06-1996', 'perempuan', NULL, 1, 1661584457, '558859', 1, 'nasabah', 1661584213),
('154140010021', '466744@domain.com', 'elkoro424', 'TguJ5n10RvnkkoMmedd4vA==', 'muhammad akbar', '085155352499', '3674070310000004', 'Cendana', '12-09-2022', 'laki-laki', NULL, 1, 1663149785, NULL, 1, 'nasabah', 1663126081),
('154140010022', 'elkoro424@gmail.com', 'bagaselkoro', 'mNZ4/iFPgddIrEVvaUcx3A==', 'bagaskoro', '085155352488', '3674070310000006', 'cendana', '15-09-2022', 'laki-laki', NULL, 1, 1663230505, '566086', 0, 'nasabah', 1663230505),
('A002', 'superadmin2@gmail.com', 'superadmin2', '$2y$10$GaRf62v.UAS6BP3I9ONwsOKoaI1XtkLxkzKrTvIKoCIob.X265T5S', 'ini superadmin 2', '08512345678', '0689353083338158', 'indonesia', '18-05-2022', 'laki-laki', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IkEwMDIiLCJ1bmlxdWVpZCI6IjYzMzZlYjg1ZTBkMTEiLCJwYXNzd29yZCI6IiQyeSQxMCRHYVJmNjJ2LlVBUzZCUDNJOU9Od3NPS29hSTFYdGtMeGt6S3JUdklLb0NJb2IuWDI2NVQ1UyIsInByaXZpbGVnZSI6InN1cGVyYWRtaW4iLCJleHBpcmVkIjoxNjY0NjMwMDIxfQ.nIN0dZFIk96IXPm0F_pKzkk_ResVvIR9T4PiyfmcfgU', 0, 1664543621, NULL, 1, 'superadmin', 1652861147),
('A003', NULL, 'superadmin1', '$2y$10$gSHs1j0SlA3gV.66g65mLO2UIeavgIF4j8YriYgQUeWM0J0iPADeO', 'superadmin1', NULL, NULL, NULL, '31-08-2022', 'laki-laki', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IkEwMDMiLCJ1bmlxdWVpZCI6IjYzMmJjZjYwNTMyN2UiLCJwYXNzd29yZCI6IiQyeSQxMCRnU0hzMWowU2xBM2dWLjY2ZzY1bUxPMlVJZWF2Z0lGNGo4WXJpWWdRVWVXTTBKMGlQQURlTyIsInByaXZpbGVnZSI6InN1cGVyYWRtaW4iLCJleHBpcmVkIjoxNjYzOTAxOTIwfQ.lbRqaqqevrrUfRa08wHACcqTTqX3lXYSxlNN2VsJP3A', 0, 1663815520, NULL, 1, 'superadmin', 1661928594),
('A004', NULL, 'superadmin3', '$2y$10$1GJzNnGoGOYvEQRq2Z/MGu96i3BfmW0amt4vsej2JcgxjMZ.1E3vq', 'superadmin3', NULL, NULL, NULL, '31-08-2022', 'laki-laki', NULL, 1, 1661928637, NULL, 1, 'superadmin', 1661928637);

-- --------------------------------------------------------

--
-- Table structure for table `wilayah`
--

CREATE TABLE `wilayah` (
  `id` int(11) NOT NULL,
  `id_user` varchar(200) NOT NULL,
  `kodepos` varchar(10) NOT NULL,
  `kelurahan` varchar(200) NOT NULL,
  `kecamatan` varchar(200) NOT NULL,
  `kota` varchar(200) NOT NULL,
  `provinsi` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `wilayah`
--

INSERT INTO `wilayah` (`id`, `id_user`, `kodepos`, `kelurahan`, `kecamatan`, `kota`, `provinsi`) VALUES
(13, '152260110121', '15226', 'pondok kacang timur', 'pondok aren', 'tangerang selatan', 'banten'),
(14, '154140010021', '15414', 'sarua (serua)', 'ciputat', 'tangerang selatan', 'banten'),
(17, '154140010022', '15414', 'sarua (serua)', 'ciputat', 'tangerang selatan', 'banten');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artikel`
--
ALTER TABLE `artikel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`),
  ADD KEY `artikel_id_kategori_foreign` (`id_kategori`);

--
-- Indexes for table `dompet`
--
ALTER TABLE `dompet`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- Indexes for table `jual_sampah`
--
ALTER TABLE `jual_sampah`
  ADD PRIMARY KEY (`no`),
  ADD KEY `jual_sampah_id_transaksi_foreign` (`id_transaksi`),
  ADD KEY `jual_sampah_id_sampah_foreign` (`id_sampah`);

--
-- Indexes for table `kategori_artikel`
--
ALTER TABLE `kategori_artikel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `kategori_sampah`
--
ALTER TABLE `kategori_sampah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mitra`
--
ALTER TABLE `mitra`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `penghargaan`
--
ALTER TABLE `penghargaan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `pindah_saldo`
--
ALTER TABLE `pindah_saldo`
  ADD PRIMARY KEY (`no`),
  ADD KEY `pindah_saldo_id_transaksi_foreign` (`id_transaksi`);

--
-- Indexes for table `sampah`
--
ALTER TABLE `sampah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jenis` (`jenis`),
  ADD KEY `sampah_id_kategori_foreign` (`id_kategori`);

--
-- Indexes for table `setor_sampah`
--
ALTER TABLE `setor_sampah`
  ADD PRIMARY KEY (`no`),
  ADD KEY `setor_sampah_id_transaksi_foreign` (`id_transaksi`),
  ADD KEY `setor_sampah_id_sampah_foreign` (`id_sampah`);

--
-- Indexes for table `tarik_saldo`
--
ALTER TABLE `tarik_saldo`
  ADD PRIMARY KEY (`no`),
  ADD KEY `tarik_saldo_id_transaksi_foreign` (`id_transaksi`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_id_user_foreign` (`id_user`),
  ADD KEY `no` (`no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD UNIQUE KEY `notelp` (`notelp`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wilayah`
--
ALTER TABLE `wilayah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wilayah_id_user_foreign` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dompet`
--
ALTER TABLE `dompet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `jual_sampah`
--
ALTER TABLE `jual_sampah`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `mitra`
--
ALTER TABLE `mitra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `penghargaan`
--
ALTER TABLE `penghargaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pindah_saldo`
--
ALTER TABLE `pindah_saldo`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `setor_sampah`
--
ALTER TABLE `setor_sampah`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tarik_saldo`
--
ALTER TABLE `tarik_saldo`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wilayah`
--
ALTER TABLE `wilayah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `artikel`
--
ALTER TABLE `artikel`
  ADD CONSTRAINT `artikel_id_kategori_foreign` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_artikel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dompet`
--
ALTER TABLE `dompet`
  ADD CONSTRAINT `dompet_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jual_sampah`
--
ALTER TABLE `jual_sampah`
  ADD CONSTRAINT `jual_sampah_id_sampah_foreign` FOREIGN KEY (`id_sampah`) REFERENCES `sampah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jual_sampah_id_transaksi_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pindah_saldo`
--
ALTER TABLE `pindah_saldo`
  ADD CONSTRAINT `pindah_saldo_id_transaksi_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sampah`
--
ALTER TABLE `sampah`
  ADD CONSTRAINT `sampah_id_kategori_foreign` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_sampah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `setor_sampah`
--
ALTER TABLE `setor_sampah`
  ADD CONSTRAINT `setor_sampah_id_sampah_foreign` FOREIGN KEY (`id_sampah`) REFERENCES `sampah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `setor_sampah_id_transaksi_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tarik_saldo`
--
ALTER TABLE `tarik_saldo`
  ADD CONSTRAINT `tarik_saldo_id_transaksi_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wilayah`
--
ALTER TABLE `wilayah`
  ADD CONSTRAINT `wilayah_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
