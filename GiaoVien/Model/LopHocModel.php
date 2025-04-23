<?php
require_once 'Config/Database.php';

class LopHocModel {
    private $conn;
    private $table = 'lop_hoc';
    private $table_lop_hoc_mon_hoc = 'lop_hoc_mon_hoc';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy danh sách tất cả các lớp học
    public function getAllLopHoc() {
        $query = "SELECT lh.*, gv.id as giao_vien_id, u.ho_va_ten as ten_giao_vien, 
                  COUNT(DISTINCT sv.id) as so_sinh_vien,
                  COUNT(DISTINCT lhmh.mon_hoc_id) as so_mon_hoc
                  FROM " . $this->table . " lh
                  LEFT JOIN giao_vien gv ON lh.giao_vien_id = gv.id
                  LEFT JOIN nguoi_dung u ON gv.nguoi_dung_id = u.id
                  LEFT JOIN " . $this->table_lop_hoc_mon_hoc . " lhmh ON lh.id = lhmh.lop_hoc_id
                  LEFT JOIN sinh_vien sv ON lh.id = sv.lop_hoc_id
                  GROUP BY lh.id
                  ORDER BY lh.ngay_tao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách lớp học của giáo viên
    public function getLopHocByGiaoVien($giao_vien_id) {
        $query = "SELECT lh.*, COUNT(DISTINCT sv.id) as so_sinh_vien,
                 COUNT(DISTINCT lhmh.mon_hoc_id) as so_mon_hoc
                 FROM " . $this->table . " lh
                 LEFT JOIN " . $this->table_lop_hoc_mon_hoc . " lhmh ON lh.id = lhmh.lop_hoc_id
                 LEFT JOIN sinh_vien sv ON lh.id = sv.lop_hoc_id
                 WHERE lh.giao_vien_id = ?
                 GROUP BY lh.id
                 ORDER BY lh.ngay_tao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $giao_vien_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin chi tiết một lớp học
    public function getLopHocById($id) {
        $query = "SELECT lh.*, gv.id as giao_vien_id, u.ho_va_ten as ten_giao_vien
                 FROM " . $this->table . " lh
                 LEFT JOIN giao_vien gv ON lh.giao_vien_id = gv.id
                 LEFT JOIN nguoi_dung u ON gv.nguoi_dung_id = u.id
                 WHERE lh.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo lớp học mới
    public function createLopHoc($ma_lop, $ten_lop, $giao_vien_id, $mo_ta, $ngay_bat_dau, $ngay_ket_thuc) {
        try {
            // Kiểm tra xem mã lớp đã tồn tại chưa
            $check_query = "SELECT COUNT(*) FROM " . $this->table . " WHERE ma_lop = ?";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([$ma_lop]);
            
            if ($check_stmt->fetchColumn() > 0) {
                return ['error' => 'duplicate_code', 'message' => 'Mã lớp này đã tồn tại. Vui lòng chọn mã khác.'];
            }
            
            $query = "INSERT INTO " . $this->table . " (ma_lop, ten_lop, giao_vien_id, mo_ta, ngay_bat_dau, ngay_ket_thuc) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $ma_lop);
            $stmt->bindParam(2, $ten_lop);
            $stmt->bindParam(3, $giao_vien_id);
            $stmt->bindParam(4, $mo_ta);
            $stmt->bindParam(5, $ngay_bat_dau);
            $stmt->bindParam(6, $ngay_ket_thuc);
            
            if($stmt->execute()) {
                return ['success' => true, 'id' => $this->conn->lastInsertId()];
            }
            return ['error' => 'insert_failed', 'message' => 'Không thể tạo lớp học mới'];
        } catch (PDOException $e) {
            error_log("Error creating class: " . $e->getMessage());
            return ['error' => 'database_error', 'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()];
        }
    }

    // Cập nhật thông tin lớp học
    public function updateLopHoc($id, $ma_lop, $ten_lop, $mo_ta, $ngay_bat_dau, $ngay_ket_thuc) {
        try {
            // Kiểm tra xem mã lớp đã tồn tại cho lớp khác chưa
            $check_query = "SELECT COUNT(*) FROM " . $this->table . " WHERE ma_lop = ? AND id != ?";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([$ma_lop, $id]);
            
            if ($check_stmt->fetchColumn() > 0) {
                return ['error' => 'duplicate_code', 'message' => 'Mã lớp này đã tồn tại. Vui lòng chọn mã khác.'];
            }
            
            $query = "UPDATE " . $this->table . " SET ma_lop = ?, ten_lop = ?, mo_ta = ?, 
                     ngay_bat_dau = ?, ngay_ket_thuc = ?, ngay_cap_nhat = CURRENT_TIMESTAMP 
                     WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $ma_lop);
            $stmt->bindParam(2, $ten_lop);
            $stmt->bindParam(3, $mo_ta);
            $stmt->bindParam(4, $ngay_bat_dau);
            $stmt->bindParam(5, $ngay_ket_thuc);
            $stmt->bindParam(6, $id);
            
            if($stmt->execute()) {
                return ['success' => true];
            }
            return ['error' => 'update_failed', 'message' => 'Không thể cập nhật thông tin lớp học'];
        } catch (PDOException $e) {
            error_log("Error updating class: " . $e->getMessage());
            return ['error' => 'database_error', 'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()];
        }
    }

    // Xóa lớp học
    public function deleteLopHoc($id) {
        try {
            $this->conn->beginTransaction();
            
            // Xóa các liên kết với môn học
            $delete_subjects = "DELETE FROM " . $this->table_lop_hoc_mon_hoc . " WHERE lop_hoc_id = ?";
            $stmt_subjects = $this->conn->prepare($delete_subjects);
            $stmt_subjects->execute([$id]);
            
            // Xóa bản thân lớp học
            $query = "DELETE FROM " . $this->table . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            
            $this->conn->commit();
            return ['success' => true];
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error deleting class: " . $e->getMessage());
            return ['error' => 'delete_failed', 'message' => 'Không thể xóa lớp học: ' . $e->getMessage()];
        }
    }

    // Lấy danh sách môn học trong lớp học
    public function getMonHocByLopHoc($lop_hoc_id) {
        $query = "SELECT mh.*, gv.id as giao_vien_id, u.ho_va_ten as ten_giao_vien 
                  FROM mon_hoc mh 
                  INNER JOIN " . $this->table_lop_hoc_mon_hoc . " lhmh ON mh.id = lhmh.mon_hoc_id 
                  LEFT JOIN giao_vien gv ON mh.giao_vien_id = gv.id
                  LEFT JOIN nguoi_dung u ON gv.nguoi_dung_id = u.id
                  WHERE lhmh.lop_hoc_id = ?
                  ORDER BY mh.ten_mon";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $lop_hoc_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Lấy danh sách môn học có sẵn (chưa được thêm vào lớp) cho giáo viên
    public function getAvailableMonHoc($giao_vien_id, $lop_hoc_id) {
        $query = "SELECT mh.* FROM mon_hoc mh 
                  WHERE mh.giao_vien_id = ? AND mh.id NOT IN (
                      SELECT mon_hoc_id FROM " . $this->table_lop_hoc_mon_hoc . " WHERE lop_hoc_id = ?
                  )
                  ORDER BY mh.ten_mon";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $giao_vien_id);
        $stmt->bindParam(2, $lop_hoc_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Thêm môn học vào lớp học
    public function addMonHocToLopHoc($lop_hoc_id, $mon_hoc_id) {
        try {
            // Kiểm tra xem liên kết đã tồn tại chưa
            $check_query = "SELECT COUNT(*) FROM " . $this->table_lop_hoc_mon_hoc . " WHERE lop_hoc_id = ? AND mon_hoc_id = ?";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([$lop_hoc_id, $mon_hoc_id]);
            
            if ($check_stmt->fetchColumn() > 0) {
                return ['error' => 'already_exists', 'message' => 'Môn học này đã được thêm vào lớp học!'];
            }
            
            // Lấy thông tin giáo viên từ môn học
            $get_teacher_query = "SELECT giao_vien_id FROM mon_hoc WHERE id = ?";
            $get_teacher_stmt = $this->conn->prepare($get_teacher_query);
            $get_teacher_stmt->execute([$mon_hoc_id]);
            $giao_vien_id = $get_teacher_stmt->fetchColumn();
            
            if (!$giao_vien_id) {
                return ['error' => 'teacher_not_found', 'message' => 'Không tìm thấy thông tin giáo viên của môn học!'];
            }
            
            $query = "INSERT INTO " . $this->table_lop_hoc_mon_hoc . " (lop_hoc_id, mon_hoc_id, giao_vien_id) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $lop_hoc_id);
            $stmt->bindParam(2, $mon_hoc_id);
            $stmt->bindParam(3, $giao_vien_id);
            
            if($stmt->execute()) {
                return ['success' => true];
            }
            return ['error' => 'insert_failed', 'message' => 'Không thể thêm môn học vào lớp'];
        } catch (PDOException $e) {
            error_log("Error adding subject to class: " . $e->getMessage());
            return ['error' => 'database_error', 'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()];
        }
    }
    
    // Xóa môn học khỏi lớp học
    public function removeMonHocFromLopHoc($lop_hoc_id, $mon_hoc_id) {
        try {
            $query = "DELETE FROM " . $this->table_lop_hoc_mon_hoc . " WHERE lop_hoc_id = ? AND mon_hoc_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $lop_hoc_id);
            $stmt->bindParam(2, $mon_hoc_id);
            
            if($stmt->execute()) {
                return ['success' => true];
            }
            return ['error' => 'delete_failed', 'message' => 'Không thể xóa môn học khỏi lớp'];
        } catch (PDOException $e) {
            error_log("Error removing subject from class: " . $e->getMessage());
            return ['error' => 'database_error', 'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()];
        }
    }
}
?> 