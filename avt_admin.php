<?php
session_start();
require_once('db.php');

// Если уже авторизован, перенаправляем в админку
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $pass = $_POST['pass'] ?? '';

    if (empty($login) || empty($pass)) {
        $error = 'Заполните все поля';
    } else {
        // Prepared statement для безопасности
        $stmt = $conn->prepare("SELECT login, pass FROM admin WHERE login = ? LIMIT 1");
        $stmt->bind_param('s', $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Проверка пароля (если в БД хеш, используйте password_verify($pass, $row['pass']))
            if ($pass === $row['pass']) {  // Замените на password_verify для хешированных паролей [web:13]
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_login'] = $row['login'];
                header('Location: admin.php');
                exit;
            } else {
                $error = 'Неверный логин или пароль';
            }
        } else {
            $error = 'Пользователь не найден';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход в админ-панель</title>
    <link rel="icon" href="img/5128876.png" type="image/x-icon">
    <style>
        body { font-family: Arial; max-width: 400px; margin: 100px auto; padding: 20px; }
        form { display: flex; flex-direction: column; gap: 10px; }
        input { padding: 10px; }
        .error { color: red; }
        button {  color: white; border: none; padding: 10px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Авторизация в админ-панель</h2>
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="login" placeholder="Логин" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>" required>
        <input type="password" name="pass" placeholder="Пароль" required>
        <button type="submit">Войти</button>
    </form>
</body>
</html>
