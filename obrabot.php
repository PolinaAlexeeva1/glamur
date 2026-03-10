<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Обработка оплаты</title>
  <link rel="icon" href="img/5128876.png" type="image/x-icon">
  <link rel="stylesheet" href="style.css"/>
</head>
<body>
<?php
require_once('header_catalog.php'); 
?>   
<main>    
    <form action="obrabot_obr.php" method="post">
        <h2>Оформление заказа</h2>
        <input type="text" placeholder="Логин" name="login">
        <input type="text" placeholder="Телефон" name="phone">
        <input type="text" placeholder="Заказ" name="name">
        <input type="text" placeholder="Адрес доставки" name="address">
             
        <button type="submit" class="button2">Оформить</button>

    </form>
</main>
</body>
</html>


<style>
    /*obrabot.php*/
.payment-container{
    
    margin: 0 auto;
    flex-direction: column;
    gap: 10px;
    max-width: 300px;
    text-align: center;
}


</style>