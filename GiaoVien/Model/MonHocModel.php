<?php
require_once 'Config/Database.php';

class MonHocModel {
    private $conn;
    private $table = 'mon_hoc';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function getMonHocById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getAllMonHoc() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY ten_mon ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getMonHocByLopHoc($lop_hoc_id) {
        $query = "SELECT mh.* FROM " . $this->table . " mh 
                  INNER JOIN lop_hoc_mon_hoc lhmh ON mh.id = lhmh.mon_hoc_id 
                  WHERE lhmh.lop_hoc_id = ?
                  ORDER BY mh.ten_mon ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $lop_hoc_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function createMonHoc($ten_mon, $ma_mon, $mo_ta = null) {
        $query = "INSERT INTO " . $this->table . " (ten_mon, ma_mon, mo_ta) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $ten_mon);
        $stmt->bindParam(2, $ma_mon);
        $stmt->bindParam(3, $mo_ta);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        } else {
            return false;
        }
    }
    
    public function updateMonHoc($id, $ten_mon, $ma_mon, $mo_ta = null) {
        $query = "UPDATE " . $this->table . " SET ten_mon = ?, ma_mon = ?, mo_ta = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $ten_mon);
        $stmt->bindParam(2, $ma_mon);
        $stmt->bindParam(3, $mo_ta);
        $stmt->bindParam(4, $id);
        
        return $stmt->execute();
    }
    
    public function deleteMonHoc($id) {
        // Kiểm tra xem môn học có liên kết với lớp học nào không
        $query = "SELECT COUNT(*) FROM lop_hoc_mon_hoc WHERE mon_hoc_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        if ($stmt->fetchColumn() > 0) {
            return false; // Không thể xóa vì có liên kết
        }
        
        // Kiểm tra xem môn học có liên kết với bài thi nào không
        $query = "SELECT COUNT(*) FROM bai_kiem_tra WHERE mon_hoc_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        if ($stmt->fetchColumn() > 0) {
            return false; // Không thể xóa vì có liên kết
        }
        
        // Tiến hành xóa môn học
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        
        return $stmt->execute();
    }
    
    public function assignMonHocToLopHoc($mon_hoc_id, $lop_hoc_id) {
        // Kiểm tra xem đã có liên kết chưa
        $query = "SELECT COUNT(*) FROM lop_hoc_mon_hoc WHERE mon_hoc_id = ? AND lop_hoc_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $mon_hoc_id);
        $stmt->bindParam(2, $lop_hoc_id);
        $stmt->execute();
        
        if ($stmt->fetchColumn() > 0) {
            return true; // Đã có liên kết
        }
        
        // Thêm liên kết mới
        $query = "INSERT INTO lop_hoc_mon_hoc (mon_hoc_id, lop_hoc_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $mon_hoc_id);
        $stmt->bindParam(2, $lop_hoc_id);
        
        return $stmt->execute();
    }
    
    public function removeMonHocFromLopHoc($mon_hoc_id, $lop_hoc_id) {
        $query = "DELETE FROM lop_hoc_mon_hoc WHERE mon_hoc_id = ? AND lop_hoc_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $mon_hoc_id);
        $stmt->bindParam(2, $lop_hoc_id);
        
        return $stmt->execute();
    }
}
?> 