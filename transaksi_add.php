<?php
require 'config.php';
$errors = [];
$barang = $pdo->query('SELECT * FROM barang')->fetchAll();
$pembeli = $pdo->query('SELECT * FROM pembeli')->fetchAll();
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $id_pembeli = (int)$_POST['id_pembeli'];
  $id_barang = (int)$_POST['id_barang'];
  $jumlah = (int)$_POST['jumlah'];
  if($jumlah <= 0) $errors[] = 'Jumlah harus lebih dari 0.';
  $stmt = $pdo->prepare('SELECT stok,harga FROM barang WHERE id_barang = ?'); $stmt->execute([$id_barang]); $b = $stmt->fetch();
  if(!$b) $errors[] = 'Barang tidak ditemukan.';
  elseif($b['stok'] < $jumlah) $errors[] = 'Stok tidak mencukupi (tersisa: '.$b['stok'].').';
  if(empty($errors)){
    $stmt = $pdo->prepare('INSERT INTO transaksi (id_pembeli,id_barang,jumlah) VALUES (?,?,?)');
    $stmt->execute([$id_pembeli,$id_barang,$jumlah]);
    header('Location: transaksi_list.php'); exit;
  }
}
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Tambah Transaksi</title><link rel="stylesheet" href="css/style.css"></head><body>
<div class="container">
  <h2>Tambah Transaksi</h2>
  <?php if($errors) foreach($errors as $e) echo '<p style="color:red">'.htmlspecialchars($e).'</p>'; ?>
  <form method="post">
    <div class="form-group"><label>Pembeli</label>
      <select name="id_pembeli">
        <?php foreach($pembeli as $p) echo '<option value="'.$p['id_pembeli'].'">'.htmlspecialchars($p['nama_pembeli']).'</option>'; ?>
      </select>
    </div>
    <div class="form-group"><label>Barang</label>
      <select id="id_barang" name="id_barang" onchange="updateHarga()">
        <?php foreach($barang as $b) echo '<option value="'.$b['id_barang'].'" data-harga="'.$b['harga'].'" data-stok="'.$b['stok'].'">'.htmlspecialchars($b['nama_barang']).' (stok: '.$b['stok'].')</option>'; ?>
      </select>
    </div>
    <div class="form-group"><label>Jumlah</label><input id="jumlah" class="input" name="jumlah" type="number" value="1" onchange="updateHarga()"></div>
    <div class="form-group"><label>Harga per unit</label><input id="harga_unit" class="input" disabled></div>
    <div class="form-group"><label>Total Harga</label><input id="total_harga" class="input" disabled></div>
    <button class="btn btn-primary">Simpan</button>
  </form>
  <p><a href="transaksi_list.php">Kembali</a></p>
</div>
<script>
function updateHarga(){
  var sel = document.getElementById('id_barang');
  var opt = sel.options[sel.selectedIndex];
  var harga = parseFloat(opt.getAttribute('data-harga')) || 0;
  var stok = parseInt(opt.getAttribute('data-stok')) || 0;
  var jumlah = parseInt(document.getElementById('jumlah').value) || 0;
  document.getElementById('harga_unit').value = 'Rp ' + harga.toLocaleString();
  document.getElementById('total_harga').value = 'Rp ' + (harga*jumlah).toLocaleString();
}
updateHarga();
</script>
</body></html>