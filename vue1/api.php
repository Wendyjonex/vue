<?php
header('Content-Type: application/json');

// ========== 1. 数据库连接（简化版，兼容所有 PHP 版本） ==========
$host = 'localhost';
$dbname = 'test_db';
$username = 'phpuser';
$password = 'slytheiro';

try {
    // 去掉不兼容的 SSL 参数
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => '数据库连接失败：' . $e->getMessage()]);
    exit;
}

// ========== 2. 获取前端传来的操作类型 ==========
$action = $_GET['action'] ?? '';

// ========== 3. 路由分发 ==========
switch ($action) {
    case 'list':
        getList($pdo);
        break;
    case 'add':
        addUser($pdo);
        break;
    case 'delete':
        deleteUser($pdo);
        break;
    default:
        echo json_encode(['error' => '无效的操作']);
        break;
}

// ========== 4. 查询所有用户 ==========
function getList($pdo) {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
}

// ========== 5. 添加用户 ==========
function addUser($pdo) {
    $name = $_POST['name'] ?? '';
    $salary = intval($_POST['salary'] ?? 0);

    if (empty($name) || $salary <= 0) {
        echo json_encode(['error' => '姓名和薪水不能为空']);
        return;
    }

    $stmt = $pdo->prepare("INSERT INTO users (name, salary) VALUES (?, ?)");
    $stmt->execute([$name, $salary]);

    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
}

// ========== 6. 删除用户 ==========
function deleteUser($pdo) {
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['error' => '无效的ID']);
        return;
    }

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true]);
}
?>