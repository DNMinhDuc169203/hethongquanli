<?php
require_once __DIR__ . '/../Model/UserModel.php';

class UserController {
    private $db;
    private $userModel;

    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new UserModel($db);
    }

    /**
     * Hiển thị danh sách người dùng
     */
    public function index() {
        // Xác định vai trò cần lọc (nếu có)
        $roleFilter = isset($_GET['role']) ? $_GET['role'] : '';
        
        // Xác định từ khóa tìm kiếm (nếu có)
        $searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        try {
            // Chuẩn bị câu truy vấn SQL cơ bản
            $sql = "SELECT 
                    nd.id, 
                    nd.ma_so, 
                    nd.ho_va_ten, 
                    nd.email, 
                    nd.vai_tro, 
                    nd.ngay_tao, 
                    nd.trang_thai,
                    CASE 
                        WHEN nd.vai_tro = 'giao_vien' THEN gv.hoc_vi 
                        ELSE NULL 
                    END AS hoc_vi,
                    CASE 
                        WHEN nd.vai_tro = 'giao_vien' THEN gv.chuyen_nganh 
                        WHEN nd.vai_tro = 'sinh_vien' THEN sv.nganh_hoc 
                        ELSE NULL 
                    END AS chuyen_nganh,
                    CASE 
                        WHEN nd.vai_tro = 'sinh_vien' THEN lh.ten_lop
                        ELSE NULL 
                    END AS ten_lop
                FROM 
                    nguoi_dung nd 
                LEFT JOIN 
                    giao_vien gv ON nd.id = gv.nguoi_dung_id AND nd.vai_tro = 'giao_vien' 
                LEFT JOIN 
                    sinh_vien sv ON nd.id = sv.nguoi_dung_id AND nd.vai_tro = 'sinh_vien'
                LEFT JOIN 
                    lop_hoc lh ON sv.lop_hoc_id = lh.id
                WHERE 
                    nd.vai_tro != 'admin'";
            
            // Thêm điều kiện lọc theo vai trò (nếu có)
            $params = [];
            if ($roleFilter) {
                $sql .= " AND nd.vai_tro = ?";
                $params[] = $roleFilter;
            }
            
            // Thêm điều kiện tìm kiếm (nếu có)
            if ($searchKeyword) {
                $sql .= " AND (nd.ma_so LIKE ? OR nd.ho_va_ten LIKE ? OR nd.email LIKE ?)";
                $searchPattern = "%$searchKeyword%";
                $params[] = $searchPattern;
                $params[] = $searchPattern;
                $params[] = $searchPattern;
            }
            
            // Sắp xếp kết quả
            $sql .= " ORDER BY nd.ngay_tao DESC";
            
            // Thực thi truy vấn
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Lấy tổng số người dùng từng loại
            $countStmt = $this->db->prepare("SELECT vai_tro, COUNT(*) as total FROM nguoi_dung WHERE vai_tro != 'admin' GROUP BY vai_tro");
            $countStmt->execute();
            $counts = $countStmt->fetchAll(PDO::FETCH_ASSOC);
            
            $userCounts = [
                'total' => 0,
                'giao_vien' => 0,
                'sinh_vien' => 0
            ];
            
            foreach ($counts as $count) {
                $userCounts[$count['vai_tro']] = $count['total'];
                $userCounts['total'] += $count['total'];
            }
            
        } catch (PDOException $e) {
            $error = 'Lỗi truy vấn cơ sở dữ liệu: ' . $e->getMessage();
            $users = [];
            $userCounts = [
                'total' => 0,
                'giao_vien' => 0,
                'sinh_vien' => 0
            ];
        }
        
        // Hiển thị danh sách người dùng
        include __DIR__ . '/../View/User/index.php';
    }

    /**
     * Xem chi tiết người dùng
     */
    public function view() {
        // Lấy ID người dùng từ request
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if (!$id) {
            header('Location: ' . BASE_URL . '/index.php?controller=user&error=ID người dùng không hợp lệ');
            exit;
        }
        
        // Lấy thông tin người dùng
        $user = $this->userModel->getUserById($id);
        
        if (!$user) {
            header('Location: ' . BASE_URL . '/index.php?controller=user&error=Không tìm thấy người dùng');
            exit;
        }
        
        $teacherInfo = null;
        $teacherSubjects = null;
        $studentInfo = null;
        $studentSubjects = null;
        
        // Lấy thông tin bổ sung dựa trên vai trò
        if ($user['vai_tro'] == 'giao_vien') {
            // Lấy thông tin giáo viên
            $teacherInfo = $this->userModel->getTeacherInfo($id);
            
            // Lấy danh sách môn học giáo viên phụ trách
            $teacherSubjects = $this->userModel->getTeacherSubjects($id);
            
        } elseif ($user['vai_tro'] == 'sinh_vien') {
            // Lấy thông tin sinh viên
            $studentInfo = $this->userModel->getStudentInfo($id);
            
            // Lấy danh sách môn học sinh viên đã đăng ký
            $studentSubjects = $this->userModel->getStudentSubjects($id);
        }
        
        // Truyền dữ liệu ra view
        include __DIR__ . '/../View/User/view.php';
    }

    /**
     * Kích hoạt/Vô hiệu hóa người dùng - Chức năng mẫu
     */
    public function toggleStatus() {
        // Lấy ID người dùng từ request
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if (!$id) {
            header('Location: ' . BASE_URL . '/index.php?controller=user&error=ID người dùng không hợp lệ');
            exit;
        }
        
        // Lấy thông tin người dùng
        $user = $this->userModel->getUserById($id);
        
        if (!$user) {
            header('Location: ' . BASE_URL . '/index.php?controller=user&error=Không tìm thấy người dùng');
            exit;
        }
        
        // Đảo ngược trạng thái
        $newStatus = $user['trang_thai'] == 1 ? 0 : 1;
        $success = $this->userModel->updateUserStatus($id, $newStatus);
        
        if ($success) {
            $message = $newStatus == 1 ? 'Kích hoạt tài khoản thành công' : 'Vô hiệu hóa tài khoản thành công';
            header("Location: " . BASE_URL . "/index.php?controller=user&action=view&id=$id&message=" . urlencode($message));
        } else {
            header("Location: " . BASE_URL . "/index.php?controller=user&action=view&id=$id&error=Cập nhật trạng thái thất bại");
        }
        exit;
    }

    /**
     * Đặt lại mật khẩu người dùng
     */
    public function resetPassword() {
        // Lấy ID người dùng từ request
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if (!$id) {
            header('Location: ' . BASE_URL . '/index.php?controller=user&error=ID người dùng không hợp lệ');
            exit;
        }
        
        // Lấy thông tin người dùng
        $user = $this->userModel->getUserById($id);
        
        if (!$user) {
            header('Location: ' . BASE_URL . '/index.php?controller=user&error=Không tìm thấy người dùng');
            exit;
        }
        
        // Đặt lại mật khẩu về mặc định (123456) mà không mã hóa
        $defaultPassword = '123456';
        $success = $this->userModel->resetUserPassword($id, $defaultPassword);
        
        if ($success) {
            header("Location: " . BASE_URL . "/index.php?controller=user&action=view&id=$id&message=Đặt lại mật khẩu thành công thành '123456'");
        } else {
            header("Location: " . BASE_URL . "/index.php?controller=user&action=view&id=$id&error=Đặt lại mật khẩu thất bại");
        }
        exit;
    }
}
?> 