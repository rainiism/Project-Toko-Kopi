<?php
require 'config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama  = strtoupper(trim($_POST['nama'] ?? ''));
    $harga = isset($_POST['harga']) ? (float)$_POST['harga'] : 0;
    $stok  = isset($_POST['stok']) ? (int)$_POST['stok'] : 0;

    // Validasi
    if ($nama === '') {
        $errors[] = 'Nama barang harus diisi.';
    }
    if ($harga < 0) {
        $errors[] = 'Harga tidak boleh negatif.';
    }
    if ($stok < 0) {
        $errors[] = 'Stok tidak boleh negatif.';
    }

    // Simpan jika tidak ada error
    if (count($errors) === 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO barang (nama_barang, harga, stok) VALUES (:n, :h, :s)");
            $stmt->execute([
                ':n' => $nama,
                ':h' => $harga,
                ':s' => $stok
            ]);
            header('Location: barang_list.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Gagal menyimpan data: ' . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Tambah Barang | Toko Kopi</title>
  <link rel="stylesheet" href="css/style.css?v=10">
  <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>
  <div class="navbar">
      <a href="index.php">Dashboard</a>
      <a href="barang_list.php">Data Barang</a>
      <a href="pembeli_list.php">Data Pembeli</a>
      <a href="transaksi_list.php">Data Transaksi</a>
      <a href="laporan.php">Laporan</a>
  </div>

  <div class="container">
    <h2>Tambah Barang</h2>

    <?php if (!empty($errors)): ?>
      <div class="errors">
        <?php foreach ($errors as $e): ?>
          <div><?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="post" action="">
      <div class="form-group">
        <label for="nama">Nama Barang (disimpan HURUF BESAR)</label>
        <input id="nama" class="input" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
      </div>

      <div class="form-group">
        <label for="harga">Harga (per unit)</label>
        <input id="harga" class="input" name="harga" type="number" step="0.01" value="<?= htmlspecialchars($_POST['harga'] ?? '') ?>" required>
      </div>

      <div class="form-group">
        <label for="stok">Stok</label>
        <input id="stok" class="input" name="stok" type="number" value="<?= htmlspecialchars($_POST['stok'] ?? '0') ?>" required>
      </div>

      <button class="btn" type="submit">💾 Simpan</button>
      <a href="barang_list.php" class="btn kembali">← Kembali</a>
    </form>
  </div>

  <footer class="footer">
    <p>Aplikasi Penjualan Kopi Sederhana — PHP Native</p>
  </footer>
</body>
</html>