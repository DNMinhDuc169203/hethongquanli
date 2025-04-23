<?php
require_once 'Model/BaiThiModel.php';
require_once 'Model/GiaoVienModel.php';
require_once 'Model/CauHoiModel.php';

class BaiThiController {
    private $baiThiModel;
    private $giaoVienModel;
    private $cauHoiModel;
    
    public function __construct() {
        $this->baiThiModel = new BaiThiModel();
        $this->giaoVienModel = new GiaoVienModel();
        $this->cauHoiModel = new CauHoiModel();
        
        session_start();
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
        $giao_vien_id = $this->getGiaoVienId();
        
        // Thiết lập múi giờ Việt Nam
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        
        // Lấy danh sách bài thi trắc nghiệm với thông tin môn học và lớp học
        $danhSachBaiThiTracNghiem = $this->baiThiModel->getBaiThiTracNghiemByGiaoVien($giao_vien_id);
        
        // Khởi tạo BaiThiTuLuanModel để lấy danh sách bài thi tự luận
        require_once 'Model/BaiThiTuLuanModel.php';
        $baiThiTuLuanModel = new BaiThiTuLuanModel();
        $danhSachBaiThiTuLuan = $baiThiTuLuanModel->getBaiThiTuLuanByGiaoVien($giao_vien_id);
        
        include 'View/BaiThi/index.php';
    }
    
