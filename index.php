<?php
require 'config.php';
$stmt = $pdo->query('SELECT COUNT(*) AS total_transaksi, COALESCE(SUM(total_harga),0) AS total_pendapatan FROM transaksi');
$stats = $stmt->fetch();
$stmt = $pdo->query('SELECT b.id_barang, b.nama_barang, COALESCE(SUM(t.jumlah),0) AS total_terjual
                     FROM barang b
                     LEFT JOIN transaksi t ON b.id_barang = t.id_barang
                     GROUP BY b.id_barang
                     ORDER BY total_terjual DESC
                     LIMIT 5');
$best = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Tambah Barang</title>
  <link rel="stylesheet" href="css/style.css?v=7">
</head>

<body>
<div class="navbar">
  <a href="barang_list.php">Data Barang</a>
  <a href="pembeli_list.php">Data Pembeli</a>
  <a href="transaksi_list.php">Data Transaksi</a>
  <a href="laporan.php">Laporan</a>
</div>

  </div>

  <div>
    <h3>Statistik Singkat</h3>
    <p>Total Transaksi: <span class="badge"><?php echo $stats['total_transaksi']; ?></span></p>
    <p>Total Pendapatan: <span class="badge">Rp <?php echo number_format($stats['total_pendapatan'],0,',','.'); ?></span></p>
  </div>

  <div>
    <h3>Barang Terlaris</h3>
    <table class="table">
      <tr><th>No</th><th>Nama Barang</th><th>Terjual (jumlah)</th></tr>
      <?php $n=1; foreach($best as $b): ?>
        <tr>
          <td><?php echo $n++; ?></td>
          <td><?php echo htmlspecialchars($b['nama_barang']); ?></td>
          <td><?php echo (int)$b['total_terjual']; ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>

</div>
</body>
</html>