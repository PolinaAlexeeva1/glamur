<?php
$mysqli = new mysqli("localhost", "root", "", "reg");
if ($mysqli->connect_error) {
    die("Ошибка соединения: " . $mysqli->connect_error);
}

?>