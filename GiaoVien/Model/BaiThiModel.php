<?php
require_once 'Config/Database.php';

class BaiThiModel {
    private $conn;
    private $table_baithi = 'bai_kiem_tra_trac_nghiem';
    private $table_cauhoi_baithi = 'cau_hoi_bai_kiem_tra_trac_nghiem';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function getBaiThiByGiaoVien($giao_vien_id) {
        $query = "SELECT DISTINCT bt.* FROM " . $this->table_baithi . " bt 
                INNER JOIN lop_hoc lh ON bt.lop_hoc_id = lh.id 
                WHERE lh.giao_vien_id = ? 
                ORDER BY bt.ngay_tao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $giao_vien_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getBaiThiById($id) {
        $query = "SELECT * FROM " . $this->table_baithi . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function createBaiThi($lop_hoc_id, $tieu_de, $mo_ta, $thoi_gian_lam, $thoi_gian_bat_dau, $thoi_gian_ket_thuc, $tron_cau_hoi, $tron_dap_an, $hien_thi_dap_an, $so_lan_lam, $mon_hoc_id = null) {
        // Đặt giá trị mặc định cho thời gian bắt đầu nếu là NULL
        if (empty($thoi_gian_bat_dau)) {
            // Đặt múi giờ thành Asia/Ho_Chi_Minh (múi giờ Việt Nam)
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $thoi_gian_bat_dau = date('Y-m-d H:i:s'); // Thời gian hiện tại
        } else {
            // Chuyển đổi định dạng datetime-local thành định dạng MySQL
            // và đảm bảo múi giờ là đúng
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $thoi_gian_bat_dau = date('Y-m-d H:i:s', strtotime($thoi_gian_bat_dau));
        }
        
        // Nếu là bài thi trên lớp (có thoi_gian_lam), không cần thoi_gian_ket_thuc
        if ($thoi_gian_lam !== null) {
            $thoi_gian_ket_thuc = null;
        }
        
        $query = "INSERT INTO " . $this->table_baithi . " (lop_hoc_id, tieu_de, mo_ta, thoi_gian_lam, thoi_gian_bat_dau, thoi_gian_ket_thuc, tron_cau_hoi, tron_dap_an, hien_thi_dap_an, so_lan_lam";
        
        // Thêm cột mon_hoc_id nếu được cung cấp
        if ($mon_hoc_id !== null) {
            $query .= ", mon_hoc_id";
        }
        
        $query .= ") VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
        
        // Thêm placeholder cho mon_hoc_id nếu được cung cấp
        if ($mon_hoc_id !== null) {
            $query .= ", ?";
        }
        
        $query .= ")";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $lop_hoc_id);
        $stmt->bindParam(2, $tieu_de);
        $stmt->bindParam(3, $mo_ta);
        $stmt->bindParam(4, $thoi_gian_lam);
        $stmt->bindParam(5, $thoi_gian_bat_dau);
        $stmt->bindParam(6, $thoi_gian_ket_thuc);
        $stmt->bindParam(7, $tron_cau_hoi);
        $stmt->bindParam(8, $tron_dap_an);
        $stmt->bindParam(9, $hien_thi_dap_an);
        $stmt->bindParam(10, $so_lan_lam);
        
        // Bind mon_hoc_id nếu được cung cấp
        if ($mon_hoc_id !== null) {
            $stmt->bindParam(11, $mon_hoc_id);
        }
        
        // Thực thi câu lệnh
        $result = $stmt->execute();
        if ($result) {
            return $this->conn->lastInsertId();
        } else {
            return false;
        }
    }
    
    public function updateBaiThi($id, $tieu_de, $mo_ta, $thoi_gian_lam, $thoi_gian_bat_dau, $thoi_gian_ket_thuc, $tron_cau_hoi, $tron_dap_an, $hien_thi_dap_an, $so_lan_lam, $mon_hoc_id = null) {
        // Đặt giá trị mặc định cho thời gian bắt đầu nếu là NULL
        if (empty($thoi_gian_bat_dau)) {
            // Đặt múi giờ thành Asia/Ho_Chi_Minh (múi giờ Việt Nam)
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $thoi_gian_bat_dau = date('Y-m-d H:i:s'); // Thời gian hiện tại
        } else {
            // Chuyển đổi định dạng datetime-local thành định dạng MySQL
            // và đảm bảo múi giờ là đúng
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $thoi_gian_bat_dau = date('Y-m-d H:i:s', strtotime($thoi_gian_bat_dau));
        }
        
        // Nếu là bài thi trên lớp (có thoi_gian_lam), không cần thoi_gian_ket_thuc
        if ($thoi_gian_lam !== null) {
            $thoi_gian_ket_thuc = null;
        }
        
        $query = "UPDATE " . $this->table_baithi . " SET 
                tieu_de = ?, mo_ta = ?, thoi_gian_lam = ?, thoi_gian_bat_dau = ?, 
                thoi_gian_ket_thuc = ?, tron_cau_hoi = ?, tron_dap_an = ?, 
                hien_thi_dap_an = ?, so_lan_lam = ?";
        
        // Thêm cập nhật môn học nếu được cung cấp
        if ($mon_hoc_id !== null) {
            $query .= ", mon_hoc_id = ?";
        }
        
        $query .= ", ngay_cap_nhat = CURRENT_TIMESTAMP WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $tieu_de);
        $stmt->bindParam(2, $mo_ta);
        $stmt->bindParam(3, $thoi_gian_lam);
        $stmt->bindParam(4, $thoi_gian_bat_dau);
        $stmt->bindParam(5, $thoi_gian_ket_thuc);
        $stmt->bindParam(6, $tron_cau_hoi);
        $stmt->bindParam(7, $tron_dap_an);
        $stmt->bindParam(8, $hien_thi_dap_an);
        $stmt->bindParam(9, $so_lan_lam);
        
        $paramIndex = 10;
        if ($mon_hoc_id !== null) {
            $stmt->bindParam($paramIndex++, $mon_hoc_id);
        }
        $stmt->bindParam($paramIndex, $id);
        
        return $stmt->execute();
    }
    
    public function deleteBaiThi($id) {
        try {
            $this->conn->beginTransaction();
            
            // 1. Xóa các liên kết câu hỏi trong bài thi
            $query = "DELETE FROM " . $this->table_cauhoi_baithi . " WHERE bai_trac_nghiem_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            
            // 2. Xóa các liên kết chủ đề trong bài thi
            $query = "DELETE FROM bai_kiem_tra_chu_de WHERE bai_kiem_tra_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            
            // 3. Xóa các bài làm của sinh viên (nếu có)
            $query = "DELETE FROM bai_lam_trac_nghiem WHERE bai_kiem_tra_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            
            // 4. Cuối cùng xóa bài thi
            $query = "DELETE FROM " . $this->table_baithi . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    
    public function addCauHoiToBaiThi($bai_thi_id, $cau_hoi_id, $chu_de_id = null) {
        // Kiểm tra xem câu hỏi đã tồn tại trong bài thi chưa
        $check_query = "SELECT COUNT(*) FROM " . $this->table_cauhoi_baithi . " 
                       WHERE bai_trac_nghiem_id = ? AND cau_hoi_id = ?";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(1, $bai_thi_id);
        $check_stmt->bindParam(2, $cau_hoi_id);
        $check_stmt->execute();
        
        if ($check_stmt->fetchColumn() == 0) {
            // Nếu không có chu_de_id được cung cấp, lấy chủ đề đầu tiên trong cau_hoi_chu_de
            if ($chu_de_id === null) {
                $get_chu_de_query = "SELECT chu_de_id FROM cau_hoi_chu_de WHERE cau_hoi_id = ? LIMIT 1";
                $get_chu_de_stmt = $this->conn->prepare($get_chu_de_query);
                $get_chu_de_stmt->bindParam(1, $cau_hoi_id);
                $get_chu_de_stmt->execute();
                $chu_de_id = $get_chu_de_stmt->fetchColumn();
            }
            
            $query = "INSERT INTO " . $this->table_cauhoi_baithi . " (bai_trac_nghiem_id, cau_hoi_id, chu_de_id) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $bai_thi_id);
            $stmt->bindParam(2, $cau_hoi_id);
            $stmt->bindParam(3, $chu_de_id);
            
            return $stmt->execute();
        }
        return true; // Nếu câu hỏi đã tồn tại, trả về true
    }
    
    public function deleteCauHoiFromBaiThi($bai_thi_id) {
        $query = "DELETE FROM " . $this->table_cauhoi_baithi . " WHERE bai_trac_nghiem_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $bai_thi_id);
        
        return $stmt->execute();
    }
    
    public function getCauHoiTrongBaiThi($bai_thi_id) {
        $query = "SELECT 
                    ch.*,
                    cb.chu_de_id,
                    GROUP_CONCAT(cd.dap_an_cua_trac_nghiem) as dap_an_all,
                    GROUP_CONCAT(cd.dung_hay_sai) as dung_hay_sai
                FROM cau_hoi_trac_nghiem ch 
                INNER JOIN " . $this->table_cauhoi_baithi . " cb ON ch.id = cb.cau_hoi_id 
                LEFT JOIN cac_dap_an cd ON ch.id = cd.cau_hoi_id
                WHERE cb.bai_trac_nghiem_id = ?
                GROUP BY ch.id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $bai_thi_id);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Format the answers as arrays
        foreach ($results as &$row) {
            $dapAnAll = $row['dap_an_all'] ? explode(',', $row['dap_an_all']) : [];
            $dungHaySai = $row['dung_hay_sai'] ? explode(',', $row['dung_hay_sai']) : [];
            
            $dapAn = [];
            $dapAnDung = [];
            
            // Phân loại đáp án dựa vào trường dung_hay_sai
            foreach ($dapAnAll as $index => $dapAn_value) {
                if (isset($dungHaySai[$index])) {
                    if ($dungHaySai[$index] == '1') {
                        $dapAnDung[] = $dapAn_value;
                    } else {
                        $dapAn[] = $dapAn_value;
                    }
                }
            }
            
            $row['dap_an'] = array_values($dapAn);
            $row['dap_an_dung'] = array_values($dapAnDung);
            
            // Xóa các trường tạm
            unset($row['dap_an_all']);
            unset($row['dung_hay_sai']);
        }
        
        return $results;
    }
    
    public function getCauHoiTrongBaiThiTheoChuDe($bai_thi_id, $chu_de_id) {
        $query = "SELECT 
                    ch.*,
                    GROUP_CONCAT(cd.dap_an_cua_trac_nghiem) as dap_an_all,
                    GROUP_CONCAT(cd.dung_hay_sai) as dung_hay_sai
                FROM cau_hoi_trac_nghiem ch 
                INNER JOIN " . $this->table_cauhoi_baithi . " cb ON ch.id = cb.cau_hoi_id 
                LEFT JOIN cac_dap_an cd ON ch.id = cd.cau_hoi_id
                WHERE cb.bai_trac_nghiem_id = ? AND cb.chu_de_id = ?
                GROUP BY ch.id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $bai_thi_id);
        $stmt->bindParam(2, $chu_de_id);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Format the answers as arrays
        foreach ($results as &$row) {
            $dapAnAll = $row['dap_an_all'] ? explode(',', $row['dap_an_all']) : [];
            $dungHaySai = $row['dung_hay_sai'] ? explode(',', $row['dung_hay_sai']) : [];
            
            $dapAn = [];
            $dapAnDung = [];
            
            // Phân loại đáp án dựa vào trường dung_hay_sai
            foreach ($dapAnAll as $index => $dapAn_value) {
                if (isset($dungHaySai[$index])) {
                    if ($dungHaySai[$index] == '1') {
                        $dapAnDung[] = $dapAn_value;
                    } else {
                        $dapAn[] = $dapAn_value;
                    }
                }
            }
            
            $row['dap_an'] = array_values($dapAn);
            $row['dap_an_dung'] = array_values($dapAnDung);
            
            // Xóa các trường tạm
            unset($row['dap_an_all']);
            unset($row['dung_hay_sai']);
        }
        
        return $results;
    }
    
    // Thêm chủ đề vào bài thi
    public function addChuDeToBaiThi($bai_thi_id, $chu_de_id) {
        $query = "INSERT INTO bai_kiem_tra_chu_de (bai_kiem_tra_id, chu_de_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $bai_thi_id);
        $stmt->bindParam(2, $chu_de_id);
        
        return $stmt->execute();
    }
    
    // Xóa các chủ đề của bài thi
    public function deleteChuDeFromBaiThi($bai_thi_id) {
        $query = "DELETE FROM bai_kiem_tra_chu_de WHERE bai_kiem_tra_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $bai_thi_id);
        
        return $stmt->execute();
    }
    
    // Lấy danh sách chủ đề của bài thi
    public function getChuDeTrongBaiThi($bai_thi_id) {
        $query = "SELECT cd.* FROM chu_de cd 
                INNER JOIN bai_kiem_tra_chu_de bkcd ON cd.id = bkcd.chu_de_id 
                WHERE bkcd.bai_kiem_tra_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $bai_thi_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getMonHocByLopHoc($lop_hoc_id) {
        try {
            $query = "SELECT mh.* FROM mon_hoc mh 
                      INNER JOIN lop_hoc_mon_hoc lhmh ON mh.id = lhmh.mon_hoc_id 
                      WHERE lhmh.lop_hoc_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $lop_hoc_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting subjects by class: " . $e->getMessage());
            return [];
        }
    }
    
    public function getChuDeByMonHoc($mon_hoc_id) {
        try {
            $query = "SELECT * FROM chu_de WHERE mon_hoc_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $mon_hoc_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting topics by subject: " . $e->getMessage());
            return [];
        }
    }
    
    public function getCauHoiByMonHoc($mon_hoc_id, $giao_vien_id) {
        try {
            $query = "SELECT ch.* FROM cau_hoi_trac_nghiem ch
                      INNER JOIN cau_hoi_chu_de chcd ON ch.id = chcd.cau_hoi_id
                      INNER JOIN chu_de cd ON chcd.chu_de_id = cd.id
                      WHERE cd.mon_hoc_id = ? AND ch.giao_vien_id = ?
                      GROUP BY ch.id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $mon_hoc_id);
            $stmt->bindParam(2, $giao_vien_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting questions by subject: " . $e->getMessage());
            return [];
        }
    }
    
    public function getCauHoiByChuDe($chu_de_id) {
        try {
            // Chỉ lấy các câu hỏi thuộc chủ đề này 
            $query = "SELECT ch.* FROM cau_hoi_trac_nghiem ch
                      INNER JOIN cau_hoi_chu_de chcd ON ch.id = chcd.cau_hoi_id
                      WHERE chcd.chu_de_id = ?
                      GROUP BY ch.id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $chu_de_id);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Thêm log để debug
            error_log("Tìm thấy " . count($results) . " câu hỏi cho chủ đề ID: " . $chu_de_id);
            
            return $results;
        } catch (PDOException $e) {
            error_log("Error getting questions by topic: " . $e->getMessage());
            return [];
        }
    }
    
