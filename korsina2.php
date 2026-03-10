<?php
session_start();

// Инициализация корзины в сессии
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Обработка AJAX запросов
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add':
            $product = [
                'id' => $_POST['id'],
                'name' => $_POST['name'],
                'price' => (int)$_POST['price'],
                'quantity' => 1
            ];
            
            // Проверяем, есть ли уже такой товар
            $found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $product['id']) {
                    $item['quantity'] += 1;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $_SESSION['cart'][] = $product;
            }
            
            echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
            break;
            
        case 'update':
            $index = (int)$_POST['index'];
            $quantity = (int)$_POST['quantity'];
            
            if (isset($_SESSION['cart'][$index])) {
                $_SESSION['cart'][$index]['quantity'] = max(1, $quantity);
            }
            
            echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
            break;
            
        case 'remove':
            $index = (int)$_POST['index'];
            
            if (isset($_SESSION['cart'][$index])) {
                array_splice($_SESSION['cart'], $index, 1);
            }
            
            echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
            break;
            
        case 'get':
            echo json_encode(['success' => true, 'cart' => $_SESSION['cart']]);
            break;
            
        case 'clear':
            $_SESSION['cart'] = [];
            echo json_encode(['success' => true, 'cart' => []]);
            break;
    }
    exit;
}

// Если это не AJAX запрос, показываем страницу корзины
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="grid.css">
    <link rel="stylesheet" href="style.css">
    <title>Корзина</title>
    <link rel="icon" href="img/5128876.png" type="image/x-icon">
<style>
    /* Контейнер для центрирования */
    .cart-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        min-height: 50vh; /* Минимальная высота для вертикального центрирования */
        padding: 20px;
        box-sizing: border-box;
    }
    
    /* Стили для корзины */
    #cart-table {
        margin: 0 auto; /* Горизонтальное центрирование */
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
        height: 70px;
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
        transition: all 0.3s ease;
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
        background: #d00000;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .remove-btn:hover {
        background: rgb(165, 0, 0);
        transform: scale(1.05);
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
    }
    
    .checkout-btn {
        background: #800000;
        color: white;
        border: none;
        padding: 15px 30px;
        font-size: 18px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .checkout-btn:hover {
        background: #660000;
        transform: scale(1.05);
    }
    
    .continue-shopping {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background: #800000;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: all 0.3s ease;
    }
    
    .continue-shopping:hover {
        background: #660000;
        transform: scale(1.05);
    }
    
    /* Адаптивность для планшетов */
    @media screen and (max-width: 768px) {
        .cart-container {
            padding: 15px;
            min-height: auto;
        }
        
        #cart-table {
            width: 95%;
            font-size: 14px;
        }
        
        #cart-table th, 
        #cart-table td {
            padding: 10px;
        }
        
        .cart-image {
            width: 50px;
            height: 60px;
        }
        
        .quantity-controls {
            gap: 5px;
        }
        
        .quantity-btn {
            width: 25px;
            height: 25px;
            font-size: 14px;
        }
        
        .quantity-input {
            width: 40px;
            padding: 3px;
        }
        
        .remove-btn {
            padding: 6px 12px;
            font-size: 12px;
        }
        
        .cart-total {
            font-size: 18px;
            margin: 15px;
        }
        
        .checkout-btn {
            padding: 12px 25px;
            font-size: 16px;
        }
    }
    
    /* Адаптивность для телефонов */
    @media screen and (max-width: 480px) {
        .cart-container {
            padding: 10px;
        }
        
        /* Превращаем таблицу в блочную структуру */
        #cart-table,
        #cart-table tbody,
        #cart-table tr,
        #cart-table td {
            display: block;
            width: 100%;
        }
        
        #cart-table thead {
            display: none; /* Скрываем шапку таблицы на телефонах */
        }
        
        #cart-table tr {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            position: relative;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            background: white;
        }
        
        #cart-table td {
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
            text-align: left;
            min-height: 40px;
        }
        
        #cart-table td:last-child {
            border-bottom: none;
        }
        
        #cart-table td:before {
            content: attr(data-label);
            font-weight: bold;
            width: 40%;
            min-width: 100px;
            color: #800000;
            font-size: 14px;
        }
        
        /* Специальные стили для ячеек с изображением */
        #cart-table td:first-child:before {
            content: "Товар";
        }
        
        #cart-table td:nth-child(2):before {
            content: "Название";
        }
        
        #cart-table td:nth-child(3):before {
            content: "Цена";
        }
        
        #cart-table td:nth-child(4):before {
            content: "Количество";
        }
        
        #cart-table td:nth-child(5):before {
            content: "Сумма";
        }
        
        #cart-table td:last-child:before {
            content: "Действия";
        }
        
        /* Стили для содержимого ячеек */
        #cart-table td:first-child {
            justify-content: flex-start;
        }
        
        .cart-image {
            width: 70px;
            height: 80px;
            margin-left: 10px;
        }
        
        .quantity-controls {
            width: auto;
            margin-left: 10px;
        }
        
        .remove-btn {
            margin-left: 10px;
            padding: 8px 15px;
        }
        
        /* Стили для цены и суммы */
        #cart-table td:nth-child(3) span,
        #cart-table td:nth-child(5) span {
            margin-left: 10px;
            font-weight: bold;
            color: #800000;
            font-size: 16px;
        }
        
        .cart-total {
            text-align: center;
            font-size: 18px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
            margin: 15px 0;
        }
        
        .checkout-btn {
            width: 100%;
            padding: 15px;
            font-size: 16px;
        }
        
        .continue-shopping {
            display: block;
            text-align: center;
            margin: 15px auto;
            width: 90%;
            box-sizing: border-box;
        }
    }
    
    /* Дополнительная адаптация для очень маленьких экранов */
    @media screen and (max-width: 360px) {
        #cart-table td:before {
            min-width: 80px;
            font-size: 12px;
        }
        
        .cart-image {
            width: 50px;
            height: 60px;
        }
        
        .quantity-controls {
            flex-wrap: wrap;
        }
        
        .quantity-btn {
            width: 25px;
            height: 25px;
        }
        
        .quantity-input {
            width: 35px;
        }
        
        .remove-btn {
            padding: 5px 10px;
            font-size: 11px;
        }
        
        #cart-table td:nth-child(3) span,
        #cart-table td:nth-child(5) span {
            font-size: 14px;
        }
    }
