<?php
require 'config.php';
$id = (int)($_GET['id'] ?? 0);
$pdo->prepare('DELETE FROM barang WHERE id_barang = ?')->execute([$id]);
header('Location: barang_list.php'); exit;
?>