<?php
session_start();
require 'functions_users.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = authenticate($_POST['username'], $_POST['password']);
    if ($user) {
        $token = bin2hex(openssl_random_pseudo_bytes(32));
        $user->token = $token;
        saveUsers(loadUsers());

        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'username' => (string)$user->username,
            'role' => (string)$user->role,
            'token' => $token
        ];
        header("Location: index.php");
        exit;
    } else {
        $error = "Vale kasutajanimi või parool!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Logi sisse</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="flex-center">
    <div class="card-container">
        <h3>Logi sisse</h3>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="input-group">
                <span class="icon">👤</span>
                <input name="username" type="text" placeholder="Kasutajanimi" required>
            </div>
            <div class="input-group">
                <span class="icon">🔒</span>
                <input name="password" type="password" placeholder="Parool" required>
            </div>
            <button type="submit" class="btn">Logi sisse</button>
            <p style="text-align:center; margin-top:10px;">
                <a href="register.php">Loo konto</a>
            </p>
        </form>
    </div>
</div>

</body>
</html>
