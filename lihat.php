<?php
require_once 'cek_login.php';
require_once 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM pesan WHERE id = :id");
$stmt->execute([':id' => $id]);
$pesan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pesan) {
    die("Pesan tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesan | Dashboard Admin</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .detail-box {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 4px;
            margin-top: 20px;
        }
        .detail-item {
            margin-bottom: 15px;
        }
        .detail-item label {
            display: block;
            font-weight: 600;
            color: #162659;
            margin-bottom: 5px;
        }
        .detail-item p {
            margin: 0;
            line-height: 1.6;
        }
    </style>
</head>
<body>

    <header class="dash-header">
        <h1>Dashboard Admin</h1>
        <nav class="dash-nav">
            <a href="dashboard.php">Kembali ke Dashboard</a>
            <a href="logout.php" class="btn-logout">Logout</a>
        </nav>
    </header>

    <div class="dash-container">
        <div class="dash-header-content">
            <h2>Detail Pesan</h2>
        </div>

        <div class="detail-box">
            <div class="detail-item">
                <label>Tanggal Dikirim (Waktu)</label>
                <p><?php echo date('d F Y, H:i:s', strtotime($pesan['created_at'])); ?></p>
            </div>
            <div class="detail-item">
                <label>Nama Pengirim</label>
                <p><?php echo htmlspecialchars($pesan['nama']); ?></p>
            </div>
            <div class="detail-item">
                <label>Email Pengirim</label>
                <p><a href="mailto:<?php echo htmlspecialchars($pesan['email']); ?>"><?php echo htmlspecialchars($pesan['email']); ?></a></p>
            </div>
            <div class="detail-item" style="border-top:1px solid #ddd; padding-top:15px; margin-top:15px;">
                <label>Isi Pesan</label>
                <p><?php echo nl2br(htmlspecialchars($pesan['isi_pesan'])); ?></p>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <a href="dashboard.php" class="btn btn-primary">Kembali</a>
            <a href="edit.php?id=<?php echo $pesan['id']; ?>" class="btn btn-warning">Edit</a>
            <a href="hapus.php?id=<?php echo $pesan['id']; ?>" class="btn btn-danger" onclick="return confirm('Hapus pesan ini?');">Hapus</a>
        </div>
    </div>

</body>
</html>
