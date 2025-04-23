<?php
require_once 'Config/Database.php';

class CauHoiModel {
    private $conn;
    private $table_cauhoi = 'cau_hoi_trac_nghiem';
    private $table_dapan = 'cac_dap_an';
    private $table_chude = 'chu_de';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getCauHoiByID($id) {
        $query = "SELECT * FROM " . $this->table_cauhoi . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllCauHoi() {
        $query = "SELECT * FROM " . $this->table_cauhoi . " ORDER BY ngay_tao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCauHoiByGiaoVien($giao_vien_id) {
        $query = "SELECT * FROM " . $this->table_cauhoi . " WHERE giao_vien_id = ? ORDER BY ngay_tao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $giao_vien_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createCauHoi($giao_vien_id, $chu_de_id, $noi_dung, $tieu_de) {
        try {
            // Kiểm tra xem chủ đề có tồn tại không
            if ($chu_de_id) {
                $check_chu_de = $this->conn->prepare("SELECT id FROM " . $this->table_chude . " WHERE id = ?");
                $check_chu_de->execute([$chu_de_id]);
                if ($check_chu_de->rowCount() == 0) {
                    error_log("Error: Cannot create question for non-existent topic (ID: $chu_de_id)");
                    return false;
                }
            }
            
            // Check for duplicate question
            $stmt = $this->conn->prepare("SELECT id FROM " . $this->table_cauhoi . " WHERE noi_dung = ? AND giao_vien_id = ?");
            $stmt->execute([$noi_dung, $giao_vien_id]);
            if ($stmt->rowCount() > 0) {
                error_log("Error: Duplicate question detected for teacher ID: $giao_vien_id");
                return false; // Question already exists
            }

            // Start transaction
            $this->conn->beginTransaction();

            // Tạo câu hỏi mới
            $stmt = $this->conn->prepare("INSERT INTO " . $this->table_cauhoi . " (giao_vien_id, noi_dung, tieu_de, ngay_tao) VALUES (?, ?, ?, NOW())");
            $result = $stmt->execute([$giao_vien_id, $noi_dung, $tieu_de]);
            
            if (!$result) {
                error_log("Error inserting question: " . implode(', ', $stmt->errorInfo()));
                throw new PDOException("Failed to insert question");
            }
            
            $cau_hoi_id = $this->conn->lastInsertId();
            
            // Kiểm tra và tạo liên kết trong bảng cau_hoi_chu_de
            if ($cau_hoi_id && $chu_de_id) {
                $stmt_link = $this->conn->prepare("INSERT INTO cau_hoi_chu_de (cau_hoi_id, chu_de_id) VALUES (?, ?)");
                $link_result = $stmt_link->execute([$cau_hoi_id, $chu_de_id]);
                
                if (!$link_result) {
                    error_log("Error linking question to topic: " . implode(', ', $stmt_link->errorInfo()));
                    throw new PDOException("Failed to link question to topic");
                }
            }
            
            // Commit transaction
            $this->conn->commit();
            
            return $cau_hoi_id;
        } catch (PDOException $e) {
            // Rollback transaction on error
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("Error creating question: " . $e->getMessage());
            return false;
        }
    }

    public function getDapAnByCauHoi($cau_hoi_id) {
        $query = "SELECT * FROM " . $this->table_dapan . " WHERE cau_hoi_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $cau_hoi_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createDapAn($cau_hoi_id, $dap_an, $dung_hay_sai) {
        try {
            // Kiểm tra xem câu hỏi có tồn tại không
            $check_stmt = $this->conn->prepare("SELECT id FROM " . $this->table_cauhoi . " WHERE id = ?");
            $check_stmt->execute([$cau_hoi_id]);
            if ($check_stmt->rowCount() == 0) {
                error_log("Error: Cannot create answer for non-existent question (ID: $cau_hoi_id)");
                return false;
            }
            
            $stmt = $this->conn->prepare("INSERT INTO " . $this->table_dapan . " (cau_hoi_id, dap_an_cua_trac_nghiem, dung_hay_sai) VALUES (?, ?, ?)");
            $result = $stmt->execute([$cau_hoi_id, $dap_an, $dung_hay_sai]);
            
            if (!$result) {
                error_log("Error inserting answer: " . implode(', ', $stmt->errorInfo()));
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error creating answer: " . $e->getMessage());
            return false;
        }
    }

    public function getAllChuDe() {
        $query = "SELECT * FROM " . $this->table_chude;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getChuDeByMonHoc($mon_hoc_id) {
        $query = "SELECT * FROM " . $this->table_chude . " WHERE mon_hoc_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $mon_hoc_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createChuDe($mon_hoc_id, $ten_chu_de) {
        $query = "INSERT INTO " . $this->table_chude . " (mon_hoc_id, ten_chu_de) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $mon_hoc_id);
        $stmt->bindParam(2, $ten_chu_de);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function getAllMonHoc() {
        $query = "SELECT * FROM mon_hoc ORDER BY ten_mon";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateCauHoi($id, $noi_dung, $tieu_de) {
        $query = "UPDATE " . $this->table_cauhoi . " SET noi_dung = ?, tieu_de = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $noi_dung);
        $stmt->bindParam(2, $tieu_de);
        $stmt->bindParam(3, $id);
        
        return $stmt->execute();
    }

    public function deleteDapAnByCauHoi($cau_hoi_id) {
        $query = "DELETE FROM " . $this->table_dapan . " WHERE cau_hoi_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $cau_hoi_id);
        
        return $stmt->execute();
    }

    public function updateCauHoiTime($id) {
        // Cập nhật ngày_cap_nhat cho câu hỏi
        $query = "UPDATE " . $this->table_cauhoi . " SET ngay_cap_nhat = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        
        return $stmt->execute();
    }

    public function deleteCauHoi($id) {
        try {
            // Start transaction
            $this->conn->beginTransaction();
            
            // Xóa các liên kết trong bảng cau_hoi_chu_de
            $query_delete_link = "DELETE FROM cau_hoi_chu_de WHERE cau_hoi_id = ?";
            $stmt_link = $this->conn->prepare($query_delete_link);
            $stmt_link->execute([$id]);
            
            // Trước tiên xóa tất cả đáp án của câu hỏi
            $this->deleteDapAnByCauHoi($id);
            
            // Sau đó xóa câu hỏi
            $query = "DELETE FROM " . $this->table_cauhoi . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            
            // Commit transaction
            $this->conn->commit();
            
            return true;
        } catch (PDOException $e) {
            // Rollback transaction on error
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("Error deleting question: " . $e->getMessage());
            return false;
        }
    }

    public function getCauHoiByChude($chu_de_id) {
        $query = "SELECT ch.* FROM " . $this->table_cauhoi . " ch
                  INNER JOIN cau_hoi_chu_de chcd ON ch.id = chcd.cau_hoi_id
                  WHERE chcd.chu_de_id = ?
                  ORDER BY ch.ngay_tao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $chu_de_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignCauHoiToChude($cau_hoi_id, $chu_de_id) {
        // Kiểm tra xem đã có liên kết này chưa
        $check_query = "SELECT COUNT(*) FROM cau_hoi_chu_de WHERE cau_hoi_id = ? AND chu_de_id = ?";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(1, $cau_hoi_id);
        $check_stmt->bindParam(2, $chu_de_id);
        $check_stmt->execute();
        
        if ($check_stmt->fetchColumn() == 0) {
            $query = "INSERT INTO cau_hoi_chu_de (cau_hoi_id, chu_de_id) VALUES (?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $cau_hoi_id);
            $stmt->bindParam(2, $chu_de_id);
            return $stmt->execute();
        }
        return true;
    }

    public function getMonHocByGiaoVien($giao_vien_id) {
        $query = "SELECT * FROM mon_hoc WHERE giao_vien_id = ? ORDER BY ten_mon";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $giao_vien_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createMonHoc($giao_vien_id, $ma_mon, $ten_mon, $mo_ta, $hoc_ky, $nam_hoc, $so_tin_chi) {
        try {
            // Kiểm tra xem mã môn học đã tồn tại cho giáo viên này chưa
            $check_query = "SELECT COUNT(*) FROM mon_hoc WHERE ma_mon = ? AND giao_vien_id = ?";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([$ma_mon, $giao_vien_id]);
            
            if ($check_stmt->fetchColumn() > 0) {
                // Giáo viên này đã có môn học với mã này
                return ['error' => 'duplicate_code', 'message' => 'Bạn đã có môn học với mã này. Vui lòng chọn mã khác.'];
            }
            
            $query = "INSERT INTO mon_hoc (giao_vien_id, ma_mon, ten_mon, mo_ta, hoc_ky, nam_hoc, so_tin_chi) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $giao_vien_id);
            $stmt->bindParam(2, $ma_mon);
            $stmt->bindParam(3, $ten_mon);
            $stmt->bindParam(4, $mo_ta);
            $stmt->bindParam(5, $hoc_ky);
            $stmt->bindParam(6, $nam_hoc);
            $stmt->bindParam(7, $so_tin_chi);
            
            if($stmt->execute()) {
                return ['success' => true, 'id' => $this->conn->lastInsertId()];
            }
            return ['error' => 'insert_failed', 'message' => 'Không thể thêm môn học'];
        } catch (PDOException $e) {
            error_log("Error creating subject: " . $e->getMessage());
            // Kiểm tra lỗi duplicate key ở mức cơ sở dữ liệu (nếu chưa xóa ràng buộc UNIQUE)
            if ($e->getCode() == 23000 && strpos($e->getMessage(), 'Duplicate entry') !== false) {
                // Cần phải sửa lại cấu trúc CSDL - xóa ràng buộc UNIQUE trên cột ma_mon
                return ['error' => 'duplicate_key', 'message' => 'Lỗi cơ sở dữ liệu: Cần xóa ràng buộc UNIQUE trên cột ma_mon. Vui lòng liên hệ quản trị viên.'];
            }
            return ['error' => 'database_error', 'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()];
        }
    }

    public function getChuDeByCauHoi($cau_hoi_id) {
        try {
            $query = "SELECT * FROM cau_hoi_chu_de WHERE cau_hoi_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $cau_hoi_id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Luôn trả về một mảng, trống nếu không có kết quả
            return $result;
        } catch (PDOException $e) {
            error_log("Error getting topic for question: " . $e->getMessage());
            return [];
        }
    }

    public function deleteChuDe($id) {
        try {
            // Kiểm tra xem chủ đề có chứa câu hỏi nào không
            $check_query = "SELECT COUNT(*) FROM cau_hoi_chu_de WHERE chu_de_id = ?";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([$id]);
            
            if ($check_stmt->fetchColumn() > 0) {
                // Có câu hỏi liên kết với chủ đề này
                return false;
            }
            
            // Bắt đầu transaction
            $this->conn->beginTransaction();
            
            // Xóa chủ đề
            $stmt = $this->conn->prepare("DELETE FROM " . $this->table_chude . " WHERE id = ?");
            $result = $stmt->execute([$id]);
            
            // Commit transaction
            $this->conn->commit();
            
            return $result;
        } catch (PDOException $e) {
            // Rollback transaction nếu có lỗi
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("Error deleting topic: " . $e->getMessage());
            return false;
        }
    }

    public function deleteMonHoc($id) {
        try {
            // Kiểm tra xem môn học có chủ đề nào không
            $stmt_chude = $this->conn->prepare("SELECT COUNT(*) FROM chu_de WHERE mon_hoc_id = ?");
            $stmt_chude->execute([$id]);
            
            if ($stmt_chude->fetchColumn() > 0) {
                // Có chủ đề thuộc môn học này
                return false;
            }
            
            // Bắt đầu transaction
            $this->conn->beginTransaction();
            
            // Xóa môn học
            $stmt = $this->conn->prepare("DELETE FROM mon_hoc WHERE id = ?");
            $result = $stmt->execute([$id]);
            
            // Commit transaction
            $this->conn->commit();
            
            return $result;
        } catch (PDOException $e) {
            // Rollback transaction nếu có lỗi
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("Error deleting subject: " . $e->getMessage());
            return false;
        }
    }

    public function getChuDeById($chu_de_id) {
        try {
            $query = "SELECT * FROM " . $this->table_chude . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $chu_de_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting topic by ID: " . $e->getMessage());
            return null;
        }
    }

    public function getChuDeIDForQues($cau_hoi_id) {
        try {
            $query = "SELECT chu_de_id FROM cau_hoi_chu_de WHERE cau_hoi_id = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$cau_hoi_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $result['chu_de_id'];
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error getting topic ID for question: " . $e->getMessage());
            return null;
        }
    }

}
?> 