</style>
</head>
<body>
<?php require_once('header_catalog.php'); ?>

<main>
    <h1 style="text-align: center; color: #800000;">Корзина</h1>
    
    <div id="cart-container">
        <table id="cart-table" style="display: none;">
            <thead>
                <tr>
                    <th>Изображение</th>
                    <th>Товар</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody id="cart-items"></tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right; font-weight: bold;">Итого:</td>
                    <td id="cart-total" colspan="2"></td>
                </tr>
            </tfoot>
        </table>
        <div id="empty-cart" class="empty-cart" style="display: none;">
            Корзина пуста
        </div>
        <div class="cart-actions" style="display: none; padding-top: 10px;" id="cart-actions">
            <a href="obrabot.php" class="continue-shopping">Оформить заказ</a>
        </div>
        <div style="text-align: center; padding-bottom: 20px;">
            <a href="catalog.php" class="continue-shopping">Продолжить покупки</a>
        </div>
    </div>
</main>

<?php require_once('footer.php'); ?>

<script>
// Функционал корзины с серверным хранением
function loadCart() {
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
            updateCartDisplay(data.cart);
            updateCartCounter(data.cart);
        }
    });
}

function updateCartCounter(cart) {
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    document.getElementById('cart-count').textContent = totalItems;
}

function updateCartDisplay(cart) {
    const cartItems = document.getElementById('cart-items');
    const cartTable = document.getElementById('cart-table');
    const emptyCart = document.getElementById('empty-cart');
    const cartActions = document.getElementById('cart-actions');
    
    if (cart.length === 0) {
        cartTable.style.display = 'none';
        emptyCart.style.display = 'block';
        cartActions.style.display = 'none';
        return;
    }
    
    cartTable.style.display = 'table';
    emptyCart.style.display = 'none';
    cartActions.style.display = 'block';
    
    cartItems.innerHTML = cart.map((item, index) => `
        <tr>
            <td><img src="img/${item.id}.jpg" alt="${item.name}" class="cart-image"></td>
            <td>${item.name}</td>
            <td>${item.price} ₽</td>
            <td>
                <div class="quantity-controls">
                    <button class="quantity-btn" onclick="changeQuantity(${index}, -1)">-</button>
                    <input type="number" class="quantity-input" value="${item.quantity}" min="1" onchange="updateQuantity(${index}, this.value)">
                    <button class="quantity-btn" onclick="changeQuantity(${index}, 1)">+</button>
                </div>
            </td>
            <td>${(item.price * item.quantity).toLocaleString()} ₽</td>
            <td><button class="remove-btn" onclick="removeFromCart(${index})">Удалить</button></td>
        </tr>
    `).join('');
    
    updateTotal(cart);
}

function updateTotal(cart) {
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    document.getElementById('cart-total').textContent = total.toLocaleString() + ' ₽';
}

function changeQuantity(index, delta) {
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
            const newQuantity = data.cart[index].quantity + delta;
            if (newQuantity >= 1) {
                return fetch('korsina2.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=update&index=${index}&quantity=${newQuantity}`
                });
            }
        }
    })
    .then(response => response?.json())
    .then(data => {
        if (data?.success) {
            updateCartDisplay(data.cart);
            updateCartCounter(data.cart);
        }
    });
}

function updateQuantity(index, quantity) {
    fetch('korsina2.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=update&index=${index}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartDisplay(data.cart);
            updateCartCounter(data.cart);
        }
    });
}

function removeFromCart(index) {
    fetch('korsina2.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=remove&index=${index}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartDisplay(data.cart);
            updateCartCounter(data.cart);
        }
    });
}

// Инициализация
document.addEventListener('DOMContentLoaded', function() {
    loadCart();
    
    // Обработчик оформления заказа
    document.querySelector('.checkout-btn')?.addEventListener('click', function() {
        fetch('korsina2.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=get'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.cart.length > 0) {
                alert('Переход к оформлению заказа...');
                // Здесь можно добавить редирект на страницу оформления
            }
        });
    });
});
</script>
</body>
</html>