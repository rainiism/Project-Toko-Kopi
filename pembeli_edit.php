<?php
require 'config.php';
$id=(int)($_GET['id']??0);
$stmt=$pdo->prepare('SELECT * FROM pembeli WHERE id_pembeli=?'); $stmt->execute([$id]); $item=$stmt->fetch(); if(!$item){echo 'Tidak ditemukan'; exit;}
$errors=[]; if($_SERVER['REQUEST_METHOD']==='POST'){
  $nama=trim($_POST['nama']); $alamat=trim($_POST['alamat']); if($nama==='') $errors[]='Nama harus diisi'; if(empty($errors)){ $pdo->prepare('UPDATE pembeli SET nama_pembeli=?,alamat=? WHERE id_pembeli=?')->execute([$nama,$alamat,$id]); header('Location:pembeli_list.php'); exit; }
}
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Edit Pembeli</title><link rel="stylesheet" href="css/style.css"></head><body>
<div class="container"><h2>Edit Pembeli</h2><?php if($errors) foreach($errors as $e) echo '<p style="color:red">'.htmlspecialchars($e).'</p>'; ?>
<form method="post"><div class="form-group"><label>Nama</label><input class="input" name="nama" value="<?php echo htmlspecialchars($item['nama_pembeli']); ?>"></div>
<div class="form-group"><label>Alamat</label><textarea class="input" name="alamat"><?php echo htmlspecialchars($item['alamat']); ?></textarea></div>
<button class="btn btn-primary">Simpan</button></form>
<p><a href="pembeli_list.php">Kembali</a></p></div></body></html>