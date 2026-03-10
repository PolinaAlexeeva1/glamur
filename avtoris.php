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
    <form action="avt.php" method="post">
        
        <h2>Авторизация</h2>
        <input type="text" placeholder="Логин" name="login">
        <input type="password" placeholder="Пароль" name="pass">
        <button type="submit" class="button2">Войти</button>
        <p>
            У вас нет аккаунта? - <a class="a1" href="register.php">Зарегистрироваться</a>
        </p>
        <a href="avtoris_admin.php" style="color: rgb(225, 225, 225);">Войти как администратор</a>
    </form>
    </main>    
</body>
</html>