<?php

require_once('db.php');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}


$action = $_GET['action'] ?? 'list';

// Добавление товара
if ($action == 'add' && $_POST) {
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, quantity) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['description'], $_POST['price'], $_POST['quantity']]);
    header('Location: tovar_uprav.php');
    exit;
}

// Редактирование товара
if ($action == 'edit' && $_POST) {
    $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, quantity=? WHERE id=?");
    $stmt->execute([$_POST['name'], $_POST['description'], $_POST['price'], $_POST['quantity'], $_POST['id']]);
    header('Location: tovar_uprav.php');
    exit;
}

// Удаление товара
if ($action == 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
    $stmt->execute([$_GET['id']]);
    header('Location: tovar_uprav.php');
    exit;
}

// Получение данных для списка или редактирования
if ($action == 'edit') {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление товарами</title>
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
        input, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        textarea { height: 80px; resize: vertical; }
        .price { font-weight: bold; color: #00a32a; }
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
         <h1>Управление товарами</h1>
        <a href="admin.php" style="color: white; text-decoration: none; background: #000000; padding: 8px 15px; border-radius: 5px;">Назад</a>
    </div>

   

    <!-- Форма добавления/редактирования -->
    <?php if ($action == 'add' || $action == 'edit'): ?>
        <h2><?= $action == 'add' ? 'Добавить товар' : 'Редактировать товар' ?></h2>
        <form method="POST">
            <?php if ($action == 'edit'): ?>
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Название:</label>
                <input type="text" name="name" required value="<?= $product['name'] ?? '' ?>">
            </div>
            
            <div class="form-group">
                <label>Описание:</label>
                <textarea name="description" required><?= $product['description'] ?? '' ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Цена (руб.):</label>
                <input type="number" name="price" step="0.01" required value="<?= $product['price'] ?? '' ?>">
            </div>
            
            <div class="form-group">
                <label>Количество:</label>
                <input type="number" name="quantity" required value="<?= $product['quantity'] ?? '' ?>">
            </div>
            
            <button type="submit" class="btn btn-add">Сохранить</button>
            <a href="index.php" class="btn">Отмена</a>
        </form>
    <?php endif; ?>

    <!-- Таблица товаров -->
    <h2>Список товаров (<?= count($products) ?> шт.)</h2>
    
    <?php if (empty($products)): ?>
        <p>Товары отсутствуют. <a href="?action=add" class="btn btn-add">Добавить первый товар</a></p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= $product['id'] ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars(substr($product['description'], 0, 50)) ?>...</td>
                        <td class="price"><?= number_format($product['price'], 2, ',', ' ') ?> ₽</td>
                        <td><?= $product['quantity'] ?></td>
                        <td>
                            <a href="?action=edit&id=<?= $product['id'] ?>" class="btn btn-edit">Изменить</a>
                            <a href="?action=delete&id=<?= $product['id'] ?>" 
                               class="btn btn-delete" 
                               onclick="return confirm('Удалить товар?')">Удалить</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p><a href="?action=add" class="btn btn-add">Добавить новый товар</a></p>
</body>
</html>
