<?php
require_once 'Config/Database.php';
require_once 'Model/GiaoVienModel.php';

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $error= '';
            // Xử lý đăng nhập đơn giản (cần bổ sung bảo mật)
            $maso = $_POST['ma_so'];
            $password = $_POST['password'];
            
            // Kiểm tra thông tin đăng nhập từ database
            $db = new Database();
            $conn = $db->getConnection();
            
            // Kiểm tra tài khoản và mật khẩu, cùng với trạng thái hoạt động
            $query = "SELECT id, vai_tro, trang_thai FROM nguoi_dung WHERE ma_so = ? AND mat_khau = ? AND vai_tro = 'giao_vien'";
            $stmt = $conn->prepare($query);
            $stmt->execute([$maso, $password]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Kiểm tra trạng thái tài khoản
                if (isset($user['trang_thai']) && $user['trang_thai'] == 0) {
                    $error = 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.';
                } else {
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['vai_tro'] = $user['vai_tro'];
                    
                    // Redirect to dashboard page instead of cauhoi
                    header('Location: index.php?controller=trangchu');
                    exit;
                }
            } else {
                $error = 'Mã số hoặc mật khẩu không đúng';
            }
        }
        
        // Hiển thị form đăng nhập
        include 'View/Auth/login.php';
    }

    public function register() {
        $error = '';
        $success = '';
        
        // Bật hiển thị lỗi cho debug
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        // Tăng thời gian thực thi script để tránh timeout
        set_time_limit(120); // 120 giây
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra xem nút đăng ký đã được nhấn chưa
            if (isset($_POST['submit']) && $_POST['submit'] === 'register') {
                
                // Lấy dữ liệu từ form
                $ma_so = isset($_POST['ma_so']) ? trim($_POST['ma_so']) : '';
                $ho_va_ten = isset($_POST['ho_va_ten']) ? trim($_POST['ho_va_ten']) : '';
                $email = isset($_POST['email']) ? trim($_POST['email']) : '';
                $mat_khau = isset($_POST['mat_khau']) ? $_POST['mat_khau'] : '';
                $xac_nhan_mat_khau = isset($_POST['xac_nhan_mat_khau']) ? $_POST['xac_nhan_mat_khau'] : '';
                $hoc_vi = isset($_POST['hoc_vi']) ? trim($_POST['hoc_vi']) : '';
                $chuyen_nganh = isset($_POST['chuyen_nganh']) ? trim($_POST['chuyen_nganh']) : '';
                $mo_ta = isset($_POST['mo_ta']) ? trim($_POST['mo_ta']) : '';
                
                // File ghi log
                $log_file = 'logs/register_' . date('Y-m-d') . '.log';
                
                // Tạo thư mục logs nếu chưa tồn tại
                if (!file_exists('logs')) {
                    mkdir('logs', 0777, true);
                }
                
                // Ghi log thông tin đăng ký
                file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Start registration for: $ma_so, $ho_va_ten, $email\n", FILE_APPEND);
                
                // Kiểm tra các trường bắt buộc
                if (empty($ma_so) || empty($ho_va_ten) || empty($email) || empty($mat_khau) || empty($xac_nhan_mat_khau)) {
                    $error = 'Vui lòng điền đầy đủ thông tin bắt buộc';
                    file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Error: Missing required fields\n", FILE_APPEND);
                }
                // Kiểm tra mật khẩu
                else if ($mat_khau !== $xac_nhan_mat_khau) {
                    $error = 'Mật khẩu xác nhận không khớp';
                    file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Error: Password mismatch\n", FILE_APPEND);
                } 
                // Kiểm tra email hợp lệ
                else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Email không hợp lệ';
                    file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Error: Invalid email format\n", FILE_APPEND);
                }
                // Kiểm tra mật khẩu là 6 chữ số
                else if (!preg_match('/^\d{6}$/', $mat_khau)) {
                    $error = 'Mật khẩu phải là 6 chữ số';
                    file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Error: Password must be 6 digits\n", FILE_APPEND);
                }
                else {
                    try {
                        // Kiểm tra mã số và email đã tồn tại chưa
                        $db = new Database();
                        $conn = $db->getConnection();
                        file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Database connection successful\n", FILE_APPEND);
                        
                        // Kiểm tra mã số đã tồn tại chưa
                        $query = "SELECT id FROM nguoi_dung WHERE ma_so = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->execute([$ma_so]);
                        $existing_user_by_maso = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        // Kiểm tra email đã tồn tại chưa - chỉ kiểm tra email khác rỗng
                        $query = "SELECT id FROM nguoi_dung WHERE email = ? AND email != ''";
                        $stmt = $conn->prepare($query);
                        $stmt->execute([$email]);
                        $existing_user_by_email = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if ($existing_user_by_maso) {
                            $error = 'Mã số đã tồn tại trong hệ thống';
                            file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Error: Ma so already exists\n", FILE_APPEND);
                        } 
                        else if ($existing_user_by_email) {
                            $error = 'Email đã tồn tại trong hệ thống';
                            file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Error: Email already exists\n", FILE_APPEND);
                        } 
                        else {
                            try {
                                // Bắt đầu transaction
                                $conn->beginTransaction();
                                file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Started transaction\n", FILE_APPEND);
                                
                                // Thêm người dùng mới với trang_thai mặc định là 1 (hoạt động)
                                $query = "INSERT INTO nguoi_dung (ma_so, ho_va_ten, email, mat_khau, vai_tro, trang_thai) VALUES (?, ?, ?, ?, 'giao_vien', 1)";
                                $stmt = $conn->prepare($query);
                                $result_user = $stmt->execute([$ma_so, $ho_va_ten, $email, $mat_khau]);
                                
                                if (!$result_user) {
                                    throw new Exception("Không thể thêm dữ liệu vào bảng nguoi_dung");
                                }
                                
                                file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "User added to nguoi_dung table\n", FILE_APPEND);
                                
                                // Lấy ID người dùng vừa thêm
                                $nguoi_dung_id = $conn->lastInsertId();
                                
                                file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "New user ID: $nguoi_dung_id\n", FILE_APPEND);
                                
                                // Kiểm tra bảng giao_vien tồn tại
                                $check_table = $conn->query("SHOW TABLES LIKE 'giao_vien'");
                                if ($check_table->rowCount() == 0) {
                                    throw new Exception("Bảng 'giao_vien' không tồn tại");
                                }
                                
                                file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Table giao_vien exists\n", FILE_APPEND);
                                
                                // Thêm thông tin giáo viên trực tiếp
                                $query = "INSERT INTO giao_vien (nguoi_dung_id, hoc_vi, chuyen_nganh, mo_ta) VALUES (?, ?, ?, ?)";
                                $stmt = $conn->prepare($query);
                                $result_teacher = $stmt->execute([$nguoi_dung_id, $hoc_vi, $chuyen_nganh, $mo_ta]);
                                
                                if (!$result_teacher) {
                                    $error_info = $stmt->errorInfo();
                                    throw new Exception("Không thể tạo thông tin giáo viên: " . print_r($error_info, true));
                                }
                                
                                file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Teacher data added to giao_vien table\n", FILE_APPEND);
                                
                                // Commit transaction
                                $conn->commit();
                                file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Transaction committed successfully\n", FILE_APPEND);
                                
                                $success = 'Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.';
                                
                                // Reset form data after successful registration
                                unset($_POST);
                            } catch (Exception $e) {
                                // Rollback transaction nếu có lỗi
                                $conn->rollBack();
                                $error = 'Đã xảy ra lỗi trong quá trình đăng ký: ' . $e->getMessage();
                                file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Error in transaction: " . $e->getMessage() . "\n", FILE_APPEND);
                                file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Transaction rolled back\n", FILE_APPEND);
                            }
                        }
                    } catch (Exception $e) {
                        $error = 'Đã xảy ra lỗi khi kết nối đến cơ sở dữ liệu: ' . $e->getMessage();
                        file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "Database error: " . $e->getMessage() . "\n", FILE_APPEND);
                    }
                }
            }
        }
        
        // Hiển thị form đăng ký
        include 'View/Auth/dangki.php';
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: index.php?controller=auth&action=login');
        exit;
    }
}
?> 