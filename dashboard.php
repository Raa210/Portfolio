<?php
require_once 'cek_login.php';
require_once 'koneksi.php';

// Ambil semua data pesan, diurutkan dari yang terbaru
$stmt = $pdo->query("SELECT * FROM pesan ORDER BY created_at DESC");
$pesan_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Portofolio</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

    <header class="dash-header">
        <h1>Dashboard Admin</h1>
        <nav class="dash-nav">
            <span>Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="index.html" target="_blank">Lihat Web</a>
            <a href="logout.php" class="btn-logout">Logout</a>
        </nav>
    </header>

    <div class="dash-container">
        <div class="dash-header-content">
            <h2>Daftar Pesan Masuk</h2>
            <a href="tambah.php" class="btn btn-primary">+ Tambah Pesan</a>
        </div>

        <div class="table-responsive">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Nama</th>
                        <th width="20%">Email</th>
                        <th width="30%">Cuplikan Pesan</th>
                        <th width="10%">Tanggal</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($pesan_list) > 0): ?>
                        <?php $no = 1; foreach ($pesan_list as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td>
                                <?php 
                                    $cuplikan = htmlspecialchars($row['isi_pesan']);
                                    echo (strlen($cuplikan) > 30) ? substr($cuplikan, 0, 30) . '...' : $cuplikan; 
                                ?>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                            <td class="action-buttons">
                                <a href="lihat.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">Lihat</a>
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pesan ini?');">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">Belum ada pesan masuk.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
