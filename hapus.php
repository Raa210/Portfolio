<?php
require_once 'cek_login.php';
require_once 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM pesan WHERE id = :id");
        $stmt->execute([':id' => $id]);
    } catch (PDOException $e) {
        die("Gagal menghapus pesan: " . $e->getMessage());
    }
}

// Redirect kembali ke dashboard setelah menghapus
header("Location: dashboard.php");
exit();
?>
