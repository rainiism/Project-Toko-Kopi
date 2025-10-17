<?php
require 'config.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM barang WHERE id_barang = ?');
$stmt->execute([$id]);
$item = $stmt->fetch();
if(!$item){ echo 'Barang tidak ditemukan'; exit; }
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $nama = trim($_POST['nama']);
  $harga = (float)$_POST['harga'];
  $stok = (int)$_POST['stok'];
  if($nama === '') $errors[] = 'Nama barang harus diisi.';
  if($harga < 0) $errors[] = 'Harga tidak boleh negatif.';
  if($stok < 0) $errors[] = 'Stok tidak boleh negatif.';
  if(empty($errors)){
    $stmt = $pdo->prepare('UPDATE barang SET nama_barang=:n,harga=:h,stok=:s WHERE id_barang=:id');
    $stmt->execute([':n'=>$nama,':h'=>$harga,':s'=>$stok,':id'=>$id]);
    header('Location: barang_list.php'); exit;
  }
}
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Edit Barang</title><link rel="stylesheet" href="css/style.css"></head><body>
<div class="container">
  <h2>Edit Barang</h2>
  <?php if($errors) foreach($errors as $e) echo '<p style="color:red">'.htmlspecialchars($e).'</p>'; ?>
  <form method="post">
    <div class="form-group"><label>Nama Barang</label><input class="input" name="nama" value="<?php echo htmlspecialchars($item['nama_barang']); ?>"></div>
    <div class="form-group"><label>Harga</label><input class="input" name="harga" type="number" step="0.01" value="<?php echo $item['harga']; ?>"></div>
    <div class="form-group"><label>Stok</label><input class="input" name="stok" type="number" value="<?php echo $item['stok']; ?>"></div>
    <button class="btn btn-primary">Simpan</button>
  </form>
  <p><a href="barang_list.php">Kembali</a></p>
</div></body></html>