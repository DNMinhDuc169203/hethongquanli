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
    <title>Quản lý người dùng - Hệ thống quản lý môn học</title>
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
            <h1 class="h3 mb-0 text-gray-800">Quản lý người dùng</h1>
            <a href="<?php echo BASE_URL; ?>/index.php?controller=auth&action=logout" class="btn btn-danger btn-sm">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
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

        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Tổng số người dùng</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $userCounts['total']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Giáo viên</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $userCounts['giao_vien']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Sinh viên</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $userCounts['sinh_vien']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Danh sách người dùng</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="filterDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter fa-sm fa-fw text-gray-400"></i> Lọc
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="filterDropdown">
                        <a class="dropdown-item<?php echo empty($_GET['role']) ? ' active' : ''; ?>" href="<?php echo BASE_URL; ?>/index.php?controller=user">Tất cả</a>
                        <a class="dropdown-item<?php echo isset($_GET['role']) && $_GET['role'] === 'giao_vien' ? ' active' : ''; ?>" href="<?php echo BASE_URL; ?>/index.php?controller=user&role=giao_vien">Chỉ giáo viên</a>
                        <a class="dropdown-item<?php echo isset($_GET['role']) && $_GET['role'] === 'sinh_vien' ? ' active' : ''; ?>" href="<?php echo BASE_URL; ?>/index.php?controller=user&role=sinh_vien">Chỉ sinh viên</a>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="mb-3">
                    <form action="<?php echo BASE_URL; ?>/index.php" method="GET" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <input type="hidden" name="controller" value="user">
                        <?php if (isset($_GET['role'])): ?>
                            <input type="hidden" name="role" value="<?php echo htmlspecialchars($_GET['role']); ?>">
                        <?php endif; ?>
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" name="search" placeholder="Tìm kiếm..."
                                aria-label="Search" aria-describedby="basic-addon2" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Mã số</th>
                                <th>Họ và tên</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th>Thông tin thêm</th>
                                <th>Ngày tạo</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="8" class="text-center">Không có người dùng nào.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['ma_so']); ?></td>
                                        <td><?php echo htmlspecialchars($user['ho_va_ten']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <?php if ($user['vai_tro'] == 'giao_vien'): ?>
                                                <span class="badge badge-success">Giáo viên</span>
                                            <?php elseif ($user['vai_tro'] == 'sinh_vien'): ?>
                                                <span class="badge badge-info">Sinh viên</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary"><?php echo htmlspecialchars($user['vai_tro']); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($user['vai_tro'] == 'giao_vien' && !empty($user['hoc_vi'])): ?>
                                                <span class="font-weight-bold"><?php echo htmlspecialchars($user['hoc_vi']); ?></span>
                                                <?php echo !empty($user['chuyen_nganh']) ? ' - ' . htmlspecialchars($user['chuyen_nganh']) : ''; ?>
                                            <?php elseif ($user['vai_tro'] == 'sinh_vien'): ?>
                                                <?php echo !empty($user['chuyen_nganh']) ? htmlspecialchars($user['chuyen_nganh']) : ''; ?>
                                                <?php echo !empty($user['ten_lop']) ? ' - Lớp: ' . htmlspecialchars($user['ten_lop']) : ''; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('d/m/Y', strtotime($user['ngay_tao'])); ?></td>
                                        <td>
                                            <?php if (isset($user['trang_thai']) && $user['trang_thai'] == 1): ?>
                                                <span class="badge badge-success">Đang hoạt động</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Bị khóa</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>/index.php?controller=user&action=view&id=<?php echo $user['id']; ?>" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo BASE_URL; ?>/index.php?controller=user&action=resetPassword&id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm" onclick="return confirm('Bạn có chắc muốn đặt lại mật khẩu cho người dùng này?');">
                                                <i class="fas fa-key"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

