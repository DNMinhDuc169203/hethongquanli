<?php
// Đảm bảo rằng không truy cập trực tiếp vào file
if (!defined('BASE_URL')) {
    define('BASE_URL', '/Admin');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Hệ thống quản lý môn học">
    <meta name="author" content="">
    <title>Chi tiết người dùng - Hệ thống quản lý môn học</title>
    <!-- Custom fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', sans-serif;
        }
        .navbar {
            background-color: #4e73df;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        .navbar-brand {
            color: white;
            font-weight: 700;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #4e73df;
            background-image: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
        }
        .sidebar-link {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 700;
            display: block;
            padding: 1rem;
            transition: all 0.3s;
        }
        .sidebar-link:hover {
            color: white;
            text-decoration: none;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
        }
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }
        .border-left-primary {
            border-left: 0.25rem solid #4e73df;
        }
        .border-left-success {
            border-left: 0.25rem solid #1cc88a;
        }
        .border-left-info {
            border-left: 0.25rem solid #36b9cc;
        }
        .text-primary {
            color: #4e73df !important;
        }
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        .btn-info {
            background-color: #36b9cc;
            border-color: #36b9cc;
        }
        .btn-warning {
            background-color: #f6c23e;
            border-color: #f6c23e;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #e3e6f0;
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #e3e6f0;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-4">
            <h1 class="h3 mb-0 text-gray-800">Chi tiết người dùng</h1>
            <div>
                <a href="<?php echo BASE_URL; ?>/index.php?controller=user" class="btn btn-primary btn-sm">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <a href="<?php echo BASE_URL; ?>/index.php?controller=auth&action=logout" class="btn btn-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </div>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Thông tin cơ bản -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin cơ bản</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Mã số:</th>
                                <td><?php echo htmlspecialchars($user['ma_so']); ?></td>
                            </tr>
                            <tr>
                                <th>Họ và tên:</th>
                                <td><?php echo htmlspecialchars($user['ho_va_ten']); ?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                            </tr>
                            <tr>
                                <th>Vai trò:</th>
                                <td>
                                    <?php if ($user['vai_tro'] == 'giao_vien'): ?>
                                        <span class="badge badge-success">Giáo viên</span>
                                    <?php elseif ($user['vai_tro'] == 'sinh_vien'): ?>
                                        <span class="badge badge-info">Sinh viên</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary"><?php echo htmlspecialchars($user['vai_tro']); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Ngày tạo:</th>
                                <td><?php echo date('d/m/Y', strtotime($user['ngay_tao'])); ?></td>
                            </tr>
                            <tr>
                                <th>Trạng thái:</th>
                                <td>
                                    <?php if (isset($user['trang_thai']) && $user['trang_thai'] == 1): ?>
                                        <span class="badge badge-success">Đang hoạt động</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Bị khóa</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Thao tác:</th>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>/index.php?controller=user&action=resetPassword&id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm" onclick="return confirm('Bạn có chắc muốn đặt lại mật khẩu cho người dùng này?');">
                                        <i class="fas fa-key"></i> Đặt lại mật khẩu
                                    </a>
                                    
                                    <?php if (isset($user['trang_thai']) && $user['trang_thai'] == 1): ?>
                                        <a href="<?php echo BASE_URL; ?>/index.php?controller=user&action=toggleStatus&id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn khóa tài khoản này?');">
                                            <i class="fas fa-lock"></i> Khóa tài khoản
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo BASE_URL; ?>/index.php?controller=user&action=toggleStatus&id=<?php echo $user['id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Bạn có chắc muốn mở khóa tài khoản này?');">
                                            <i class="fas fa-unlock"></i> Mở khóa
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <?php if ($user['vai_tro'] == 'giao_vien' && $teacherInfo): ?>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Học vị:</th>
                                    <td><?php echo htmlspecialchars($teacherInfo['hoc_vi']); ?></td>
                                </tr>
                                <tr>
                                    <th>Chuyên ngành:</th>
                                    <td><?php echo htmlspecialchars($teacherInfo['chuyen_nganh']); ?></td>
                                </tr>
                                <tr>
                                    <th>Thông tin thêm:</th>
                                    <td><?php echo isset($teacherInfo['thong_tin_them']) ? nl2br(htmlspecialchars($teacherInfo['thong_tin_them'])) : nl2br(htmlspecialchars($teacherInfo['mo_ta'])); ?></td>
                                </tr>
                            </table>
                        <?php elseif ($user['vai_tro'] == 'sinh_vien' && $studentInfo): ?>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Lớp học:</th>
                                    <td><?php echo htmlspecialchars($studentInfo['ten_lop']); ?></td>
                                </tr>
                                <tr>
                                    <th>Ngành học:</th>
                                    <td><?php echo htmlspecialchars($studentInfo['nganh_hoc']); ?></td>
                                </tr>
                                <tr>
                                    <th>Khóa:</th>
                                    <td><?php echo isset($studentInfo['khoa']) ? htmlspecialchars($studentInfo['khoa']) : htmlspecialchars($studentInfo['nam_nhap_hoc']); ?></td>
                                </tr>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nếu là giáo viên, hiển thị danh sách môn học phụ trách -->
        <?php if ($user['vai_tro'] == 'giao_vien' && $teacherSubjects): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách môn học phụ trách</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Mã môn học</th>
                                    <th>Tên môn học</th>
                                    <th>Số tín chỉ</th>
                                    <th>Lớp</th>
                                    <th>Học kỳ</th>
                                    <th>Năm học</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($teacherSubjects as $subject): ?>
                                    <tr>
                                        <td><?php echo isset($subject['ma_mon_hoc']) ? htmlspecialchars($subject['ma_mon_hoc']) : htmlspecialchars($subject['ma_mon']); ?></td>
                                        <td><?php echo isset($subject['ten_mon_hoc']) ? htmlspecialchars($subject['ten_mon_hoc']) : htmlspecialchars($subject['ten_mon']); ?></td>
                                        <td><?php echo htmlspecialchars($subject['so_tin_chi']); ?></td>
                                        <td><?php echo isset($subject['ten_lop']) ? htmlspecialchars($subject['ten_lop']) : ''; ?></td>
                                        <td><?php echo htmlspecialchars($subject['hoc_ky']); ?></td>
                                        <td><?php echo htmlspecialchars($subject['nam_hoc']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Nếu là sinh viên, hiển thị danh sách môn học đã đăng ký -->
        <?php if ($user['vai_tro'] == 'sinh_vien' && $studentSubjects): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách môn học đã đăng ký</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Mã môn học</th>
                                    <th>Tên môn học</th>
                                    <th>Số tín chỉ</th>
                                    <th>Giáo viên</th>
                                    <th>Học kỳ</th>
                                    <th>Năm học</th>
                                    <th>Điểm</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($studentSubjects as $subject): ?>
                                    <tr>
                                        <td><?php echo isset($subject['ma_mon_hoc']) ? htmlspecialchars($subject['ma_mon_hoc']) : htmlspecialchars($subject['ma_mon']); ?></td>
                                        <td><?php echo isset($subject['ten_mon_hoc']) ? htmlspecialchars($subject['ten_mon_hoc']) : htmlspecialchars($subject['ten_mon']); ?></td>
                                        <td><?php echo htmlspecialchars($subject['so_tin_chi']); ?></td>
                                        <td><?php echo isset($subject['ho_va_ten_gv']) ? htmlspecialchars($subject['ho_va_ten_gv']) : ''; ?></td>
                                        <td><?php echo htmlspecialchars($subject['hoc_ky']); ?></td>
                                        <td><?php echo htmlspecialchars($subject['nam_hoc']); ?></td>
                                        <td>
                                            <?php if (isset($subject['diem']) && $subject['diem'] !== null): ?>
                                                <?php echo number_format($subject['diem'], 1); ?>
                                            <?php elseif (isset($subject['diem_trung_binh']) && $subject['diem_trung_binh'] !== null): ?>
                                                <?php echo number_format($subject['diem_trung_binh'], 1); ?>
                                            <?php else: ?>
                                                <span class="text-muted">Chưa có</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

