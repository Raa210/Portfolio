<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'koneksi.php';
    
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Portofolio</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(to bottom, rgba(22, 38, 89, 0.9), rgba(22, 38, 89, 0.9)), url('img/bg.png') no-repeat center center / cover;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.3);
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: white;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: white !important;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid rgba(255, 255, 255, 0.6);
            border-radius: 4px;
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            font-size: 16px;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #fff;
            background-color: rgba(255, 255, 255, 0.25);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: transparent;
            color: white;
            border: 2px solid white;
            border-radius: 30px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: white;
            color: #162659;
        }

        .alert-error {
            background-color: rgba(231, 76, 60, 0.2);
            border: 1px solid #e74c3c;
            color: #ffcccc;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            text-decoration: none;
        }

        .back-link:hover {
            color: white;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Admin Login</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert-error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>

        <a href="index.html" class="back-link">&larr; Kembali ke Portofolio</a>
    </div>

</body>
</html>
