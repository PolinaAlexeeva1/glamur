<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
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
    <form action="reg.php" method="post">
        <h2>Регистрация</h2>
        <input type="text" placeholder="Логин" name="login">
        <input type="text" placeholder="Номер телефона" name="phone">
        <input type="email" placeholder="Email" name="email">
        <input type="password" placeholder="Пароль" name="pass">
        <input type="password" placeholder="Повторите пароль" name="reppass">     
        <button type="submit" class="button2">Зарегистрироваться</button>
        <p>
            У вас уже есть аккаунт? - <a class="a1" href="avtoris.php">Авторизоваться</a>
        </p>
    </form>
    </main>
</body>
</html>
