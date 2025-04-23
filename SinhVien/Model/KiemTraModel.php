<?php
class KiemTraModel {
    private $db;

    public function __construct() {
        require_once 'Config/Database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Lấy danh sách bài kiểm tra trắc nghiệm cho sinh viên
     * @param int $userId ID của người dùng sinh viên
     * @return array Danh sách bài kiểm tra
     */
    public function getTracNghiemTestsForStudent($userId) {
        // Lấy ID sinh viên từ bảng sinh_vien dựa trên nguoi_dung_id
        $query = "SELECT sv.id, nd.ma_so 
                 FROM sinh_vien sv 
                 JOIN nguoi_dung nd ON sv.nguoi_dung_id = nd.id
                 WHERE sv.nguoi_dung_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$sinhVien) {
            return [];
        }
        
        $sinhVienId = $sinhVien['id'];
        
        // Lấy danh sách bài kiểm tra trắc nghiệm từ bảng bai_kiem_tra_trac_nghiem
        $query = "SELECT bkt.id, bkt.tieu_de, bkt.mo_ta, bkt.thoi_gian_lam, 
                         bkt.thoi_gian_bat_dau, bkt.thoi_gian_ket_thuc, mh.ten_mon, mh.ma_mon, bkt.so_lan_lam,
                         COALESCE((SELECT COUNT(*) FROM bai_lam_trac_nghiem blt
                                   JOIN sinh_vien_bai_lam_trac_nghiem svbl ON blt.id = svbl.bai_trac_nghiem
                                   WHERE svbl.sinh_vien_id = ? AND blt.bai_kiem_tra_id = bkt.id), 0) as so_lan_da_lam,
                         COALESCE((SELECT MAX(blt.diem) FROM bai_lam_trac_nghiem blt
                                   JOIN sinh_vien_bai_lam_trac_nghiem svbl ON blt.id = svbl.bai_trac_nghiem
                                   WHERE svbl.sinh_vien_id = ? AND blt.bai_kiem_tra_id = bkt.id), NULL) as diem_cao_nhat,
                         COALESCE((SELECT COUNT(*) FROM cau_hoi_bai_kiem_tra_trac_nghiem 
                                  WHERE bai_trac_nghiem_id = bkt.id), 0) as so_cau_hoi
                 FROM bai_kiem_tra_trac_nghiem bkt
                 JOIN mon_hoc mh ON bkt.mon_hoc_id = mh.id
                 JOIN lop_hoc_mon_hoc lhmh ON bkt.mon_hoc_id = lhmh.mon_hoc_id AND bkt.lop_hoc_id = lhmh.lop_hoc_id
                 JOIN sinh_vien sv ON sv.lop_hoc_id = bkt.lop_hoc_id
                 WHERE sv.id = ?
                 ORDER BY bkt.thoi_gian_bat_dau DESC";
                 
        $stmt = $this->db->prepare($query);
        $stmt->execute([$sinhVienId, $sinhVienId, $sinhVienId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy danh sách bài kiểm tra tự luận cho sinh viên
     * @param int $userId ID của người dùng sinh viên
     * @return array Danh sách bài kiểm tra
     */
    public function getTuLuanTestsForStudent($userId) {
        // Lấy ID sinh viên từ bảng sinh_vien dựa trên nguoi_dung_id
        $query = "SELECT sv.id, nd.ma_so 
                 FROM sinh_vien sv
                 JOIN nguoi_dung nd ON sv.nguoi_dung_id = nd.id 
                 WHERE sv.nguoi_dung_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$sinhVien) {
            return [];
        }
        
        $sinhVienId = $sinhVien['id'];
        
        // Lấy danh sách bài kiểm tra tự luận từ bảng bai_kiem_tra_tu_luan
        $query = "SELECT bktl.id, bktl.tieu_de, bktl.mo_ta, bktl.thoi_gian_lam, 
                        bktl.thoi_gian_bat_dau, bktl.thoi_gian_ket_thuc, mh.ten_mon, mh.ma_mon,
                        COALESCE((SELECT COUNT(*) FROM bai_lam_tu_luan bltl
                                WHERE bltl.sinh_vien_id = ? AND bltl.bai_kiem_tra_id = bktl.id), 0) as da_nop,
                        COALESCE((SELECT COUNT(*) FROM bai_lam_tu_luan bltl
                                WHERE bltl.sinh_vien_id = ? AND bltl.bai_kiem_tra_id = bktl.id), 0) as so_lan_nop,
                        COALESCE((SELECT bltl.diem FROM bai_lam_tu_luan bltl
                                WHERE bltl.sinh_vien_id = ? AND bltl.bai_kiem_tra_id = bktl.id), NULL) as diem,
                        bktl.so_lan_lam
                 FROM bai_kiem_tra_tu_luan bktl
                 JOIN mon_hoc mh ON bktl.mon_hoc_id = mh.id
                 JOIN lop_hoc_mon_hoc lhmh ON bktl.mon_hoc_id = lhmh.mon_hoc_id AND bktl.lop_hoc_id = lhmh.lop_hoc_id
                 JOIN sinh_vien sv ON sv.lop_hoc_id = bktl.lop_hoc_id
                 WHERE sv.id = ?
                 ORDER BY bktl.thoi_gian_bat_dau DESC";
                 
        $stmt = $this->db->prepare($query);
        $stmt->execute([$sinhVienId, $sinhVienId, $sinhVienId, $sinhVienId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy lịch sử làm bài thi trắc nghiệm của sinh viên
     * @param int $userId ID của người dùng sinh viên
     * @return array Lịch sử làm bài
     */
    public function getTracNghiemTestHistory($userId) {
        // Lấy ID sinh viên từ bảng sinh_vien dựa trên nguoi_dung_id
        $query = "SELECT sv.id
                 FROM sinh_vien sv 
                 WHERE sv.nguoi_dung_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$sinhVien) {
            return [];
        }
        
        $sinhVienId = $sinhVien['id'];
        $result = [];
        
        // 1. Lấy lịch sử bài làm đã nộp
        $query = "SELECT blt.id, blt.bai_kiem_tra_id, blt.thoi_gian_bat_dau, blt.thoi_gian_nop, blt.diem,
                         bkt.tieu_de, mh.ten_mon,
                         (SELECT COUNT(*) FROM cau_hoi_bai_kiem_tra_trac_nghiem chbkt 
                          WHERE chbkt.bai_trac_nghiem_id = bkt.id) AS so_cau_hoi,
                         NULL as trang_thai_phien
                 FROM bai_lam_trac_nghiem blt
                 JOIN sinh_vien_bai_lam_trac_nghiem svbl ON blt.id = svbl.bai_trac_nghiem
                 JOIN bai_kiem_tra_trac_nghiem bkt ON blt.bai_kiem_tra_id = bkt.id
                 JOIN mon_hoc mh ON bkt.mon_hoc_id = mh.id
                 WHERE svbl.sinh_vien_id = ?
                 ORDER BY blt.thoi_gian_nop DESC";
                 
        $stmt = $this->db->prepare($query);
        $stmt->execute([$sinhVienId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 2. Lấy thông tin phiên làm bài đang dở
        $query = "SELECT p.id, p.bai_kiem_tra_id, p.thoi_gian_bat_dau, p.thoi_gian_con_lai, p.trang_thai as trang_thai_phien,
                         bkt.tieu_de, mh.ten_mon,
                         (SELECT COUNT(*) FROM cau_hoi_bai_kiem_tra_trac_nghiem chbkt 
                          WHERE chbkt.bai_trac_nghiem_id = bkt.id) AS so_cau_hoi
                  FROM phien_lam_bai_trac_nghiem p
                  JOIN bai_kiem_tra_trac_nghiem bkt ON p.bai_kiem_tra_id = bkt.id
                  JOIN mon_hoc mh ON bkt.mon_hoc_id = mh.id
                  WHERE p.sinh_vien_id = ? AND p.trang_thai = 'dang_lam'
                  ORDER BY p.thoi_gian_bat_dau DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$sinhVienId]);
        $phienDangLam = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Kết hợp kết quả
        if (!empty($phienDangLam)) {
            // Đảm bảo các trường cần thiết tồn tại để tương thích với các bài đã nộp
            foreach ($phienDangLam as &$phien) {
                $phien['thoi_gian_nop'] = isset($phien['thoi_gian_bat_dau']) ? $phien['thoi_gian_bat_dau'] : null;
            }
            $result = array_merge($phienDangLam, $result);
        }
        
        return $result;
    }
    
    /**
     * Lấy lịch sử làm bài thi tự luận của sinh viên
     * @param int $userId ID của người dùng sinh viên
     * @return array Lịch sử làm bài
     */
    public function getTuLuanTestHistory($userId) {
        // Lấy ID sinh viên từ bảng sinh_vien dựa trên nguoi_dung_id
        $query = "SELECT sv.id
                 FROM sinh_vien sv 
                 WHERE sv.nguoi_dung_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$sinhVien) {
            return [];
        }
        
        $sinhVienId = $sinhVien['id'];
        
        // Lấy lịch sử làm bài
        $query = "SELECT bltl.id, bltl.bai_kiem_tra_id, bltl.ngay_nop, bltl.diem, bltl.tep_tin,
                         bktl.tieu_de, mh.ten_mon, 
                         CASE 
                            WHEN bltl.diem IS NOT NULL THEN 'da_cham'
                            ELSE 'da_nop' 
                         END as trang_thai
                 FROM bai_lam_tu_luan bltl
                 JOIN bai_kiem_tra_tu_luan bktl ON bltl.bai_kiem_tra_id = bktl.id
                 JOIN mon_hoc mh ON bktl.mon_hoc_id = mh.id
                 WHERE bltl.sinh_vien_id = ?
                 ORDER BY bltl.ngay_nop DESC";
                 
        $stmt = $this->db->prepare($query);
        $stmt->execute([$sinhVienId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin chi tiết bài kiểm tra trắc nghiệm theo ID
     */
    public function getTracNghiemTestById($testId, $userId) {
        try {
            $query = "SELECT bt.*, m.ma_mon, m.ten_mon, 
                      (SELECT COUNT(*) FROM bai_lam_trac_nghiem bl JOIN sinh_vien_bai_lam_trac_nghiem sv ON bl.id = sv.bai_trac_nghiem WHERE bl.bai_kiem_tra_id = bt.id AND sv.sinh_vien_id = ?) as so_lan_da_lam,
                      bt.lop_hoc_id
                      FROM bai_kiem_tra_trac_nghiem bt 
                      JOIN lop_hoc_mon_hoc lmh ON bt.lop_hoc_id = lmh.lop_hoc_id AND bt.mon_hoc_id = lmh.mon_hoc_id
                      JOIN sinh_vien sv ON sv.lop_hoc_id = lmh.lop_hoc_id
                      JOIN mon_hoc m ON bt.mon_hoc_id = m.id
                      WHERE bt.id = ? AND sv.nguoi_dung_id = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId, $testId, $userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Lưu giữ các giá trị gốc cho debugging
            if ($result) {
                $result['original_thoi_gian_bat_dau'] = $result['thoi_gian_bat_dau'];
                $result['original_thoi_gian_ket_thuc'] = $result['thoi_gian_ket_thuc'];
                
                // Đảm bảo timezone được xử lý chính xác
                $timezone = new DateTimeZone(date_default_timezone_get());
                $now = new DateTime('now', $timezone);
                
                // Chuyển đổi sang DateTime object để đảm bảo cùng múi giờ
                if (!empty($result['thoi_gian_bat_dau'])) {
                    $startTime = new DateTime($result['thoi_gian_bat_dau'], $timezone);
                    $result['thoi_gian_bat_dau'] = $startTime->format('Y-m-d H:i:s');
                }
                
                if (!empty($result['thoi_gian_ket_thuc'])) {
                    $endTime = new DateTime($result['thoi_gian_ket_thuc'], $timezone);
                    $result['thoi_gian_ket_thuc'] = $endTime->format('Y-m-d H:i:s');
                }
                
                // Thêm debug info
                $result['debug_timezone'] = date_default_timezone_get();
                $result['debug_now'] = $now->format('Y-m-d H:i:s');
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("getTracNghiemTestById Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy thông tin chi tiết bài kiểm tra tự luận theo ID
     * @param int $testId ID của bài thi
     * @param int $userId ID của người dùng
     * @return array|null Thông tin bài thi hoặc null nếu không tìm thấy
     */
    public function getTuLuanTestById($testId, $userId) {
        try {
            // Lấy ID sinh viên từ bảng sinh_vien dựa trên nguoi_dung_id
            $query = "SELECT sv.id
                     FROM sinh_vien sv 
                     WHERE sv.nguoi_dung_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$sinhVien) {
                return null;
            }
            
            $sinhVienId = $sinhVien['id'];
            
            // Lấy thông tin bài kiểm tra với đầy đủ thông tin cần thiết
            $query = "SELECT bktl.*, m.ma_mon, m.ten_mon,
                    (SELECT COUNT(*) FROM bai_lam_tu_luan bltl WHERE bltl.sinh_vien_id = ? AND bltl.bai_kiem_tra_id = bktl.id) as so_lan_nop,
                    (SELECT MAX(id) FROM bai_lam_tu_luan WHERE sinh_vien_id = ? AND bai_kiem_tra_id = bktl.id) as submission_id,
                    (SELECT nd.ho_va_ten FROM lop_hoc lh 
                        JOIN giao_vien gv ON lh.giao_vien_id = gv.id
                        JOIN nguoi_dung nd ON gv.nguoi_dung_id = nd.id
                        WHERE lh.id = bktl.lop_hoc_id) as ten_giao_vien
                    FROM bai_kiem_tra_tu_luan bktl
                    JOIN mon_hoc m ON bktl.mon_hoc_id = m.id
                    JOIN lop_hoc_mon_hoc lhmh ON bktl.mon_hoc_id = lhmh.mon_hoc_id AND bktl.lop_hoc_id = lhmh.lop_hoc_id
                    JOIN sinh_vien sv ON sv.lop_hoc_id = bktl.lop_hoc_id
                    WHERE bktl.id = ? AND sv.id = ?";
                    
            $stmt = $this->db->prepare($query);
            $stmt->execute([$sinhVienId, $sinhVienId, $testId, $sinhVienId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Lỗi khi lấy thông tin bài kiểm tra tự luận: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Lấy danh sách câu hỏi cho bài kiểm tra trắc nghiệm
     */
    public function getTracNghiemQuestions($testId) {
        // Kiểm tra nếu bài thi có tính năng trộn câu hỏi
        $query = "SELECT tron_cau_hoi, tron_dap_an FROM bai_kiem_tra_trac_nghiem WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$testId]);
        $testSettings = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $orderClause = $testSettings && $testSettings['tron_cau_hoi'] == 1 ? "ORDER BY RAND()" : "ORDER BY chbkt.id ASC";
        
        // Lấy danh sách câu hỏi kèm theo thông tin chủ đề
        $query = "SELECT chtn.id, chtn.noi_dung, chtn.tieu_de, chbkt.chu_de_id, cd.ten_chu_de
                 FROM cau_hoi_trac_nghiem chtn
                 JOIN cau_hoi_bai_kiem_tra_trac_nghiem chbkt ON chtn.id = chbkt.cau_hoi_id
                 LEFT JOIN chu_de cd ON chbkt.chu_de_id = cd.id
                 WHERE chbkt.bai_trac_nghiem_id = ?
                 $orderClause";
                 
        $stmt = $this->db->prepare($query);
        $stmt->execute([$testId]);
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Kiểm tra nếu không có câu hỏi nào, trả về mảng rỗng
        if (empty($questions)) {
            return [];
        }
        
        // Lấy các đáp án cho từng câu hỏi
        foreach ($questions as &$question) {
            $orderClauseDA = $testSettings && $testSettings['tron_dap_an'] == 1 ? "ORDER BY RAND()" : "ORDER BY da.id ASC";
            
            $query = "SELECT da.id, da.dap_an_cua_trac_nghiem, da.dung_hay_sai
                     FROM cac_dap_an da
                     WHERE da.cau_hoi_id = ?
                     $orderClauseDA";
                     
            $stmt = $this->db->prepare($query);
            $stmt->execute([$question['id']]);
            $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Đếm số đáp án đúng để xác định dạng chọn 1 hay nhiều đáp án
            $countCorrectAnswers = 0;
            foreach ($answers as $answer) {
                if ($answer['dung_hay_sai'] == 1) {
                    $countCorrectAnswers++;
                }
            }
            
            // Lưu lại số đáp án đúng cho câu hỏi
            $question['so_dap_an_dung'] = $countCorrectAnswers;
            $question['nhieu_dap_an'] = ($countCorrectAnswers > 1) ? true : false;
            
            // Cấu trúc lại đáp án để phù hợp với hiển thị
            if (!empty($answers)) {
                $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
                $questionAnswers = [];
                $correctAnswerLetters = [];
                
                foreach ($answers as $index => $answer) {
                    if ($index < count($letters)) {
                        $letter = $letters[$index];
                        $question['dap_an_' . strtolower($letter)] = $answer['dap_an_cua_trac_nghiem'];
                        $question['dap_an_id_' . strtolower($letter)] = $answer['id'];
                        
                        if ($answer['dung_hay_sai'] == 1) {
                            $correctAnswerLetters[] = $letter;
                        }
                    }
                }
                
                if (count($correctAnswerLetters) == 1) {
                    // Nếu chỉ có 1 đáp án đúng
                    $question['dap_an_dung'] = $correctAnswerLetters[0];
                } else {
                    // Nếu có nhiều đáp án đúng
                    $question['dap_an_dung_mang'] = $correctAnswerLetters;
                }
            }
        }
        
        return $questions;
    }
    
    /**
     * Nộp bài thi trắc nghiệm và tính điểm
     */
    public function submitTracNghiemTest($userId, $testId, $answers = []) {
        try {
            // Bắt đầu ghi log cho quá trình debug
            error_log("Bắt đầu nộp bài thi trắc nghiệm - userId: $userId, testId: $testId");
            
            $this->db->beginTransaction();
            
            // Lấy thông tin bài kiểm tra từ bảng bai_kiem_tra_trac_nghiem
            $query = "SELECT * FROM bai_kiem_tra_trac_nghiem WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$testId]);
            $testInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$testInfo) {
                error_log("Không tìm thấy thông tin bài kiểm tra ID: $testId");
                $this->db->rollBack();
                return false;
            }
            
            // Lấy ID sinh viên
            $query = "SELECT sv.id FROM sinh_vien sv WHERE sv.nguoi_dung_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$sinhVien) {
                error_log("Không tìm thấy thông tin sinh viên với nguoi_dung_id: $userId");
                $this->db->rollBack();
                return false;
            }
            
            $sinhVienId = $sinhVien['id'];
            error_log("Đã xác định được sinh viên ID: $sinhVienId");
            
            // Kiểm tra số lần làm bài đã đạt giới hạn chưa
            if (isset($testInfo['so_lan_lam']) && $testInfo['so_lan_lam'] > 0) {
                $query = "SELECT COUNT(*) as so_lan_da_lam FROM bai_lam_trac_nghiem blt
                          JOIN sinh_vien_bai_lam_trac_nghiem svbl ON blt.id = svbl.bai_trac_nghiem 
                          WHERE svbl.sinh_vien_id = ? AND blt.bai_kiem_tra_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$sinhVienId, $testId]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($result && $result['so_lan_da_lam'] >= $testInfo['so_lan_lam']) {
                    error_log("Sinh viên ID: $sinhVienId đã làm đủ số lần cho phép của bài kiểm tra ID: $testId");
                    $this->db->rollBack();
                    return "Bạn đã làm bài này đủ số lần cho phép";
                }
            }
            
            // Lấy tất cả câu hỏi của bài kiểm tra
            $questions = $this->getTracNghiemQuestions($testId);
            
            if (empty($questions)) {
                error_log("Không tìm thấy câu hỏi nào cho bài kiểm tra ID: $testId");
                $this->db->rollBack();
                return false;
            }
            
            // Tính điểm
            $totalQuestions = count($questions);
            $correctAnswers = 0;
            $answeredQuestions = [];
            
            foreach ($questions as $question) {
                $questionId = $question['id'];
                
                // Kiểm tra xem câu hỏi có nhiều đáp án đúng không
                $isMultiAnswer = isset($question['nhieu_dap_an']) && $question['nhieu_dap_an'] === true;
                
                if (isset($answers[$questionId])) {
                    $answeredQuestions[] = $questionId;
                    $userAnswer = $answers[$questionId];
                    
                    if ($isMultiAnswer) {
                        // Xử lý câu hỏi nhiều đáp án
                        if (is_array($userAnswer)) {
                            // Sắp xếp đáp án người dùng
                            sort($userAnswer);
                            $userAnswerStr = implode(",", $userAnswer);
                            
                            // Sắp xếp đáp án đúng để so sánh
                            $correctAnswersList = $question['dap_an_dung_mang'];
                            sort($correctAnswersList);
                            $correctAnswerStr = implode(",", $correctAnswersList);
                            
                            // So sánh chuỗi đáp án
                            if ($userAnswerStr === $correctAnswerStr) {
                                $correctAnswers++;
                            }
                        }
                    } else {
                        // Xử lý câu hỏi một đáp án
                        if (is_array($userAnswer)) {
                            // Nếu người dùng chọn nhiều đáp án cho câu hỏi 1 đáp án, lấy đáp án đầu tiên
                            $userAnswer = $userAnswer[0];
                        }
                        
                        if ($userAnswer === $question['dap_an_dung']) {
                            $correctAnswers++;
                        }
                    }
                }
            }
            
            // Tính điểm theo thang điểm của bài kiểm tra (mặc định là 10)
            $maxScore = 10;
            $score = ($correctAnswers / $totalQuestions) * $maxScore;
            error_log("Đã tính điểm: $score (Đúng: $correctAnswers/$totalQuestions câu)");
            
            // Lấy số lần làm bài hiện tại
            $query = "SELECT COUNT(*) as lan_thu FROM bai_lam_trac_nghiem blt
                      JOIN sinh_vien_bai_lam_trac_nghiem svbl ON blt.id = svbl.bai_trac_nghiem 
                      WHERE svbl.sinh_vien_id = ? AND blt.bai_kiem_tra_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$sinhVienId, $testId]);
            $lanThu = $stmt->fetch(PDO::FETCH_ASSOC);
            $lanThu = ($lanThu && isset($lanThu['lan_thu'])) ? $lanThu['lan_thu'] + 1 : 1;
            error_log("Lần làm bài thứ: $lanThu");
            
            // Lưu kết quả bài làm trắc nghiệm vào bảng bai_lam_trac_nghiem
            $query = "INSERT INTO bai_lam_trac_nghiem (bai_kiem_tra_id, thoi_gian_bat_dau, thoi_gian_nop, diem, lan_thu) 
                      VALUES (?, NOW(), NOW(), ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$testId, $score, $lanThu]);
            $baiLamId = $this->db->lastInsertId();
            
            if (!$baiLamId) {
                error_log("Không thể tạo bài làm mới trong bảng bai_lam_trac_nghiem");
                $this->db->rollBack();
                return false;
            }
            error_log("Đã tạo bài làm ID: $baiLamId");
            
            // Lưu câu trả lời của sinh viên
            foreach ($answeredQuestions as $questionId) {
                $userAnswer = $answers[$questionId];
                $answerText = is_array($userAnswer) ? implode(',', $userAnswer) : $userAnswer;
                
                $query = "INSERT INTO cau_tra_loi (bai_lam_id, cau_hoi_id, dap_an_chon) VALUES (?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$baiLamId, $questionId, $answerText]);
                error_log("Đã lưu câu trả lời cho câu hỏi ID: $questionId");
            }

            // Liên kết bài làm với sinh viên
            try {
                $query = "INSERT INTO sinh_vien_bai_lam_trac_nghiem (sinh_vien_id, bai_trac_nghiem) 
                          VALUES (?, ?)";
                $stmt = $this->db->prepare($query);
                $success = $stmt->execute([$sinhVienId, $baiLamId]);
                
                if (!$success) {
                    error_log("Lỗi khi thêm vào bảng sinh_vien_bai_lam_trac_nghiem: " . implode(" ", $stmt->errorInfo()));
                    $this->db->rollBack();
                    return false;
                }
                error_log("Đã liên kết bài làm với sinh viên thành công");
            } catch (PDOException $e) {
                error_log("Exception khi liên kết bài làm với sinh viên: " . $e->getMessage());
                $this->db->rollBack();
                return false;
            }

            // Xóa tất cả phiên làm bài của sinh viên cho bài thi này
            try {
                $query = "DELETE FROM phien_lam_bai_trac_nghiem 
                          WHERE sinh_vien_id = ? AND bai_kiem_tra_id = ?";
                $stmt = $this->db->prepare($query);
                $deleteResult = $stmt->execute([$sinhVienId, $testId]);
                
                if ($deleteResult) {
                    $rowCount = $stmt->rowCount();
                    error_log("Đã xóa $rowCount phiên làm bài sau khi nộp bài thành công");
                } else {
                    error_log("Không thể xóa phiên làm bài: " . implode(" ", $stmt->errorInfo()));
                }
            } catch (PDOException $e) {
                error_log("Exception khi xóa phiên làm bài: " . $e->getMessage());
                // Không rollback ở đây vì phần chính của việc nộp bài đã thành công
            }
            
            $this->db->commit();
            error_log("Nộp bài thi trắc nghiệm thành công - sinh viên ID: $sinhVienId, bài thi ID: $testId");
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Lỗi submitTracNghiemTest: " . $e->getMessage() . " - Dòng: " . $e->getLine());
            return false;
        }
    }
    
    /**
     * Xóa phiên làm bài trắc nghiệm
     * 
     * @param int $sinhVienId ID của sinh viên
     * @param int $testId ID của bài kiểm tra
     * @param bool $forceDelete Nếu true, sẽ xóa tất cả các phiên bất kể trạng thái
     * @return bool Kết quả xóa phiên
     */
    public function deleteTracNghiemSession($sinhVienId, $testId, $forceDelete = false) {
        try {
            error_log("Bắt đầu xóa phiên làm bài của sinh viên ID: $sinhVienId, bài kiểm tra ID: $testId" . ($forceDelete ? " (force delete)" : ""));
            
            // Thực hiện xóa trong một transaction độc lập nếu chưa có transaction nào đang chạy
            $inTransaction = $this->db->inTransaction();
            if (!$inTransaction) {
                $this->db->beginTransaction();
            }
            
            // Xây dựng query lấy phiên dựa vào tùy chọn forceDelete
            if ($forceDelete) {
                $querySelect = "SELECT id FROM phien_lam_bai_trac_nghiem WHERE sinh_vien_id = ? AND bai_kiem_tra_id = ?";
            } else {
                $querySelect = "SELECT id FROM phien_lam_bai_trac_nghiem WHERE sinh_vien_id = ? AND bai_kiem_tra_id = ? AND (trang_thai = 'dang_lam' OR trang_thai = 'tam_ngung')";
            }
            
            $stmtSelect = $this->db->prepare($querySelect);
            $stmtSelect->execute([$sinhVienId, $testId]);
            $sessions = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($sessions)) {
                error_log("Không tìm thấy phiên làm bài nào để xóa cho sinh viên ID: $sinhVienId, bài kiểm tra ID: $testId");
                if (!$inTransaction) {
                    $this->db->commit();
                }
                return false;
            }
            
            $sessionIds = array_column($sessions, 'id');
            $placeholders = str_repeat('?,', count($sessionIds) - 1) . '?';
            
            // Xóa từ các bảng liên quan
            $tables = [
                'phien_lam_bai_trac_nghiem' => 'id'
            ];
            
            foreach ($tables as $table => $idColumn) {
                $queryDelete = "DELETE FROM $table WHERE $idColumn IN ($placeholders)";
                $stmtDelete = $this->db->prepare($queryDelete);
                $stmtDelete->execute($sessionIds);
                error_log("Đã xóa dữ liệu từ bảng $table: " . $stmtDelete->rowCount() . " dòng");
            }
            
            // Nếu đây là transaction riêng biệt, commit transaction
            if (!$inTransaction) {
                $this->db->commit();
            }
            
            error_log("Xóa phiên làm bài thành công");
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi khi xóa phiên làm bài: " . $e->getMessage());
            
            // Rollback nếu đây là transaction riêng biệt
            if (!$inTransaction && $this->db->inTransaction()) {
                $this->db->rollBack();
            }
            
            throw $e;
        }
    }
    
    /**
     * Lấy kết quả bài làm trắc nghiệm của sinh viên
     */
    public function getTracNghiemTestResult($userId, $testId) {
        // Lấy ID sinh viên
        $query = "SELECT sv.id
                 FROM sinh_vien sv 
                 WHERE sv.nguoi_dung_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$sinhVien) {
            return null;
        }
        
        $sinhVienId = $sinhVien['id'];
        
        // Lấy kết quả bài làm với điểm cao nhất
        $query = "SELECT blt.*, 
                        (SELECT COUNT(*) FROM cau_hoi_bai_kiem_tra_trac_nghiem chbkt 
                         WHERE chbkt.bai_trac_nghiem_id = blt.bai_kiem_tra_id) AS tong_so_cau,
                        (SELECT ROUND(blt.diem, 1)) as diem_formatted,
                        (SELECT COUNT(*) FROM cau_hoi_bai_kiem_tra_trac_nghiem chbkt 
                         WHERE chbkt.bai_trac_nghiem_id = blt.bai_kiem_tra_id) AS so_cau_hoi,
                        (SELECT CEIL((blt.diem / 10) * COUNT(*)) 
                         FROM cau_hoi_bai_kiem_tra_trac_nghiem chbkt 
                         WHERE chbkt.bai_trac_nghiem_id = blt.bai_kiem_tra_id) AS so_cau_dung
                 FROM bai_lam_trac_nghiem blt
                 JOIN sinh_vien_bai_lam_trac_nghiem svbl ON blt.id = svbl.bai_trac_nghiem
                 WHERE svbl.sinh_vien_id = ? AND blt.bai_kiem_tra_id = ?
                 ORDER BY blt.diem DESC
                 LIMIT 1";
                 
        $stmt = $this->db->prepare($query);
        $stmt->execute([$sinhVienId, $testId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            return null;
        }
        
        // Lấy thông tin câu hỏi trong bài thi
        $query = "SELECT chtn.id, chtn.noi_dung, chbkt.chu_de_id
                 FROM cau_hoi_trac_nghiem chtn
                 JOIN cau_hoi_bai_kiem_tra_trac_nghiem chbkt ON chtn.id = chbkt.cau_hoi_id
                 WHERE chbkt.bai_trac_nghiem_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$testId]);
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Tạo mảng chi tiết câu trả lời với cấu trúc phù hợp để hiển thị
        $result['chi_tiet'] = [];
        if (!empty($questions)) {
            // Tạo một danh sách đáp án của học sinh
            foreach ($questions as $question) {
                $questionId = $question['id'];
                
                // Lấy các đáp án cho câu hỏi này
                $query = "SELECT id, dap_an_cua_trac_nghiem
                         FROM cac_dap_an
                         WHERE cau_hoi_id = ?
                         ORDER BY id ASC";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$questionId]);
                $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Lấy lựa chọn của sinh viên
                $query = "SELECT ctl.dap_an_chon
                         FROM cau_tra_loi ctl
                         JOIN bai_lam_trac_nghiem blt ON ctl.bai_lam_id = blt.id
                         JOIN sinh_vien_bai_lam_trac_nghiem svbl ON blt.id = svbl.bai_trac_nghiem
                         WHERE svbl.sinh_vien_id = ? AND blt.bai_kiem_tra_id = ? AND ctl.cau_hoi_id = ?
                         ORDER BY blt.thoi_gian_nop DESC
                         LIMIT 1";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$sinhVienId, $testId, $questionId]);
                $userChoice = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $userChoiceValue = $userChoice ? $userChoice['dap_an_chon'] : '';
                
                // Lưu thông tin câu hỏi và lựa chọn của sinh viên
                $result['chi_tiet'][] = [
                    'cau_hoi_id' => $questionId,
                    'noi_dung' => $question['noi_dung'],
                    'lua_chon' => $userChoiceValue
                ];
            }
        }
        
        // Format ngày nộp bài để hiển thị
        $result['ngay_lam'] = $result['thoi_gian_nop'];
        
        return $result;
    }
    
    /**
     * Lấy thông tin bài làm tự luận của sinh viên theo ID bài thi hoặc ID bài làm
     * @param int $userId ID của người dùng
     * @param int $id ID của bài thi hoặc bài làm tùy theo $isSubmissionId
     * @param bool $isSubmissionId True nếu $id là ID bài làm, False nếu $id là ID bài thi
     * @return array|null Thông tin bài làm hoặc null nếu không tìm thấy
     */
    public function getTuLuanSubmission($userId, $id, $isSubmissionId = false) {
        // Lấy ID sinh viên
        $query = "SELECT sv.id
                 FROM sinh_vien sv 
                 WHERE sv.nguoi_dung_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$sinhVien) {
            return null;
        }
        
        $sinhVienId = $sinhVien['id'];
        
        // Lấy bài làm tự luận
        if ($isSubmissionId) {
            // Nếu $id là ID bài làm
            $query = "SELECT *
                     FROM bai_lam_tu_luan
                     WHERE id = ? AND sinh_vien_id = ?";
                     
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id, $sinhVienId]);
        } else {
            // Nếu $id là ID bài thi
            $query = "SELECT *
                     FROM bai_lam_tu_luan
                     WHERE sinh_vien_id = ? AND bai_kiem_tra_id = ?";
                     
            $stmt = $this->db->prepare($query);
            $stmt->execute([$sinhVienId, $id]);
        }
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy thông tin bài làm tự luận gần nhất của sinh viên cho bài thi
     * @param int $userId ID của người dùng
     * @param int $testId ID của bài thi
     * @return array|null Thông tin bài làm hoặc null nếu không tìm thấy
     */
    public function getLastTuLuanSubmission($userId, $testId) {
        try {
            // Lấy ID sinh viên
            $query = "SELECT sv.id
                     FROM sinh_vien sv 
                     WHERE sv.nguoi_dung_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$sinhVien) {
                return null;
            }
            
            $sinhVienId = $sinhVien['id'];
            
            // Lấy bài làm tự luận gần nhất
            $query = "SELECT *
                     FROM bai_lam_tu_luan
                     WHERE sinh_vien_id = ? AND bai_kiem_tra_id = ?
                     ORDER BY ngay_nop DESC
                     LIMIT 1";
                     
            $stmt = $this->db->prepare($query);
            $stmt->execute([$sinhVienId, $testId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Lỗi getLastTuLuanSubmission: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Nộp bài làm tự luận
     */
    public function submitTuLuanTest($userId, $testId, $content, $file = null) {
        try {
            error_log("Bắt đầu nộp bài thi tự luận - userId: $userId, testId: $testId");
            
            $this->db->beginTransaction();
            
            // Lấy ID sinh viên
            $query = "SELECT sv.id
                     FROM sinh_vien sv 
                     WHERE sv.nguoi_dung_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$sinhVien) {
                error_log("Không tìm thấy thông tin sinh viên với nguoi_dung_id: $userId");
                $this->db->rollBack();
                return false;
            }
            
            $sinhVienId = $sinhVien['id'];
            error_log("Đã xác định được sinh viên ID: $sinhVienId");
            
            // Kiểm tra xem đã nộp bài chưa
            $query = "SELECT id
                     FROM bai_lam_tu_luan
                     WHERE sinh_vien_id = ? AND bai_kiem_tra_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$sinhVienId, $testId]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $result = false;
            
            if ($existing) {
                // Nếu đã nộp rồi thì cập nhật
                error_log("Cập nhật bài làm tự luận ID: {$existing['id']}");
                $query = "UPDATE bai_lam_tu_luan
                         SET noi_dung = ?, tep_tin = ?, ngay_nop = NOW()
                         WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $result = $stmt->execute([$content, $file, $existing['id']]);
                
                if (!$result) {
                    error_log("Lỗi khi cập nhật bài làm tự luận: " . implode(" ", $stmt->errorInfo()));
                }
            } else {
                // Nếu chưa nộp thì tạo mới
                error_log("Tạo bài làm tự luận mới cho sinh viên ID: $sinhVienId, bài thi ID: $testId");
                $query = "INSERT INTO bai_lam_tu_luan
                         (sinh_vien_id, bai_kiem_tra_id, noi_dung, tep_tin, ngay_nop)
                         VALUES (?, ?, ?, ?, NOW())";
                $stmt = $this->db->prepare($query);
                $result = $stmt->execute([$sinhVienId, $testId, $content, $file]);
                
                if (!$result) {
                    error_log("Lỗi khi tạo bài làm tự luận mới: " . implode(" ", $stmt->errorInfo()));
                } else {
                    $newId = $this->db->lastInsertId();
                    error_log("Đã tạo bài làm tự luận mới ID: $newId");
                }
            }
            
            // Cập nhật trạng thái phiên làm bài tự luận thành 'da_nop'
            try {
                $query = "UPDATE phien_lam_bai_tu_luan 
                          SET trang_thai = 'da_nop', 
                              thoi_gian_con_lai = 0,
                              ngay_cap_nhat = NOW()
                          WHERE sinh_vien_id = ? AND bai_kiem_tra_id = ?";
                $stmt = $this->db->prepare($query);
                $updateResult = $stmt->execute([$sinhVienId, $testId]);
                
                if ($updateResult) {
                    $rowCount = $stmt->rowCount();
                    error_log("Đã cập nhật trạng thái phiên làm bài tự luận thành 'da_nop' cho $rowCount phiên");
                } else {
                    error_log("Không thể cập nhật trạng thái phiên làm bài tự luận: " . implode(" ", $stmt->errorInfo()));
                }
            } catch (PDOException $e) {
                error_log("Exception khi cập nhật trạng thái phiên làm bài tự luận: " . $e->getMessage());
                // Không rollback ở đây vì phần chính của việc nộp bài đã thành công
            }
            
            if ($result) {
                $this->db->commit();
                error_log("Nộp bài thi tự luận thành công - sinh viên ID: $sinhVienId, bài thi ID: $testId");
            } else {
                $this->db->rollBack();
                error_log("Nộp bài thi tự luận thất bại - sinh viên ID: $sinhVienId, bài thi ID: $testId");
            }
            
            return $result;
        } catch (Exception $e) {
            if ($this->db && $this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Lỗi submitTuLuanTest: " . $e->getMessage() . " - Dòng: " . $e->getLine());
            return false;
        }
    }

    /**
     * Lấy thông tin bài làm trắc nghiệm theo ID
     * @param int $baiLamId ID của bài làm
     * @param int $userId ID của người dùng
     * @return array|null Thông tin bài làm hoặc null nếu không tìm thấy
     */
    public function getBaiLamById($baiLamId, $userId) {
        try {
            // Lấy ID sinh viên
            $query = "SELECT sv.id
                     FROM sinh_vien sv 
                     WHERE sv.nguoi_dung_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$sinhVien) {
                return null;
            }
            
            $sinhVienId = $sinhVien['id'];
            
            // Lấy thông tin bài làm
            $query = "SELECT bl.*
                     FROM bai_lam_trac_nghiem bl
                     JOIN sinh_vien_bai_lam_trac_nghiem svbl ON bl.id = svbl.bai_trac_nghiem
                     WHERE bl.id = ? AND svbl.sinh_vien_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$baiLamId, $sinhVienId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Lỗi getBaiLamById: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Lấy câu trả lời của người dùng cho một bài làm
     * @param int $baiLamId ID của bài làm
     * @return array Mảng chứa câu trả lời của người dùng, key là ID câu hỏi, value là đáp án đã chọn
     */
    public function getUserAnswers($baiLamId) {
        try {
            $query = "SELECT cau_hoi_id, dap_an_chon
                     FROM cau_tra_loi
                     WHERE bai_lam_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$baiLamId]);
            
            $answers = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $answers[$row['cau_hoi_id']] = $row['dap_an_chon'];
            }
            
            return $answers;
        } catch (Exception $e) {
            error_log("Lỗi getUserAnswers: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy thông tin phiên làm bài trắc nghiệm
     */
    public function getTracNghiemSession($sinhVienId, $testId) {
        try {
            $query = "SELECT * FROM phien_lam_bai_trac_nghiem
                     WHERE sinh_vien_id = ? AND bai_kiem_tra_id = ? AND trang_thai = 'dang_lam'
                     ORDER BY id DESC LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$sinhVienId, $testId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                error_log("Đã tìm thấy phiên làm bài - ID: {$result['id']}, Trạng thái: {$result['trang_thai']}, Thời gian còn lại: {$result['thoi_gian_con_lai']} giây");
            } else {
                error_log("Không tìm thấy phiên làm bài đang dở cho sinh viên ID: $sinhVienId, bài kiểm tra ID: $testId");
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Lỗi khi lấy phiên làm bài: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Cập nhật trạng thái phiên làm bài
     */
    public function updateTracNghiemSessionStatus($sessionId, $status) {
        try {
            $query = "UPDATE phien_lam_bai_trac_nghiem
                     SET trang_thai = ?, thoi_gian_cap_nhat = NOW()
                     WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([$status, $sessionId]);
            
            if ($result) {
                error_log("Đã cập nhật trạng thái phiên ID: $sessionId thành '$status'");
            } else {
                error_log("Không thể cập nhật trạng thái phiên ID: $sessionId");
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Lỗi khi cập nhật trạng thái phiên: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tạo hoặc cập nhật phiên làm bài trắc nghiệm
     */
    public function createOrUpdateTracNghiemSession($sinhVienId, $testId, $timeRemaining, $answers = null) {
        try {
            // Kiểm tra tham số đầu vào
            if (!$sinhVienId || !$testId) {
                error_log("Tham số không hợp lệ: sinhVienId=$sinhVienId, testId=$testId");
                return false;
            }
            
            // Đảm bảo timeRemaining luôn là số dương
            $timeRemaining = max(0, intval($timeRemaining));
            
            // Chuẩn hóa tham số answers
            if (is_array($answers)) {
                $answers = json_encode($answers, JSON_UNESCAPED_UNICODE);
            } else if (is_string($answers) && !empty($answers) && $answers[0] !== '{' && $answers[0] !== '[') {
                // Nếu là chuỗi nhưng không phải JSON, cố gắng giải mã và mã hóa lại
                $decodedAnswers = json_decode($answers, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $answers = json_encode($decodedAnswers, JSON_UNESCAPED_UNICODE);
                }
            }
            
            // Kiểm tra kết nối database
            if (!$this->db) {
                error_log("Không có kết nối đến cơ sở dữ liệu");
                return false;
            }
            
            // Kiểm tra xem đã có phiên nào chưa
            $session = $this->getTracNghiemSession($sinhVienId, $testId);
            
            $this->db->beginTransaction();
            
            if ($session) {
                // Cập nhật phiên hiện có
                $query = "UPDATE phien_lam_bai_trac_nghiem
                         SET thoi_gian_con_lai = ?,
                             cau_tra_loi = ?,
                             trang_thai = 'dang_lam',
                             ngay_cap_nhat = NOW()
                         WHERE id = ?";
                
                $stmt = $this->db->prepare($query);
                $result = $stmt->execute([$timeRemaining, $answers, $session['id']]);
                
                $affected = $stmt->rowCount();
                
                if ($affected) {
                    error_log("Đã cập nhật phiên làm bài trắc nghiệm với ID: {$session['id']} - Thời gian còn lại: $timeRemaining giây");
                    $this->db->commit();
                    return $session['id'];
                } else {
                    error_log("Không thể cập nhật phiên làm bài trắc nghiệm với ID: {$session['id']}");
                    $this->db->rollBack();
                    return false;
                }
            } else {
                // Tạo phiên mới mà không xóa phiên cũ chưa hoàn thành
                $query = "INSERT INTO phien_lam_bai_trac_nghiem
                         (sinh_vien_id, bai_kiem_tra_id, thoi_gian_bat_dau, thoi_gian_con_lai, cau_tra_loi, trang_thai)
                         VALUES (?, ?, NOW(), ?, ?, 'dang_lam')";
                
                $stmt = $this->db->prepare($query);
                $result = $stmt->execute([$sinhVienId, $testId, $timeRemaining, $answers]);
                
                if ($result) {
                    $sessionId = $this->db->lastInsertId();
                    error_log("Đã tạo phiên làm bài trắc nghiệm mới với ID: $sessionId - Thời gian còn lại: $timeRemaining giây");
                    $this->db->commit();
                    return $sessionId;
                } else {
                    error_log("Không thể tạo phiên làm bài trắc nghiệm mới");
                    $this->db->rollBack();
                    return false;
                }
            }
        } catch (PDOException $e) {
            if ($this->db && $this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Lỗi khi tạo/cập nhật phiên làm bài trắc nghiệm: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy đáp án của sinh viên cho một bài kiểm tra trắc nghiệm
     * @param int $testId ID bài kiểm tra
     * @param int $sinhVienId ID sinh viên
     * @return array Danh sách đáp án của sinh viên
     */
    public function getStudentTracNghiemAnswers($testId, $sinhVienId) {
        $query = "SELECT blt.cau_tra_loi
                  FROM bai_lam_trac_nghiem blt
                  JOIN sinh_vien_bai_lam_trac_nghiem svblt ON blt.id = svblt.bai_trac_nghiem
                  WHERE blt.bai_kiem_tra_id = ? AND svblt.sinh_vien_id = ?
                  ORDER BY blt.lan_thu DESC
                  LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$testId, $sinhVienId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            return [];
        }
        
        try {
            $answers = json_decode($result['cau_tra_loi'], true);
            return is_array($answers) ? $answers : [];
        } catch (Exception $e) {
            error_log("Lỗi giải mã JSON đáp án: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Kiểm tra và xóa phiên làm bài trắc nghiệm còn sót lại
     * 
     * @param int $sinhVienId ID của sinh viên
     * @param int $testId ID của bài kiểm tra
     * @return bool Kết quả xử lý
     */
    public function cleanupTracNghiemSession($sinhVienId, $testId) {
        try {
            error_log("Bắt đầu dọn dẹp phiên làm bài còn sót lại của sinh viên ID: $sinhVienId, bài kiểm tra ID: $testId");
            
            // Kiểm tra xem có transaction đang chạy không
            $hasTransaction = $this->db->inTransaction();
            if (!$hasTransaction) {
                $this->db->beginTransaction();
                error_log("Bắt đầu transaction mới cho việc dọn dẹp phiên");
            } else {
                error_log("Sử dụng transaction hiện tại cho việc dọn dẹp phiên");
            }
            
            // Xóa trực tiếp tất cả phiên làm bài của sinh viên đối với bài kiểm tra này
            $query = "DELETE FROM phien_lam_bai_trac_nghiem 
                      WHERE sinh_vien_id = ? AND bai_kiem_tra_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$sinhVienId, $testId]);
            
            $rowCount = $stmt->rowCount();
            error_log("Đã xóa $rowCount phiên làm bài");
            
            // Commit transaction nếu chúng ta tạo ra
            if (!$hasTransaction) {
                $this->db->commit();
                error_log("Đã commit transaction dọn dẹp phiên");
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi khi dọn dẹp phiên làm bài: " . $e->getMessage());
            
            // Rollback chỉ khi chúng ta bắt đầu transaction
            if (!$hasTransaction && $this->db->inTransaction()) {
                $this->db->rollBack();
                error_log("Đã rollback transaction dọn dẹp phiên");
            }
            
            return false;
        }
    }

    /**
     * Lấy thông tin phiên làm bài tự luận
     */
    public function getTuLuanSession($sinhVienId, $testId) {
        try {
            $query = "SELECT * FROM phien_lam_bai_tu_luan
                     WHERE sinh_vien_id = ? AND bai_kiem_tra_id = ? AND trang_thai = 'dang_lam'
                     ORDER BY id DESC LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$sinhVienId, $testId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                error_log("Đã tìm thấy phiên làm bài tự luận - ID: {$result['id']}, Trạng thái: {$result['trang_thai']}, Thời gian còn lại: {$result['thoi_gian_con_lai']} giây");
            } else {
                error_log("Không tìm thấy phiên làm bài tự luận đang dở cho sinh viên ID: $sinhVienId, bài kiểm tra ID: $testId");
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Lỗi khi lấy phiên làm bài tự luận: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Cập nhật trạng thái phiên làm bài tự luận
     * 
     * @param int $sessionId ID của phiên làm bài
     * @param string $status Trạng thái mới ('dang_lam', 'da_nop', 'tam_ngung')
     * @return bool Kết quả cập nhật
     */
    public function updateTuLuanSessionStatus($sessionId, $status) {
        try {
            $query = "UPDATE phien_lam_bai_tu_luan
                     SET trang_thai = ?, ngay_cap_nhat = NOW()
                     WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([$status, $sessionId]);
            
            if ($result) {
                error_log("Đã cập nhật trạng thái phiên tự luận ID: $sessionId thành '$status'");
            } else {
                error_log("Không thể cập nhật trạng thái phiên tự luận ID: $sessionId");
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Lỗi khi cập nhật trạng thái phiên tự luận: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tạo hoặc cập nhật phiên làm bài tự luận
     */
    public function createOrUpdateTuLuanSession($sinhVienId, $testId, $timeRemaining, $content, $files = null) {
        try {
            // Kiểm tra tham số đầu vào
            if (!$sinhVienId || !$testId) {
                error_log("Tham số không hợp lệ: sinhVienId=$sinhVienId, testId=$testId");
                return false;
            }
            
            // Đảm bảo timeRemaining luôn là số dương
            $timeRemaining = max(0, intval($timeRemaining));
            
            // Kiểm tra kết nối database
            if (!$this->db) {
                error_log("Không có kết nối đến cơ sở dữ liệu");
                return false;
            }
            
            // Kiểm tra xem đã có phiên nào chưa
            $session = $this->getTuLuanSession($sinhVienId, $testId);
            
            $this->db->beginTransaction();
            
            if ($session) {
                // Nếu đã có phiên, cập nhật
                $query = "UPDATE phien_lam_bai_tu_luan
                         SET thoi_gian_con_lai = ?,
                             noi_dung = ?,
                             tep_tin = ?,
                             ngay_cap_nhat = NOW()
                         WHERE id = ?";
                
                $stmt = $this->db->prepare($query);
                $stmt->execute([$timeRemaining, $content, $files, $session['id']]);
                
                $affected = $stmt->rowCount();
                
                if ($affected) {
                    error_log("Đã cập nhật phiên làm bài tự luận với ID: {$session['id']} - Thời gian còn lại: $timeRemaining giây");
                    $this->db->commit();
                    return $session['id'];
                } else {
                    error_log("Không thể cập nhật phiên làm bài tự luận với ID: {$session['id']}");
                    $this->db->rollBack();
                    return false;
                }
            } else {
                // Nếu chưa có phiên, tạo mới
                $query = "INSERT INTO phien_lam_bai_tu_luan
                         (sinh_vien_id, bai_kiem_tra_id, thoi_gian_con_lai, noi_dung, tep_tin, trang_thai, ngay_tao, ngay_cap_nhat)
                         VALUES (?, ?, ?, ?, ?, 'dang_lam', NOW(), NOW())";
                
                $stmt = $this->db->prepare($query);
                $result = $stmt->execute([$sinhVienId, $testId, $timeRemaining, $content, $files]);
                
                if ($result) {
                    $sessionId = $this->db->lastInsertId();
                    error_log("Đã tạo phiên làm bài tự luận mới với ID: $sessionId - Thời gian còn lại: $timeRemaining giây");
                    $this->db->commit();
                    return $sessionId;
                } else {
                    error_log("Không thể tạo phiên làm bài tự luận mới");
                    $this->db->rollBack();
                    return false;
                }
            }
        } catch (PDOException $e) {
            if ($this->db && $this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Lỗi khi tạo/cập nhật phiên làm bài tự luận: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kiểm tra và xóa phiên làm bài tự luận còn sót lại
     * 
     * @param int $sinhVienId ID của sinh viên
     * @param int $testId ID của bài kiểm tra
     * @return bool Kết quả xử lý
     */
    public function cleanupTuLuanSession($sinhVienId, $testId) {
        try {
            error_log("Bắt đầu dọn dẹp phiên làm bài tự luận của sinh viên ID: $sinhVienId, bài kiểm tra ID: $testId");
            
            // Kiểm tra xem có transaction đang chạy không
            $hasTransaction = $this->db->inTransaction();
            if (!$hasTransaction) {
                $this->db->beginTransaction();
                error_log("Bắt đầu transaction mới cho việc dọn dẹp phiên tự luận");
            } else {
                error_log("Sử dụng transaction hiện tại cho việc dọn dẹp phiên tự luận");
            }
            
            // Xóa trực tiếp tất cả phiên làm bài của sinh viên đối với bài kiểm tra này
            $query = "DELETE FROM phien_lam_bai_tu_luan 
                      WHERE sinh_vien_id = ? AND bai_kiem_tra_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$sinhVienId, $testId]);
            
            $rowCount = $stmt->rowCount();
            error_log("Đã xóa $rowCount phiên làm bài tự luận");
            
            // Commit transaction nếu chúng ta tạo ra
            if (!$hasTransaction) {
                $this->db->commit();
                error_log("Đã commit transaction dọn dẹp phiên tự luận");
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Lỗi khi dọn dẹp phiên làm bài tự luận: " . $e->getMessage());
            
            // Rollback chỉ khi chúng ta bắt đầu transaction
            if (!$hasTransaction && $this->db->inTransaction()) {
                $this->db->rollBack();
                error_log("Đã rollback transaction dọn dẹp phiên tự luận");
            }
            
            return false;
        }
    }
} 