<?php
session_start();

if (isset($_POST["login"]) && isset($_POST["pass"])) {
    require 'db_avtoris.php';

    $login = $_POST['login'];
    $pass  = $_POST['pass'];

    // Проверка существования $mysqli
    if (!isset($mysqli) || $mysqli === null) {
        die("Соединение с БД не установлено.");
    }

    // Подготовленный запрос (безопаснее)
    $stmt = $mysqli->prepare("SELECT login, pass FROM users WHERE login = ? AND pass = ?");
    if (!$stmt) {
        die("Ошибка подготовки запроса: " . $mysqli->error);
    }

    $stmt->bind_param("ss", $login, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['login'] = $row['login'];
        $_SESSION['pass']  = $row['pass'];
        require_once('catalog.php');
    } else {
        echo "<script>alert('Неверные данные');</script>";
    }
}
?>