    public function create() {
        $giao_vien_id = $this->getGiaoVienId();
        
        // Lấy danh sách lớp học của giáo viên
        $danhSachLopHoc = $this->giaoVienModel->getLopHocByGiaoVien($giao_vien_id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra riêng cấu trúc chu_de trong POST
            if (isset($_POST['chu_de']) && is_array($_POST['chu_de'])) {
                foreach ($_POST['chu_de'] as $index => $chu_de) {
                    error_log("Chủ đề index $index - ID: " . ($chu_de['id'] ?? 'không có'));
                    
                    // Kiểm tra cau_hoi_ids
                    if (isset($chu_de['cau_hoi_ids']) && is_array($chu_de['cau_hoi_ids'])) {
                        error_log("   Số lượng câu hỏi được chọn: " . count($chu_de['cau_hoi_ids']));
                        foreach ($chu_de['cau_hoi_ids'] as $key => $cau_hoi_id) {
                            error_log("   Câu hỏi thứ $key - ID: $cau_hoi_id");
                        }
                    } else {
                        error_log("   Không có câu hỏi nào được chọn");
                    }
                }
            } else {
                error_log("Không có dữ liệu chủ đề trong POST");
            }
            
            $lop_hoc_id = $_POST['lop_hoc_id'] ?? '';
            $mon_hoc_id = $_POST['mon_hoc_id'] ?? '';
            $tieu_de = $_POST['tieu_de'] ?? '';
            $mo_ta = $_POST['mo_ta'] ?? '';
            $tron_cau_hoi = isset($_POST['tron_cau_hoi']) ? 1 : 0;
            $tron_dap_an = isset($_POST['tron_dap_an']) ? 1 : 0;
            $hien_thi_dap_an = isset($_POST['hien_thi_dap_an']) ? 1 : 0;
            $so_lan_lam = $_POST['so_lan_lam'] ?? 1;
            
            // Xử lý chế độ bài thi: trên lớp hoặc bài tập về nhà
            $che_do_thi = $_POST['che_do_thi'] ?? 'tren_lop';
            
            if ($che_do_thi == 'tren_lop') {
                // Chế độ làm bài trên lớp - chỉ cần thời gian làm bài
                $thoi_gian_lam = $_POST['thoi_gian_lam'] ?? null;
                // Thiết lập múi giờ Việt Nam
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $thoi_gian_bat_dau = date('Y-m-d H:i:s'); // Sử dụng thời gian hiện tại
                $thoi_gian_ket_thuc = null; // Không có thời gian kết thúc cho bài thi trên lớp
            } else {
                // Chế độ bài tập về nhà - cần ngày bắt đầu và kết thúc
                $thoi_gian_lam = null;
                $thoi_gian_bat_dau = $_POST['thoi_gian_bat_dau'] ?? null;
                $thoi_gian_ket_thuc = $_POST['thoi_gian_ket_thuc'] ?? null;
            }
            
            // Xử lý các chủ đề và câu hỏi
            $chu_de_data = isset($_POST['chu_de']) ? $_POST['chu_de'] : [];
            
            // Tạo bài thi mới
            $bai_thi_id = $this->baiThiModel->createBaiThi(
                $lop_hoc_id,
                $tieu_de,
                $mo_ta,
                $thoi_gian_lam,
                $thoi_gian_bat_dau,
                $thoi_gian_ket_thuc,
                $tron_cau_hoi,
                $tron_dap_an,
                $hien_thi_dap_an,
                $so_lan_lam,
                $mon_hoc_id
            );
            
            if ($bai_thi_id) {
                // Xử lý chủ đề và câu hỏi
                foreach ($chu_de_data as $chu_de) {
                    $chu_de_id = $chu_de['id'];
                    $cau_hoi_ids = isset($chu_de['cau_hoi_ids']) ? $chu_de['cau_hoi_ids'] : [];
                    
                    // Kiểm tra và thêm chủ đề vào bài thi
                    if ($chu_de_id && is_numeric($chu_de_id)) {
                        $this->baiThiModel->addChuDeToBaiThi($bai_thi_id, $chu_de_id);
                        
                        // Thêm câu hỏi vào bài thi với chủ đề gốc của nó
                        foreach ($cau_hoi_ids as $cau_hoi_id) {
                            // Lấy chủ đề gốc của câu hỏi từ bảng cau_hoi_chu_de
                            $chuDeGoc = $this->cauHoiModel->getChuDeByCauHoi($cau_hoi_id);
                            
                            // Sử dụng chủ đề gốc nếu có, nếu không sử dụng chủ đề hiện tại
                            $chuDeGocId = null;
                            if (!empty($chuDeGoc) && isset($chuDeGoc[0]['chu_de_id'])) {
                                $chuDeGocId = $chuDeGoc[0]['chu_de_id'];
                            }
                            
                            // Thêm câu hỏi với chủ đề gốc của nó
                            $this->baiThiModel->addCauHoiToBaiThi($bai_thi_id, $cau_hoi_id, $chuDeGocId);
                        }
                    }
                }
                
                // header('Location: index.php?controller=baithi&action=view&id=' . $bai_thi_id);
                header('Location: index.php?controller=baithi');
                exit;
            }
        }
        
        // Chuẩn bị dữ liệu cho AJAX
        if (isset($_GET['lop_hoc_id'])) {
            $lop_hoc_id = $_GET['lop_hoc_id'];
            $danhSachMonHoc = $this->baiThiModel->getMonHocByLopHoc($lop_hoc_id);
            
            // Trả về dữ liệu dạng JSON
            header('Content-Type: application/json');
            echo json_encode($danhSachMonHoc);
            exit;
        }
        
        if (isset($_GET['mon_hoc_id'])) {
            $mon_hoc_id = $_GET['mon_hoc_id'];
            $danhSachChuDe = $this->baiThiModel->getChuDeByMonHoc($mon_hoc_id);
            
            // Trả về dữ liệu dạng JSON
            header('Content-Type: application/json');
            echo json_encode($danhSachChuDe);
            exit;
        }
        
        if (isset($_GET['chu_de_id'])) {
            $chu_de_id = $_GET['chu_de_id'];
            $bai_thi_id = isset($_GET['bai_thi_id']) ? $_GET['bai_thi_id'] : null;
            
            // Lấy danh sách câu hỏi thuộc chủ đề
            $danhSachCauHoi = $this->baiThiModel->getCauHoiByChuDe($chu_de_id);
            
            // Lấy thông tin môn học của chủ đề này
            $chuDeInfo = $this->cauHoiModel->getChuDeById($chu_de_id);
            $monHocId = isset($chuDeInfo['mon_hoc_id']) ? $chuDeInfo['mon_hoc_id'] : null;
            
            // Thêm thông tin môn học vào từng câu hỏi
            foreach ($danhSachCauHoi as &$cauHoi) {
                $cauHoi['mon_hoc_id'] = $monHocId;
                $cauHoi['original_topic_id'] = $chu_de_id;
            }
            
            if ($bai_thi_id) {
                // Nếu có bai_thi_id, đánh dấu các câu hỏi đã được chọn trong bài thi
                $danhSachCauHoiInBaiThi = $this->baiThiModel->getCauHoiTrongBaiThi($bai_thi_id);
                $cauHoiDaChon = [];
                
                foreach ($danhSachCauHoiInBaiThi as $ch) {
                    $cauHoiDaChon[$ch['id']] = $ch['chu_de_id'];
                }
                
                // Đánh dấu các câu hỏi đã được chọn hoặc đã được sử dụng ở chủ đề khác
                foreach ($danhSachCauHoi as &$cauHoi) {
                    if (isset($cauHoiDaChon[$cauHoi['id']])) {
                        $cauHoi['selected'] = true;
                        $cauHoi['selected_topic'] = $cauHoiDaChon[$cauHoi['id']];
                    } else {
                        $cauHoi['selected'] = false;
                    }
                }
            }
            
            // Trả về dữ liệu dạng JSON
            header('Content-Type: application/json');
            echo json_encode($danhSachCauHoi);
            exit;
        }
        
        include 'View/BaiThi/taobaithitracnghiem.php';
    }
    
