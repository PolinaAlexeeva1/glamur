<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="grid.css">
    <link rel="stylesheet" href="style.css">
    <title>ГЛАМУР</title>
    <link rel="icon" href="img/5128876.png" type="image/x-icon">
    <style>
        /* Стили для корзины */
        #cart-table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 90%;
            max-width: 1000px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        #cart-table th, #cart-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        #cart-table th {
            background: #800000;
            color: white;
            font-weight: 600;
        }
        .cart-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .quantity-btn {
            width: 30px;
            height: 30px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        .quantity-btn:hover {
            background: #f5f5f5;
        }
        .quantity-input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
        }
        .remove-btn {
            background: #ff4444;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .remove-btn:hover {
            background: #cc0000;
        }
        .cart-total {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            color: #800000;
            margin: 20px;
        }
        .empty-cart {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 18px;
        }
        .cart-actions {
            text-align: center;
            margin: 20px;
        }
        .checkout-btn {
            background: #800000;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
        }
        .checkout-btn:hover {
            background: #660000;
        }
    </style>
</head>
<body>
<?php require_once('header_catalog.php'); ?>

<main>
    <h1 style="text-align: center;  color: #800000;"> Женские сумки</h1>    
    <section class="parent" id="catalog">   
        <div class="elem1 product" data-id="1" data-name="Daniele Patrici цвет черный" data-price="1999">
            <div class="img">
               <img class="picture" src="img/1.jpg" alt="Daniele Patrici"> 
            </div>
            <div class="p1">  
                <hr> 
                <h3> 1999 ₽</h3> 
                <div>Daniele Patrici, цвет черный</div>            
            </div>
            <button class="add-to-cart button1">В корзину</button>
        </div>
        <div class="elem1 product" data-id="2" data-name="Daniele Patrici цвет белый" data-price="2299">
            <div class="img">
               <img class="picture" src="img/2.jpg" alt="Daniele Patrici"> 
            </div>
            <div class="p1">
                <hr>
                <h3> 2299 ₽</h3>
                <div>Daniele Patrici, цвет белый </div>
            </div>
            <button class="add-to-cart button1">В корзину</button>
        </div>
        <div class="elem1 product" data-id="3" data-name="Daniele Patrici цвет желтый" data-price="399">
            <div class="img">
               <img class="picture" src="img/3.jpg" alt="Daniele Patrici"> 
            </div>
            <div class="p1">
                <hr>
                <h3> 399 ₽</h3>
                <div>Daniele Patrici, цвет желтый</div>     
            </div>
            <button class="add-to-cart button1">В корзину</button>
        </div>
        <div class="elem1 product" data-id="4" data-name="Daniele Patrici цвет бежевый" data-price="1999">
            <div class="img">
               <img class="picture" src="img/4.jpg" alt="Daniele Patrici"> 
            </div>
            <div class="p1">
                <hr>
                <h3> 1999 ₽</h3>
                <div>Daniele Patrici, цвет бежевый</div> 
            </div>
            <button class="add-to-cart button1">В корзину</button>
        </div>   
        <div class="elem1 product" data-id="5" data-name="Vivian Royal цвет коричневый" data-price="1200">
            <div class="img">
               <img class="picture" src="img/5.jpg" alt="Vivian Royal"> 
            </div>
            <div class="p1">
                <hr>
                <h3>1200 ₽</h3>
                <div>Vivian Royal, цвет коричневый</div>
            </div>
            <button class="add-to-cart button1">В корзину</button>
        </div>  
        <div class="elem1 product" data-id="6" data-name="Lusio цвет черный" data-price="5000">
            <div class="img">
               <img class="picture" src="img/6.jpg" alt="Lusio"> 
            </div>
            <div class="p1">
                <hr>
                <h3>1500 ₽</h3>
                <div>Lusio, цвет черный</div>
            </div>
            <button class="add-to-cart button1">В корзину</button>
        </div>
        <div class="elem1 product" data-id="7" data-name="Vivian Royal цвет бежевый" data-price="5000">
            <div class="img">
               <img class="picture" src="img/7.jpg" alt="Vivian Royal"> 
            </div>
            <div class="p1">
                <hr>
                <h3>1500 ₽</h3>
                <div>Vivian Royal, цвет бежевый</div>
            </div>
            <button class="add-to-cart button1">В корзину</button>
        </div>
        <div class="elem1 product" data-id="8" data-name="Nstc.moscow  цвет синий" data-price="5000">
            <div class="img">
               <img class="picture" src="img/8.jpg" alt="Nstc.moscow"> 
            </div>
            <div class="p1">
                 <hr>
                <h3>1500 ₽</h3>
                <div>Nstc.moscow, цвет синий</div> 
            </div>
            <button class="add-to-cart button1">В корзину</button>
        </div>
    </section>
</main>

<!-- Таблица корзины (скрыта, так как теперь корзина на отдельной странице) -->


<script>
let cart = [];

function updateCartCounter() {
    fetch('korsina2.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=get'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            cart = data.cart;
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cart-count').textContent = totalItems;
        }
    });
}

function addToCart(product) {
    const formData = new FormData();
    formData.append('action', 'add');
    formData.append('id', product.dataset.id);
    formData.append('name', product.dataset.name);
    formData.append('price', product.dataset.price);
    
    fetch('korsina2.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            cart = data.cart;
            updateCartCounter();
            alert('Товар добавлен в корзину!');
        }
    });
}

// Инициализация
document.addEventListener('DOMContentLoaded', function() {
    updateCartCounter();
    
    // Обработчики кнопок "В корзину"
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            addToCart(this.parentElement);
        });
    });
});
</script>

<?php require_once('footer.php'); ?>
</body>
</html>