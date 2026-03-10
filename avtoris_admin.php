<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="icon" href="img/5128876.png" type="image/x-icon">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="grid.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
require_once('header.php');
?>
    <main>
    <form action="avt_admin.php" method="post">
        <h2>Вход в систему</h2>
        <input type="text" placeholder="Логин" name="login">
        <input type="password" placeholder="Пароль" name="pass">
        <button type="submit" class="button2">Войти</button>

    </form>
    </main>    
</body>
</html>