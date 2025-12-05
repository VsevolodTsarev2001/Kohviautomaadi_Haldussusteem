<?php
require 'functions_users.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        registerUser($username, $password);
        header("Location: login.php");
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="flex-center">
    <div class="card-container">
        <h3>Loo konto</h3>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
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
            <button type="submit" class="btn">Loo konto</button>
            <p style="text-align:center; margin-top:10px;">
                On juba konto? <a href="login.php">Logi sisse</a>
            </p>
        </form>
    </div>
</div>

</body>
</html>