    public function updateCauHoiChuDeInBaiThi($bai_thi_id) {
        // Phương thức này không cần thiết vì ta muốn giữ nguyên chủ đề người dùng đã chọn
        // Không cần cập nhật chủ đề dựa trên cau_hoi_chu_de
        return true;
        
        /* try {
            // Lấy danh sách tất cả câu hỏi trong bài thi với chu_de_id là NULL hoặc không chính xác
            $query = "SELECT cb.id, cb.cau_hoi_id, cb.chu_de_id 
                     FROM " . $this->table_cauhoi_baithi . " cb
                     WHERE cb.bai_trac_nghiem_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $bai_thi_id);
            $stmt->execute();
            
            $cauHoiTrongBaiThi = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($cauHoiTrongBaiThi as $cauHoi) {
                // Lấy chủ đề thực sự của câu hỏi từ bảng cau_hoi_chu_de
                $query_chu_de = "SELECT chu_de_id FROM cau_hoi_chu_de WHERE cau_hoi_id = ? LIMIT 1";
                $stmt_chu_de = $this->conn->prepare($query_chu_de);
                $stmt_chu_de->bindParam(1, $cauHoi['cau_hoi_id']);
                $stmt_chu_de->execute();
                
                $chu_de_id_thuc_te = $stmt_chu_de->fetchColumn();
                
                // Nếu tìm thấy chủ đề và nó khác với chủ đề hiện tại, cập nhật
                if ($chu_de_id_thuc_te && $chu_de_id_thuc_te != $cauHoi['chu_de_id']) {
                    $update_query = "UPDATE " . $this->table_cauhoi_baithi . " 
                                   SET chu_de_id = ? 
                                   WHERE id = ?";
                    $update_stmt = $this->conn->prepare($update_query);
                    $update_stmt->bindParam(1, $chu_de_id_thuc_te);
                    $update_stmt->bindParam(2, $cauHoi['id']);
                    $update_stmt->execute();
                }
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Error updating question topic in exam: " . $e->getMessage());
            return false;
        } */
    }
    
