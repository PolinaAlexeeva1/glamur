<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "reg";

    $conn = mysqli_connect($host, $username, $password, $dbname);

    if(!$conn){
        die("Запрос не найден".mysqli_connect_error());
    }else{
     "Успех";
    }

?>