<?php
require_once('db.php');
    $login = $_POST['login'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $reppass = $_POST['reppass'];
    
    if( empty($login) || empty($phone) || empty($email) || empty($pass) || empty($reppass)){
        echo "Заполните все поля";
    }else
    {
        if($pass != $reppass){
            echo "Пароли не совпадают";
        } else {
                $sql = "INSERT INTO users (login,phone,email,pass) VALUES ('$login','$phone','$email','$pass')";
                if ($conn -> query($sql) === TRUE){
                    echo "Успешная регистрация";
                } else {
                    echo "Ошибка: " . $conn->error;
                }
                
        }
    }


?>