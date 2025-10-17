# Aplikasi Penjualan Sederhana - Toko Kopi

Instruksi singkat:
1. Pastikan XAMPP berjalan (Apache + MySQL).
2. Buka phpMyAdmin -> import `toko_kopi.sql` atau jalankan SQL di bawah untuk membuat database dan tabel.
3. Letakkan folder `toko_kopi` di `htdocs` (mis. C:\xampp\htdocs\toko_kopi).
4. Akses aplikasi di: http://localhost/toko_kopi/

File penting:
- toko_kopi.sql  (skrip pembuatan database + trigger)
- css/style.css
- config.php (koneksi DB)
- index.php (dashboard)
- barang_* (CRUD barang)
- pembeli_* (CRUD pembeli)
- transaksi_* (CRUD transaksi + filter)
- laporan.php (laporan / export cetak sederhana)

Catatan:
- Validasi utama dilakukan di PHP, dan trigger DB menambah lapisan keamanan (mis. stok, total_harga, uppercase nama).
- Jika MySQL versi Anda tidak mendukung CHECK atau SIGNAL, Anda bisa menghapusnya dan mengandalkan validasi PHP.
- Untuk export PDF, integrasikan library seperti TCPDF/FPDF (opsional).
