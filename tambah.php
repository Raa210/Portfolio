<?php
require_once 'cek_login.php';
require_once 'koneksi.php';

$error = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $pesan = trim($_POST['pesan']);

    if (empty($nama) || empty($email) || empty($pesan)) {
        $error = "Semua kolom wajib diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO pesan (nama, email, isi_pesan) VALUES (:nama, :email, :pesan)");
            $stmt->execute([
                ':nama' => $nama,
                ':email' => $email,
                ':pesan' => $pesan
            ]);
            $success_message = "Pesan baru berhasil ditambahkan.";
            // Kosongkan form setelah sukses
            $nama = $email = $pesan = "";
        } catch (PDOException $e) {
            $error = "Gagal menyimpan: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pesan | Dashboard Admin</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .alert-success { background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
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
            <h2>Tambah Pesan Manual</h2>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <form action="tambah.php" method="POST">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" class="form-control" value="<?php echo isset($nama) ? htmlspecialchars($nama) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="pesan">Isi Pesan</label>
                <textarea id="pesan" name="pesan" class="form-control" rows="5" required><?php echo isset($pesan) ? htmlspecialchars($pesan) : ''; ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Pesan</button>
            <a href="dashboard.php" class="btn btn-warning" style="margin-left:10px;">Batal</a>
        </form>
    </div>

</body>
</html>
