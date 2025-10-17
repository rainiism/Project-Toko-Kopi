<?php
require 'config.php';
$id = (int)($_GET['id'] ?? 0);
$pdo->prepare('DELETE FROM transaksi WHERE id_transaksi = ?')->execute([$id]);
header('Location: transaksi_list.php'); exit;
?>