<?php
require_once 'Config/Database.php';

class SinhVienModel {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function getSinhVienById($sinhVienId) {
        $query = "SELECT sv.*, nd.ma_so, nd.ho_va_ten, nd.email, lh.ma_lop, lh.ten_lop
                 FROM sinh_vien sv
                 JOIN nguoi_dung nd ON sv.nguoi_dung_id = nd.id
                 JOIN lop_hoc lh ON sv.lop_hoc_id = lh.id
                 WHERE sv.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $sinhVienId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getSinhVienByLopHoc($lopHocId) {
        $query = "SELECT sv.*, nd.ma_so, nd.ho_va_ten, nd.email
                 FROM sinh_vien sv
                 JOIN nguoi_dung nd ON sv.nguoi_dung_id = nd.id
                 WHERE sv.lop_hoc_id = ?
                 ORDER BY nd.ho_va_ten ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $lopHocId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 