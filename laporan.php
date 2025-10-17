<?php
require 'config.php';
$summary = $pdo->query('SELECT COUNT(*) AS total_transaksi, COALESCE(SUM(total_harga),0) AS total_pendapatan FROM transaksi')->fetch();
$rows = $pdo->query('SELECT t.*, p.nama_pembeli, b.nama_barang FROM transaksi t JOIN pembeli p ON t.id_pembeli=p.id_pembeli JOIN barang b ON t.id_barang=b.id_barang ORDER BY t.tanggal DESC')->fetchAll();
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Laporan</title><link rel="stylesheet" href="css/style.css"></head><body>
<div class="container">
  <h2>Laporan Transaksi</h2>
  <p>Total Transaksi: <?php echo $summary['total_transaksi']; ?></p>
  <p>Total Pendapatan: Rp <?php echo number_format($summary['total_pendapatan'],0,',','.'); ?></p>
  <p><a href="javascript:window.print()" class="btn">Cetak Halaman</a></p>
  <table class="table"><tr><th>ID</th><th>Pembeli</th><th>Barang</th><th>Jumlah</th><th>Total</th><th>Tanggal</th></tr>
  <?php foreach($rows as $r): ?>
    <tr>
      <td><?php echo $r['id_transaksi']; ?></td>
      <td><?php echo htmlspecialchars($r['nama_pembeli']); ?></td>
      <td><?php echo htmlspecialchars($r['nama_barang']); ?></td>
      <td><?php echo $r['jumlah']; ?></td>
      <td>Rp <?php echo number_format($r['total_harga'],0,',','.'); ?></td>
      <td><?php echo $r['tanggal']; ?></td>
    </tr>
  <?php endforeach; ?></table>
</div></body></html>