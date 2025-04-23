<?php
require_once 'Model/SinhVienModel.php';
require_once 'Model/KiemTraModel.php';

class HomeController {
    private $db;
    private $sinhVienModel;
    private $kiemTraModel;

    public function __construct($db) {
        $this->db = $db;
        $this->sinhVienModel = new SinhVienModel($this->db);
        $this->kiemTraModel = new KiemTraModel($this->db);
        
        // Đặt múi giờ Việt Nam
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    /**
     * Hiển thị trang chủ
     */
    public function index() {
        // Kiểm tra đăng nhập - sửa lỗi kiểm tra vai trò
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['vai_tro']) || $_SESSION['vai_tro'] !== 'sinh_vien') {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        
        // Lấy thông tin người dùng
        $user = $this->sinhVienModel->getUserById($userId);
        if (!$user) {
            $error = "Không tìm thấy thông tin sinh viên.";
            include 'View/Home/index.php';
            return;
        }

        // Lấy thông tin chi tiết sinh viên
        $sinhVienInfo = $this->sinhVienModel->getSinhVienInfoById($userId);
        
        // Đảm bảo sinh_vien_id đã được set trong session
        if ($sinhVienInfo && isset($sinhVienInfo['sinh_vien_id']) && !isset($_SESSION['sinh_vien_id'])) {
            $_SESSION['sinh_vien_id'] = $sinhVienInfo['sinh_vien_id'];
        }
        
        // Lấy danh sách các bài kiểm tra trắc nghiệm
        $tracNghiemTests = $this->kiemTraModel->getTracNghiemTestsForStudent($userId);
        
        // Lấy danh sách các bài kiểm tra tự luận
        $tuLuanTests = $this->kiemTraModel->getTuLuanTestsForStudent($userId);
        
        // Hiển thị view trang chủ
        include 'View/Home/index.php';
    }

    public function profile() {
        // Kiểm tra đăng nhập - sửa lỗi kiểm tra vai trò
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['vai_tro']) || $_SESSION['vai_tro'] !== 'sinh_vien') {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        
        // Lấy thông tin người dùng
        $user = $this->sinhVienModel->getUserById($userId);
        if (!$user) {
            $error = "Không tìm thấy thông tin sinh viên.";
            include 'View/Home/profile.php';
            return;
        }

        // Lấy thông tin chi tiết sinh viên
        $sinhVienInfo = $this->sinhVienModel->getSinhVienInfoById($userId);
        
        // Xử lý form cập nhật thông tin
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
            $email = trim($_POST['email']);
            $phoneNumber = trim($_POST['phone_number']);
            
            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Email không hợp lệ.";
            } else {
                // Cập nhật thông tin
                $result = $this->sinhVienModel->updateStudentProfile($userId, $email, $phoneNumber);
                
                if ($result) {
                    $success = "Cập nhật thông tin thành công.";
                    // Cập nhật thông tin mới
                    $user = $this->sinhVienModel->getUserById($userId);
                    $sinhVienInfo = $this->sinhVienModel->getSinhVienInfoById($userId);
                } else {
                    $error = "Có lỗi xảy ra khi cập nhật thông tin. Vui lòng thử lại.";
                }
            }
        }
        
        // Hiển thị view hồ sơ
        include 'View/Home/profile.php';
    }
}
?> 