<?php
require_once 'Model/KiemTraModel.php';

class KiemTraController {
    private $kiemTraModel;
    private $db;
    
    public function __construct() {
        $this->kiemTraModel = new KiemTraModel();
        require_once 'Config/Database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    /**
     * Hiển thị danh sách bài thi cho sinh viên
     */
    public function index() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['vai_tro']) || $_SESSION['vai_tro'] !== 'sinh_vien') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        // Đặt múi giờ Việt Nam
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        
        $userId = $_SESSION['user_id'];
        
        // Lấy danh sách bài thi trắc nghiệm và tự luận
        $tracNghiemTests = $this->kiemTraModel->getTracNghiemTestsForStudent($userId);
        $tuLuanTests = $this->kiemTraModel->getTuLuanTestsForStudent($userId);
        
        // Hiển thị view danh sách bài thi
        require_once 'View/KiemTra/index.php';
    }
    
    /**
     * Hiển thị lịch sử bài thi của sinh viên
     */
    public function lichSu() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['vai_tro']) || $_SESSION['vai_tro'] !== 'sinh_vien') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        
        // Lấy lịch sử bài thi trắc nghiệm và tự luận
        $tracNghiemHistory = $this->kiemTraModel->getTracNghiemTestHistory($userId);
        $tuLuanHistory = $this->kiemTraModel->getTuLuanTestHistory($userId);
        
        // Hiển thị view lịch sử bài thi
        require_once 'View/KiemTra/lichSu.php';
    }
    
    /**
     * Xem bài thi trắc nghiệm
     */
    public function xemTracNghiem() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['vai_tro']) || $_SESSION['vai_tro'] !== 'sinh_vien') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        // Đặt múi giờ Việt Nam
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        
        // Database connection is already initialized in constructor, no need to do it again
        
        $userId = $_SESSION['user_id'];
        $testId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if (!$testId) {
            $_SESSION['error'] = "Bài kiểm tra không hợp lệ";
            header('Location: index.php?controller=kiemtra&action=lichSu');
            exit;
        }
        
        // Lấy thông tin bài kiểm tra
        $test = $this->kiemTraModel->getTracNghiemTestById($testId, $userId);
        
        if (!$test) {
            // Nếu không tìm thấy bài kiểm tra, tạo một mảng trống để tránh lỗi
            $test = array(
                'id' => $testId,
                'tieu_de' => '',
                'ten_mon' => '',
                'ma_mon' => '',
                'thoi_gian_bat_dau' => null,
                'thoi_gian_ket_thuc' => null,
                'thoi_gian_lam' => 0,
                'so_cau_hoi' => 0,
                'so_lan_lam' => 0,
                'so_lan_da_lam' => 0
            );
            
            $_SESSION['error'] = "Không tìm thấy bài kiểm tra hoặc bạn không có quyền truy cập";
            // Vẫn hiển thị trang nhưng với thông báo lỗi
        } else {
            // Lấy tên giáo viên từ bảng giao_vien dựa trên lop_hoc_id
            if (isset($test['lop_hoc_id'])) {
                $query = "SELECT nd.ho_va_ten as ten_giao_vien 
                         FROM lop_hoc lh 
                         JOIN giao_vien gv ON lh.giao_vien_id = gv.id
                         JOIN nguoi_dung nd ON gv.nguoi_dung_id = nd.id
                         WHERE lh.id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$test['lop_hoc_id']]);
                $giaovien = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($giaovien) {
                    $test['ten_giao_vien'] = $giaovien['ten_giao_vien'];
                }
            }
            
            // Cải thiện truy vấn đếm câu hỏi - Đảm bảo có số câu hỏi đúng
            $query = "SELECT COUNT(*) as so_cau_hoi 
                     FROM cau_hoi_bai_kiem_tra_trac_nghiem 
                     WHERE bai_trac_nghiem_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$testId]);
            $soCauHoi = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($soCauHoi && isset($soCauHoi['so_cau_hoi'])) {
                $test['so_cau_hoi'] = intval($soCauHoi['so_cau_hoi']);
                
                // Thêm thông báo khi không có câu hỏi
                if ($test['so_cau_hoi'] === 0) {
                    $_SESSION['warning'] = "Bài kiểm tra này chưa có câu hỏi nào. Vui lòng liên hệ giáo viên.";
                }
            } else {
                $test['so_cau_hoi'] = 0;
                $_SESSION['warning'] = "Bài kiểm tra này chưa có câu hỏi nào. Vui lòng liên hệ giáo viên.";
            }
        }
        
        // Kiểm tra thời gian làm bài
        $timezone = new DateTimeZone(date_default_timezone_get());
        $now = new DateTime('now', $timezone);
        $startTime = !empty($test['thoi_gian_bat_dau']) ? new DateTime($test['thoi_gian_bat_dau'], $timezone) : $now;
        $endTime = !empty($test['thoi_gian_ket_thuc']) ? new DateTime($test['thoi_gian_ket_thuc'], $timezone) : null;
        
        // Debug thông tin thời gian
        error_log("DEBUG TIME: Now=" . $now->format('Y-m-d H:i:s') . 
                 ", Start=" . $startTime->format('Y-m-d H:i:s') . 
                 ($endTime ? ", End=" . $endTime->format('Y-m-d H:i:s') : ", No End Time"));
        
        // Thêm debug trực tiếp vào trang để dễ theo dõi
        echo "<!-- DEBUG TIMEZONE: " . date_default_timezone_get() . " -->";
        echo "<!-- DEBUG TIME: Now=" . $now->format('Y-m-d H:i:s') . 
             ", Start=" . $startTime->format('Y-m-d H:i:s') . 
             ($endTime ? ", End=" . $endTime->format('Y-m-d H:i:s') : ", No End Time") . " -->";
        
        if ($now < $startTime) {
            $_SESSION['error'] = "Bài kiểm tra chưa mở. Thời gian hiện tại: " . $now->format('Y-m-d H:i:s') . 
                               ", Thời gian mở: " . $startTime->format('Y-m-d H:i:s');
            header('Location: index.php?controller=kiemtra');
            exit;
        }
        
        if ($endTime && $now > $endTime) {
            $_SESSION['error'] = "Bài kiểm tra đã đóng. Thời gian hiện tại: " . $now->format('Y-m-d H:i:s') . 
                               ", Thời gian đóng: " . $endTime->format('Y-m-d H:i:s');
            header('Location: index.php?controller=kiemtra');
            exit;
        }
        
        // Kiểm tra số lần làm bài
        if (isset($test['so_lan_lam']) && $test['so_lan_lam'] > 0 && isset($test['so_lan_da_lam']) && $test['so_lan_da_lam'] >= $test['so_lan_lam']) {
            $_SESSION['error'] = "Bạn đã làm bài thi này đủ số lần cho phép";
            // Vẫn hiển thị trang với thông báo lỗi
        }
        
        // Lưu trữ câu trả lời hiện tại nếu có
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_answers']) && isset($_POST['answers'])) {
            $_SESSION['saved_answers_'.$testId] = $_POST['answers'];
            // Redirect để tránh việc gửi lại form khi refresh trang
            if (isset($_POST['redirect_url']) && !empty($_POST['redirect_url'])) {
                // Nếu có URL chuyển hướng, chuyển hướng đến URL đó
                header('Location: ' . $_POST['redirect_url']);
            } else {
                // Nếu không có URL chuyển hướng, quay lại trang làm bài
                header('Location: index.php?controller=kiemtra&action=xemTracNghiem&id=' . $testId . '&continue=1');
            }
            exit;
        }
        
        // Khởi tạo biến để lưu trữ câu trả lời đã lưu
        $savedAnswers = isset($_SESSION['saved_answers_'.$testId]) ? $_SESSION['saved_answers_'.$testId] : [];
        
        // Lấy câu hỏi của bài kiểm tra nếu người dùng bắt đầu làm bài hoặc tiếp tục làm bài
        if (isset($_POST['start_test']) && $_POST['start_test'] == 1 || isset($_GET['continue']) || isset($_SESSION['test_in_progress_'.$testId])) {
            // Đánh dấu bài thi đang làm
            $_SESSION['test_in_progress_'.$testId] = true;
            
            // Kiểm tra trước nếu bài kiểm tra không có câu hỏi
            if ($test['so_cau_hoi'] === 0) {
                $_SESSION['error'] = "Không thể bắt đầu làm bài vì bài kiểm tra này chưa có câu hỏi nào.";
                $questions = [];
            } else {
                // Lấy câu hỏi của bài kiểm tra
                $questions = $this->kiemTraModel->getTracNghiemQuestions($testId);
                
                // Nếu không có câu hỏi, khởi tạo mảng trống
                if (empty($questions)) {
                    $questions = [];
                    $_SESSION['error'] = "Không tìm thấy câu hỏi nào cho bài kiểm tra này.";
                } else {
                    // Cập nhật số câu hỏi trong test nếu chưa có
                    if (!isset($test['so_cau_hoi']) || $test['so_cau_hoi'] == 0) {
                        $test['so_cau_hoi'] = count($questions);
                    }
                    
                    // Đảm bảo mỗi câu hỏi có đầy đủ các field cần thiết
                    foreach ($questions as &$question) {
                        if (!isset($question['dap_an_a'])) $question['dap_an_a'] = '';
                        if (!isset($question['dap_an_b'])) $question['dap_an_b'] = '';
                        if (!isset($question['dap_an_c'])) $question['dap_an_c'] = '';
                        if (!isset($question['dap_an_d'])) $question['dap_an_d'] = '';
                        if (!isset($question['dap_an_e'])) $question['dap_an_e'] = '';
                        if (!isset($question['dap_an_f'])) $question['dap_an_f'] = '';
                        if (!isset($question['dap_an_g'])) $question['dap_an_g'] = '';
                        if (!isset($question['dap_an_h'])) $question['dap_an_h'] = '';
                    }
                    unset($question); // Xóa tham chiếu
                }
            }
        } else {
            $questions = [];
            // Xóa trạng thái bài thi nếu đã hoàn thành
            unset($_SESSION['test_in_progress_'.$testId]);
            unset($_SESSION['saved_answers_'.$testId]);
        }
        
        // Lấy kết quả làm bài trước đó (nếu có)
        $previousResult = $this->kiemTraModel->getTracNghiemTestResult($userId, $testId);
        
        // Kiểm tra xem có phiên làm bài nào đang dở hay không
        $sinhVienId = $_SESSION['sinh_vien_id'];
        $session = $this->kiemTraModel->getTracNghiemSession($sinhVienId, $testId);
        
        // Nếu có phiên đang dở
        if ($session) {
            // Lấy thời gian còn lại từ phiên
            $timeRemaining = $session['thoi_gian_con_lai'];
            
            // Cập nhật biến timeRemaining trong $test
            $test['thoi_gian_con_lai'] = $timeRemaining;
            
            // Phân tích câu trả lời đã lưu
            if (!empty($session['cau_tra_loi'])) {
                $savedAnswers = json_decode($session['cau_tra_loi'], true);
                // Truyền câu trả lời đã lưu vào view
                $test['saved_answers'] = $savedAnswers;
            }
            
            // Cập nhật phiên thành 'dang_lam'
            $this->kiemTraModel->updateTracNghiemSessionStatus($session['id'], 'dang_lam');
        } else {
            // Nếu chưa có phiên, tạo phiên mới
            if (isset($test['thoi_gian_lam']) && $test['thoi_gian_lam'] > 0) {
                $this->kiemTraModel->createOrUpdateTracNghiemSession(
                    $sinhVienId, 
                    $testId, 
                    $test['thoi_gian_lam'] * 60, // Chuyển phút thành giây
                    null
                );
            }
        }
        
        // Hiển thị view bài thi
        require_once 'View/KiemTra/xemTracNghiem.php';
    }
    
    /**
     * Nộp bài thi trắc nghiệm
     */
    public function nopTracNghiem() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['vai_tro']) || $_SESSION['vai_tro'] !== 'sinh_vien') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=kiemtra');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $testId = isset($_POST['test_id']) ? intval($_POST['test_id']) : 0;
        $answers = isset($_POST['answers']) ? $_POST['answers'] : [];
        
        if (!$testId) {
            $_SESSION['error_message'] = "Bài kiểm tra không hợp lệ";
            header('Location: index.php?controller=kiemtra');
            exit;
        }
        
        if (!empty($answers)) {
            error_log("Số câu trả lời: " . count($answers));
        } else {
            error_log("Không có câu trả lời nào");
        }
        
        // Lấy ID sinh viên từ session
        $sinhVienId = isset($_SESSION['sinh_vien_id']) ? $_SESSION['sinh_vien_id'] : null;
        
        if (!$sinhVienId) {
            // Nếu không có trong session, lấy từ database
            $query = "SELECT id FROM sinh_vien WHERE nguoi_dung_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($sinhVien) {
                $sinhVienId = $sinhVien['id'];
                $_SESSION['sinh_vien_id'] = $sinhVienId;
            } else {
                $_SESSION['error_message'] = "Không tìm thấy thông tin sinh viên";
                header('Location: index.php?controller=kiemtra');
                exit;
            }
        }
        
        try {
            // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
            $this->db->beginTransaction();
            
            // Nộp bài thi
            $submissionResult = $this->kiemTraModel->submitTracNghiemTest($userId, $testId, $answers);
            
            if (!$submissionResult) {
                throw new Exception("Lỗi khi nộp bài thi");
            }
            
            // Xóa tất cả phiên làm bài của người dùng sử dụng phương thức từ model
            $cleanupResult = $this->kiemTraModel->cleanupTracNghiemSession($sinhVienId, $testId);
            
            if (!$cleanupResult) {
                error_log("Cảnh báo: Không thể xóa phiên làm bài nhưng bài thi đã được nộp thành công");
                // Không throw exception ở đây vì nộp bài đã thành công
            }
            
            // Commit transaction
            $this->db->commit();
            
            $_SESSION['success_message'] = "Bạn đã nộp bài thành công!";
            
            // Xóa các biến phiên trong PHP
            unset($_SESSION["test_in_progress_$testId"]);
            unset($_SESSION["saved_answers_$testId"]);
            
            // Chuyển hướng đến trang lịch sử
            header("Location: index.php?controller=kiemtra&action=lichSu");
            exit();
        } catch (Exception $e) {
            // Rollback nếu có lỗi
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            
            error_log("Lỗi khi nộp bài và xóa phiên: " . $e->getMessage());
            $_SESSION['error_message'] = "Có lỗi xảy ra khi nộp bài. Vui lòng thử lại!";
            header("Location: index.php?controller=kiemtra&action=lambaithitracnghiem&id=$testId");
            exit();
        }
    }
    
    /**
     * Xem bài thi tự luận
     */
    public function xemTuLuan() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['vai_tro']) || $_SESSION['vai_tro'] !== 'sinh_vien') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $testId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if (!$testId) {
            $_SESSION['error'] = "Bài kiểm tra không hợp lệ";
            header('Location: index.php?controller=kiemtra&action=lichSu');
            exit;
        }
        
        try {
            // Lấy thông tin bài kiểm tra
            $test = $this->kiemTraModel->getTuLuanTestById($testId, $userId);
            
            if (!$test) {
                $_SESSION['error'] = "Không tìm thấy bài kiểm tra hoặc bạn không có quyền truy cập";
                header('Location: index.php?controller=kiemtra&action=lichSu');
                exit;
            }
            
            // Kiểm tra thời gian làm bài
            $now = new DateTime();
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            
            // Định dạng lại chuỗi thời gian để đảm bảo đúng định dạng DateTime
            if (!empty($test['thoi_gian_bat_dau']) && is_string($test['thoi_gian_bat_dau'])) {
                $startTime = new DateTime($test['thoi_gian_bat_dau']);
            } else {
                $startTime = $now;
            }
            
            if (!empty($test['thoi_gian_ket_thuc']) && is_string($test['thoi_gian_ket_thuc'])) {
                $endTime = new DateTime($test['thoi_gian_ket_thuc']);
            } else {
                // Nếu không có thời gian kết thúc, đặt thành null
                $endTime = null;
            }
            
            // Ghi log để debug
            error_log("NopTuLuan - Thời gian hiện tại: " . $now->format('Y-m-d H:i:s'));
            error_log("NopTuLuan - Thời gian bắt đầu: " . ($startTime ? $startTime->format('Y-m-d H:i:s') : 'null'));
            error_log("NopTuLuan - Thời gian kết thúc: " . ($endTime ? $endTime->format('Y-m-d H:i:s') : 'null'));
            
            // Kiểm tra nếu bài thi chưa mở
            if ($now < $startTime) {
                $_SESSION['error'] = "Bài kiểm tra chưa mở. Thời gian hiện tại: " . $now->format('d/m/Y H:i:s') .
                                    ", Thời gian mở: " . $startTime->format('d/m/Y H:i:s');
                header('Location: index.php?controller=kiemtra&action=lamBaiThiTuLuan&id=' . $testId);
                exit;
            }
            
            // Kiểm tra nếu bài thi đã đóng
            if ($endTime && $now > $endTime) {
                $_SESSION['error'] = "Bài kiểm tra đã đóng. Thời gian hiện tại: " . $now->format('d/m/Y H:i:s') . 
                                    ", Thời gian đóng: " . $endTime->format('d/m/Y H:i:s');
                header('Location: index.php?controller=kiemtra&action=lamBaiThiTuLuan&id=' . $testId);
                exit;
            }
            
            // Kiểm tra số lần nộp bài tối đa
            $canSubmit = true;
            if ($now > $endTime) {
                $canSubmit = false;
                $_SESSION['warning'] = "Bài kiểm tra đã kết thúc. Bạn chỉ có thể xem nhưng không thể nộp bài.";
            } else if (isset($test['so_lan_lam']) && isset($test['so_lan_nop']) && $test['so_lan_nop'] >= $test['so_lan_lam']) {
                $canSubmit = false;
                $_SESSION['warning'] = "Bạn đã đạt đến số lần nộp bài tối đa (" . $test['so_lan_lam'] . " lần).";
            }
            
            // Chuẩn bị dữ liệu cho view
            $test['ten_bai_thi'] = $test['tieu_de'];
            $test['ten_mon_hoc'] = $test['ten_mon'];
            $test['thoi_gian_lam_bai'] = $test['thoi_gian_lam'];
            $test['so_lan_nop_toi_da'] = $test['so_lan_lam'];
            
            // Lấy thông tin nộp bài gần nhất nếu có
            if (!empty($test['submission_id'])) {
                $lastSubmission = $this->kiemTraModel->getTuLuanSubmission($userId, $test['submission_id'], true);
                if ($lastSubmission) {
                    $test['noi_dung'] = $lastSubmission['noi_dung'];
                    $test['file_dinh_kem'] = $lastSubmission['tep_tin'];
                    $test['thoi_gian_nop'] = $lastSubmission['ngay_nop'];
                    $test['diem'] = $lastSubmission['diem'];
                }
            }
            
            // Lấy nội dung đề bài từ cơ sở dữ liệu
            if (!isset($test['noi_dung_de_bai'])) {
                // Lấy từ bảng bai_kiem_tra_tu_luan
                $query = "SELECT noi_dung FROM bai_kiem_tra_tu_luan WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$testId]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($result && isset($result['noi_dung'])) {
                    $test['noi_dung_de_bai'] = $result['noi_dung'];
                } else {
                    $test['noi_dung_de_bai'] = '<p>Không có nội dung đề bài</p>';
                }
            }
            
            // Hiển thị view bài thi
            require_once 'View/KiemTra/xemTuLuan.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Có lỗi xảy ra: " . $e->getMessage();
            header('Location: index.php?controller=kiemtra&action=lichSu');
            exit;
        }
    }
    
    /**
     * Nộp bài thi tự luận
     */
    public function nopTuLuan() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['vai_tro']) || $_SESSION['vai_tro'] !== 'sinh_vien') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=kiemtra&action=lichSu');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $testId = isset($_POST['test_id']) ? intval($_POST['test_id']) : 0;
        $content = isset($_POST['content']) ? $_POST['content'] : '';
        
        if (!$testId) {
            $_SESSION['error'] = "Bài kiểm tra không hợp lệ";
            header('Location: index.php?controller=kiemtra&action=lichSu');
            exit;
        }
        
        // Lấy thông tin bài kiểm tra
        $test = $this->kiemTraModel->getTuLuanTestById($testId, $userId);
        
        if (!$test) {
            $_SESSION['error'] = "Không tìm thấy bài kiểm tra hoặc bạn không có quyền truy cập";
            header('Location: index.php?controller=kiemtra&action=lichSu');
            exit;
        }
        
        // Kiểm tra thời gian làm bài
        $now = new DateTime();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        
        // Định dạng lại chuỗi thời gian để đảm bảo đúng định dạng DateTime
        if (!empty($test['thoi_gian_bat_dau']) && is_string($test['thoi_gian_bat_dau'])) {
            $startTime = new DateTime($test['thoi_gian_bat_dau']);
        } else {
            $startTime = $now;
        }
        
        if (!empty($test['thoi_gian_ket_thuc']) && is_string($test['thoi_gian_ket_thuc'])) {
            $endTime = new DateTime($test['thoi_gian_ket_thuc']);
        } else {
            // Nếu không có thời gian kết thúc, đặt thành null
            $endTime = null;
        }
        
        // Ghi log để debug
        error_log("NopTuLuan - Thời gian hiện tại: " . $now->format('Y-m-d H:i:s'));
        error_log("NopTuLuan - Thời gian bắt đầu: " . ($startTime ? $startTime->format('Y-m-d H:i:s') : 'null'));
        error_log("NopTuLuan - Thời gian kết thúc: " . ($endTime ? $endTime->format('Y-m-d H:i:s') : 'null'));
        
        // Kiểm tra nếu bài thi chưa mở
        if ($now < $startTime) {
            $_SESSION['error'] = "Bài kiểm tra chưa mở. Thời gian hiện tại: " . $now->format('d/m/Y H:i:s') .
                                ", Thời gian mở: " . $startTime->format('d/m/Y H:i:s');
            header('Location: index.php?controller=kiemtra&action=lamBaiThiTuLuan&id=' . $testId);
            exit;
        }
        
        // Kiểm tra nếu bài thi đã đóng
        if ($endTime && $now > $endTime) {
            $_SESSION['error'] = "Bài kiểm tra đã đóng. Thời gian hiện tại: " . $now->format('d/m/Y H:i:s') . 
                                ", Thời gian đóng: " . $endTime->format('d/m/Y H:i:s');
            header('Location: index.php?controller=kiemtra&action=lamBaiThiTuLuan&id=' . $testId);
            exit;
        }
        
        // Kiểm tra số lần nộp bài
        if (isset($test['so_lan_nop']) && isset($test['so_lan_nop_toi_da']) && $test['so_lan_nop'] >= $test['so_lan_nop_toi_da']) {
            $_SESSION['error'] = "Bạn đã đạt đến số lần nộp bài tối đa cho phép";
            header('Location: index.php?controller=kiemtra&action=xemTuLuan&id=' . $testId);
            exit;
        }
        
        // Xử lý file đính kèm (nếu có)
        $filesToUpload = [];
        
        if (isset($_FILES['file_dinh_kem']) && !empty($_FILES['file_dinh_kem']['name'][0])) {
            $uploadDir = 'uploads/tu_luan/' . $userId . '/' . $testId . '/';
            
            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'txt'];
            $fileCount = count($_FILES['file_dinh_kem']['name']);
            
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['file_dinh_kem']['error'][$i] == 0) {
                    $fileName = basename($_FILES['file_dinh_kem']['name'][$i]);
                    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
                    $uniqueFileName = uniqid() . '.' . $fileExt;
                    $uploadFile = $uploadDir . $uniqueFileName;
                    
                    if (!in_array(strtolower($fileExt), $allowedExtensions)) {
                        $_SESSION['error'] = "Định dạng file không được hỗ trợ. Chỉ cho phép: " . implode(', ', $allowedExtensions);
                        header('Location: index.php?controller=kiemtra&action=lamBaiThiTuLuan&id=' . $testId);
                        exit;
                    }
                    
                    if ($_FILES['file_dinh_kem']['size'][$i] > 10 * 1024 * 1024) {
                        $_SESSION['error'] = "Kích thước file không được vượt quá 10MB";
                        header('Location: index.php?controller=kiemtra&action=lamBaiThiTuLuan&id=' . $testId);
                        exit;
                    }
                    
                    if (move_uploaded_file($_FILES['file_dinh_kem']['tmp_name'][$i], $uploadFile)) {
                        $filesToUpload[] = $uploadFile;
                    } else {
                        $_SESSION['error'] = "Có lỗi xảy ra khi tải lên file";
                        header('Location: index.php?controller=kiemtra&action=lamBaiThiTuLuan&id=' . $testId);
                        exit;
                    }
                }
            }
        }
        
        // Kiểm tra nếu không có nội dung và không có file
        if (empty($content) && empty($filesToUpload)) {
            $_SESSION['error'] = "Vui lòng nhập nội dung hoặc đính kèm file";
            header('Location: index.php?controller=kiemtra&action=lamBaiThiTuLuan&id=' . $testId);
            exit;
        }
        
        // Ghi log thông tin nộp bài
        error_log("Sinh viên ID: {$userId} đang nộp bài tự luận ID: {$testId}");
        
        // Nếu có nhiều file, nối chúng thành chuỗi phân cách bằng dấu phẩy
        $fileToUpload = !empty($filesToUpload) ? implode(',', $filesToUpload) : null;
        
        // Lưu bài làm tự luận
        $result = $this->kiemTraModel->submitTuLuanTest($userId, $testId, $content, $fileToUpload);
        
        if ($result) {
            $_SESSION['success'] = "Nộp bài thành công";
            
            // Lấy ID sinh viên từ session
            $sinhVienId = isset($_SESSION['sinh_vien_id']) ? $_SESSION['sinh_vien_id'] : null;
            
            if (!$sinhVienId) {
                // Nếu không có sinh_vien_id trong session, lấy từ database
                $query = "SELECT id FROM sinh_vien WHERE nguoi_dung_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$userId]);
                $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($sinhVien) {
                    $sinhVienId = $sinhVien['id'];
                }
            }
            
            // Xóa phiên làm bài trong database nếu có sinh viên ID
            if ($sinhVienId) {
                $this->kiemTraModel->cleanupTuLuanSession($sinhVienId, $testId);
            }
            
            // Xóa phiên làm bài trong session PHP nếu có
            if (isset($_SESSION['tu_luan_in_progress_'.$testId])) {
                unset($_SESSION['tu_luan_in_progress_'.$testId]);
                error_log("Đã xóa session PHP 'tu_luan_in_progress_$testId'");
            }
            if (isset($_SESSION['saved_tu_luan_'.$testId])) {
                unset($_SESSION['saved_tu_luan_'.$testId]);
                error_log("Đã xóa session PHP 'saved_tu_luan_$testId'");
            }
            
            header('Location: index.php?controller=kiemtra&action=lichSu');
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi nộp bài";
            header('Location: index.php?controller=kiemtra&action=lamBaiThiTuLuan&id=' . $testId);
        }
        exit;
    }
    
    /**
     * Hiển thị chi tiết lịch sử bài thi
     */
    public function chiTietLichSu() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['vai_tro']) || $_SESSION['vai_tro'] !== 'sinh_vien') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $testId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $baiLamId = isset($_GET['bai_lam_id']) ? intval($_GET['bai_lam_id']) : 0;
        
        if (!$testId || !$baiLamId) {
            $_SESSION['error'] = "Thông tin bài làm không hợp lệ";
            header('Location: index.php?controller=kiemtra&action=lichSu');
            exit;
        }
        
        try {
            // Lấy thông tin bài kiểm tra
            $test = $this->kiemTraModel->getTracNghiemTestById($testId, $userId);
            
            if (!$test) {
                $_SESSION['error'] = "Không tìm thấy bài kiểm tra";
                header('Location: index.php?controller=kiemtra&action=lichSu');
                exit;
            }
            
            // Lấy thông tin bài làm
            $baiLam = $this->kiemTraModel->getBaiLamById($baiLamId, $userId);
            
            if (!$baiLam) {
                $_SESSION['error'] = "Không tìm thấy bài làm";
                header('Location: index.php?controller=kiemtra&action=lichSu');
                exit;
            }
            
            // Lấy danh sách câu hỏi và đáp án đúng
            $questions = $this->kiemTraModel->getTracNghiemQuestions($testId);
            
            // Lấy câu trả lời của sinh viên
            $userAnswers = $this->kiemTraModel->getUserAnswers($baiLamId);
            
            // Tính số câu đúng
            $soCauDung = 0;
            
            foreach ($questions as $question) {
                $questionId = $question['id'];
                $userAnswer = isset($userAnswers[$questionId]) ? $userAnswers[$questionId] : '';
                
                // Chuyển đáp án người dùng thành mảng nếu là chuỗi có dấu phẩy
                $userAnswerArray = is_string($userAnswer) && strpos($userAnswer, ',') !== false ? 
                                explode(',', $userAnswer) : (is_string($userAnswer) ? [$userAnswer] : []);
                
                // Lấy đáp án đúng của câu hỏi
                $correctAnswerArray = isset($question['dap_an_dung_mang']) ? $question['dap_an_dung_mang'] : 
                                    (isset($question['dap_an_dung']) ? [$question['dap_an_dung']] : []);
                
                // So sánh đáp án
                if (!empty($userAnswerArray) && !empty($correctAnswerArray)) {
                    sort($userAnswerArray);
                    sort($correctAnswerArray);
                    if ($userAnswerArray == $correctAnswerArray) {
                        $soCauDung++;
                    }
                }
            }
            
            // Hiển thị view chi tiết bài làm
            require_once 'View/KiemTra/chitietlichsu.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Có lỗi xảy ra: " . $e->getMessage();
            header('Location: index.php?controller=kiemtra&action=lichSu');
            exit;
        }
    }
    
    /**
     * Làm bài thi trắc nghiệm - form mới
     */
    public function lambaithitracnghiem($testId = 0) {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['vai_tro']) || $_SESSION['vai_tro'] !== 'sinh_vien') {
            $_SESSION['redirect_after_login'] = "index.php?controller=kiemtra&action=lambaithitracnghiem&id=" . $testId;
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        // Đặt múi giờ Việt Nam
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        
        $userId = $_SESSION['user_id'];
        
        if (!$testId) {
            $testId = isset($_GET['id']) ? intval($_GET['id']) : 0;
            if (!$testId) {
                $_SESSION['error'] = "Bài kiểm tra không hợp lệ";
                header('Location: index.php?controller=kiemtra&action=lichSu');
                exit;
            }
        }
        
        // Lấy thông tin bài kiểm tra
        $test = $this->kiemTraModel->getTracNghiemTestById($testId, $userId);
        
        if (!$test) {
            $_SESSION['error'] = "Không tìm thấy bài kiểm tra hoặc bạn không có quyền truy cập";
            header('Location: index.php?controller=kiemtra');
            exit;
        }
        
        $sinhVienId = $_SESSION['sinh_vien_id'];
        
        // Kiểm tra xem có phiên đang làm dở không
        $session = $this->kiemTraModel->getTracNghiemSession($sinhVienId, $testId);
        
        // Nếu có phiên đang dở, tiếp tục từ phiên đó
        if ($session) {
            // Lấy thời gian còn lại từ phiên
            $timeRemaining = $session['thoi_gian_con_lai'];
            
            // Cập nhật biến timeRemaining trong $test
            $test['thoi_gian_con_lai'] = $timeRemaining;
            
            // Phân tích câu trả lời đã lưu
            if (!empty($session['cau_tra_loi'])) {
                $savedAnswers = json_decode($session['cau_tra_loi'], true);
                // Truyền câu trả lời đã lưu vào view
                $test['saved_answers'] = $savedAnswers;
            }
            
            // Cập nhật phiên thành 'dang_lam'
            $this->kiemTraModel->updateTracNghiemSessionStatus($session['id'], 'dang_lam');
        } else {
            // Nếu chưa có phiên, tạo phiên mới
            if (isset($test['thoi_gian_lam']) && $test['thoi_gian_lam'] > 0) {
                $this->kiemTraModel->createOrUpdateTracNghiemSession(
                    $sinhVienId, 
                    $testId, 
                    $test['thoi_gian_lam'] * 60, // Chuyển phút thành giây
                    null  // Không có câu trả lời nào
                );
                
                // Lấy phiên mới tạo để có thể sử dụng thông tin của nó
                $session = $this->kiemTraModel->getTracNghiemSession($sinhVienId, $testId);
                if ($session) {
                    $test['thoi_gian_con_lai'] = $session['thoi_gian_con_lai'];
                }
            }
        }
        
        // Kiểm tra các điều kiện làm bài
        
        // Kiểm tra thời gian làm bài
        $now = new DateTime();
        $startTime = !empty($test['thoi_gian_bat_dau']) ? new DateTime($test['thoi_gian_bat_dau']) : $now;
        $endTime = !empty($test['thoi_gian_ket_thuc']) ? new DateTime($test['thoi_gian_ket_thuc']) : null;
        
        // Đặt biến môi trường PHP để đảm bảo hiển thị đúng thời gian
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        
        // Kiểm tra nếu bài thi chưa mở
        if ($now < $startTime) {
            $_SESSION['error'] = "Bài kiểm tra chưa mở. Thời gian hiện tại: " . $now->format('Y-m-d H:i:s') . 
                               ", Thời gian mở: " . $startTime->format('Y-m-d H:i:s');
            header('Location: index.php?controller=kiemtra');
            exit;
        }
        
        if ($endTime && $now > $endTime) {
            $_SESSION['error'] = "Bài kiểm tra đã đóng. Thời gian hiện tại: " . $now->format('Y-m-d H:i:s') . 
                               ", Thời gian đóng: " . $endTime->format('Y-m-d H:i:s');
            header('Location: index.php?controller=kiemtra');
            exit;
        }
        
        // Kiểm tra số lần làm bài
        if (isset($test['so_lan_lam']) && $test['so_lan_lam'] > 0 && isset($test['so_lan_da_lam']) && $test['so_lan_da_lam'] >= $test['so_lan_lam']) {
            $_SESSION['error'] = "Bạn đã làm bài thi này đủ số lần cho phép";
            header('Location: index.php?controller=kiemtra');
            exit;
        }
        
        // Lưu trữ câu trả lời hiện tại nếu có
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_answers']) && isset($_POST['answers'])) {
            $_SESSION['saved_answers_'.$testId] = $_POST['answers'];
            // Redirect để tránh việc gửi lại form khi refresh trang
            header('Location: index.php?controller=kiemtra&action=lambaithitracnghiem&id=' . $testId);
            exit;
        }
        
        // Đánh dấu bài thi đang làm
        $_SESSION['test_in_progress_'.$testId] = true;
        
        // Khởi tạo biến để lưu trữ câu trả lời đã lưu
        $savedAnswers = isset($_SESSION['saved_answers_'.$testId]) ? $_SESSION['saved_answers_'.$testId] : [];
        
        // Nếu có session answers, ưu tiên dùng nó
        if (isset($test['saved_answers']) && !empty($test['saved_answers'])) {
            $savedAnswers = $test['saved_answers'];
        }
        
        // Lấy câu hỏi của bài kiểm tra
        $questions = $this->kiemTraModel->getTracNghiemQuestions($testId);
        
        // Nếu không có câu hỏi, khởi tạo mảng trống
        if (empty($questions)) {
            $questions = [];
            $_SESSION['error'] = "Không tìm thấy câu hỏi nào cho bài kiểm tra này.";
        } else {
            // Cập nhật số câu hỏi trong test nếu chưa có
            if (!isset($test['so_cau_hoi']) || $test['so_cau_hoi'] == 0) {
                $test['so_cau_hoi'] = count($questions);
            }
            
            // Đảm bảo mỗi câu hỏi có đầy đủ các field cần thiết
            foreach ($questions as &$question) {
                if (!isset($question['dap_an_a'])) $question['dap_an_a'] = '';
                if (!isset($question['dap_an_b'])) $question['dap_an_b'] = '';
                if (!isset($question['dap_an_c'])) $question['dap_an_c'] = '';
                if (!isset($question['dap_an_d'])) $question['dap_an_d'] = '';
                if (!isset($question['dap_an_e'])) $question['dap_an_e'] = '';
                if (!isset($question['dap_an_f'])) $question['dap_an_f'] = '';
                if (!isset($question['dap_an_g'])) $question['dap_an_g'] = '';
                if (!isset($question['dap_an_h'])) $question['dap_an_h'] = '';
            }
            unset($question); // Xóa tham chiếu
        }
        
        // Hiển thị view làm bài thi
        require_once 'View/KiemTra/lambaithitracnghiem.php';
    }

    /**
     * Làm bài thi tự luận với giao diện mới
     */
    public function lamBaiThiTuLuan($testId = 0) {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['vai_tro']) || $_SESSION['vai_tro'] !== 'sinh_vien') {
            $_SESSION['redirect_after_login'] = "index.php?controller=kiemtra&action=lamBaiThiTuLuan&id=" . $testId;
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        // Đặt múi giờ Việt Nam
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        
        $userId = $_SESSION['user_id'];
        
        if (!$testId) {
            $testId = isset($_GET['id']) ? intval($_GET['id']) : 0;
            if (!$testId) {
                $_SESSION['error'] = "Bài kiểm tra không hợp lệ";
                header('Location: index.php?controller=kiemtra&action=lichSu');
                exit;
            }
        }
        
        try {
            // Lấy thông tin bài kiểm tra
            $test = $this->kiemTraModel->getTuLuanTestById($testId, $userId);
            
            if (!$test) {
                $_SESSION['error'] = "Không tìm thấy bài kiểm tra hoặc bạn không có quyền truy cập";
                header('Location: index.php?controller=kiemtra');
                exit;
            }
            
            // Lấy ID sinh viên từ session
            $sinhVienId = isset($_SESSION['sinh_vien_id']) ? $_SESSION['sinh_vien_id'] : null;
            
            if (!$sinhVienId) {
                // Nếu không có sinh_vien_id trong session, lấy từ database
                $query = "SELECT id FROM sinh_vien WHERE nguoi_dung_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$userId]);
                $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$sinhVien) {
                    throw new Exception("Không tìm thấy thông tin sinh viên");
                }
                
                $sinhVienId = $sinhVien['id'];
                $_SESSION['sinh_vien_id'] = $sinhVienId;
            }
            
            // Kiểm tra xem có phiên đang làm dở không
            $session = $this->kiemTraModel->getTuLuanSession($sinhVienId, $testId);
            
            // Nếu có phiên đang dở, tiếp tục từ phiên đó
            if ($session) {
                // Lấy thời gian còn lại từ phiên
                $timeRemaining = $session['thoi_gian_con_lai'];
                
                // Cập nhật biến timeRemaining trong $test
                $test['thoi_gian_con_lai'] = $timeRemaining;
                
                // Lấy nội dung đã lưu
                $test['saved_content'] = $session['noi_dung'];
                $test['saved_files'] = !empty($session['tep_tin']) ? explode(',', $session['tep_tin']) : [];
                
                // Cập nhật phiên thành 'dang_lam'
                if (method_exists($this->kiemTraModel, 'updateTuLuanSessionStatus')) {
                    $this->kiemTraModel->updateTuLuanSessionStatus($session['id'], 'dang_lam');
                }
            } else {
                // Nếu chưa có phiên, tạo phiên mới
                if (isset($test['thoi_gian_lam']) && $test['thoi_gian_lam'] > 0) {
                    $this->kiemTraModel->createOrUpdateTuLuanSession(
                        $sinhVienId,
                        $testId,
                        $test['thoi_gian_lam'] * 60, // Chuyển phút thành giây
                        "",  // Chưa có nội dung
                        null  // Chưa có file
                    );
                    
                    // Lấy phiên mới tạo để có thể sử dụng thông tin của nó
                    $session = $this->kiemTraModel->getTuLuanSession($sinhVienId, $testId);
                    if ($session) {
                        $test['thoi_gian_con_lai'] = $session['thoi_gian_con_lai'];
                    }
                }
            }
            
            // Kiểm tra các điều kiện làm bài
            
            // Kiểm tra thời gian làm bài
            $now = new DateTime();
            $startTime = !empty($test['thoi_gian_bat_dau']) ? new DateTime($test['thoi_gian_bat_dau']) : $now;
            $endTime = !empty($test['thoi_gian_ket_thuc']) ? new DateTime($test['thoi_gian_ket_thuc']) : null;
            
            // Đặt biến môi trường PHP để đảm bảo hiển thị đúng thời gian
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            
            // Kiểm tra nếu bài thi chưa mở
            if ($now < $startTime) {
                $_SESSION['error'] = "Bài kiểm tra chưa mở. Thời gian hiện tại: " . $now->format('Y-m-d H:i:s') . 
                                   ", Thời gian mở: " . $startTime->format('Y-m-d H:i:s');
                header('Location: index.php?controller=kiemtra');
                exit;
            }
            
            if ($endTime && $now > $endTime) {
                $_SESSION['error'] = "Bài kiểm tra đã đóng. Thời gian hiện tại: " . $now->format('Y-m-d H:i:s') . 
                                   ", Thời gian đóng: " . $endTime->format('Y-m-d H:i:s');
                header('Location: index.php?controller=kiemtra');
                exit;
            }
            
            // Kiểm tra số lần làm bài
            if (isset($test['so_lan_lam']) && $test['so_lan_lam'] > 0 && isset($test['so_lan_nop']) && $test['so_lan_nop'] >= $test['so_lan_lam']) {
                $_SESSION['error'] = "Bạn đã làm bài thi này đủ số lần cho phép";
                header('Location: index.php?controller=kiemtra');
                exit;
            }
            
            // Đánh dấu bài thi đang làm
            $_SESSION['tu_luan_in_progress_'.$testId] = true;
            
            // Chuẩn bị dữ liệu cho view
            $test['bai_thi_id'] = $test['id'];
            $test['ten_bai_thi'] = $test['tieu_de'];
            $test['ten_mon_hoc'] = $test['ten_mon'];
            $test['ngay_bat_dau'] = $test['thoi_gian_bat_dau'];
            $test['ngay_ket_thuc'] = $test['thoi_gian_ket_thuc'];
            $test['thoi_gian_lam_bai'] = $test['thoi_gian_lam'];
            $test['so_lan_nop_toi_da'] = $test['so_lan_lam'];
            
            // Lấy nội dung bài kiểm tra từ cơ sở dữ liệu
            if (!isset($test['noi_dung']) || empty($test['noi_dung'])) {
                // Nếu không có trong $test, truy vấn trực tiếp
                $query = "SELECT noi_dung FROM bai_kiem_tra_tu_luan WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$testId]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($result && isset($result['noi_dung'])) {
                    $test['noi_dung'] = $result['noi_dung'];
                } else {
                    $test['noi_dung'] = '<p>Không có nội dung bài kiểm tra</p>';
                }
            }
            
            // Ghi log để debug
            error_log("LamBaiThiTuLuan - Nội dung bài kiểm tra ID: " . $testId . (isset($test['noi_dung']) ? " có nội dung" : " không có nội dung"));
            
            // Hiển thị view bài thi
            require_once 'View/KiemTra/lambaithituluan.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Có lỗi xảy ra: " . $e->getMessage();
            header('Location: index.php?controller=kiemtra&action=lichSu');
            exit;
        }
    }

    /**
     * Lưu tiến độ làm bài tự luận
     */
    public function saveTuLuanProgress() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['vai_tro']) || $_SESSION['vai_tro'] !== 'sinh_vien') {
            echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập hoặc phiên làm việc đã hết hạn']);
            exit;
        }
        
        // Lấy và validate dữ liệu từ POST
        $testId = isset($_POST['test_id']) ? intval($_POST['test_id']) : 0;
        $content = isset($_POST['content']) ? $_POST['content'] : '';
        $timeRemaining = isset($_POST['time_remaining']) ? intval($_POST['time_remaining']) : 0;
        $status = isset($_POST['status']) ? $_POST['status'] : 'dang_lam';
        
        // Log dữ liệu nhận được
        error_log("saveTuLuanProgress - Data: testId=$testId, timeRemaining=$timeRemaining, status=$status");
        
        if (!$testId) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy bài kiểm tra']);
            exit;
        }
        
        if (!isset($_SESSION['sinh_vien_id'])) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy thông tin sinh viên']);
            exit;
        }
        
        $sinhVienId = $_SESSION['sinh_vien_id'];
        $fileToUpload = null;
        
        // Xử lý file đính kèm nếu có
        if (isset($_FILES['file_dinh_kem']) && !empty($_FILES['file_dinh_kem']['name'][0])) {
            $uploadDir = 'uploads/tu_luan/' . $_SESSION['user_id'] . '/' . $testId . '/temp/';
            
            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $uploadedFiles = [];
            $fileCount = count($_FILES['file_dinh_kem']['name']);
            
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['file_dinh_kem']['error'][$i] == 0) {
                    $fileName = basename($_FILES['file_dinh_kem']['name'][$i]);
                    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
                    $uniqueFileName = uniqid() . '.' . $fileExt;
                    $uploadFile = $uploadDir . $uniqueFileName;
                    
                    $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'txt'];
                    
                    if (!in_array(strtolower($fileExt), $allowedExtensions)) {
                        continue;
                    }
                    
                    if ($_FILES['file_dinh_kem']['size'][$i] > 10 * 1024 * 1024) {
                        continue;
                    }
                    
                    if (move_uploaded_file($_FILES['file_dinh_kem']['tmp_name'][$i], $uploadFile)) {
                        $uploadedFiles[] = $uploadFile;
                    }
                }
            }
            
            if (!empty($uploadedFiles)) {
                $fileToUpload = implode(',', $uploadedFiles);
            }
        }
        
        try {
            // Lưu tiến độ
            $sessionId = $this->kiemTraModel->createOrUpdateTuLuanSession(
                $sinhVienId,
                $testId,
                $timeRemaining,
                $content,
                $fileToUpload
            );
            
            if ($sessionId) {
                echo json_encode(['success' => true, 'message' => 'Đã lưu tiến độ thành công', 'session_id' => $sessionId]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu tiến độ']);
            }
        } catch (Exception $e) {
            error_log("Lỗi lưu tiến độ tự luận: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu tiến độ: ' . $e->getMessage()]);
        }
        exit;
    }

    /**
     * Xóa phiên làm bài trắc nghiệm
     */
    public function xoaPhienTracNghiem() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['vai_tro']) || $_SESSION['vai_tro'] !== 'sinh_vien') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $testId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if (!$testId) {
            $_SESSION['error'] = "Bài kiểm tra không hợp lệ";
            header('Location: index.php?controller=kiemtra&action=lichSu');
            exit;
        }
        
        try {
            // Lấy ID sinh viên từ session
            $sinhVienId = isset($_SESSION['sinh_vien_id']) ? $_SESSION['sinh_vien_id'] : null;
            
            if (!$sinhVienId) {
                // Nếu không có sinh_vien_id trong session, lấy từ database
                $query = "SELECT id FROM sinh_vien WHERE nguoi_dung_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$userId]);
                $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$sinhVien) {
                    throw new Exception("Không tìm thấy thông tin sinh viên");
                }
                
                $sinhVienId = $sinhVien['id'];
            }
            
            // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
            $this->db->beginTransaction();
            
            // 1. Lấy danh sách các bài làm để xóa
            $query = "SELECT blt.id 
                     FROM bai_lam_trac_nghiem blt
                     JOIN sinh_vien_bai_lam_trac_nghiem svbl ON blt.id = svbl.bai_trac_nghiem
                     WHERE svbl.sinh_vien_id = ? AND blt.bai_kiem_tra_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$sinhVienId, $testId]);
            $baiLams = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $deletedSubmissions = 0;
            
            if (!empty($baiLams)) {
                $baiLamIds = array_column($baiLams, 'id');
                $placeholders = str_repeat('?,', count($baiLamIds) - 1) . '?';
                
                // 2. Xóa các bản ghi liên kết trong sinh_vien_bai_lam_trac_nghiem
                $query = "DELETE FROM sinh_vien_bai_lam_trac_nghiem WHERE bai_trac_nghiem IN ($placeholders)";
                $stmt = $this->db->prepare($query);
                $stmt->execute($baiLamIds);
                
                // 3. Xóa các câu trả lời
                $query = "DELETE FROM cau_tra_loi WHERE bai_lam_id IN ($placeholders)";
                $stmt = $this->db->prepare($query);
                $stmt->execute($baiLamIds);
                
                // 4. Xóa các bài làm
                $query = "DELETE FROM bai_lam_trac_nghiem WHERE id IN ($placeholders)";
                $stmt = $this->db->prepare($query);
                $stmt->execute($baiLamIds);
                $deletedSubmissions = $stmt->rowCount();
            }
            
            // 5. Xóa các phiên làm bài đang dở (dùng hàm có sẵn)
            $result = $this->kiemTraModel->deleteTracNghiemSession($sinhVienId, $testId, true);
            
            $this->db->commit();
            
            if ($result || $deletedSubmissions > 0) {
                $_SESSION['success'] = "Đã xóa phiên làm bài và lịch sử bài thi thành công";
                
                // Xóa cả dữ liệu trong session nếu có
                if (isset($_SESSION['saved_answers_'.$testId])) {
                    unset($_SESSION['saved_answers_'.$testId]);
                }
            } else {
                $_SESSION['error'] = "Không tìm thấy phiên làm bài nào để xóa";
            }
        } catch (Exception $e) {
            // Rollback nếu có lỗi
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
        }
        
        header('Location: index.php?controller=kiemtra&action=lichSu');
        exit;
    }
    
    /**
     * Hiển thị chi tiết lịch sử bài thi tự luận
     */
    public function chiTietLichSuTuLuan() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['vai_tro']) || $_SESSION['vai_tro'] !== 'sinh_vien') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        // Thêm dòng này để đặt múi giờ Việt Nam
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        
        $userId = $_SESSION['user_id'];
        $testId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $baiLamId = isset($_GET['bai_lam_id']) ? intval($_GET['bai_lam_id']) : 0;
        
        if (!$testId || !$baiLamId) {
            $_SESSION['error'] = "Thông tin bài làm không hợp lệ";
            header('Location: index.php?controller=kiemtra&action=lichSu');
            exit;
        }
        
        try {
            // Lấy thông tin bài kiểm tra
            $test = $this->kiemTraModel->getTuLuanTestById($testId, $userId);
            
            if (!$test) {
                $_SESSION['error'] = "Không tìm thấy bài kiểm tra";
                header('Location: index.php?controller=kiemtra&action=lichSu');
                exit;
            }
            
            // Lấy thông tin bài làm
            $baiLam = $this->kiemTraModel->getTuLuanSubmission($userId, $baiLamId, true);
            
            if (!$baiLam) {
                $_SESSION['error'] = "Không tìm thấy bài làm";
                header('Location: index.php?controller=kiemtra&action=lichSu');
                exit;
            }
            
            // Lấy nội dung đề bài từ bảng bai_kiem_tra_tu_luan
            $query = "SELECT noi_dung FROM bai_kiem_tra_tu_luan WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$testId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['noi_dung'])) {
                $test['noi_dung'] = $result['noi_dung'];
            }
            
            // Hiển thị view chi tiết bài làm
            require_once 'View/KiemTra/chitietlichsutuluan.php';
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Có lỗi xảy ra: " . $e->getMessage();
            header('Location: index.php?controller=kiemtra&action=lichSu');
            exit;
        }
    }
    
    /**
     * Lưu tiến độ làm bài trắc nghiệm (AJAX)
     */
    public function saveTracNghiemProgress() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['vai_tro']) || $_SESSION['vai_tro'] !== 'sinh_vien') {
            echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập hoặc phiên làm việc đã hết hạn']);
            exit;
        }
        
        // Lấy và validate dữ liệu từ POST
        $testId = isset($_POST['test_id']) ? intval($_POST['test_id']) : 0;
        $answers = isset($_POST['answers']) ? $_POST['answers'] : [];
        $timeRemaining = isset($_POST['time_remaining']) ? intval($_POST['time_remaining']) : 0;
        $status = isset($_POST['status']) ? $_POST['status'] : 'dang_lam';
        
        // Log dữ liệu nhận được để debug
        error_log("saveTracNghiemProgress - Data: testId=$testId, status=$status, timeRemaining=$timeRemaining, answers count=" . count($answers));
        
        if (!$testId) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy bài kiểm tra']);
            exit;
        }
        
        if (!isset($_SESSION['sinh_vien_id'])) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy thông tin sinh viên']);
            exit;
        }
        
        $sinhVienId = $_SESSION['sinh_vien_id'];
        
        try {
            // Lưu tiến độ
            $sessionId = $this->kiemTraModel->createOrUpdateTracNghiemSession(
                $sinhVienId,
                $testId,
                $timeRemaining,
                $answers
            );
            
            if ($sessionId) {
                echo json_encode(['success' => true, 'message' => 'Đã lưu tiến độ thành công', 'session_id' => $sessionId]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu tiến độ']);
            }
        } catch (Exception $e) {
            error_log("Lỗi lưu tiến độ trắc nghiệm: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu tiến độ: ' . $e->getMessage()]);
        }
        exit;
    }
    
}
?> 