    // Lấy chu_de_id của câu hỏi từ bảng cau_hoi_bai_kiem_tra_trac_nghiem
    public function getChuDeForCauHoiInBaiThi($bai_thi_id, $cau_hoi_id) {
        $query = "SELECT chu_de_id FROM " . $this->table_cauhoi_baithi . "
                 WHERE bai_trac_nghiem_id = ? AND cau_hoi_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $bai_thi_id);
        $stmt->bindParam(2, $cau_hoi_id);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }
    
    public function getBaiThiTracNghiemByGiaoVien($giao_vien_id) {
        $query = "SELECT bt.*, mh.ma_mon, mh.ten_mon, lh.ma_lop, lh.ten_lop,
                (SELECT COUNT(DISTINCT svbl.sinh_vien_id) FROM bai_lam_trac_nghiem blt
                JOIN sinh_vien_bai_lam_trac_nghiem svbl ON blt.id = svbl.bai_trac_nghiem
                WHERE blt.bai_kiem_tra_id = bt.id) as so_sinh_vien_lam
                FROM " . $this->table_baithi . " bt 
                INNER JOIN lop_hoc lh ON bt.lop_hoc_id = lh.id 
                INNER JOIN mon_hoc mh ON bt.mon_hoc_id = mh.id
                WHERE lh.giao_vien_id = ? 
                ORDER BY bt.ngay_tao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $giao_vien_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getBaiThiTuLuanByGiaoVien($giao_vien_id) {
        $query = "SELECT bt.*, mh.ma_mon, mh.ten_mon, lh.ma_lop, lh.ten_lop,
                (SELECT COUNT(DISTINCT bltl.sinh_vien_id) FROM bai_lam_tu_luan bltl
                WHERE bltl.bai_kiem_tra_id = bt.id) as so_sinh_vien_lam
                FROM bai_kiem_tra_tu_luan bt 
                INNER JOIN lop_hoc lh ON bt.lop_hoc_id = lh.id 
                INNER JOIN mon_hoc mh ON bt.mon_hoc_id = mh.id
                WHERE lh.giao_vien_id = ? 
                ORDER BY bt.ngay_tao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $giao_vien_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getBaiThiTracNghiemById($baiThiId) {
        $query = "SELECT bt.*, mh.ma_mon, mh.ten_mon, lh.ma_lop, lh.ten_lop,
                (SELECT COUNT(*) FROM cau_hoi_bai_kiem_tra_trac_nghiem WHERE bai_trac_nghiem_id = bt.id) as so_cau_hoi
                FROM " . $this->table_baithi . " bt 
                INNER JOIN lop_hoc lh ON bt.lop_hoc_id = lh.id 
                INNER JOIN mon_hoc mh ON bt.mon_hoc_id = mh.id
                WHERE bt.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $baiThiId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getBaiThiTuLuanById($baiThiId) {
        $query = "SELECT bt.*, mh.ma_mon, mh.ten_mon, lh.ma_lop, lh.ten_lop
                FROM bai_kiem_tra_tu_luan bt 
                INNER JOIN lop_hoc lh ON bt.lop_hoc_id = lh.id 
                INNER JOIN mon_hoc mh ON bt.mon_hoc_id = mh.id
                WHERE bt.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $baiThiId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getKetQuaTracNghiemByBaiThiId($baiThiId) {
        $query = "SELECT blt.id as bai_lam_id, blt.thoi_gian_bat_dau, blt.thoi_gian_nop, blt.diem, blt.lan_thu,
                sv.id as sinh_vien_id, nd.ma_so, nd.ho_va_ten, nd.email,
                (SELECT COUNT(*) FROM cau_tra_loi ctl 
                 JOIN cac_dap_an cda ON ctl.cau_hoi_id = cda.cau_hoi_id
                 WHERE ctl.bai_lam_id = blt.id AND ctl.dap_an_chon = cda.dap_an_cua_trac_nghiem AND cda.dung_hay_sai = 1) as so_cau_dung,
                (SELECT COUNT(*) FROM cau_tra_loi ctl WHERE ctl.bai_lam_id = blt.id) as so_cau_tra_loi,
                (SELECT COUNT(*) FROM cau_hoi_bai_kiem_tra_trac_nghiem chbkt WHERE chbkt.bai_trac_nghiem_id = ?) as tong_so_cau
                FROM bai_lam_trac_nghiem blt
                JOIN sinh_vien_bai_lam_trac_nghiem svbl ON blt.id = svbl.bai_trac_nghiem
                JOIN sinh_vien sv ON svbl.sinh_vien_id = sv.id
                JOIN nguoi_dung nd ON sv.nguoi_dung_id = nd.id
                WHERE blt.bai_kiem_tra_id = ?
                ORDER BY blt.thoi_gian_nop DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $baiThiId);
        $stmt->bindParam(2, $baiThiId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getKetQuaTuLuanByBaiThiId($baiThiId) {
        $query = "SELECT bltl.id as bai_lam_id, bltl.noi_dung, bltl.tep_tin, bltl.ngay_nop, bltl.diem, bltl.nhan_xet,
                sv.id as sinh_vien_id, nd.ma_so, nd.ho_va_ten, nd.email 
                FROM bai_lam_tu_luan bltl
                JOIN sinh_vien sv ON bltl.sinh_vien_id = sv.id
                JOIN nguoi_dung nd ON sv.nguoi_dung_id = nd.id
                WHERE bltl.bai_kiem_tra_id = ?
                ORDER BY bltl.ngay_nop DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $baiThiId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getBaiLamTuLuanById($baiLamId) {
        $query = "SELECT bltl.*, sv.id as sinh_vien_id, nd.ma_so, nd.ho_va_ten, nd.email
                FROM bai_lam_tu_luan bltl
                JOIN sinh_vien sv ON bltl.sinh_vien_id = sv.id
                JOIN nguoi_dung nd ON sv.nguoi_dung_id = nd.id
                WHERE bltl.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $baiLamId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateDiemBaiLamTuLuan($baiLamId, $diem, $nhanXet) {
        try {
            $query = "UPDATE bai_lam_tu_luan SET diem = ?, nhan_xet = ?, ngay_cap_nhat = CURRENT_TIMESTAMP WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $diem);
            $stmt->bindParam(2, $nhanXet);
            $stmt->bindParam(3, $baiLamId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Lỗi khi cập nhật điểm bài làm tự luận: " . $e->getMessage());
            return false;
        }
    }
}
?> 