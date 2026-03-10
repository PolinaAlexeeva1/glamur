<?php
session_start();
require_once('db.php');

// Проверка авторизации
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: avt_admin.php');
    exit;
}

// Логика выхода
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: avtoris.php');
    exit;
}

// Получаем данные пользователей из БД
$users = [];
$sql = "SELECT id, email, login, pass FROM users ORDER BY id DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <link rel="icon" href="img/5128876.png" type="image/x-icon">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background: #f5f5f5;
        }
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
        .menu { 
            background: white; 
            padding: 15px; 
            border-radius: 8px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .menu a { 
            margin: 0 15px; 
            text-decoration: none; 
            color: #2c3e50; 
            font-weight: bold;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .menu a:hover { background: #ecf0f1; }
        .content { 
            background: white; 
            padding: 25px; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        th, td { 
            padding: 12px; 
            text-align: left; 
            border-bottom: 1px solid #ddd;
        }
        th { 
            background: #800000; 
            color: white; 
            font-weight: bold;
        }
        tr:hover { background: #f8f9fa; }
        .pass { font-family: monospace; color: #7f8c8d; }
        .no-data { 
            text-align: center; 
            padding: 40px; 
            color: #7f8c8d;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1> Добро пожаловать, <?= htmlspecialchars($_SESSION['admin_login']) ?>!</h1>
        <a href="?logout=1" style="color: white; text-decoration: none; background: #000000; padding: 8px 15px; border-radius: 5px;">Выход</a>
    </div>

    <nav class="menu">
        <a href="orders.php">📝 Управление заказами</a>
        <a href="tovar_uprav.php">🛒 Управление товарами</a>
        
    </nav>

    <div class="content">
        <h2>📋 Список пользователей</h2>
        
        <?php if (!empty($users)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Логин</th>
                        <th>Пароль</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><strong>#<?= $user['id'] ?></strong></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['login']) ?></td>
                            <td class="pass"><?= htmlspecialchars(substr($user['pass'], 0, 20)) ?>...</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                👤 Пользователей пока нет
            </div>
        <?php endif; ?>

        <div style="margin-top: 30px; padding: 15px; background: #e8f5e8; border-radius: 5px;">
            <strong>Всего пользователей:</strong> <?= count($users) ?>
        </div>
    </div>
</body>
</html>
