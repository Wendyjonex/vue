<?php
// ========== 0. 解决跨域问题（最关键的一步） ==========
header("Access-Control-Allow-Origin: *"); // 允许所有来源访问
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS"); // 允许的请求方法
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // 允许的请求头
header('Content-Type: application/json; charset=utf-8'); // 返回 JSON 格式

// 处理 OPTIONS 预检请求（浏览器跨域时会自动发这个，直接返回200即可）
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ========== 1. 数据库连接 ==========
$host = 'localhost';
$dbname = 'test_db';
$username = 'phpuser';
$password = 'slytheiro';

try {
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
    // 兼容 Vue 发送 JSON 数据的情况
    $input = json_decode(file_get_contents('php://input'), true);
    $name = $input['name'] ?? $_POST['name'] ?? '';
    $salary = intval($input['salary'] ?? $_POST['salary'] ?? 0);

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