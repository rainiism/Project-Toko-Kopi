<?php
require 'config.php';
$rows = $pdo->query('SELECT * FROM pembeli')->fetchAll();
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Data Pembeli</title><link rel="stylesheet" href="css/style.css"></head><body>
<div class="container">
  <div class="header"><h1>Data Pembeli</h1><div><a href="index.php" class="nav">Dashboard</a><a href="pembeli_add.php" class="nav">Tambah Pembeli</a></div></div>
  <table class="table"><tr><th>ID</th><th>Nama</th><th>Alamat</th><th>Aksi</th></tr>
  <?php foreach($rows as $r): ?>
    <tr>
      <td><?php echo $r['id_pembeli']; ?></td>
      <td><?php echo htmlspecialchars($r['nama_pembeli']); ?></td>
      <td><?php echo htmlspecialchars($r['alamat']); ?></td>
      <td>
        <a href="pembeli_edit.php?id=<?php echo $r['id_pembeli']; ?>" class="btn btn-primary">Edit</a>
        <a href="pembeli_delete.php?id=<?php echo $r['id_pembeli']; ?>" onclick="return confirm('Hapus pembeli?')" class="btn btn-danger">Hapus</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </table>
</div>
</body></html>