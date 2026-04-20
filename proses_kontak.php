<?php
// Inisialisasi variabel untuk menyimpan data dan pesan error
$nama = $email = $pesan = "";
$errors = [];
$success_message = "";
$submitted_data = [];

// Mengecek apakah form disubmit menggunakan method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Fungsi untuk membersihkan input data (mencegah XSS)
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // 1. Validasi Nama
    if (empty($_POST["nama"])) {
        $errors[] = "Nama lengkap wajib diisi.";
    } else {
        $nama = sanitize_input($_POST["nama"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $nama)) {
            $errors[] = "Pada kolom Nama, hanya huruf dan spasi yang diperbolehkan.";
        }
    }

    // 2. Validasi Email
    if (empty($_POST["email"])) {
        $errors[] = "Email wajib diisi.";
    } else {
        $email = sanitize_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format email tidak valid.";
        }
    }

    // 3. Validasi Pesan
    if (empty($_POST["pesan"])) {
        $errors[] = "Pesan wajib diisi.";
    } else {
        $pesan = sanitize_input($_POST["pesan"]);
        if (strlen($pesan) < 10) {
            $errors[] = "Pesan terlalu singkat. Mohon tuliskan minimal 10 karakter.";
        }
    }

    // Jika tidak ada error, proses data simulasi berhasil
    if (empty($errors)) {
        $success_message = "Pesan Anda berhasil terkirim! Terima kasih telah menghubungi saya.";
        
        $submitted_data = [
            'Nama Lengkap' => $nama,
            'Email' => $email,
            'Isi Pesan' => $pesan
        ];
    }
} else {
    // Jika diakses secara langsung tanpa melalui form
    header("Location: contact.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesan | Portofolio</title>
    
    <!-- CSS Eksternal -->
    <link rel="stylesheet" href="style.css">
    
    <style>
        /* Desain yang sama seperti halaman kontak */
        header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background-color: transparent !important;
            box-shadow: none !important;
            z-index: 10;
        }

        body {
            background: linear-gradient(to bottom, rgba(22, 38, 89, 0.8), rgba(0, 0, 0, 0.6)), url('img/bg.png') no-repeat center center / cover;
            background-attachment: fixed;
            color: white;
            min-height: 100vh;
        }

        .contact-container {
            max-width: 600px;
            margin: 140px auto 40px auto;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
        }

        h1, p {
            color: white !important;
            text-align: center;
        }

        /* Styling Alert */
        .alert {
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 8px;
            text-align: center;
        }
        
        .alert-success {
            background-color: rgba(46, 204, 113, 0.2);
            border: 1px solid #2ecc71;
            color: #2ecc71;
        }

        .alert-error {
            background-color: rgba(231, 76, 60, 0.2);
            border: 1px solid #e74c3c;
            color: #ffcccc;
            text-align: left;
        }

        .alert-error ul {
            margin-top: 10px;
            margin-bottom: 0;
            padding-left: 20px;
        }

        /* Styling Table */
        .response-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            overflow: hidden;
        }

        .response-table th, .response-table td {
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 15px;
            text-align: left;
            color: #fff;
        }

        .response-table th {
            background-color: rgba(255, 255, 255, 0.1);
            width: 35%;
        }

        .action-links {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn-kembali {
            display: inline-block;
            padding: 10px 20px;
            background-color: transparent;
            color: white;
            border: 2px solid white;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-kembali:hover {
            background-color: white;
            color: #162659 !important;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <span> Rifki Azis </span> 
        </div>
        <nav>
            <ul>
                <li><a href="index.html">Beranda</a></li>
                <li><a href="about.html">Tentang</a></li>
                <li><a href="project.html">Proyek</a></li>
                <li><a href="contact.html">Kontak</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="content-section">
            <div class="contact-container">
                <h1 style="font-weight: normal;">Status Pengiriman</h1>
                
                <?php if (!empty($errors)): ?>
                    <!-- Tampilan Jika Error -->
                    <div class="alert alert-error">
                        <strong style="color: #e74c3c;">Gagal Mengirim Pesan!</strong>
                        <p style="text-align: left; margin-top: 10px; font-size: 14px;">Ditemukan beberapa kesalahan pada data yang Anda masukkan:</p>
                        <ul>
                            <?php foreach ($errors as $err): ?>
                                <li><?php echo $err; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="action-links">
                        <a href="javascript:history.back()" class="btn-kembali">Kembali Perbaiki Form</a>
                    </div>
                <?php else: ?>
                    <!-- Tampilan Jika Sukses -->
                    <div class="alert alert-success">
                        <strong>Sukses!</strong> <?php echo $success_message; ?>
                    </div>
                    

                    <div class="action-links">
                        <a href="contact.html" class="btn-kembali">Kirim Pesan Lain</a>
                        <a href="index.html" class="btn-kembali">Ke Beranda</a>
                    </div>
                <?php endif; ?>

            </div>
        </section>
    </main>

</body>
</html>
