<?php
include 'config.php';
session_start();

// Jika sudah login, langsung lempar ke utama, tidak usah login lagi
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, username, password FROM `user` WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                header("Location: index.php");
                exit;
            } else {
                $error = "Password salah.";
            }
        } else {
            $error = "Username tidak ditemukan.";
        }
        $stmt->close();
    } else {
        $error = "Semua kolom wajib diisi.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS GAMING - Login</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <style>
        .auth-body { display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #0a0a0a; margin: 0; }
        .auth-container { max-width: 400px; width: 100%; padding: 40px 30px; background: #0f0f0f; border: 1px solid #1a1a1a; text-align: center; box-shadow: 0 0 20px rgba(0,0,0,0.8); }
        .auth-container h2 { font-family: 'Orbitron'; color: #ff003c; margin-bottom: 25px; letter-spacing: 2px; }
        .auth-input { width: 100%; padding: 12px; margin: 10px 0; background: #151515; border: 1px solid #222; color: #fff; font-size: 14px; }
        .auth-input:focus { border-color: #ff003c; outline: none; }
        .auth-btn { width: 100%; background: #ff003c; color: white; border: none; padding: 12px; font-family: 'Orbitron'; cursor: pointer; font-weight: bold; margin-top: 15px; transition: 0.3s; letter-spacing: 1px; }
        .auth-btn:hover { background: #d00030; box-shadow: 0 0 15px rgba(255, 0, 60, 0.4); }
        .msg-error { margin: 10px 0; padding: 10px; font-size: 14px; background: rgba(255,0,60,0.1); color: #ff003c; border: 1px solid #ff003c; text-align: left; }
    </style>
</head>
<body class="auth-body">
    <div class="auth-container">
        <h2>USER LOGIN</h2>
        
        <?php if($error): ?> 
            <div class="msg-error"><?php echo $error; ?></div> 
        <?php endif; ?>

        <form action="" method="POST">
            <input type="text" name="username" class="auth-input" placeholder="Username" required>
            <input type="password" name="password" class="auth-input" placeholder="Password" required>
            <button type="submit" class="auth-btn">ACCESS SYSTEM</button>
        </form>
        <p style="margin-top: 25px; font-size: 13px; color: #666;">Belum memiliki akun? <a href="register.php" style="color: #ff003c; text-decoration: none; font-weight: bold;">Daftar Disini</a></p>
    </div>
</body>
</html>