    public function view($id) {
        $giao_vien_id = $this->getGiaoVienId();
        $baiThi = $this->baiThiModel->getBaiThiById($id);
        
        if (!$baiThi) {
            echo "Không tìm thấy bài thi";
            return;
        }
        
        // Kiểm tra quyền xem
        $lopHoc = $this->giaoVienModel->getLopHocById($baiThi['lop_hoc_id']);
        if ($lopHoc['giao_vien_id'] != $giao_vien_id) {
            echo "Bạn không có quyền xem bài thi này";
            return;
        }
        
        // Chỉ bật hiển thị đáp án khi có tham số show_answer=1 trong URL
        if (isset($_GET['show_answer']) && $_GET['show_answer'] == 1) {
            $baiThi['hien_thi_dap_an'] = 1;
        }
        
        // Lấy tất cả các chủ đề trong bài thi
        $chuDeTrongBaiThi = $this->baiThiModel->getChuDeTrongBaiThi($id);
        
        // Lấy tất cả câu hỏi trong bài thi
        $tatCaCauHoi = $this->baiThiModel->getCauHoiTrongBaiThi($id);
        
        // Chuẩn bị dữ liệu hiển thị
        $cauHoiTheoChuDe = [];
        
        // Khởi tạo cấu trúc dữ liệu cho mỗi chủ đề
        foreach ($chuDeTrongBaiThi as $chuDe) {
            $cauHoiTheoChuDe[$chuDe['id']] = [
                'chu_de' => $chuDe,
                'cau_hoi' => []
            ];
        }
        
        // Mảng đánh dấu câu hỏi đã được phân loại
        $cauHoiDaPhanLoai = [];
        
        // Phân loại câu hỏi theo chủ đề từ bảng cau_hoi_bai_kiem_tra_trac_nghiem
        foreach ($tatCaCauHoi as $cauHoi) {
            // Sử dụng chu_de_id từ kết quả câu hỏi 
            $chu_de_id = isset($cauHoi['chu_de_id']) ? $cauHoi['chu_de_id'] : null;
            
            if ($chu_de_id && isset($cauHoiTheoChuDe[$chu_de_id])) {
                // Thêm câu hỏi vào chủ đề tương ứng
                $cauHoiTheoChuDe[$chu_de_id]['cau_hoi'][] = $cauHoi;
                $cauHoiDaPhanLoai[$cauHoi['id']] = true;
            } else if (!empty($chuDeTrongBaiThi)) {
                // Nếu không tìm thấy chủ đề, thêm vào chủ đề đầu tiên (trường hợp dự phòng)
                $cauHoiTheoChuDe[$chuDeTrongBaiThi[0]['id']]['cau_hoi'][] = $cauHoi;
                $cauHoiDaPhanLoai[$cauHoi['id']] = true;
            }
        }
        
        include 'View/BaiThi/chitietbaithitracnghiem.php';
    }
    
