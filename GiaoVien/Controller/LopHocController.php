<?php
require_once 'Model/LopHocModel.php';
require_once 'Model/GiaoVienModel.php';
require_once 'Model/CauHoiModel.php';

class LopHocController {
    private $lopHocModel;
    private $giaoVienModel;
    private $cauHoiModel;
    
    public function __construct() {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['vai_tro'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        $this->lopHocModel = new LopHocModel();
        $this->giaoVienModel = new GiaoVienModel();
        $this->cauHoiModel = new CauHoiModel();
    }
    
    private function getGiaoVienId() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        $giao_vien = $this->giaoVienModel->getGiaoVienByNguoiDungId($_SESSION['user_id']);
        if (!$giao_vien) {
            die('Không tìm thấy thông tin giáo viên');
        }
        
        return $giao_vien['id'];
    }
    
    // Hiển thị danh sách lớp học
    public function index() {
        $giao_vien_id = $this->getGiaoVienId();
        $danhSachLopHoc = $this->lopHocModel->getLopHocByGiaoVien($giao_vien_id);
        
        include 'View/LopHoc/index.php';
    }
    
    // Tạo lớp học mới
    public function create() {
        $giao_vien_id = $this->getGiaoVienId();
        $error = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ma_lop = $_POST['ma_lop'] ?? '';
            $ten_lop = $_POST['ten_lop'] ?? '';
            $mo_ta = $_POST['mo_ta'] ?? '';
            $ngay_bat_dau = $_POST['ngay_bat_dau'] ?? null;
            $ngay_ket_thuc = $_POST['ngay_ket_thuc'] ?? null;
            
            if (empty($ma_lop)) {
                $error = "Vui lòng nhập mã lớp học";
            } else if (empty($ten_lop)) {
                $error = "Vui lòng nhập tên lớp học";
            } else {
                $result = $this->lopHocModel->createLopHoc($ma_lop, $ten_lop, $giao_vien_id, $mo_ta, $ngay_bat_dau, $ngay_ket_thuc);
                
                if (isset($result['success']) && $result['success']) {
                    header('Location: index.php?controller=lophoc&success=created');
                    exit;
                } else {
                    $error = $result['message'] ?? "Không thể tạo lớp học";
                }
            }
        }
        
