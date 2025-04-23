<?php
require_once 'Model/SinhVienModel.php';

class AuthController {
    private $db;
    private $sinhVienModel;

    public function __construct($db) {
        $this->db = $db;
        $this->sinhVienModel = new SinhVienModel($db);
    }

    public function login() {
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy thông tin đăng nhập
            $maSo = isset($_POST['ma_so']) ? trim($_POST['ma_so']) : '';
            $matKhau = isset($_POST['mat_khau']) ? $_POST['mat_khau'] : '';
            
            if (empty($maSo) || empty($matKhau)) {
                $error = 'Vui lòng nhập đầy đủ thông tin đăng nhập';
            } else {
                // Kiểm tra thông tin đăng nhập và trạng thái tài khoản
                $user = $this->sinhVienModel->checkLogin($maSo, $matKhau);
                
                if ($user) {
                    // Kiểm tra nếu tài khoản bị khóa
                    if (isset($user['trang_thai']) && $user['trang_thai'] == 0) {
                        $error = 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.';
                    } else {
                        // Đăng nhập thành công
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['ho_va_ten'] = $user['ho_va_ten'];
                        $_SESSION['ma_so'] = $user['ma_so'];
                        $_SESSION['vai_tro'] = $user['vai_tro'];
                        
                        // Lấy thông tin sinh viên để lưu sinh_vien_id vào session
                        $sinhVienInfo = $this->sinhVienModel->getSinhVienInfoById($user['id']);
                        if ($sinhVienInfo && isset($sinhVienInfo['sinh_vien_id'])) {
                            $_SESSION['sinh_vien_id'] = $sinhVienInfo['sinh_vien_id'];
                        }
                        
                        // Kiểm tra lần đăng nhập đầu tiên (mật khẩu trùng với mã số)
                        if ($matKhau === $maSo) {
                            $_SESSION['change_password_required'] = true;
                            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=changePassword');
                        } else {
                            // Kiểm tra xem có redirect URL sau khi đăng nhập hay không (ví dụ: đang làm bài thi)
                            if (isset($_SESSION['redirect_after_login']) && !empty($_SESSION['redirect_after_login'])) {
                                $redirectUrl = $_SESSION['redirect_after_login'];
                                unset($_SESSION['redirect_after_login']); // Xóa URL chuyển hướng sau khi sử dụng
                                header('Location: ' . $redirectUrl);
                            } else {
                                header('Location: ' . BASE_URL . '/index.php?controller=home');
                            }
                        }
                        exit;
                    }
                } else {
                    $error = 'Mã số hoặc mật khẩu không đúng';
                }
            }
        }
        
        // Hiển thị form đăng nhập
        include 'View/Auth/login.php';
    }
    
    public function changePassword() {
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = isset($_POST['current_password']) ? $_POST['current_password'] : '';
            $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
            $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
            
            // Kiểm tra điều kiện đổi mật khẩu
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $error = 'Vui lòng điền đầy đủ thông tin';
            } elseif ($newPassword !== $confirmPassword) {
                $error = 'Mật khẩu mới và xác nhận mật khẩu không khớp';
            } elseif ($newPassword === $currentPassword) {
                $error = 'Mật khẩu mới không được giống mật khẩu hiện tại';
            } elseif (strlen($newPassword) < 6) {
                $error = 'Mật khẩu mới phải có ít nhất 6 ký tự';
            } else {
                // Kiểm tra mật khẩu hiện tại
                $userId = $_SESSION['user_id'];
                $user = $this->sinhVienModel->getUserById($userId);
                
                if ($user && $user['mat_khau'] === $currentPassword) {
                    // Cập nhật mật khẩu mới
                    $result = $this->sinhVienModel->updatePassword($userId, $newPassword);
                    
                    if ($result) {
                        // Đánh dấu rõ ràng là đã đổi mật khẩu
                        $_SESSION['change_password_required'] = false;
                        
                        // Lưu session ngay lập tức
                        session_write_close();
                        session_start();
                        
                        $success = 'Đổi mật khẩu thành công! Bạn sẽ được chuyển đến trang chính sau 2 giây.';
                        
                        // Chuyển hướng sau 2 giây
                        header('refresh:2;url=' . BASE_URL . '/index.php?controller=home');
                    } else {
                        $error = 'Đã xảy ra lỗi khi cập nhật mật khẩu';
                    }
                } else {
                    $error = 'Mật khẩu hiện tại không đúng';
                }
            }
        }
        
        // Hiển thị form đổi mật khẩu
        include 'View/Auth/change_password.php';
    }
    
    public function logout() {
        // Check if student is in the middle of a test
        if (isset($_SESSION['user_id']) && isset($_SESSION['vai_tro']) && $_SESSION['vai_tro'] === 'sinh_vien' && isset($_SESSION['sinh_vien_id'])) {
            $sinhVienId = $_SESSION['sinh_vien_id'];
            
            // Check if any test is in progress by looking for session keys
            foreach ($_SESSION as $key => $value) {
                if (strpos($key, 'test_in_progress_') === 0) {
                    $testId = substr($key, strlen('test_in_progress_'));
                    
                    // Load model if needed
                    require_once 'Model/KiemTraModel.php';
                    $kiemTraModel = new KiemTraModel();
                    
                    // Get session for this test
                    $session = $kiemTraModel->getTracNghiemSession($sinhVienId, $testId);
                    
                    if ($session) {
                        // Update session status to 'tam_ngung'
                        $kiemTraModel->updateTracNghiemSessionStatus($session['id'], 'tam_ngung');
                    }
                }
            }
        }
        
        // Hủy phiên đăng nhập
        session_start();
        session_destroy();
        
        // Chuyển hướng về trang đăng nhập
        header('Location: ' . BASE_URL . '/index.php?controller=auth&action=login');
        exit;
    }
}
?> 