    public function edit($id) {
        $giao_vien_id = $this->getGiaoVienId();
        $baiThi = $this->baiThiModel->getBaiThiById($id);
        
        if (!$baiThi) {
            header('Location: index.php?controller=baithi&error=not_found');
            return;
        }
        
        // Kiểm tra quyền chỉnh sửa thông qua lớp học
        $lopHoc = $this->giaoVienModel->getLopHocById($baiThi['lop_hoc_id']);
        if (!$lopHoc || $lopHoc['giao_vien_id'] != $giao_vien_id) {
            header('Location: index.php?controller=baithi&error=permission_denied');
            return;
        }
        
        $danhSachCauHoi = $this->cauHoiModel->getCauHoiByGiaoVien($giao_vien_id);
        $danhSachChuDe = $this->cauHoiModel->getAllChuDe();
        $danhSachMonHoc = $this->cauHoiModel->getAllMonHoc();
        
        $chuDeTrongBaiThi = $this->baiThiModel->getChuDeTrongBaiThi($id);
        $cauHoiTheoChuDe = [];
        
        foreach ($chuDeTrongBaiThi as $chuDe) {
            $cauHoiTheoChuDe[$chuDe['id']] = [
                'chu_de' => $chuDe,
                'cau_hoi' => $this->baiThiModel->getCauHoiTrongBaiThiTheoChuDe($id, $chuDe['id'])
            ];
        }
        
        // Lấy danh sách ID của các câu hỏi đã được sử dụng
        $daCoCauHoi = [];
        foreach ($cauHoiTheoChuDe as $chuDeData) {
            foreach ($chuDeData['cau_hoi'] as $cauHoi) {
                $daCoCauHoi[] = $cauHoi['id'];
            }
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra riêng cấu trúc chu_de trong POST
            if (isset($_POST['chu_de']) && is_array($_POST['chu_de'])) {
                foreach ($_POST['chu_de'] as $index => $chu_de) {
                    error_log("Chủ đề index $index - ID: " . ($chu_de['id'] ?? 'không có'));
                    
                    // Kiểm tra cau_hoi_ids
                    if (isset($chu_de['cau_hoi_ids']) && is_array($chu_de['cau_hoi_ids'])) {
                        error_log("   Số lượng câu hỏi được chọn: " . count($chu_de['cau_hoi_ids']));
                        foreach ($chu_de['cau_hoi_ids'] as $key => $cau_hoi_id) {
                            error_log("   Câu hỏi thứ $key - ID: $cau_hoi_id");
                        }
                    } else {
                        error_log("   Không có câu hỏi nào được chọn");
                    }
                }
            } else {
                error_log("Không có dữ liệu chủ đề trong POST");
            }
            
            $tieu_de = $_POST['tieu_de'] ?? '';
            $mo_ta = $_POST['mo_ta'] ?? '';
            $tron_cau_hoi = isset($_POST['tron_cau_hoi']) ? 1 : 0;
            $tron_dap_an = isset($_POST['tron_dap_an']) ? 1 : 0;
            $hien_thi_dap_an = isset($_POST['hien_thi_dap_an']) ? 1 : 0;
            $so_lan_lam = $_POST['so_lan_lam'] ?? 1;
            
            // Xử lý chế độ bài thi: trên lớp hoặc bài tập về nhà
            $che_do_thi = $_POST['che_do_thi'] ?? 'tren_lop';
            
            if ($che_do_thi == 'tren_lop') {
                // Chế độ làm bài trên lớp - chỉ cần thời gian làm bài
                $thoi_gian_lam = $_POST['thoi_gian_lam'] ?? null;
                // Thiết lập múi giờ Việt Nam
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $thoi_gian_bat_dau = date('Y-m-d H:i:s'); // Sử dụng thời gian hiện tại
                $thoi_gian_ket_thuc = null; // Không có thời gian kết thúc cho bài thi trên lớp
            } else {
                // Chế độ bài tập về nhà - cần ngày bắt đầu và kết thúc
                $thoi_gian_lam = null;
                $thoi_gian_bat_dau = $_POST['thoi_gian_bat_dau'] ?? null;
                $thoi_gian_ket_thuc = $_POST['thoi_gian_ket_thuc'] ?? null;
            }
            
            // Xử lý các chủ đề và câu hỏi
            $chu_de_data = isset($_POST['chu_de']) ? $_POST['chu_de'] : [];
            
            // Cập nhật thông tin bài thi
            $this->baiThiModel->updateBaiThi(
                $id,
                $tieu_de,
                $mo_ta,
                $thoi_gian_lam,
                $thoi_gian_bat_dau,
                $thoi_gian_ket_thuc,
                $tron_cau_hoi,
                $tron_dap_an,
                $hien_thi_dap_an,
                $so_lan_lam
            );
            
            // Xóa tất cả các liên kết hiện tại
            $this->baiThiModel->deleteCauHoiFromBaiThi($id);
            $this->baiThiModel->deleteChuDeFromBaiThi($id);
            
            // Tạo lại các liên kết mới
            foreach ($chu_de_data as $chu_de) {
                $chu_de_id = $chu_de['id'];
                $cau_hoi_ids = isset($chu_de['cau_hoi_ids']) ? $chu_de['cau_hoi_ids'] : [];
                
                // Nếu là chủ đề mới
                if ($chu_de_id == 'new' && !empty($chu_de['ten']) && !empty($chu_de['mon_hoc_id'])) {
                    $chu_de_id = $this->cauHoiModel->createChuDe($chu_de['mon_hoc_id'], $chu_de['ten']);
                }
                
                // Thêm chủ đề vào bài thi
                if ($chu_de_id && is_numeric($chu_de_id)) {
                    $this->baiThiModel->addChuDeToBaiThi($id, $chu_de_id);
                    
                    // Thêm câu hỏi vào bài thi với chủ đề gốc của nó
                    foreach ($cau_hoi_ids as $cau_hoi_id) {
                        // Lấy chủ đề gốc của câu hỏi từ bảng cau_hoi_chu_de
                        $chuDeGoc = $this->cauHoiModel->getChuDeByCauHoi($cau_hoi_id);
                        
                        // Sử dụng chủ đề gốc nếu có, nếu không sử dụng chủ đề hiện tại
                        $chuDeGocId = null;
                        if (!empty($chuDeGoc) && isset($chuDeGoc[0]['chu_de_id'])) {
                            $chuDeGocId = $chuDeGoc[0]['chu_de_id'];
                        }
                        
                        // Thêm câu hỏi với chủ đề gốc của nó
                        $this->baiThiModel->addCauHoiToBaiThi($id, $cau_hoi_id, $chuDeGocId);
                    }
                }
            }
            
            header('Location: index.php?controller=baithi&action=view&id=' . $id);
            exit;
        }
        
        // Make cauHoiModel available to the view
        $cauHoiModel = $this->cauHoiModel;
        
        include 'View/BaiThi/suabaithitracnghiem.php';
    }
    
