<?php
require_once 'Model/BaiThiModel.php';
require_once 'Model/GiaoVienModel.php';
require_once 'Model/SinhVienModel.php';

class KetQuaBaiThiController {
    private $baiThiModel;
    private $giaoVienModel;
    private $sinhVienModel;
    
    public function __construct() {
        $this->baiThiModel = new BaiThiModel();
        $this->giaoVienModel = new GiaoVienModel();
        $this->sinhVienModel = new SinhVienModel();
        
        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] !== 'giao_vien') {
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
    
    public function index() {
        $giao_vien_id = $this->getGiaoVienId();
        
        // Lấy danh sách bài thi trắc nghiệm
        $danhSachBaiThiTracNghiem = $this->baiThiModel->getBaiThiTracNghiemByGiaoVien($giao_vien_id);
        
        // Lấy danh sách bài thi tự luận
        $danhSachBaiThiTuLuan = $this->baiThiModel->getBaiThiTuLuanByGiaoVien($giao_vien_id);
        
        include 'View/KetQuabaithi/index.php';
    }
    
    public function ketQuaTracNghiem($baiThiId) {
        $giao_vien_id = $this->getGiaoVienId();
        
        // Kiểm tra bài thi có thuộc về giáo viên không
        $baiThi = $this->baiThiModel->getBaiThiTracNghiemById($baiThiId);
        if (!$baiThi) {
            $_SESSION['error'] = "Không tìm thấy bài thi.";
            header('Location: index.php?controller=ketquabaithi');
            exit;
        }
        
        // Lấy danh sách kết quả của sinh viên làm bài trắc nghiệm
        $ketQuaBaiThi = $this->baiThiModel->getKetQuaTracNghiemByBaiThiId($baiThiId);
        
        // Lấy số câu hỏi trong bài kiểm tra
        // Sử dụng kết quả đầu tiên nếu có
        if (!empty($ketQuaBaiThi) && isset($ketQuaBaiThi[0]['tong_so_cau'])) {
            $baiThi['so_cau_hoi'] = $ketQuaBaiThi[0]['tong_so_cau'];
        } else {
            // Nếu không có kết quả, đặt giá trị mặc định là 0
            $baiThi['so_cau_hoi'] = 0;
        }
        
        include 'View/KetQuabaithi/ketqua_tracnghiem.php';
    }
    
    public function ketQuaTuLuan($baiThiId) {
        $giao_vien_id = $this->getGiaoVienId();
        
        // Kiểm tra bài thi có thuộc về giáo viên không
        $baiThi = $this->baiThiModel->getBaiThiTuLuanById($baiThiId);
        if (!$baiThi) {
            $_SESSION['error'] = "Không tìm thấy bài thi.";
            header('Location: index.php?controller=ketquabaithi');
            exit;
        }
        
        // Lấy danh sách kết quả của sinh viên làm bài tự luận
        $ketQuaBaiThi = $this->baiThiModel->getKetQuaTuLuanByBaiThiId($baiThiId);
        
        include 'View/KetQuabaithi/ketqua_tuluan.php';
    }
    
    public function xemBaiTuLuan($baiLamId) {
        $giao_vien_id = $this->getGiaoVienId();
        
        // Lấy thông tin bài làm tự luận
        $baiLam = $this->baiThiModel->getBaiLamTuLuanById($baiLamId);
        if (!$baiLam) {
            $_SESSION['error'] = "Không tìm thấy bài làm.";
            header('Location: index.php?controller=ketquabaithi');
            exit;
        }
        
        // Lấy thông tin sinh viên
        $sinhVien = $this->sinhVienModel->getSinhVienById($baiLam['sinh_vien_id']);
        
        // Lấy thông tin bài kiểm tra
        $baiKiemTra = $this->baiThiModel->getBaiThiTuLuanById($baiLam['bai_kiem_tra_id']);
        
        include 'View/KetQuabaithi/xem_bai_tuluan.php';
    }
    
    public function chamDiem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=ketquabaithi');
            exit;
        }
        
        $baiLamId = $_POST['bai_lam_id'] ?? 0;
        $diem = $_POST['diem'] ?? null;
        $nhanXet = $_POST['nhan_xet'] ?? '';
        
        // Kiểm tra dữ liệu đầu vào
        if (empty($baiLamId) || !is_numeric($diem) || $diem < 0 || $diem > 10) {
            $_SESSION['error'] = "Dữ liệu không hợp lệ.";
            header('Location: index.php?controller=ketquabaithi&action=xemBaiTuLuan&id=' . $baiLamId);
            exit;
        }
        
        // Cập nhật điểm và nhận xét
        $result = $this->baiThiModel->updateDiemBaiLamTuLuan($baiLamId, $diem, $nhanXet);
        
        if ($result) {
            $_SESSION['success'] = "Đã chấm điểm và lưu nhận xét thành công.";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi lưu điểm và nhận xét.";
        }
        
        header('Location: index.php?controller=ketquabaithi&action=xemBaiTuLuan&id=' . $baiLamId);
    }
} 