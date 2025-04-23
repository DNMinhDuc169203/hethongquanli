<?php
require_once 'Config/Database.php';

class TrangChuController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Hàm kiểm tra xem bảng có tồn tại không
    private function tableExists($tableName) {
        $query = "SHOW TABLES LIKE ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$tableName]);
        return $stmt->rowCount() > 0;
    }

    // Hàm lấy ID giáo viên từ ID người dùng
    private function getGiaoVienId($userId) {
        $query = "SELECT id FROM giao_vien WHERE nguoi_dung_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }

    // Hàm lấy thông tin giáo viên
    private function getGiaoVienInfo($userId) {
        $query = "SELECT gv.*, nd.ho_va_ten, nd.email, nd.ma_so
                FROM giao_vien gv
                JOIN nguoi_dung nd ON gv.nguoi_dung_id = nd.id
                WHERE gv.nguoi_dung_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function index() {
        // Kiểm tra người dùng đã đăng nhập
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $giaoVienId = $this->getGiaoVienId($userId);
        $giaoVienInfo = $this->getGiaoVienInfo($userId);

        // Khởi tạo các biến với giá trị mặc định
        $totalLopHoc = 0;
        $totalSinhVien = 0;
        $totalMonHoc = 0;
        $totalCauHoi = 0;
        $totalBaiThi = 0;
        $lopHocMoi = [];
        $monHocList = [];

        // Thống kê tổng số lớp học của giáo viên
        if ($this->tableExists('lop_hoc') && $giaoVienId) {
            $queryLopHoc = "SELECT COUNT(*) as total FROM lop_hoc WHERE giao_vien_id = ?";
            $stmtLopHoc = $this->db->prepare($queryLopHoc);
            $stmtLopHoc->execute([$giaoVienId]);
            $totalLopHoc = $stmtLopHoc->fetch(PDO::FETCH_ASSOC)['total'];

            // Lấy danh sách lớp học mới nhất của giáo viên
            $queryLopHocMoi = "
                SELECT lh.*, 
                       (SELECT COUNT(*) FROM sinh_vien sv WHERE sv.lop_hoc_id = lh.id) as so_sinh_vien,
                       (SELECT COUNT(*) FROM lop_hoc_mon_hoc lhmh WHERE lhmh.lop_hoc_id = lh.id) as so_mon_hoc
                FROM lop_hoc lh 
                WHERE lh.giao_vien_id = ? 
                ORDER BY lh.ngay_tao DESC 
                LIMIT 5";
            $stmtLopHocMoi = $this->db->prepare($queryLopHocMoi);
            $stmtLopHocMoi->execute([$giaoVienId]);
            $lopHocMoi = $stmtLopHocMoi->fetchAll(PDO::FETCH_ASSOC);
        }

        // Thống kê tổng số sinh viên trong các lớp của giáo viên
        if ($this->tableExists('sinh_vien') && $this->tableExists('lop_hoc') && $giaoVienId) {
            $querySinhVien = "
                SELECT COUNT(DISTINCT sv.id) as total 
                FROM sinh_vien sv
                JOIN lop_hoc lh ON sv.lop_hoc_id = lh.id
                WHERE lh.giao_vien_id = ?";
            $stmtSinhVien = $this->db->prepare($querySinhVien);
            $stmtSinhVien->execute([$giaoVienId]);
            $totalSinhVien = $stmtSinhVien->fetch(PDO::FETCH_ASSOC)['total'];
        }

        // Thống kê tổng số môn học của giáo viên
        if ($this->tableExists('mon_hoc') && $giaoVienId) {
            $queryMonHoc = "SELECT COUNT(*) as total FROM mon_hoc WHERE giao_vien_id = ?";
            $stmtMonHoc = $this->db->prepare($queryMonHoc);
            $stmtMonHoc->execute([$giaoVienId]);
            $totalMonHoc = $stmtMonHoc->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Lấy danh sách môn học của giáo viên
            $queryMonHocList = "SELECT * FROM mon_hoc WHERE giao_vien_id = ? ORDER BY ngay_tao DESC LIMIT 5";
            $stmtMonHocList = $this->db->prepare($queryMonHocList);
            $stmtMonHocList->execute([$giaoVienId]);
            $monHocList = $stmtMonHocList->fetchAll(PDO::FETCH_ASSOC);
        }

        // Thống kê tổng số câu hỏi của giáo viên
        if ($this->tableExists('cau_hoi_trac_nghiem') && $giaoVienId) {
            $queryCauHoi = "SELECT COUNT(*) as total FROM cau_hoi_trac_nghiem WHERE giao_vien_id = ?";
            $stmtCauHoi = $this->db->prepare($queryCauHoi);
            $stmtCauHoi->execute([$giaoVienId]);
            $totalCauHoi = $stmtCauHoi->fetch(PDO::FETCH_ASSOC)['total'];
        }

        // Thống kê tổng số bài thi (cả trắc nghiệm và tự luận)
        $totalBaiThi = 0;
        
        // Đếm bài kiểm tra trắc nghiệm
        if ($this->tableExists('bai_kiem_tra_trac_nghiem') && $giaoVienId) {
            $queryTracNghiem = "
                SELECT COUNT(*) as total 
                FROM bai_kiem_tra_trac_nghiem bkt
                JOIN lop_hoc lh ON bkt.lop_hoc_id = lh.id
                WHERE lh.giao_vien_id = ?";
            $stmtTracNghiem = $this->db->prepare($queryTracNghiem);
            $stmtTracNghiem->execute([$giaoVienId]);
            $totalTracNghiem = $stmtTracNghiem->fetch(PDO::FETCH_ASSOC)['total'];
            $totalBaiThi += $totalTracNghiem;
        }

        // Đếm bài kiểm tra tự luận
        if ($this->tableExists('bai_kiem_tra_tu_luan') && $giaoVienId) {
            $queryTuLuan = "
                SELECT COUNT(*) as total 
                FROM bai_kiem_tra_tu_luan bkt
                JOIN lop_hoc lh ON bkt.lop_hoc_id = lh.id
                WHERE lh.giao_vien_id = ?";
            $stmtTuLuan = $this->db->prepare($queryTuLuan);
            $stmtTuLuan->execute([$giaoVienId]);
            $totalTuLuan = $stmtTuLuan->fetch(PDO::FETCH_ASSOC)['total'];
            $totalBaiThi += $totalTuLuan;
        }

        // Lấy danh sách bài kiểm tra mới nhất
        $baiKiemTraMoi = [];
        if ($this->tableExists('bai_kiem_tra_trac_nghiem') && $this->tableExists('bai_kiem_tra_tu_luan') && $giaoVienId) {
            $query = "
                (SELECT bkt.id, bkt.tieu_de, 'trac_nghiem' as loai, bkt.ngay_tao, lh.ten_lop, mh.ten_mon
                FROM bai_kiem_tra_trac_nghiem bkt
                JOIN lop_hoc lh ON bkt.lop_hoc_id = lh.id
                JOIN mon_hoc mh ON bkt.mon_hoc_id = mh.id
                WHERE lh.giao_vien_id = ?
                ORDER BY bkt.ngay_tao DESC LIMIT 5)
                UNION
                (SELECT bkt.id, bkt.tieu_de, 'tu_luan' as loai, bkt.ngay_tao, lh.ten_lop, mh.ten_mon 
                FROM bai_kiem_tra_tu_luan bkt
                JOIN lop_hoc lh ON bkt.lop_hoc_id = lh.id
                JOIN mon_hoc mh ON bkt.mon_hoc_id = mh.id
                WHERE lh.giao_vien_id = ?
                ORDER BY bkt.ngay_tao DESC LIMIT 5)
                ORDER BY ngay_tao DESC LIMIT 5
            ";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$giaoVienId, $giaoVienId]);
            $baiKiemTraMoi = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Chuyển dữ liệu tới view
        include 'View/TrangChu/index.php';
    }
}
?>