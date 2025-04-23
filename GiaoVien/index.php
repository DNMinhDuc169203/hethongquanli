<?php
// Load database configuration
require_once 'Config/Database.php';

// Khởi tạo kết nối database
$database = new Database();
$db = $database->getConnection();

// Định nghĩa các controller và action mặc định
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'trangchu';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Chuyển đổi tên controller thành tên class
$controllerName = ucfirst($controller) . 'Controller';
$controllerFile = 'Controller/' . $controllerName . '.php';

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