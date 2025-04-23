<?php
// Cấu hình hiển thị lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Thiết lập đường dẫn gốc
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$baseURL = rtrim($scriptDir, '/');
define('BASE_URL', $baseURL);

// Bắt đầu phiên làm việc
session_start();

// Thêm các file cần thiết
require_once __DIR__ . '/Config/Database.php';

// Khởi tạo kết nối database
$database = new Database();
$db = $database->getConnection();

// Định nghĩa các controller và action mặc định
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'auth';

// Xác định action mặc định dựa trên controller
if (!isset($_GET['action'])) {
    if ($controller == 'auth') {
        $action = 'login';
    } elseif ($controller == 'user') {
        $action = 'index';
    } else {
        $action = 'index'; // Action mặc định cho các controller khác
    }
} else {
    $action = $_GET['action'];
}

// Chuyển đổi tên controller thành tên class
$controllerName = ucfirst($controller) . 'Controller';
$controllerFile = __DIR__ . '/Controller/' . $controllerName . '.php';

// Kiểm tra đăng nhập nếu không phải trang đăng nhập
if ($controller != 'auth' || $action != 'login') {
    // Kiểm tra phiên đăng nhập
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ' . BASE_URL . '/index.php?controller=auth&action=login');
        exit;
    }
}

// Kiểm tra file controller tồn tại
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    // Tạo đối tượng controller với kết nối database
    $controller = new $controllerName($db);
    
    // Kiểm tra action tồn tại
    if (method_exists($controller, $action)) {
        // Gọi action với tham số id nếu có
        if (isset($_GET['id'])) {
            $controller->$action($_GET['id']);
        } else {
            $controller->$action();
        }
    } else {
        // Action không tồn tại
        echo "Action không tồn tại";
    }
} else {
    // Controller không tồn tại
    echo "Controller không tồn tại";
}
?>
