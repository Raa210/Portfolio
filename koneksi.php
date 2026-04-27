<?php
// koneksi.php
$db_file = __DIR__ . '/database.sqlite';
$is_new_db = !file_exists($db_file);

try {
    // Koneksi ke SQLite
    $pdo = new PDO("sqlite:" . $db_file);
    
    // Set agar PDO menampilkan error/exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Jika database baru dibuat, inisialisasi tabel
    if ($is_new_db) {
        // Buat tabel users
        $pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL
        )");
        
        // Input user default (admin / admin123)
        $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->execute([':username' => 'admin', ':password' => $password_hash]);

        // Buat tabel pesan
        $pdo->exec("CREATE TABLE IF NOT EXISTS pesan (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nama TEXT NOT NULL,
            email TEXT NOT NULL,
            isi_pesan TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
    }
    
} catch(PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