    public function delete($id) {
        $giao_vien_id = $this->getGiaoVienId();
        $baiThi = $this->baiThiModel->getBaiThiById($id);
        
        if (!$baiThi) {
            header('Location: index.php?controller=baithi&error=not_found');
            exit;
        }
        
        // Lấy thông tin lớp học để kiểm tra quyền
        $lopHoc = $this->giaoVienModel->getLopHocById($baiThi['lop_hoc_id']);
        if (!$lopHoc || $lopHoc['giao_vien_id'] != $giao_vien_id) {
            header('Location: index.php?controller=baithi&error=permission_denied');
            exit;
        }
        
        if ($this->baiThiModel->deleteBaiThi($id)) {
            header('Location: index.php?controller=baithi&success=deleted');
        } else {
            header('Location: index.php?controller=baithi&error=delete_failed');
        }
        exit;
    }

    public function getCauHoiByChuDe() {
        // Lấy tham số từ cả GET và POST
        $chu_de_id = isset($_GET['chu_de_id']) ? $_GET['chu_de_id'] : (isset($_POST['chu_de_id']) ? $_POST['chu_de_id'] : null);
        $bai_thi_id = isset($_GET['bai_thi_id']) ? $_GET['bai_thi_id'] : (isset($_POST['bai_thi_id']) ? $_POST['bai_thi_id'] : null);
        
        if (!$chu_de_id) {
            echo json_encode([]);
            exit;
        }
        
        // Lấy danh sách câu hỏi thuộc chủ đề này
        $questions = $this->cauHoiModel->getCauHoiByChuDe($chu_de_id);
        
        // Đánh dấu các câu hỏi đã được chọn trong bài thi
        if ($bai_thi_id) {
            $cauHoiTrongBaiThi = $this->baiThiModel->getCauHoiTrongBaiThi($bai_thi_id);
            $cauHoiDaChon = [];
            
            foreach ($cauHoiTrongBaiThi as $ch) {
                $cauHoiDaChon[$ch['id']] = $ch['chu_de_id'];
            }
            
            foreach ($questions as &$question) {
                // Thiết lập chủ đề gốc của câu hỏi
                $question['original_chu_de_id'] = $chu_de_id;
                $question['original_chu_de_ten'] = '';
                
                // Lấy tên chủ đề
                $topic = $this->cauHoiModel->getChuDeById($chu_de_id);
                if ($topic) {
                    $question['original_chu_de_ten'] = $topic['ten_chu_de'];
                }
                
                // Đánh dấu nếu câu hỏi đã được chọn trong bài thi
                if (isset($cauHoiDaChon[$question['id']])) {
                    $question['is_selected'] = true;
                    $question['selected_topic'] = $cauHoiDaChon[$question['id']];
                } else {
                    $question['is_selected'] = false;
                }
            }
        } else {
            foreach ($questions as &$question) {
                $question['original_chu_de_id'] = $chu_de_id;
                $question['original_chu_de_ten'] = '';
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