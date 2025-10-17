<?php
require 'config.php';
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $nama = trim($_POST['nama']);
  $alamat = trim($_POST['alamat']);
  if($nama==='') $errors[]='Nama pembeli harus diisi.';
  if(empty($errors)){
    $pdo->prepare('INSERT INTO pembeli (nama_pembeli,alamat) VALUES (?,?)')->execute([$nama,$alamat]);
    header('Location: pembeli_list.php'); exit;
  }
}
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Tambah Pembeli</title><link rel="stylesheet" href="css/style.css"></head><body>
<div class="container">
  <h2>Tambah Pembeli</h2>
  <?php if($errors) foreach($errors as $e) echo '<p style="color:red">'.htmlspecialchars($e).'</p>'; ?>
  <form method="post">
    <div class="form-group"><label>Nama</label><input class="input" name="nama"></div>
    <div class="form-group"><label>Alamat</label><textarea class="input" name="alamat"></textarea></div>
    <button class="btn btn-primary">Simpan</button>
  </form>
  <p><a href="pembeli_list.php">Kembali</a></p>
</div></body></html>