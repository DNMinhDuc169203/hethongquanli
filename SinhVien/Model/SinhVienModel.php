<?php
class SinhVienModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Kiểm tra thông tin đăng nhập
     */
    public function checkLogin($maSo, $matKhau) {
        try {
            $query = "SELECT n.id, n.ma_so, n.ho_va_ten, n.email, n.mat_khau, n.vai_tro, n.trang_thai
                     FROM nguoi_dung n
                     WHERE n.ma_so = ? AND n.mat_khau = ? AND n.vai_tro = 'sinh_vien'";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$maSo, $matKhau]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Lỗi đăng nhập: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy thông tin người dùng theo ID
     */
    public function getUserById($userId) {
        try {
            $query = "SELECT id, ma_so, ho_va_ten, email, mat_khau, vai_tro, trang_thai
                     FROM nguoi_dung
                     WHERE id = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Lỗi truy vấn người dùng: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cập nhật mật khẩu người dùng
     */
    public function updatePassword($userId, $newPassword) {
        try {
            $query = "UPDATE nguoi_dung SET mat_khau = ? WHERE id = ?";
            
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([$newPassword, $userId]);
            
            return $result;
        } catch (PDOException $e) {
            error_log('Lỗi cập nhật mật khẩu: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy thông tin sinh viên từ ID người dùng
     */
    public function getSinhVienInfo($userId) {
        try {
            $query = "SELECT s.id as sinh_vien_id, s.nguoi_dung_id, s.lop_hoc_id, s.nam_nhap_hoc, s.nganh_hoc,
                            l.ma_lop, l.ten_lop,
                            g.id as giao_vien_id,
                            n.ho_va_ten as ten_giao_vien
                     FROM sinh_vien s
                     JOIN lop_hoc l ON s.lop_hoc_id = l.id
                     JOIN giao_vien g ON l.giao_vien_id = g.id
                     JOIN nguoi_dung n ON g.nguoi_dung_id = n.id
                     WHERE s.nguoi_dung_id = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Lỗi truy vấn thông tin sinh viên: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy thông tin sinh viên theo ID người dùng
     */
    public function getSinhVienInfoById($userId) {
        try {
            $query = "SELECT s.id as sinh_vien_id, s.nguoi_dung_id, s.lop_hoc_id, s.nam_nhap_hoc, s.nganh_hoc,
                            l.ma_lop, l.ten_lop,
                            g.id as giao_vien_id,
                            n.ho_va_ten as ten_giao_vien
                     FROM sinh_vien s
                     JOIN lop_hoc l ON s.lop_hoc_id = l.id
                     JOIN giao_vien g ON l.giao_vien_id = g.id
                     JOIN nguoi_dung n ON g.nguoi_dung_id = n.id
                     WHERE s.nguoi_dung_id = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Lỗi truy vấn thông tin sinh viên: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy danh sách bài kiểm tra trắc nghiệm cho sinh viên
     */
    public function getTracNghiemTests($sinhVienId) {
        try {
            $query = "SELECT bkt.id, bkt.lop_hoc_id, bkt.mon_hoc_id, bkt.tieu_de, bkt.mo_ta,
                            bkt.thoi_gian_lam, bkt.thoi_gian_bat_dau, bkt.thoi_gian_ket_thuc,
                            bkt.tron_cau_hoi, bkt.tron_dap_an, bkt.so_lan_lam,
                            mh.ma_mon, mh.ten_mon,
                            IFNULL((SELECT COUNT(*) FROM bai_lam_trac_nghiem blt
                                    JOIN sinh_vien_bai_lam_trac_nghiem svbl ON blt.id = svbl.bai_trac_nghiem
                                    WHERE svbl.sinh_vien_id = ? AND blt.bai_kiem_tra_id = bkt.id), 0) as so_lan_da_lam,
                            (SELECT MAX(blt.diem) FROM bai_lam_trac_nghiem blt
                                    JOIN sinh_vien_bai_lam_trac_nghiem svbl ON blt.id = svbl.bai_trac_nghiem
                                    WHERE svbl.sinh_vien_id = ? AND blt.bai_kiem_tra_id = bkt.id) as diem_cao_nhat
                     FROM bai_kiem_tra_trac_nghiem bkt
                     JOIN mon_hoc mh ON bkt.mon_hoc_id = mh.id
                     JOIN lop_hoc_mon_hoc lhmh ON bkt.mon_hoc_id = lhmh.mon_hoc_id AND bkt.lop_hoc_id = lhmh.lop_hoc_id
                     JOIN sinh_vien sv ON sv.lop_hoc_id = bkt.lop_hoc_id
                     WHERE sv.id = ?
                     AND (bkt.thoi_gian_ket_thuc IS NULL OR bkt.thoi_gian_ket_thuc >= NOW())
                     ORDER BY bkt.thoi_gian_bat_dau DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$sinhVienId, $sinhVienId, $sinhVienId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Lỗi truy vấn bài kiểm tra trắc nghiệm: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy danh sách bài kiểm tra tự luận cho sinh viên
     */
    public function getTuLuanTests($sinhVienId) {
        try {
            $query = "SELECT bktl.id, bktl.lop_hoc_id, bktl.mon_hoc_id, bktl.tieu_de, bktl.mo_ta,
                            bktl.thoi_gian_lam, bktl.thoi_gian_bat_dau, bktl.thoi_gian_ket_thuc, bktl.so_lan_lam,
                            mh.ma_mon, mh.ten_mon,
                            (SELECT COUNT(*) FROM bai_lam_tu_luan bltl
                                    WHERE bltl.sinh_vien_id = ? AND bltl.bai_kiem_tra_id = bktl.id) as so_lan_da_lam,
                            (SELECT MAX(bltl.diem) FROM bai_lam_tu_luan bltl
                                    WHERE bltl.sinh_vien_id = ? AND bltl.bai_kiem_tra_id = bktl.id) as diem
                     FROM bai_kiem_tra_tu_luan bktl
                     JOIN mon_hoc mh ON bktl.mon_hoc_id = mh.id
                     JOIN lop_hoc_mon_hoc lhmh ON bktl.mon_hoc_id = lhmh.mon_hoc_id AND bktl.lop_hoc_id = lhmh.lop_hoc_id
                     JOIN sinh_vien sv ON sv.lop_hoc_id = bktl.lop_hoc_id
                     WHERE sv.id = ?
                     AND (bktl.thoi_gian_ket_thuc IS NULL OR bktl.thoi_gian_ket_thuc >= NOW())
                     ORDER BY bktl.thoi_gian_bat_dau DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$sinhVienId, $sinhVienId, $sinhVienId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Lỗi truy vấn bài kiểm tra tự luận: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy lịch sử làm bài thi của sinh viên
     */
    public function getTestHistory($sinhVienId) {
        try {
            // Lịch sử bài thi trắc nghiệm
            $query1 = "SELECT 'trac_nghiem' as loai_bai_thi, bkt.id, bkt.tieu_de, mh.ma_mon, mh.ten_mon,
                              blt.thoi_gian_bat_dau, blt.thoi_gian_nop, blt.diem, blt.lan_thu
                       FROM bai_lam_trac_nghiem blt
                       JOIN sinh_vien_bai_lam_trac_nghiem svbl ON blt.id = svbl.bai_trac_nghiem
                       JOIN bai_kiem_tra_trac_nghiem bkt ON blt.bai_kiem_tra_id = bkt.id
                       JOIN mon_hoc mh ON bkt.mon_hoc_id = mh.id
                       WHERE svbl.sinh_vien_id = ?";
            
            // Lịch sử bài thi tự luận
            $query2 = "SELECT 'tu_luan' as loai_bai_thi, bktl.id, bktl.tieu_de, mh.ma_mon, mh.ten_mon,
                              bltl.ngay_nop as thoi_gian_nop, bltl.ngay_nop as thoi_gian_bat_dau, bltl.diem, 1 as lan_thu
                       FROM bai_lam_tu_luan bltl
                       JOIN bai_kiem_tra_tu_luan bktl ON bltl.bai_kiem_tra_id = bktl.id
                       JOIN mon_hoc mh ON bktl.mon_hoc_id = mh.id
                       WHERE bltl.sinh_vien_id = ?";
            
            $stmt1 = $this->db->prepare($query1);
            $stmt1->execute([$sinhVienId]);
            $tracNghiemHistory = $stmt1->fetchAll(PDO::FETCH_ASSOC);
            
            $stmt2 = $this->db->prepare($query2);
            $stmt2->execute([$sinhVienId]);
            $tuLuanHistory = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            
            // Kết hợp kết quả và sắp xếp theo thời gian nộp giảm dần
            $history = array_merge($tracNghiemHistory, $tuLuanHistory);
            usort($history, function($a, $b) {
                return strtotime($b['thoi_gian_nop']) - strtotime($a['thoi_gian_nop']);
            });
            
            return $history;
        } catch (PDOException $e) {
            error_log('Lỗi truy vấn lịch sử bài thi: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy thông tin bài kiểm tra trắc nghiệm theo ID
     */
    public function getTracNghiemTestById($testId) {
        try {
            $query = "SELECT bkt.*, mh.ma_mon, mh.ten_mon
                      FROM bai_kiem_tra_trac_nghiem bkt
                      JOIN mon_hoc mh ON bkt.mon_hoc_id = mh.id
                      WHERE bkt.id = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$testId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Lỗi truy vấn bài kiểm tra trắc nghiệm: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy câu hỏi của bài kiểm tra trắc nghiệm
     */
    public function getQuestionsByTestId($testId, $randomize = false) {
        try {
            $query = "SELECT ch.id, ch.noi_dung, ch.tieu_de
                      FROM cau_hoi_trac_nghiem ch
                      JOIN cau_hoi_bai_kiem_tra_trac_nghiem chbkt ON ch.id = chbkt.cau_hoi_id
                      WHERE chbkt.bai_trac_nghiem_id = ?";
            
            if ($randomize) {
                $query .= " ORDER BY RAND()";
            }
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$testId]);
            $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Lấy đáp án cho mỗi câu hỏi
            if (!empty($questions)) {
                foreach ($questions as &$question) {
                    $answersQuery = "SELECT id, dap_an_cua_trac_nghiem, dung_hay_sai
                                    FROM cac_dap_an
                                    WHERE cau_hoi_id = ?";
                    
                    if ($randomize) {
                        $answersQuery .= " ORDER BY RAND()";
                    }
                    
                    $answersStmt = $this->db->prepare($answersQuery);
                    $answersStmt->execute([$question['id']]);
                    $question['dap_an'] = $answersStmt->fetchAll(PDO::FETCH_ASSOC);
                }
            }
            
            return $questions;
        } catch (PDOException $e) {
            error_log('Lỗi truy vấn câu hỏi trắc nghiệm: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Tạo bài làm trắc nghiệm mới
     */
    public function createTracNghiemSubmission($sinhVienId, $testId, $answers) {
        try {
            $this->db->beginTransaction();
            
            // Tạo bài làm trắc nghiệm
            $query1 = "INSERT INTO bai_lam_trac_nghiem (bai_kiem_tra_id, thoi_gian_bat_dau, thoi_gian_nop, cau_tra_loi) 
                       VALUES (?, NOW(), NOW(), ?)";
            $stmt1 = $this->db->prepare($query1);
            $stmt1->execute([$testId, json_encode($answers)]);
            $baiLamId = $this->db->lastInsertId();
            
            // Liên kết với sinh viên
            $query2 = "INSERT INTO sinh_vien_bai_lam_trac_nghiem (sinh_vien_id, bai_trac_nghiem) 
                       VALUES (?, ?)";
            $stmt2 = $this->db->prepare($query2);
            $stmt2->execute([$sinhVienId, $baiLamId]);
            
            // Tính điểm
            $diem = 0;
            $total = 0;
            
            // Lấy danh sách câu hỏi và đáp án đúng
            $query3 = "SELECT ch.id as cau_hoi_id, da.id as dap_an_id 
                       FROM cau_hoi ch
                       JOIN cac_dap_an da ON ch.id = da.cau_hoi_id
                       WHERE ch.bai_kiem_tra_id = ? AND da.dung_hay_sai = 1";
            $stmt3 = $this->db->prepare($query3);
            $stmt3->execute([$testId]);
            $correctAnswers = [];
            while ($row = $stmt3->fetch(PDO::FETCH_ASSOC)) {
                $correctAnswers[$row['cau_hoi_id']] = $row['dap_an_id'];
            }
            
            // So sánh đáp án của sinh viên với đáp án đúng
            foreach ($answers as $questionId => $answerId) {
                if (isset($correctAnswers[$questionId])) {
                    $total++;
                    if ($correctAnswers[$questionId] == $answerId) {
                        $diem++;
                    }
                }
            }
            
            // Cập nhật điểm
            $finalScore = ($total > 0) ? ($diem * 10 / $total) : 0;
            $query4 = "UPDATE bai_lam_trac_nghiem 
                       SET diem = ? 
                       WHERE id = ?";
            $stmt4 = $this->db->prepare($query4);
            $stmt4->execute([$finalScore, $baiLamId]);
            
            $this->db->commit();
            return [
                'bai_lam_id' => $baiLamId,
                'diem' => $finalScore
            ];
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log('Lỗi lưu bài làm trắc nghiệm: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật thông tin hồ sơ sinh viên
     */
    public function updateStudentProfile($userId, $email, $phoneNumber) {
        try {
            $this->db->beginTransaction();
            
            // Cập nhật email trong bảng nguoi_dung
            $query = "UPDATE nguoi_dung SET email = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([$email, $userId]);
            
            if (!$result) {
                $this->db->rollBack();
                return false;
            }
            
            // Cập nhật số điện thoại trong bảng sinh_vien nếu có trường đó
            // Kiểm tra xem có cột số điện thoại trong bảng sinh_viên không
            $checkColumnQuery = "SHOW COLUMNS FROM sinh_vien LIKE 'so_dien_thoai'";
            $checkColumnStmt = $this->db->prepare($checkColumnQuery);
            $checkColumnStmt->execute();
            
            if ($checkColumnStmt->rowCount() > 0) {
                $query2 = "UPDATE sinh_vien SET so_dien_thoai = ? WHERE nguoi_dung_id = ?";
                $stmt2 = $this->db->prepare($query2);
                $result2 = $stmt2->execute([$phoneNumber, $userId]);
                
                if (!$result2) {
                    $this->db->rollBack();
                    return false;
                }
            }
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log('Lỗi cập nhật thông tin sinh viên: ' . $e->getMessage());
            return false;
        }
    }
}
?> 