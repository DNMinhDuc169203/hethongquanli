<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả bài thi tự luận</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
     .sidebar {
        background-color: #f8f9fa;
            min-height: 100vh;
            padding: 20px;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        .content {
            padding: 20px;
        }
        .menu-item {
            padding: 12px 20px;
            margin: 8px 0;
            background-color: #f0f0f0;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .menu-item a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            font-size: 16px;
            display: block;
        }

        .menu-item:hover {
            background-color: #007bff;
            transform: translateX(5px);
        }

        .menu-item:hover a {
            color: #fff;
        }
        .badge {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <h3>Quản lý kết quả bài thi</h3>
                <div class="menu-item">
                    <a href="index.php?controller=trangchu">Trang tổng quan</a>
                </div>
                <div class="menu-item">
                    <a href="index.php?controller=sinhvien">Quản lý sinh viên</a>
                </div>
                <div class="menu-item">
                    <a href="index.php?controller=cauhoi">Quản lý câu hỏi</a>
                </div>
                <div class="menu-item">
                    <a href="index.php?controller=baithi">Quản lý bài thi</a>
                </div>
                <div class="menu-item">
                    <a href="index.php?controller=lophoc">Quản lý lớp học</a>
                </div>
                <div class="menu-item">     
                    <a href="index.php?controller=ketquabaithi">Quản lý kết quả bài thi</a>
                </div>
                <div class="menu-item">
                    <a href="index.php?controller=auth&action=logout" class="btn btn-danger w-100">
                    Đăng xuất
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 content">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?controller=ketquabaithi">Quản lý kết quả bài thi</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Kết quả bài thi tự luận</li>
                    </ol>
                </nav>
                
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0"><?= htmlspecialchars($baiThi['tieu_de']); ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Môn học:</strong> <?= htmlspecialchars($baiThi['ma_mon'] . ' - ' . $baiThi['ten_mon']); ?></p>
                                <p><strong>Lớp:</strong> <?= htmlspecialchars($baiThi['ma_lop'] . ' - ' . $baiThi['ten_lop']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Thời gian làm bài:</strong> <?= $baiThi['thoi_gian_lam'] ? htmlspecialchars($baiThi['thoi_gian_lam'] . ' phút') : 'Không giới hạn'; ?></p>
                                <p><strong>Thời gian bắt đầu:</strong> <?= date('d/m/Y H:i', strtotime($baiThi['thoi_gian_bat_dau'])); ?></p>
                                <?php if ($baiThi['thoi_gian_ket_thuc']): ?>
                                    <p><strong>Thời gian kết thúc:</strong> <?= date('d/m/Y H:i', strtotime($baiThi['thoi_gian_ket_thuc'])); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <h5>Nội dung bài thi:</h5>
                            <div class="border p-3 bg-light">
                                <?= $baiThi['noi_dung']; ?>
                            </div>
                            
                            <?php if (!empty($baiThi['dinh_kem'])): ?>
                                <div class="mt-2">
                                    <p><strong>Tệp đính kèm:</strong> <a href="<?= $baiThi['dinh_kem']; ?>" target="_blank"><?= basename($baiThi['dinh_kem']); ?></a></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?= $_SESSION['success']; ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION['error']; ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0">Bài làm của sinh viên</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($ketQuaBaiThi)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>MSSV</th>
                                            <th>Họ và tên</th>
                                            <th>Thời gian nộp</th>
                                            <th>Trạng thái</th>
                                            <th>Điểm</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ketQuaBaiThi as $ketQua): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($ketQua['ma_so']); ?></td>
                                                <td><?= htmlspecialchars($ketQua['ho_va_ten']); ?></td>
                                                <td><?= date('d/m/Y H:i:s', strtotime($ketQua['ngay_nop'])); ?></td>
                                                <td>
                                                    <?php if ($ketQua['diem'] === NULL): ?>
                                                        <span class="badge bg-warning">Chưa chấm</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Đã chấm</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($ketQua['diem'] !== NULL): ?>
                                                        <span class="badge <?= ($ketQua['diem'] >= 5) ? 'bg-success' : 'bg-danger'; ?>"><?= number_format($ketQua['diem'], 1); ?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="index.php?controller=ketquabaithi&action=xemBaiTuLuan&id=<?= $ketQua['bai_lam_id']; ?>" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-eye"></i> Xem bài
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4">
                                <h5>Thống kê</h5>
                                <?php
                                $tong_sv = count($ketQuaBaiThi);
                                $so_sv_da_cham = count(array_filter($ketQuaBaiThi, function($item) {
                                    return $item['diem'] !== NULL;
                                }));
                                $so_sv_chua_cham = $tong_sv - $so_sv_da_cham;
                                
                                // Tính điểm trung bình (chỉ tính các bài đã chấm)
                                $diem_array = array_filter(array_column($ketQuaBaiThi, 'diem'), function($diem) {
                                    return $diem !== NULL;
                                });
                                $diem_trung_binh = !empty($diem_array) ? array_sum($diem_array) / count($diem_array) : 0;
                                
                                // Tính số sinh viên đạt
                                $so_sv_dat = count(array_filter($ketQuaBaiThi, function($item) {
                                    return $item['diem'] !== NULL && $item['diem'] >= 5;
                                }));
                                $ty_le_dat = ($so_sv_da_cham > 0) ? ($so_sv_dat / $so_sv_da_cham * 100) : 0;
                                ?>
                                <p><strong>Tổng số sinh viên nộp bài:</strong> <?= $tong_sv; ?></p>
                                <p><strong>Số bài đã chấm:</strong> <?= $so_sv_da_cham; ?> (<?= $tong_sv > 0 ? number_format($so_sv_da_cham / $tong_sv * 100, 2) : 0; ?>%)</p>
                                <p><strong>Số bài chưa chấm:</strong> <?= $so_sv_chua_cham; ?></p>
                                <?php if ($so_sv_da_cham > 0): ?>
                                    <p><strong>Điểm trung bình (các bài đã chấm):</strong> <?= number_format($diem_trung_binh, 2); ?></p>
                                    <p><strong>Số sinh viên đạt (điểm >= 5):</strong> <?= $so_sv_dat; ?> (<?= number_format($ty_le_dat, 2); ?>%)</p>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                Chưa có sinh viên nào nộp bài.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html> 