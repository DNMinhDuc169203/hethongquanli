<?php
require_once 'Model/CauHoiModel.php';
require_once 'Model/GiaoVienModel.php';

class CauHoiController {
    private $cauHoiModel;
    private $giaoVienModel;
    
    public function __construct() {
        $this->cauHoiModel = new CauHoiModel();
        $this->giaoVienModel = new GiaoVienModel();
        session_start();
        // Ngăn chặn người dùng chưa đăng nhập truy cập
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
    }
    
    private function getGiaoVienId() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        $giao_vien = $this->giaoVienModel->getGiaoVienByNguoiDungId($_SESSION['user_id']);
        if (!$giao_vien) {
            die('Không tìm thấy thông tin giáo viên');
        }
        
        return $giao_vien['id'];
    }
    
    public function index() {
        // Hiển thị danh sách môn học của giáo viên đăng nhập
        $giao_vien_id = $this->getGiaoVienId();
        $danhSachMonHoc = $this->cauHoiModel->getMonHocByGiaoVien($giao_vien_id);
        include 'View/CauHoi/index.php';
    }
    
    public function viewBySubject($mon_hoc_id) {
        // Hiển thị các chủ đề của môn học
        $monHoc = null;
        $danhSachMonHoc = $this->cauHoiModel->getAllMonHoc();
        foreach ($danhSachMonHoc as $mh) {
            if ($mh['id'] == $mon_hoc_id) {
                $monHoc = $mh;
                break;
            }
        }
        
        if (!$monHoc) {
            header('Location: index.php?controller=cauhoi&error=subject_not_found');
            exit;
        }
        
        $danhSachChuDe = $this->cauHoiModel->getChuDeByMonHoc($mon_hoc_id);
        include 'View/CauHoi/danhsachchude.php';
    }
    
    public function viewByTopic($chu_de_id) {
        // Hiển thị các câu hỏi của chủ đề
        $chuDe = null; 
        $danhSachChuDe = $this->cauHoiModel->getAllChuDe();
        foreach ($danhSachChuDe as $cd) {
            if ($cd['id'] == $chu_de_id) {
                $chuDe = $cd;
                break;
            }
        }
        
        if (!$chuDe) {
            header('Location: index.php?controller=cauhoi&error=topic_not_found');
            exit;
        }
        
        // Lấy thông tin môn học của chủ đề này
        $monHoc = null;
        $danhSachMonHoc = $this->cauHoiModel->getAllMonHoc();
        foreach ($danhSachMonHoc as $mh) {
            if ($mh['id'] == $chuDe['mon_hoc_id']) {
                $monHoc = $mh;
                break;
            }
        }
        
        $danhSachCauHoi = $this->cauHoiModel->getCauHoiByChude($chu_de_id);
        include 'View/CauHoi/danhsachcauhoi.php';
    }
    
    public function create() {
        $giao_vien_id = $this->getGiaoVienId();
        
        // Lấy món học id từ tham số URL hoặc từ form
        $mon_hoc_id = $_GET['mon_hoc_id'] ?? $_POST['mon_hoc_id'] ?? null;
        $chu_de_id = $_GET['chu_de_id'] ?? $_POST['chu_de_id'] ?? null;
        
        // Lấy danh sách môn học của giáo viên
        $danhSachMonHoc = $this->cauHoiModel->getMonHocByGiaoVien($giao_vien_id);
        
        // Nếu có mon_hoc_id, lấy danh sách chủ đề của môn học đó
        $danhSachChuDe = [];
        if ($mon_hoc_id) {
            $danhSachChuDe = $this->cauHoiModel->getChuDeByMonHoc($mon_hoc_id);
        } else {
            $danhSachChuDe = $this->cauHoiModel->getAllChuDe();
        }
        
        // Lấy thông tin chủ đề nếu có
        $chuDe = null;
        if ($chu_de_id) {
            foreach ($danhSachChuDe as $cd) {
                if ($cd['id'] == $chu_de_id) {
                    $chuDe = $cd;
                    break;
                }
            }
        }
        
        // Lấy thông tin môn học nếu có chủ đề
        $monHoc = null;
        if ($chuDe && $chuDe['mon_hoc_id']) {
            foreach ($danhSachMonHoc as $mh) {
                if ($mh['id'] == $chuDe['mon_hoc_id']) {
                    $monHoc = $mh;
                    break;
                }
            }
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tieu_de = $_POST['tieu_de'] ?? 'Câu hỏi mới';
            $chu_de_id = $_POST['chu_de_id'] ?? null;
            
            if (!$chu_de_id) {
                $error = "Vui lòng chọn chủ đề cho câu hỏi";
            } else {
                // Kiểm tra chủ đề có tồn tại không
                $chude_exists = false;
                foreach ($danhSachChuDe as $cd) {
                    if ($cd['id'] == $chu_de_id) {
                        $chude_exists = true;
                        break;
                    }
                }
                
                if (!$chude_exists) {
                    error_log("Chủ đề không tồn tại: ID = " . $chu_de_id);
                    $error = "Chủ đề không tồn tại hoặc đã bị xóa";
                } else {
                    // Validate question content
                    if (empty($_POST['cau_hoi']) || !is_array($_POST['cau_hoi'])) {
                        $error = "Vui lòng nhập nội dung câu hỏi";
                    } else {
                        foreach ($_POST['cau_hoi'] as $questionIndex => $noi_dung) {
                            if (empty(trim($noi_dung))) {
                                $error = "Nội dung câu hỏi không được để trống";
                                break;
                            }
                            if (strlen(trim($noi_dung)) > 1000) {
                                $error = "Nội dung câu hỏi không được vượt quá 1000 ký tự";
                                break;
                            }
                            
                            // Validate answers
                            if (empty($_POST['dap_an'][$questionIndex]) || !is_array($_POST['dap_an'][$questionIndex])) {
                                $error = "Vui lòng nhập đáp án cho câu hỏi";
                                break;
                            }
                            
                            $answerCount = 0;
                            foreach ($_POST['dap_an'][$questionIndex] as $answerIndex => $dap_an) {
                                if (!empty(trim($dap_an))) {
                                    $answerCount++;
                                    if (strlen(trim($dap_an)) > 500) {
                                        $error = "Nội dung đáp án không được vượt quá 500 ký tự";
                                        break 2;
                                    }
                                }
                            }
                            
                            if ($answerCount < 2) {
                                $error = "Mỗi câu hỏi cần ít nhất 2 đáp án";
                                break;
                            }
                            
                            if ($answerCount > 6) {
                                $error = "Mỗi câu hỏi không được có quá 6 đáp án";
                                break;
                            }
                            
                            // Check if at least one correct answer is selected
                            if (empty($_POST['dap_an_dung'][$questionIndex]) || !is_array($_POST['dap_an_dung'][$questionIndex])) {
                                $error = "Vui lòng chọn ít nhất một đáp án đúng";
                                break;
                            }
                        }
                    }
                }
                
                if (!isset($error)) {
                    $success = true;
                    $error_messages = [];
                    
                    // Xử lý nhiều câu hỏi
                    if (isset($_POST['cau_hoi']) && is_array($_POST['cau_hoi'])) {
                        foreach ($_POST['cau_hoi'] as $questionIndex => $noi_dung) {
                            if (!empty($noi_dung)) {
                                // Tạo câu hỏi với chủ đề từ đầu
                                $cau_hoi_id = $this->cauHoiModel->createCauHoi($giao_vien_id, $chu_de_id, $noi_dung, $tieu_de);
                                
                                if ($cau_hoi_id) {
                                    // Xử lý đáp án cho câu hỏi này
                                    if (isset($_POST['dap_an'][$questionIndex]) && is_array($_POST['dap_an'][$questionIndex])) {
                                        $dap_an_list = $_POST['dap_an'][$questionIndex];
                                        $dap_an_dung_list = isset($_POST['dap_an_dung'][$questionIndex]) ? $_POST['dap_an_dung'][$questionIndex] : [];
                                        
                                        foreach ($dap_an_list as $answerIndex => $dap_an) {
                                            if (!empty($dap_an)) {
                                                // Kiểm tra xem đáp án này có phải là đáp án đúng không
                                                $dung_hay_sai = in_array($answerIndex, $dap_an_dung_list) ? 1 : 0;
                                                $result = $this->cauHoiModel->createDapAn($cau_hoi_id, $dap_an, $dung_hay_sai);
                                                if (!$result) {
                                                    $success = false;
                                                    $error_messages[] = "Không thể lưu đáp án cho câu hỏi #" . ($questionIndex + 1);
                                                    // Ghi log chi tiết hơn về lỗi
                                                    error_log("Không thể lưu đáp án cho câu hỏi #" . ($questionIndex + 1) . 
                                                             " - ID giáo viên: " . $giao_vien_id .
                                                             " - ID chủ đề: " . $chu_de_id);
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $success = false;
                                    $error_messages[] = "Không thể tạo câu hỏi #" . ($questionIndex + 1);
                                    // Ghi log chi tiết hơn về lỗi
                                    error_log("Không thể tạo câu hỏi #" . ($questionIndex + 1) . 
                                             " - ID giáo viên: " . $giao_vien_id .
                                             " - ID chủ đề: " . $chu_de_id);
                                }
                            }
                        }
                        
                        if ($success) {
                            // Chuyển về trang danh sách câu hỏi của chủ đề
                            header('Location: index.php?controller=cauhoi&action=viewByTopic&id=' . $chu_de_id . '&success=created');
                            exit;
                        } else {
                            // Hiển thị lỗi nếu có
                            $error = "Có lỗi xảy ra khi tạo câu hỏi: " . implode(", ", $error_messages);
                        }
                    }
                }
            }
        }
        
        include 'View/CauHoi/taocauhoi.php';
    }
    
    public function view($id) {
        // Lấy thông tin câu hỏi
        $cauHoi = $this->cauHoiModel->getCauHoiByID($id);
        
        if (!$cauHoi) {
            echo "Không tìm thấy câu hỏi";
            return;
        }
        
        // Lấy danh sách đáp án
        $dapAn = $this->cauHoiModel->getDapAnByCauHoi($id);
        
        // Lấy thông tin về chủ đề của câu hỏi
        $allChuDe = $this->cauHoiModel->getAllChuDe();
        $chuDe = null;
        $chu_de_id = null;
        
        // Lấy chủ đề của câu hỏi từ bảng liên kết
        $cauHoiChuDe = $this->cauHoiModel->getChuDeByCauHoi($id);
        if (!empty($cauHoiChuDe)) {
            // Kiểm tra xem kết quả trả về có phải là một mảng và có chứa khóa 'chu_de_id' không
            if (is_array($cauHoiChuDe) && isset($cauHoiChuDe[0]) && isset($cauHoiChuDe[0]['chu_de_id'])) {
                $chu_de_id = $cauHoiChuDe[0]['chu_de_id'];
            } else if (is_array($cauHoiChuDe) && isset($cauHoiChuDe['chu_de_id'])) {
                $chu_de_id = $cauHoiChuDe['chu_de_id'];
            } else {
                $chu_de_id = null;
            }
            
            if ($chu_de_id) {
                foreach ($allChuDe as $cd) {
                    if ($cd['id'] == $chu_de_id) {
                        $chuDe = $cd;
                        break;
                    }
                }
            }
        }
        
        // Lấy thông tin môn học nếu có chủ đề
        $monHoc = null;
        if ($chuDe) {
            $danhSachMonHoc = $this->cauHoiModel->getAllMonHoc();
            foreach ($danhSachMonHoc as $mh) {
                if ($mh['id'] == $chuDe['mon_hoc_id']) {
                    $monHoc = $mh;
                    break;
                }
            }
        }
        
        // Kiểm tra xem có nhiều đáp án đúng không
        $countCorrect = 0;
        foreach ($dapAn as $da) {
            if ($da['dung_hay_sai']) {
                $countCorrect++;
            }
        }
        $cauHoi['multiple_correct'] = ($countCorrect > 1);
        
        // Thêm tiêu đề như là chu_de
        $cauHoi['chu_de'] = $cauHoi['tieu_de'];
        
        // Lấy số thứ tự câu hỏi (nếu có)
        $questionNumber = isset($_GET['question_number']) ? $_GET['question_number'] : 1;
        
        // Tạo danh sách câu hỏi với đáp án
        $danhSachCauHoi = [
            [
                'noi_dung' => $cauHoi['noi_dung'],
                'multiple_correct' => $cauHoi['multiple_correct'],
                'question_number' => $questionNumber,
                'dap_an' => array_map(function($da) {
                    return [
                        'noi_dung' => $da['dap_an_cua_trac_nghiem'],
                        'is_correct' => (bool)$da['dung_hay_sai']
                    ];
                }, $dapAn)
            ]
        ];
        
        include 'View/CauHoi/chitietcauhoi.php';
    }
    
    public function edit($id) {
        // Lấy thông tin câu hỏi
        $cauHoi = $this->cauHoiModel->getCauHoiByID($id);
        
        if (!$cauHoi) {
            echo "Không tìm thấy câu hỏi";
            return;
        }
        
        // Lấy danh sách đáp án
        $dapAn = $this->cauHoiModel->getDapAnByCauHoi($id);
        
        // Lấy thông tin về chủ đề của câu hỏi
        $allChuDe = $this->cauHoiModel->getAllChuDe();
        $chuDe = null;
        $chu_de_id = null;
        
        // Lấy chủ đề của câu hỏi từ bảng liên kết
        $cauHoiChuDe = $this->cauHoiModel->getChuDeByCauHoi($id);
        if (!empty($cauHoiChuDe)) {
            // Kiểm tra xem kết quả trả về có phải là một mảng và có chứa khóa 'chu_de_id' không
            if (is_array($cauHoiChuDe) && isset($cauHoiChuDe[0]) && isset($cauHoiChuDe[0]['chu_de_id'])) {
                $chu_de_id = $cauHoiChuDe[0]['chu_de_id'];
            } else if (is_array($cauHoiChuDe) && isset($cauHoiChuDe['chu_de_id'])) {
                $chu_de_id = $cauHoiChuDe['chu_de_id'];
            } else {
                $chu_de_id = null;
            }
            
            if ($chu_de_id) {
                foreach ($allChuDe as $cd) {
                    if ($cd['id'] == $chu_de_id) {
                        $chuDe = $cd;
                        break;
                    }
                }
            }
        }
        
        // Lấy thông tin môn học nếu có chủ đề
        $monHoc = null;
        if ($chuDe) {
            $danhSachMonHoc = $this->cauHoiModel->getAllMonHoc();
            foreach ($danhSachMonHoc as $mh) {
                if ($mh['id'] == $chuDe['mon_hoc_id']) {
                    $monHoc = $mh;
                    break;
                }
            }
        }
        
        // Lấy danh sách môn học và chủ đề cho form
        $danhSachMonHoc = $this->cauHoiModel->getAllMonHoc();
        $danhSachChuDe = $this->cauHoiModel->getAllChuDe();
        
        // Xử lý form submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $noi_dung = $_POST['noi_dung'] ?? '';
            $tieu_de = $_POST['tieu_de'] ?? '';
            $chu_de_id = $_POST['chu_de_id'] ?? null;
            
            // Cập nhật câu hỏi
            $this->cauHoiModel->updateCauHoi($id, $noi_dung, $tieu_de);
            
            // Cập nhật chủ đề nếu có
            if ($chu_de_id) {
                $this->cauHoiModel->assignCauHoiToChude($id, $chu_de_id);
            }
            
            // Xử lý đáp án
            if (isset($_POST['dap_an']) && is_array($_POST['dap_an'])) {
                // Xóa tất cả đáp án cũ
                $this->cauHoiModel->deleteDapAnByCauHoi($id);
                
                // Thêm đáp án mới
                $dap_an_list = $_POST['dap_an'];
                $dap_an_dung_list = isset($_POST['dap_an_dung']) ? $_POST['dap_an_dung'] : [];
                
                // Biến để kiểm tra có thay đổi đáp án không
                $dapAnChanged = false;
                
                foreach ($dap_an_list as $answerIndex => $dap_an) {
                    if (!empty($dap_an)) {
                        // Kiểm tra xem đáp án này có phải là đáp án đúng không
                        $dung_hay_sai = in_array($answerIndex, $dap_an_dung_list) ? 1 : 0;
                        $this->cauHoiModel->createDapAn($id, $dap_an, $dung_hay_sai);
                        $dapAnChanged = true;
                    }
                }
                
                // Nếu có thay đổi đáp án, cập nhật ngày cập nhật của câu hỏi
                if ($dapAnChanged) {
                    $this->cauHoiModel->updateCauHoiTime($id);
                }
            }
            
            // Chuyển hướng về trang chi tiết
            header('Location: index.php?controller=cauhoi&action=view&id=' . $id);
            exit;
        }
        
        include 'View/CauHoi/suacauhoi.php';
    }
    
    public function delete($id) {
        // Kiểm tra id
        if (!$id) {
            header('Location: index.php?controller=cauhoi&error=invalid_id');
            exit;
        }
        
        // Lấy thông tin câu hỏi để kiểm tra tồn tại
        $cauHoi = $this->cauHoiModel->getCauHoiByID($id);
        
        if (!$cauHoi) {
            header('Location: index.php?controller=cauhoi&error=not_found');
            exit;
        }
        
        // Kiểm tra quyền: chỉ cho phép giáo viên xóa câu hỏi của chính họ
        $giao_vien_id = $this->getGiaoVienId();
        if ($cauHoi['giao_vien_id'] != $giao_vien_id) {
            header('Location: index.php?controller=cauhoi&error=permission_denied');
            exit;
        }
        
        // Lấy thông tin về chủ đề của câu hỏi
        $chu_de_id = null;
        $cauHoiChuDe = $this->cauHoiModel->getChuDeByCauHoi($id);
        if (!empty($cauHoiChuDe)) {
            // Kiểm tra xem kết quả trả về có phải là một mảng và có chứa khóa 'chu_de_id' không
            if (is_array($cauHoiChuDe) && isset($cauHoiChuDe[0]) && isset($cauHoiChuDe[0]['chu_de_id'])) {
                $chu_de_id = $cauHoiChuDe[0]['chu_de_id'];
            } else if (is_array($cauHoiChuDe) && isset($cauHoiChuDe['chu_de_id'])) {
                $chu_de_id = $cauHoiChuDe['chu_de_id'];
            } else {
                $chu_de_id = null;
            }
        }
        
        // Tiến hành xóa câu hỏi
        $deleted = $this->cauHoiModel->deleteCauHoi($id);
        
        if ($deleted) {
            // Nếu biết chủ đề, chuyển về trang danh sách câu hỏi của chủ đề đó
            if ($chu_de_id) {
                header('Location: index.php?controller=cauhoi&action=viewByTopic&id=' . $chu_de_id . '&success=deleted');
            } else {
                header('Location: index.php?controller=cauhoi&success=deleted');
            }
        } else {
            if ($chu_de_id) {
                header('Location: index.php?controller=cauhoi&action=viewByTopic&id=' . $chu_de_id . '&error=delete_failed');
            } else {
                header('Location: index.php?controller=cauhoi&error=delete_failed');
            }
        }
        exit;
    }
    
    // Phương thức AJAX để lấy chủ đề theo môn học
    public function getTopicsBySubject() {
        $mon_hoc_id = $_GET['mon_hoc_id'] ?? null;
        
        if (!$mon_hoc_id) {
            echo json_encode([]);
            return;
        }
        
        $danhSachChuDe = $this->cauHoiModel->getChuDeByMonHoc($mon_hoc_id);
        
        // Trả về dữ liệu dạng JSON
        header('Content-Type: application/json');
        echo json_encode($danhSachChuDe);
    }
    
    public function createSubject() {
        $giao_vien_id = $this->getGiaoVienId();
        $error = null;
        
        // Lấy danh sách môn học của giáo viên hiện tại thay vì tất cả
        $danhSachMonHoc = $this->cauHoiModel->getMonHocByGiaoVien($giao_vien_id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ma_mon = $_POST['ma_mon'] ?? '';
            $ten_mon = $_POST['ten_mon'] ?? '';
            $mo_ta = $_POST['mo_ta'] ?? '';
            $hoc_ky = $_POST['hoc_ky'] ?? '';
            $nam_hoc = $_POST['nam_hoc'] ?? '';
            $so_tin_chi = $_POST['so_tin_chi'] ?? 0;
            
            if (empty($ma_mon)) {
                $error = "Vui lòng nhập mã môn học";
            } else if (empty($ten_mon)) {
                $error = "Vui lòng nhập tên môn học";
            } else {
                $result = $this->cauHoiModel->createMonHoc($giao_vien_id, $ma_mon, $ten_mon, $mo_ta, $hoc_ky, $nam_hoc, $so_tin_chi);
                
                if (isset($result['success']) && $result['success']) {
                    header('Location: index.php?controller=cauhoi&success=subject_created');
                    exit;
                } else {
                    $error = $result['message'] ?? "Không thể tạo môn học";
                }
            }
        }
        
        include 'View/CauHoi/taomonhoc.php';
    }
    
    public function createTopic() {
        $giao_vien_id = $this->getGiaoVienId();
        
        // Lấy môn học id từ tham số URL hoặc từ form
        $mon_hoc_id = $_GET['mon_hoc_id'] ?? $_POST['mon_hoc_id'] ?? null;
        
        if (!$mon_hoc_id) {
            header('Location: index.php?controller=cauhoi&error=subject_required');
            exit;
        }
        
        // Lấy thông tin môn học
        $monHoc = null;
        $danhSachMonHoc = $this->cauHoiModel->getAllMonHoc();
        foreach ($danhSachMonHoc as $mh) {
            if ($mh['id'] == $mon_hoc_id) {
                $monHoc = $mh;
                break;
            }
        }
        
        if (!$monHoc) {
            header('Location: index.php?controller=cauhoi&error=subject_not_found');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ten_chu_de = $_POST['ten_chu_de'] ?? '';
            
            if (!empty($ten_chu_de)) {
                $chu_de_id = $this->cauHoiModel->createChuDe($mon_hoc_id, $ten_chu_de);
                
                if ($chu_de_id) {
                    header('Location: index.php?controller=cauhoi&action=viewBySubject&id=' . $mon_hoc_id . '&success=topic_created');
                    exit;
                }
            }
        }
        
        include 'View/CauHoi/taochude.php';
    }
    
    public function deleteSubject($id) {
        // Lấy ID giáo viên hiện tại
        $giao_vien_id = $this->getGiaoVienId();
        
        // Kiểm tra ID
        if (!$id || !is_numeric($id)) {
            header('Location: index.php?controller=cauhoi&error=invalid_id');
            exit;
        }
        
        // Khởi tạo model
        $cauHoiModel = new CauHoiModel();
        
        // Lấy thông tin môn học
        $monHoc = $cauHoiModel->getAllMonHoc();
        $monHoc = array_filter($monHoc, function($mh) use ($id) {
            return $mh['id'] == $id;
        });
        
        // Kiểm tra môn học tồn tại
        if (empty($monHoc)) {
            header('Location: index.php?controller=cauhoi&error=subject_not_found');
            exit;
        }
        
        $monHoc = array_shift($monHoc);
        
        // Xóa môn học
        $result = $cauHoiModel->deleteMonHoc($id);
        
        if ($result) {
            header('Location: index.php?controller=cauhoi&success=subject_deleted');
        } else {
            // Thêm thông báo lỗi chi tiết
            header('Location: index.php?controller=cauhoi&error=subject_has_topics');
        }
        exit;
    }
    
    public function deleteTopic($id) {
        // Lấy ID giáo viên hiện tại
        $giao_vien_id = $this->getGiaoVienId();
        
        // Kiểm tra ID
        if (!$id || !is_numeric($id)) {
            header('Location: index.php?controller=cauhoi&error=invalid_id');
            exit;
        }
        
        // Khởi tạo model
        $cauHoiModel = new CauHoiModel();
        
        // Lấy thông tin chủ đề
        $chuDe = $cauHoiModel->getAllChuDe();
        $chuDe = array_filter($chuDe, function($cd) use ($id) {
            return $cd['id'] == $id;
        });
        
        // Kiểm tra chủ đề tồn tại
        if (empty($chuDe)) {
            header('Location: index.php?controller=cauhoi&error=topic_not_found');
            exit;
        }
        
        $chuDe = array_shift($chuDe);
        
        // Xóa chủ đề
        $result = $cauHoiModel->deleteChuDe($id);
        
        if ($result) {
            header('Location: index.php?controller=cauhoi&action=viewBySubject&id=' . $chuDe['mon_hoc_id'] . '&success=topic_deleted');
        } else {
            // Thêm thông báo lỗi chi tiết
            header('Location: index.php?controller=cauhoi&action=viewBySubject&id=' . $chuDe['mon_hoc_id'] . '&error=topic_has_questions');
        }
        exit;
    }
    
    // Thêm hàm để lấy tất cả câu hỏi cho API
    public function getAll() {
        $giao_vien_id = null;
        
        // Lấy thông tin giáo viên từ người dùng đăng nhập
        if (isset($_SESSION['user_id'])) {
            $gvInfo = $this->giaoVienModel->getGiaoVienByNguoiDungId($_SESSION['user_id']);
            if ($gvInfo) {
                $giao_vien_id = $gvInfo['id'];
            }
        }
        
        if (!$giao_vien_id) {
            echo json_encode([]);
            exit;
        }
        
        // Lấy bài thi ID nếu có
        $bai_thi_id = isset($_GET['bai_thi_id']) ? $_GET['bai_thi_id'] : null;
        
        // Lấy tất cả câu hỏi của giáo viên
        $questions = $this->cauHoiModel->getCauHoiByGiaoVien($giao_vien_id);
        
        // Đánh dấu câu hỏi đã được chọn nếu có bài thi ID
        if ($bai_thi_id) {
            // Cần import BaiThiModel
            require_once 'Model/BaiThiModel.php';
            $baiThiModel = new BaiThiModel();
            
            $cauHoiTrongBaiThi = $baiThiModel->getCauHoiTrongBaiThi($bai_thi_id);
            $cauHoiDaChon = [];
            
            foreach ($cauHoiTrongBaiThi as $ch) {
                $cauHoiDaChon[$ch['id']] = $ch['chu_de_id'];
            }
            
            foreach ($questions as &$question) {
                // Lấy thông tin chủ đề gốc
                $chuDeID = $this->cauHoiModel->getChuDeIDForQues($question['id']);
                $question['original_chu_de_id'] = $chuDeID;
                $question['original_chu_de_ten'] = '';
                
                if ($chuDeID) {
                    $topic = $this->cauHoiModel->getChuDeById($chuDeID);
                    if ($topic) {
                        $question['original_chu_de_ten'] = $topic['ten_chu_de'];
                    }
                }
                
                // Đánh dấu nếu câu hỏi đã được chọn
                if (isset($cauHoiDaChon[$question['id']])) {
                    $question['is_selected'] = true;
                    $question['selected_topic'] = $cauHoiDaChon[$question['id']];
                } else {
                    $question['is_selected'] = false;
                }
            }
        } else {
            foreach ($questions as &$question) {
                $chuDeID = $this->cauHoiModel->getChuDeIDForQues($question['id']);
                $question['original_chu_de_id'] = $chuDeID;
                $question['original_chu_de_ten'] = '';
                
                if ($chuDeID) {
                    $topic = $this->cauHoiModel->getChuDeById($chuDeID);
                    if ($topic) {
                        $question['original_chu_de_ten'] = $topic['ten_chu_de'];
                    }
                }
                
                $question['is_selected'] = false;
            }
        }
        
        // Trả về dữ liệu dạng JSON
        header('Content-Type: application/json');
        echo json_encode($questions);
        exit;
    }
}
?>