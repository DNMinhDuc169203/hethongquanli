<?php
require_once 'Model/BaiThiTuLuanModel.php';
require_once 'Model/GiaoVienModel.php';

class BaiThiTuLuanController {
    private $baiThiTuLuanModel;
    private $giaoVienModel;
    
    public function __construct() {
        $this->baiThiTuLuanModel = new BaiThiTuLuanModel();
        $this->giaoVienModel = new GiaoVienModel();
        
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
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
    
    public function create() {
        $giao_vien_id = $this->getGiaoVienId();
        
        // Lấy danh sách lớp học của giáo viên
        $danhSachLopHoc = $this->giaoVienModel->getLopHocByGiaoVien($giao_vien_id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lop_hoc_id = $_POST['lop_hoc_id'] ?? '';
            $mon_hoc_id = $_POST['mon_hoc_id'] ?? '';
            $tieu_de = $_POST['tieu_de'] ?? '';
            $mo_ta = $_POST['mo_ta'] ?? '';
            $noi_dung = $_POST['noi_dung'] ?? '';
            $so_lan_lam = $_POST['so_lan_lam'] ?? 1;
            
            // Xử lý chế độ bài thi: trên lớp hoặc bài tập về nhà
            $che_do_thi = $_POST['che_do_thi'] ?? 'tren_lop';
            
            if ($che_do_thi == 'tren_lop') {
                // Chế độ làm bài trên lớp - chỉ cần thời gian làm bài
                $thoi_gian_lam = $_POST['thoi_gian_lam'] ?? null;
                // Thiết lập múi giờ Việt Nam
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $thoi_gian_bat_dau = date('Y-m-d H:i:s'); // Sử dụng thời gian hiện tại làm thời gian bắt đầu
                $thoi_gian_ket_thuc = null; // Không có thời gian kết thúc cho bài thi trên lớp
            } else {
                // Chế độ bài tập về nhà - cần ngày bắt đầu và kết thúc
                $thoi_gian_lam = null;
                $thoi_gian_bat_dau = $_POST['thoi_gian_bat_dau'] ?? null;
                $thoi_gian_ket_thuc = $_POST['thoi_gian_ket_thuc'] ?? null;
            }
            
            // Tạo bài thi tự luận mới
            $bai_thi_id = $this->baiThiTuLuanModel->createBaiThiTuLuan(
                $lop_hoc_id,
                $mon_hoc_id,
                $tieu_de,
                $mo_ta,
                $noi_dung,
                $thoi_gian_lam,
                $thoi_gian_bat_dau,
                $thoi_gian_ket_thuc,
                $so_lan_lam
            );
            
            if ($bai_thi_id) {
                header('Location: index.php?controller=baithi');
                exit;
            }
        }
        
        // Chuẩn bị dữ liệu cho AJAX
        if (isset($_GET['lop_hoc_id'])) {
            $lop_hoc_id = $_GET['lop_hoc_id'];
            $danhSachMonHoc = $this->baiThiTuLuanModel->getMonHocByLopHoc($lop_hoc_id);
            
            // Trả về dữ liệu dạng JSON
            header('Content-Type: application/json');
            echo json_encode($danhSachMonHoc);
            exit;
        }
        
        include 'View/BaiThi/taobaithituluan.php';
    }
    
    public function view($id) {
        $giao_vien_id = $this->getGiaoVienId();
        $baiThi = $this->baiThiTuLuanModel->getBaiThiTuLuanById($id);
        
        if (!$baiThi) {
            echo "Không tìm thấy bài thi";
            return;
        }
        
        // Kiểm tra quyền xem
        $lopHoc = $this->giaoVienModel->getLopHocById($baiThi['lop_hoc_id']);
        if ($lopHoc['giao_vien_id'] != $giao_vien_id) {
            echo "Bạn không có quyền xem bài thi này";
            return;
        }
        
        include 'View/BaiThi/xembaithituluan.php';
    }
    
    public function edit($id) {
        $giao_vien_id = $this->getGiaoVienId();
        $baiThi = $this->baiThiTuLuanModel->getBaiThiTuLuanById($id);
        
        if (!$baiThi) {
            echo "Không tìm thấy bài thi";
            return;
        }
        
        // Kiểm tra quyền sửa
        $lopHoc = $this->giaoVienModel->getLopHocById($baiThi['lop_hoc_id']);
        if ($lopHoc['giao_vien_id'] != $giao_vien_id) {
            echo "Bạn không có quyền sửa bài thi này";
            return;
        }
        
        // Lấy danh sách lớp học của giáo viên
        $danhSachLopHoc = $this->giaoVienModel->getLopHocByGiaoVien($giao_vien_id);
        
        // Lấy danh sách môn học của lớp
        $danhSachMonHoc = $this->baiThiTuLuanModel->getMonHocByLopHoc($baiThi['lop_hoc_id']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lop_hoc_id = $_POST['lop_hoc_id'] ?? '';
            $mon_hoc_id = $_POST['mon_hoc_id'] ?? '';
            $tieu_de = $_POST['tieu_de'] ?? '';
            $mo_ta = $_POST['mo_ta'] ?? '';
            $noi_dung = $_POST['noi_dung'] ?? '';
            $so_lan_lam = $_POST['so_lan_lam'] ?? 1;
            
            // Xử lý chế độ bài thi: trên lớp hoặc bài tập về nhà
            $che_do_thi = $_POST['che_do_thi'] ?? 'tren_lop';
            
            if ($che_do_thi == 'tren_lop') {
                // Chế độ làm bài trên lớp - chỉ cần thời gian làm bài
                $thoi_gian_lam = $_POST['thoi_gian_lam'] ?? null;
                // Thiết lập múi giờ Việt Nam
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $thoi_gian_bat_dau = date('Y-m-d H:i:s'); // Sử dụng thời gian hiện tại làm thời gian bắt đầu
                $thoi_gian_ket_thuc = null;
            } else {
                // Chế độ bài tập về nhà - cần ngày bắt đầu và kết thúc
                $thoi_gian_lam = null;
                $thoi_gian_bat_dau = $_POST['thoi_gian_bat_dau'] ?? null;
                $thoi_gian_ket_thuc = $_POST['thoi_gian_ket_thuc'] ?? null;
            }
            
            // Cập nhật bài thi
            $success = $this->baiThiTuLuanModel->updateBaiThiTuLuan(
                $id,
                $lop_hoc_id,
                $mon_hoc_id,
                $tieu_de,
                $mo_ta,
                $noi_dung,
                $thoi_gian_lam,
                $thoi_gian_bat_dau,
                $thoi_gian_ket_thuc,
                $so_lan_lam
            );
            
            if ($success) {
                header('Location: index.php?controller=baithi');
                exit;
            }
        }
        
        include 'View/BaiThi/suabaithituluan.php';
    }
    
    public function delete($id) {
        $giao_vien_id = $this->getGiaoVienId();
        $baiThi = $this->baiThiTuLuanModel->getBaiThiTuLuanById($id);
        
        if (!$baiThi) {
            header('Location: index.php?controller=baithi&error=not_found');
            exit;
        }
        
        // Kiểm tra quyền xóa
        $lopHoc = $this->giaoVienModel->getLopHocById($baiThi['lop_hoc_id']);
        if ($lopHoc['giao_vien_id'] != $giao_vien_id) {
            header('Location: index.php?controller=baithi&error=permission_denied');
            exit;
        }
        
        $success = $this->baiThiTuLuanModel->deleteBaiThiTuLuan($id);
        
        if ($success) {
            header('Location: index.php?controller=baithi&success=deleted');
        } else {
            header('Location: index.php?controller=baithi&error=delete_failed');
        }
        exit;
    }
    
    public function index() {
        $giao_vien_id = $this->getGiaoVienId();
        $danhSachBaiThi = $this->baiThiTuLuanModel->getBaiThiTuLuanByGiaoVien($giao_vien_id);
        
        include 'View/BaiThi/danhsachbaithituluan.php';
    }
} 