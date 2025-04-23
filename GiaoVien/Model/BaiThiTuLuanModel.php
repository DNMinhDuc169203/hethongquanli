<?php
require_once 'Config/Database.php';

class BaiThiTuLuanModel {
    private $conn;
    private $table = 'bai_kiem_tra_tu_luan';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function createBaiThiTuLuan($lop_hoc_id, $mon_hoc_id, $tieu_de, $mo_ta, $noi_dung, $thoi_gian_lam, $thoi_gian_bat_dau, $thoi_gian_ket_thuc, $so_lan_lam) {
        try {
            $query = "INSERT INTO " . $this->table . " (
                lop_hoc_id,
                mon_hoc_id,
                tieu_de, 
                mo_ta, 
                noi_dung, 
                thoi_gian_lam, 
                thoi_gian_bat_dau, 
                thoi_gian_ket_thuc,
                so_lan_lam
            ) VALUES (
                :lop_hoc_id,
                :mon_hoc_id,
                :tieu_de,
                :mo_ta, 
                :noi_dung,
                :thoi_gian_lam,
                :thoi_gian_bat_dau,
                :thoi_gian_ket_thuc,
                :so_lan_lam
            )";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':lop_hoc_id', $lop_hoc_id);
            $stmt->bindParam(':mon_hoc_id', $mon_hoc_id);
            $stmt->bindParam(':tieu_de', $tieu_de);
            $stmt->bindParam(':mo_ta', $mo_ta);
            $stmt->bindParam(':noi_dung', $noi_dung);
            $stmt->bindParam(':thoi_gian_lam', $thoi_gian_lam);
            $stmt->bindParam(':thoi_gian_bat_dau', $thoi_gian_bat_dau);
            $stmt->bindParam(':thoi_gian_ket_thuc', $thoi_gian_ket_thuc);
            $stmt->bindParam(':so_lan_lam', $so_lan_lam);
            
            $stmt->execute();
            
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi tạo bài thi tự luận: " . $e->getMessage());
            return false;
        }
    }
    
    public function getBaiThiTuLuanById($id) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy bài thi tự luận: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateBaiThiTuLuan($id, $lop_hoc_id, $mon_hoc_id, $tieu_de, $mo_ta, $noi_dung, $thoi_gian_lam, $thoi_gian_bat_dau, $thoi_gian_ket_thuc, $so_lan_lam) {
        try {
            $query = "UPDATE " . $this->table . " SET 
                lop_hoc_id = :lop_hoc_id,
                mon_hoc_id = :mon_hoc_id,
                tieu_de = :tieu_de,
                mo_ta = :mo_ta,
                noi_dung = :noi_dung,
                thoi_gian_lam = :thoi_gian_lam,
                thoi_gian_bat_dau = :thoi_gian_bat_dau,
                thoi_gian_ket_thuc = :thoi_gian_ket_thuc,
                so_lan_lam = :so_lan_lam,
                ngay_cap_nhat = CURRENT_TIMESTAMP
                WHERE id = :id";
                
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':lop_hoc_id', $lop_hoc_id);
            $stmt->bindParam(':mon_hoc_id', $mon_hoc_id);
            $stmt->bindParam(':tieu_de', $tieu_de);
            $stmt->bindParam(':mo_ta', $mo_ta);
            $stmt->bindParam(':noi_dung', $noi_dung);
            $stmt->bindParam(':thoi_gian_lam', $thoi_gian_lam);
            $stmt->bindParam(':thoi_gian_bat_dau', $thoi_gian_bat_dau);
            $stmt->bindParam(':thoi_gian_ket_thuc', $thoi_gian_ket_thuc);
            $stmt->bindParam(':so_lan_lam', $so_lan_lam);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật bài thi tự luận: " . $e->getMessage());
            return false;
        }
    }
    
    public function deleteBaiThiTuLuan($id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Lỗi xóa bài thi tự luận: " . $e->getMessage());
            return false;
        }
    }
    
    public function getMonHocByLopHoc($lop_hoc_id) {
        try {
            $query = "SELECT mh.* FROM mon_hoc mh
                      JOIN lop_hoc_mon_hoc lhmh ON mh.id = lhmh.mon_hoc_id
                      WHERE lhmh.lop_hoc_id = :lop_hoc_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':lop_hoc_id', $lop_hoc_id);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy danh sách môn học theo lớp: " . $e->getMessage());
            return [];
        }
    }
    
    public function getBaiThiTuLuanByGiaoVien($giao_vien_id) {
        try {
            $query = "SELECT bt.*, mh.ma_mon, mh.ten_mon, lh.ma_lop, lh.ten_lop,
                     (SELECT COUNT(DISTINCT bltl.sinh_vien_id) FROM bai_lam_tu_luan bltl
                     WHERE bltl.bai_kiem_tra_id = bt.id) as so_sinh_vien_lam
                     FROM " . $this->table . " bt
                     INNER JOIN lop_hoc lh ON bt.lop_hoc_id = lh.id
                     INNER JOIN mon_hoc mh ON bt.mon_hoc_id = mh.id
                     WHERE lh.giao_vien_id = :giao_vien_id
                     ORDER BY bt.ngay_tao DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':giao_vien_id', $giao_vien_id);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy danh sách bài thi tự luận theo giáo viên: " . $e->getMessage());
            return [];
        }
    }
} 