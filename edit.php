<?php
require_once 'cek_login.php';
require_once 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];
$error = "";
$success_message = "";

// Mengambil data saat ini
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $stmt = $pdo->prepare("SELECT * FROM pesan WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $pesan = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pesan) {
        die("Pesan tidak ditemukan.");
    }
}

// Memproses pembaruan data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $isi_pesan = trim($_POST['pesan']);

    if (empty($nama) || empty($email) || empty($isi_pesan)) {
        $error = "Semua kolom wajib diisi!";
        // Pertahankan nilai jika error
        $pesan = ['id' => $id, 'nama' => $nama, 'email' => $email, 'isi_pesan' => $isi_pesan];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
        $pesan = ['id' => $id, 'nama' => $nama, 'email' => $email, 'isi_pesan' => $isi_pesan];
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE pesan SET nama = :nama, email = :email, isi_pesan = :pesan WHERE id = :id");
            $stmt->execute([
                ':nama' => $nama,
                ':email' => $email,
                ':pesan' => $isi_pesan,
                ':id' => $id
            ]);
            $success_message = "Pesan berhasil diubah.";
            // Perbarui data yang ditampilkan
            $stmt = $pdo->prepare("SELECT * FROM pesan WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $pesan = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $error = "Gagal memperbarui: " . $e->getMessage();
            $pesan = ['id' => $id, 'nama' => $nama, 'email' => $email, 'isi_pesan' => $isi_pesan];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesan | Dashboard Admin</title>
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
            <h2>Edit Pesan Masuk</h2>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <form action="edit.php?id=<?php echo htmlspecialchars($id); ?>" method="POST">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" class="form-control" value="<?php echo htmlspecialchars($pesan['nama']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($pesan['email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="pesan">Isi Pesan</label>
                <textarea id="pesan" name="pesan" class="form-control" rows="5" required><?php echo htmlspecialchars($pesan['isi_pesan']); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="dashboard.php" class="btn btn-warning" style="margin-left:10px;">Batal</a>
        </form>
    </div>

</body>
</html>
