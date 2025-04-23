<?php
require_once 'Config/Database.php';

class GiaoVienModel {
    private $conn;
    private $table = 'giao_vien';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function createGiaoVien($nguoi_dung_id, $hoc_vi, $chuyen_nganh, $mo_ta) {
        // File ghi log
        $log_file = 'logs/register_' . date('Y-m-d') . '.log';
        
        try {
            // Ghi log thông tin
            file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "GiaoVienModel - createGiaoVien() - Start\n", FILE_APPEND);
            file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Parameters: nguoi_dung_id=$nguoi_dung_id, hoc_vi=$hoc_vi, chuyen_nganh=$chuyen_nganh\n", FILE_APPEND);
            
            // Đặt timeout lâu hơn cho các truy vấn
            $this->conn->setAttribute(PDO::ATTR_TIMEOUT, 60); // 60 giây timeout
            
            // Chuẩn bị câu truy vấn
            $query = "INSERT INTO " . $this->table . " (nguoi_dung_id, hoc_vi, chuyen_nganh, mo_ta) 
                      VALUES (?, ?, ?, ?)";
            
            file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Query: $query\n", FILE_APPEND);
            
            // Chuẩn bị statement
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                $error = $this->conn->errorInfo();
                file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Prepare statement error: " . print_r($error, true) . "\n", FILE_APPEND);
                throw new Exception("Lỗi chuẩn bị truy vấn: " . $error[2]);
            }
            
            file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Statement prepared successfully\n", FILE_APPEND);
            
            // Bind tham số
            $stmt->bindParam(1, $nguoi_dung_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $hoc_vi, PDO::PARAM_STR);
            $stmt->bindParam(3, $chuyen_nganh, PDO::PARAM_STR);
            $stmt->bindParam(4, $mo_ta, PDO::PARAM_STR);
            
            file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Parameters bound successfully\n", FILE_APPEND);
            
            // Thực thi câu truy vấn
            $result = $stmt->execute();
            file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Execute result: " . ($result ? "true" : "false") . "\n", FILE_APPEND);
            
            if ($result) {
                $id = $this->conn->lastInsertId();
                file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "GiaoVienModel - createGiaoVien() - Success, ID: $id\n", FILE_APPEND);
                return $id;
            } else {
                $error_info = $stmt->errorInfo();
                file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "GiaoVienModel - createGiaoVien() - Execute Error: " . print_r($error_info, true) . "\n", FILE_APPEND);
                return false;
            }
        } catch (Exception $e) {
            file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "GiaoVienModel - createGiaoVien() - Exception: " . $e->getMessage() . "\n", FILE_APPEND);
            file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Stack trace: " . $e->getTraceAsString() . "\n", FILE_APPEND);
            throw $e;
        }
    }

    public function getGiaoVienByNguoiDungId($nguoi_dung_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE nguoi_dung_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $nguoi_dung_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLopHocByGiaoVien($giao_vien_id) {
        $query = "SELECT * FROM lop_hoc WHERE giao_vien_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $giao_vien_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getLopHocById($lop_hoc_id) {
        $query = "SELECT * FROM lop_hoc WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $lop_hoc_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?> 