        include 'View/LopHoc/taolophoc.php';
    }
    
    // Hiển thị chi tiết lớp học
    public function view($id) {
        $giao_vien_id = $this->getGiaoVienId();
        
        // Lấy thông tin chi tiết lớp học
        $lopHoc = $this->lopHocModel->getLopHocById($id);
        
        if (!$lopHoc) {
            header('Location: index.php?controller=lophoc&error=not_found');
            exit;
        }
        
        // Kiểm tra quyền truy cập
        if ($lopHoc['giao_vien_id'] != $giao_vien_id) {
            header('Location: index.php?controller=lophoc&error=permission_denied');
            exit;
        }
        
        // Lấy danh sách môn học trong lớp
        $danhSachMonHoc = $this->lopHocModel->getMonHocByLopHoc($id);
        
        include 'View/LopHoc/chitietlophoc.php';
    }
    
    // Cập nhật thông tin lớp học
    public function edit($id) {
        $giao_vien_id = $this->getGiaoVienId();
        $error = null;
        
        // Lấy thông tin chi tiết lớp học
        $lopHoc = $this->lopHocModel->getLopHocById($id);
        
        if (!$lopHoc) {
            header('Location: index.php?controller=lophoc&error=not_found');
            exit;
        }
        
        // Kiểm tra quyền truy cập
        if ($lopHoc['giao_vien_id'] != $giao_vien_id) {
            header('Location: index.php?controller=lophoc&error=permission_denied');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ma_lop = $_POST['ma_lop'] ?? '';
            $ten_lop = $_POST['ten_lop'] ?? '';
            $mo_ta = $_POST['mo_ta'] ?? '';
            $ngay_bat_dau = $_POST['ngay_bat_dau'] ?? null;
            $ngay_ket_thuc = $_POST['ngay_ket_thuc'] ?? null;
            
            if (empty($ma_lop)) {
                $error = "Vui lòng nhập mã lớp học";
            } else if (empty($ten_lop)) {
                $error = "Vui lòng nhập tên lớp học";
            } else {
                $result = $this->lopHocModel->updateLopHoc($id, $ma_lop, $ten_lop, $mo_ta, $ngay_bat_dau, $ngay_ket_thuc);
                
                if (isset($result['success']) && $result['success']) {
                    header('Location: index.php?controller=lophoc&action=view&id=' . $id . '&success=updated');
                    exit;
                } else {
                    $error = $result['message'] ?? "Không thể cập nhật thông tin lớp học";
                }
            }
        }
        
        include 'View/LopHoc/sualophoc.php';
    }
    
    // Xóa lớp học
    public function delete($id) {
        $giao_vien_id = $this->getGiaoVienId();
        
        // Lấy thông tin chi tiết lớp học
        $lopHoc = $this->lopHocModel->getLopHocById($id);
        
        if (!$lopHoc) {
            header('Location: index.php?controller=lophoc&error=not_found');
            exit;
        }
        
        // Kiểm tra quyền truy cập
        if ($lopHoc['giao_vien_id'] != $giao_vien_id) {
            header('Location: index.php?controller=lophoc&error=permission_denied');
            exit;
        }
        
        $result = $this->lopHocModel->deleteLopHoc($id);
        
        if (isset($result['success']) && $result['success']) {
            header('Location: index.php?controller=lophoc&success=deleted');
            exit;
        } else {
            header('Location: index.php?controller=lophoc&error=' . ($result['error'] ?? 'delete_failed'));
            exit;
        }
    }
    
    // Quản lý môn học trong lớp học
    public function manageSubjects($lop_hoc_id) {
        $giao_vien_id = $this->getGiaoVienId();
        $error = null;
        $success = null;
        
        // Lấy thông tin chi tiết lớp học
        $lopHoc = $this->lopHocModel->getLopHocById($lop_hoc_id);
        
        if (!$lopHoc) {
            header('Location: index.php?controller=lophoc&error=not_found');
            exit;
        }
        
        // Kiểm tra quyền truy cập
        if ($lopHoc['giao_vien_id'] != $giao_vien_id) {
            header('Location: index.php?controller=lophoc&error=permission_denied');
            exit;
        }
        
        // Lấy danh sách môn học trong lớp
        $danhSachMonHoc = $this->lopHocModel->getMonHocByLopHoc($lop_hoc_id);
        
        // Lấy danh sách môn học có sẵn (chưa được thêm vào lớp)
        $danhSachMonHocKhaDung = $this->lopHocModel->getAvailableMonHoc($giao_vien_id, $lop_hoc_id);
        
        // Xử lý thêm môn học vào lớp
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_mon_hoc'])) {
            $mon_hoc_id = $_POST['mon_hoc_id'] ?? '';
            
            if (empty($mon_hoc_id)) {
                $error = "Vui lòng chọn môn học cần thêm";
            } else {
                $result = $this->lopHocModel->addMonHocToLopHoc($lop_hoc_id, $mon_hoc_id);
                
                if (isset($result['success']) && $result['success']) {
                    $success = "Đã thêm môn học vào lớp thành công";
                    
                    // Cập nhật lại danh sách
                    $danhSachMonHoc = $this->lopHocModel->getMonHocByLopHoc($lop_hoc_id);
                    $danhSachMonHocKhaDung = $this->lopHocModel->getAvailableMonHoc($giao_vien_id, $lop_hoc_id);
                } else {
                    $error = $result['message'] ?? "Không thể thêm môn học vào lớp";
                }
            }
        }
        
        include 'View/LopHoc/quanlymonhoc.php';
    }
    
    // Xóa môn học khỏi lớp học
    public function removeSubject($lop_hoc_id, $mon_hoc_id) {
        $giao_vien_id = $this->getGiaoVienId();
        
        // Lấy thông tin chi tiết lớp học
        $lopHoc = $this->lopHocModel->getLopHocById($lop_hoc_id);
        
        if (!$lopHoc) {
            header('Location: index.php?controller=lophoc&error=not_found');
            exit;
        }
        
        // Kiểm tra quyền truy cập
        if ($lopHoc['giao_vien_id'] != $giao_vien_id) {
            header('Location: index.php?controller=lophoc&error=permission_denied');
            exit;
        }
        
        $result = $this->lopHocModel->removeMonHocFromLopHoc($lop_hoc_id, $mon_hoc_id);
        
        if (isset($result['success']) && $result['success']) {
            header('Location: index.php?controller=lophoc&action=manageSubjects&id=' . $lop_hoc_id . '&success=removed');
            exit;
        } else {
            header('Location: index.php?controller=lophoc&action=manageSubjects&id=' . $lop_hoc_id . '&error=' . ($result['error'] ?? 'remove_failed'));
            exit;
        }
    }
}
?> 