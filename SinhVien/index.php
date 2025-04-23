<?php
// Bắt đầu phiên làm việc
session_start();

// Enable error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Định nghĩa hằng số BASE_URL
define('BASE_URL', '/SinhVien');

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id']) && (!isset($_GET['controller']) || $_GET['controller'] !== 'auth')) {
    // Nếu chưa đăng nhập và không phải đang truy cập trang đăng nhập, chuyển hướng đến trang đăng nhập
    header('Location: ' . BASE_URL . '/index.php?controller=auth&action=login');
    exit;
}

// Kiểm tra xem người dùng có cần đổi mật khẩu không
if (isset($_SESSION['change_password_required']) && $_SESSION['change_password_required'] && 
    (!isset($_GET['controller']) || $_GET['controller'] !== 'auth' || 
     !isset($_GET['action']) || $_GET['action'] !== 'changePassword')) {
    // Chuyển hướng đến trang đổi mật khẩu
    header('Location: ' . BASE_URL . '/index.php?controller=auth&action=changePassword');
    exit;
}

// Xử lý routing
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Chuyển đổi tên controller thành tên class
$controllerName = ucfirst($controller) . 'Controller';
$controllerFile = 'Controller/' . $controllerName . '.php';

// Kiểm tra file controller tồn tại
if (file_exists($controllerFile)) {
    require_once 'Config/Database.php';
    require_once $controllerFile;
    
    // Khởi tạo database connection
    $database = new Database();
    $db = $database->getConnection();
    
    // Tạo đối tượng controller
    $controllerObj = new $controllerName($db);
    
    // Kiểm tra phương thức tồn tại
    if (method_exists($controllerObj, $action)) {
        // Gọi phương thức với tham số ID nếu có
        if (isset($_GET['id'])) {
            $controllerObj->$action($_GET['id']);
        } else {
            $controllerObj->$action();
        }
    } else {
        // Phương thức không tồn tại
        echo "Phương thức không tồn tại";
    }
} else {
    // Controller không tồn tại
    echo "Controller không tồn tại";
}
?>