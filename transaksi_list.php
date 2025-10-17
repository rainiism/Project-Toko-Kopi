<?php
require 'config.php';
$where = '';
$params = [];
if(!empty($_GET['from']) && !empty($_GET['to'])){
  $where = ' WHERE tanggal BETWEEN ? AND ? ';
  $params[] = $_GET['from'].' 00:00:00';
  $params[] = $_GET['to'].' 23:59:59';
}
$stmt = $pdo->prepare('SELECT t.*, p.nama_pembeli, b.nama_barang FROM transaksi t JOIN pembeli p ON t.id_pembeli=p.id_pembeli JOIN barang b ON t.id_barang=b.id_barang '.$where.' ORDER BY tanggal DESC');
$stmt->execute($params);
$rows = $stmt->fetchAll();
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Transaksi</title><link rel="stylesheet" href="css/style.css"></head><body>
<div class="container">
  <div class="header"><h1>Data Transaksi</h1><div><a href="index.php" class="nav">Dashboard</a><a href="transaksi_add.php" class="nav">Tambah Transaksi</a></div></div>
  <form method="get">
    <label>Filter Tanggal From:</label><input class="input" type="date" name="from" value="<?php echo htmlspecialchars($_GET['from'] ?? ''); ?>">
    <label>To:</label><input class="input" type="date" name="to" value="<?php echo htmlspecialchars($_GET['to'] ?? ''); ?>">
    <button class="btn btn-primary">Filter</button>
  </form>
  <table class="table"><tr><th>ID</th><th>Nama Pembeli</th><th>Nama Barang</th><th>Jumlah</th><th>Total</th><th>Tanggal</th><th>Aksi</th></tr>
  <?php foreach($rows as $r): ?>
    <tr>
      <td><?php echo $r['id_transaksi']; ?></td>
      <td><?php echo htmlspecialchars($r['nama_pembeli']); ?></td>
      <td><?php echo htmlspecialchars($r['nama_barang']); ?></td>
      <td><?php echo $r['jumlah']; ?></td>
      <td>Rp <?php echo number_format($r['total_harga'],0,',','.'); ?></td>
      <td><?php echo $r['tanggal']; ?></td>
      <td>
        <a href="transaksi_delete.php?id=<?php echo $r['id_transaksi']; ?>" onclick="return confirm('Hapus transaksi?')" class="btn btn-danger">Hapus</a>
      </td>
    </tr>
  <?php endforeach; ?></table>
</div></body></html>