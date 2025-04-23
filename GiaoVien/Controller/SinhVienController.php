<?php

class SinhVienController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        // Kiểm tra phiên đăng nhập và lấy ID của giáo viên đang đăng nhập
        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] !== 'giao_vien') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        $nguoi_dung_id = $_SESSION['user_id'];
        
        // Lấy thông tin giáo viên từ người dùng ID
        $giaoVienQuery = "SELECT gv.id, nd.ho_va_ten 
                          FROM giao_vien gv 
                          JOIN nguoi_dung nd ON gv.nguoi_dung_id = nd.id 
                          WHERE gv.nguoi_dung_id = ?";
        $giaoVienStmt = $this->db->prepare($giaoVienQuery);
        $giaoVienStmt->execute([$nguoi_dung_id]);
        $giaoVien = $giaoVienStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$giaoVien) {
            // Không tìm thấy thông tin giáo viên
            $danhSachLopHoc = [];
            $ten_giao_vien = '';
            require_once 'View/SinhVien/index.php';
            return;
        }
        
        $giao_vien_id = $giaoVien['id'];
        $ten_giao_vien = $giaoVien['ho_va_ten'];
        
        // Lấy danh sách lớp học của giáo viên đang đăng nhập và sinh viên trong mỗi lớp
        $query = "SELECT l.id as lop_id, l.ten_lop, l.ma_lop,
                        s.id as sinh_vien_id, s.nam_nhap_hoc, s.nganh_hoc,
                        n.ma_so, n.ho_va_ten, n.email
                 FROM lop_hoc l
                 LEFT JOIN sinh_vien s ON l.id = s.lop_hoc_id
                 LEFT JOIN nguoi_dung n ON s.nguoi_dung_id = n.id
                 WHERE l.giao_vien_id = ?
                 ORDER BY l.ten_lop, n.ho_va_ten";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$giao_vien_id]);
        $danhSachLopHoc = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $lopId = $row['lop_id'];
            
            if (!isset($danhSachLopHoc[$lopId])) {
                $danhSachLopHoc[$lopId] = [
                    'ten_lop' => $row['ten_lop'],
                    'ma_lop' => $row['ma_lop'],
                    'sinh_vien' => []
                ];
            }
            
            if ($row['sinh_vien_id']) {
                $danhSachLopHoc[$lopId]['sinh_vien'][] = [
                    'ma_so' => $row['ma_so'],
                    'ho_va_ten' => $row['ho_va_ten'],
                    'email' => $row['email'],
                    'nam_nhap_hoc' => $row['nam_nhap_hoc'],
                    'nganh_hoc' => $row['nganh_hoc']
                ];
            }
        }
        
        // Chuyển đổi mảng kết hợp thành mảng tuần tự
        $danhSachLopHoc = array_values($danhSachLopHoc);
        
        // Load view
        require_once 'View/SinhVien/index.php';
    }
} 