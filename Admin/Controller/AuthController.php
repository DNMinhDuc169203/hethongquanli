<?php
class AuthController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Xử lý đăng nhập admin
     */
    public function login() {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            if (empty($username) || empty($password)) {
                $error = 'Vui lòng nhập đầy đủ thông tin đăng nhập';
            } else {
                try {
                    // Truy vấn người dùng có vai trò admin
                    $query = "SELECT id, ma_so, ho_va_ten FROM nguoi_dung WHERE ma_so = ? AND mat_khau = ? AND vai_tro = 'admin'";
                    $stmt = $this->db->prepare($query);
                    $stmt->execute([$username, $password]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($user) {
                        // Đăng nhập thành công, lưu thông tin vào session
                        $_SESSION['admin_id'] = $user['id'];
                        $_SESSION['admin_username'] = $user['ma_so'];
                        $_SESSION['admin_name'] = $user['ho_va_ten'];

                        // Chuyển hướng đến trang quản lý người dùng
                        header('Location: ' . BASE_URL . '/index.php?controller=user&action=index');
                        exit;
                    } else {
                        $error = 'Tên đăng nhập hoặc mật khẩu không đúng hoặc tài khoản không có quyền admin';
                    }
                } catch (PDOException $e) {
                    $error = 'Đã xảy ra lỗi: ' . $e->getMessage();
                }
            }
        }

        // Hiển thị form đăng nhập
        include __DIR__ . '/../View/Auth/login.php';
    }

    /**
     * Xử lý đăng xuất admin
     */
    public function logout() {
        // Xóa session
        session_start();
        session_unset();
        session_destroy();

        // Chuyển hướng về trang đăng nhập
        header('Location: ' . BASE_URL . '/index.php?controller=auth&action=login');
        exit;
    }
}
?> 