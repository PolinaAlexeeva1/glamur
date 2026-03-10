<?php
require_once 'db.php';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

$action = $_GET['action'] ?? 'list';

// Добавление заказа
if ($action == 'add' && $_POST) {
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, address, order_price) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['user_id'], $_POST['address'], $_POST['order_price']]);
    $order_id = $pdo->lastInsertId();
    
    // Добавляем товары в заказ
    if (!empty($_POST['products'])) {
        foreach ($_POST['products'] as $product_id => $data) {
            if ($data['quantity'] > 0) {
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$order_id, $product_id, $data['quantity'], $data['price']]);
            }
        }
    }
    
    header('Location: orders.php');
    exit;
}

// Редактирование заказа
if ($action == 'edit' && $_POST) {
    $stmt = $pdo->prepare("UPDATE orders SET user_id=?, address=?, order_price=? WHERE id_order=?");
    $stmt->execute([$_POST['user_id'], $_POST['address'], $_POST['order_price'], $_POST['id_order']]);
    
    // Обновляем товары в заказе
    $stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id=?");
    $stmt->execute([$_POST['id_order']]);
    
    if (!empty($_POST['products'])) {
        foreach ($_POST['products'] as $product_id => $data) {
            if ($data['quantity'] > 0) {
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$_POST['id_order'], $product_id, $data['quantity'], $data['price']]);
            }
        }
    }
    
    header('Location: orders.php');
    exit;
}

// Удаление заказа
if ($action == 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id_order=?");
    $stmt->execute([$_GET['id']]);
    header('Location: orders.php');
    exit;
}

// Получение данных для редактирования
if ($action == 'edit') {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id_order=?");
    $stmt->execute([$_GET['id']]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Получаем товары заказа с описанием
    $stmt = $pdo->prepare("
        SELECT oi.*, p.name, p.description 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        WHERE order_id=?
    ");
    $stmt->execute([$_GET['id']]);
    $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Получаем все заказы с товарами и описаниями
$stmt = $pdo->query("
    SELECT o.*, 
           GROUP_CONCAT(CONCAT(p.name, ': ', oi.quantity, 'шт') SEPARATOR ', ') as products_info,
           GROUP_CONCAT(CONCAT(p.description, ': ', oi.quantity, 'шт') SEPARATOR ' | ') as products_desc
    FROM orders o 
    LEFT JOIN order_items oi ON o.id_order = oi.order_id 
    LEFT JOIN products p ON oi.product_id = p.id 
    GROUP BY o.id_order 
    ORDER BY o.created_at DESC
");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получаем товары для формы
$products = $pdo->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление заказами</title>
    <link rel="icon" href="img/5128876.png" type="image/x-icon">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 6px 12px; text-decoration: none; margin: 2px; border-radius: 4px; }
        .btn-edit { background: #007cba; color: white; }
        .btn-delete { background: #d00000; color: white; }
        .btn-add { background: #00a32a; color: white; }
        .form-group { margin: 10px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        textarea { height: 80px; resize: vertical; }
        .price { font-weight: bold; color: #00a32a; }
        .products-section { background: #f9f9f9; padding: 15px; margin: 10px 0; border-radius: 4px; }
        .product-row { display: flex; gap: 10px; margin: 5px 0; align-items: center; }
        .product-row input { width: 80px; }
        .desc { font-size: 0.9em; color: #666; font-style: italic; }
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            background: #800000; 
            color: white; 
            padding: 15px 20px; 
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    
    <div class="header">
<h1>Управление заказами</h1>
        <a href="admin.php" style="color: white; text-decoration: none; background: #000000; padding: 8px 15px; border-radius: 5px;">Назад</a>
    </div>
    <!-- Форма добавления/редактирования -->
    <?php if ($action == 'add' || $action == 'edit'): ?>
        <h2><?= $action == 'add' ? 'Добавить заказ' : 'Редактировать заказ' ?></h2>
        <form method="POST">
            <?php if ($action == 'edit'): ?>
                <input type="hidden" name="id_order" value="<?= $order['id_order'] ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>ID пользователя:</label>
                <input type="number" name="user_id" required value="<?= $order['user_id'] ?? '' ?>">
            </div>
            
            <div class="form-group">
                <label>Адрес доставки:</label>
                <textarea name="address" required><?= $order['address'] ?? '' ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Цена заказа:</label>
                <input type="number" name="order_price" step="0.01" required value="<?= $order['order_price'] ?? '' ?>" class="price">
            </div>
            
            <div class="products-section">
                <h4>Товары в заказе:</h4>
                <?php foreach ($products as $product): ?>
                    <div class="product-row">
                        <strong><?= htmlspecialchars($product['name']) ?></strong>
                        <span class="desc"><?= htmlspecialchars(substr($product['description'], 0, 40)) ?>...</span>
                        <input type="number" name="products[<?= $product['id'] ?>][quantity]" 
                               value="<?= $order['items'][$product['id']]['quantity'] ?? 0 ?>" min="0" placeholder="кол-во">
                        <input type="number" name="products[<?= $product['id'] ?>][price]" 
                               value="<?= $order['items'][$product['id']]['price'] ?? $product['price'] ?>" 
                               step="0.01" placeholder="цена" readonly>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <button type="submit" class="btn btn-add">Сохранить</button>
            <a href="orders.php" class="btn">Отмена</a>
        </form>
    <?php endif; ?>

    <!-- Таблица заказов -->
    <h2>Список заказов (<?= count($orders) ?> шт.)</h2>
    
    <?php if (empty($orders)): ?>
        <p>Заказы отсутствуют. <a href="?action=add" class="btn btn-add">Добавить первый заказ</a></p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID заказа</th>
                    <th>ID пользователя</th>
                    <th>Товар</th>
                    <th>Описание товара</th>
                    <th>Адрес</th>
                    <th>Цена заказа</th>
                    <th>Дата создания</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><strong>#<?= $order['id_order'] ?></strong></td>
                        <td><?= $order['user_id'] ?></td>
                        <td><?= htmlspecialchars($order['products_info'] ?: 'нет товаров') ?></td>
                        <td><?= htmlspecialchars(substr($order['products_desc'] ?: 'нет описания', 0, 60)) ?>...</td>
                        <td><?= htmlspecialchars(substr($order['address'], 0, 30)) ?>...</td>
                        <td class="price"><?= number_format($order['order_price'], 2, ',', ' ') ?> ₽</td>
                        <td><?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></td>
                        <td>
                            <a href="?action=edit&id=<?= $order['id_order'] ?>" class="btn btn-edit">Изменить</a>
                            <a href="?action=delete&id=<?= $order['id_order'] ?>" 
                               class="btn btn-delete" 
                               onclick="return confirm('Удалить заказ?')">Удалить</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p><a href="?action=add" class="btn btn-add">Добавить новый заказ</a></p>
</body>
</html>