-- SIPEPA Database Snapshot Backup (Pre-Migration: 2026_11_12_043442_create_dokumentasi_kegiatan_table)
-- Target Database: pendidikan_ansuh
-- Generated at: 2026-11-12 12:11:30

SET FOREIGN_KEY_CHECKS=0;

-- Table: asrama
DELETE FROM `asrama`;
INSERT INTO `asrama` (`id`, `kode_asrama`, `nama_asrama`, `wilayah`, `alamat`, `penanggung_jawab`, `nomor_telepon`, `email`, `jam_operasional`, `status_aktif`, `created_at`, `updated_at`) VALUES
(4, 'ASR-001', 'Asrama 01', 'Karawang', 'Jl. Panatayuda I No.15, Nagasari, Kec. Karawang Bar., Karawang, Jawa Barat 41314', 'Abi Drajat', '081283517771', NULL, '08:00 - 17:00 WIB', 1, '2026-11-04 06:35:57', '2026-11-04 06:35:57'),
(5, 'ASR-002', 'Asrama 02', 'Purwakarta', 'Cigadung, Kec. Subang, Kabupaten Subang, Jawa Barat 41211', 'Abi wawan', '081312167152', NULL, '08:00 - 17:00 WIB', 1, '2026-11-11 04:56:54', '2026-11-11 04:57:28'),
(6, 'ASR-003', 'Asrama 03', 'Jakarta', 'Jl. Cilangkap Raya No.51, RT.6/RW.3, Cipayung, Kec. Cipayung, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13840', 'Abi Asep', '08115848086', NULL, '08:00 - 17:00 WIB', 1, '2026-11-11 04:59:25', '2026-11-11 04:59:25'),
(7, 'ASR-004', 'Asrama 04', 'Bekasi', 'Jalan Pandan Blok B.9 No, 5, Mekarsari, Kec. Tambun Sel., Kabupaten Bekasi, Jawa Barat 17510', 'Abi Jajat', '081283517774', NULL, '08:00 - 17:00 WIB', 1, '2026-11-11 05:01:31', '2026-11-11 05:01:31');

-- Table: users
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `role`, `is_primary_admin`, `asrama_id`, `status_aktif`, `locale`, `theme`, `timezone`, `date_format`, `email_verified_at`, `password`, `encrypted_password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Siti Hasanah', 'Adminpusat1@gmail.com', 'admin_pusat', 1, NULL, 1, 'id', 'light', 'Asia/Jakarta', 'd/m/Y', '2026-10-29 10:10:56', '$2y$12$8GuD/F/6JfcxqPS99h8A4uekvZ1Tlnbk2/pV3WtbL8VZAmWlBvLBe', 'eyJpdiI6ImZ4R0VMMmxUUkltYnVwNEJFZGwwSXc9PSIsInZhbHVlIjoiTS9qdFZua0tYc2Jkb2ZBT3M3OTBmVnJUSXNOaCt1SUE2alVtMUR3dzlrZz0iLCJtYWMiOiJkNTlhOTYxMGM4Nzk2MDI2YjVmYTE3ZGI2N2YyMjI3MmFhMWM5NmExNzM2NDNkNDEwZWIzNjJkYjMwNDI1MTcxIiwidGFnIjoiIn0=', 'ykbLG9K35u576KJ7tnGoLZmw5Cp7mcpazaItaYhkEBQ03R4KgPTyxTyz3YGm', NULL, '2026-10-29 10:10:56'),
(5, 'Admin Pusat Developer', 'admin.dev@simpena.test', 'admin_pusat', 0, NULL, 1, 'id', 'light', 'Asia/Jakarta', 'd/m/Y', '2026-10-29 10:10:56', '$2y$12$QB9mihJN2J05g58UbO2WJuCGR1GAVcazkPPHXUEuEHlJ3ta2R/mk.', 'eyJpdiI6IjhiUG0zSERxMlRaQm9hc3Y2VDVKYlE9PSIsInZhbHVlIjoiZnd2RDVhYkRWYW5pWmtRQ1NnN0FOdGQ3MFBXYXRGM1RyMUh0NVN2T2ZPOD0iLCJtYWMiOiJmZGE0ZmM0ZGEyNDNjNTFlYmY2YjRlZTdjOTUzYTA0ODUzMzkyMmMzOWYzZjVjODY0YzEwMmVjNTIxNjA3MGM2IiwidGFnIjoiIn0=', NULL, '2026-10-29 10:10:56', '2026-10-29 10:10:56'),
(6, 'Admin cabang 1', 'admcabang01@gmail.com', 'admin_cabang', 0, 4, 1, 'id', 'light', 'Asia/Jakarta', 'd/m/Y', '2026-11-04 06:36:40', '$2y$12$qdR.FwMHy/ESVrjyS4EfDeU71lxpnc4tIsFXvmv8HMntC4l1FrtAy', 'eyJpdiI6IlhpNHlmWWZkVzlUd0tnb1ZEakRBY1E9PSIsInZhbHVlIjoiRG02NldKMkIwRjJxaUdySWhra1dRdz09IiwibWFjIjoiYWFhMTQyMDRjNjAzMjcwM2Q4NmNhOWExY2E3N2IxNjBmOGE1ZjUzNTU2Y2UxMDFiOTk4NThmYmZlN2UzYmIwYSIsInRhZyI6IiJ9', 'AQmT1NQrMKTQZBNyRFQEjNiPmUkjlJygY7yrj4YIJrONz6Dq9Xnl6dmQ8mM2', '2026-11-04 06:36:40', '2026-11-04 06:36:40');

-- Table: anak_asuh
DELETE FROM `anak_asuh`;
INSERT INTO `anak_asuh` (`id`, `asrama_id`, `nomor_induk`, `nama_lengkap`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `jenjang_pendidikan`, `kelas`, `tanggal_masuk`, `status_aktif`, `catatan`, `created_at`, `updated_at`) VALUES
(3, 4, 'AB-2026-001', 'Abid  Al Mubarok', 'L', 'Subang', '2016-07-12', 'SD', '4', '2021-08-19', 1, 'Minus matanya', '2026-11-11 05:05:05', '2026-11-11 05:05:05');

-- Table: kegiatan_harian
DELETE FROM `kegiatan_harian`;
INSERT INTO `kegiatan_harian` (`id`, `asrama_id`, `tanggal_kegiatan`, `kategori`, `nama_kegiatan`, `jam_mulai`, `jam_selesai`, `deskripsi`, `jumlah_peserta`, `catatan_tambahan`, `foto_dokumentasi`, `penginput_id`, `created_at`, `updated_at`) VALUES
(1, 4, '2026-11-04', 'belajar', 'Belajar fikih wudhu', '08:00:00', '09:30:00', 'hadir semua', 9, NULL, NULL, 6, '2026-11-04 06:55:41', '2026-11-04 06:55:41'),
(2, 4, '2026-11-11', 'acara_donatur', 'Acara di luar asrama', '18:00:00', '21:30:00', NULL, 0, NULL, 'kegiatan-harian/vE09JddXjCzRY374ATZRmxlU1DKC7vtipfYThlDs.jpg', 6, '2026-11-11 05:22:39', '2026-11-11 05:22:39');

SET FOREIGN_KEY_CHECKS=1;

