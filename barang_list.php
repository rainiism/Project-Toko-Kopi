<?php
require 'config.php';
$rows = $pdo->query('SELECT * FROM barang')->fetchAll();
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Data Barang</title><link rel="stylesheet" href="css/style.css"></head><body>
<div class="container">
  <div class="header"><h1>Data Barang</h1><div><a href="index.php" class="nav">Dashboard</a><a href="barang_add.php" class="nav">Tambah Barang</a></div></div>
  <table class="table"><tr><th>ID</th><th>Nama</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
  <?php foreach($rows as $r): ?>
    <tr>
      <td><?php echo $r['id_barang']; ?></td>
      <td><?php echo htmlspecialchars($r['nama_barang']); ?></td>
      <td>Rp <?php echo number_format($r['harga'],0,',','.'); ?></td>
      <td><?php echo $r['stok']; ?></td>
      <td>
        <a href="barang_edit.php?id=<?php echo $r['id_barang']; ?>" class="btn btn-primary">Edit</a>
        <a href="barang_delete.php?id=<?php echo $r['id_barang']; ?>" onclick="return confirm('Hapus barang?')" class="btn btn-danger">Hapus</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </table>
</div>
</body></html>