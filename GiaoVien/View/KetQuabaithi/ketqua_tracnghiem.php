<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả bài thi trắc nghiệm</title>
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
                        <li class="breadcrumb-item active" aria-current="page">Kết quả bài thi trắc nghiệm</li>
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
                                <p><strong>Số câu hỏi:</strong> <?= isset($baiThi['so_cau_hoi']) ? $baiThi['so_cau_hoi'] : 0; ?></p>
                                <p><strong>Thời gian làm bài:</strong> <?= $baiThi['thoi_gian_lam'] ? htmlspecialchars($baiThi['thoi_gian_lam'] . ' phút') : 'Không giới hạn'; ?></p>
                                <p><strong>Thời gian bắt đầu:</strong> <?= date('d/m/Y H:i', strtotime($baiThi['thoi_gian_bat_dau'])); ?></p>
                                <?php if ($baiThi['thoi_gian_ket_thuc']): ?>
                                    <p><strong>Thời gian kết thúc:</strong> <?= date('d/m/Y H:i', strtotime($baiThi['thoi_gian_ket_thuc'])); ?></p>
                                <?php endif; ?>
                            </div>
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
                        <h4 class="mb-0">Kết quả của sinh viên</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($ketQuaBaiThi)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>MSSV</th>
                                            <th>Họ và tên</th>
                                            <!-- <th>Thời gian làm</th> -->
                                            <th>Số câu đúng</th>
                                            <th>Điểm</th>
                                            <!-- <th>Thao tác</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ketQuaBaiThi as $ketQua): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($ketQua['ma_so']); ?></td>
                                                <td><?= htmlspecialchars($ketQua['ho_va_ten']); ?></td>
                                                <!-- <td>
                                                    <?php if (!empty($ketQua['thoi_gian_bat_dau']) && !empty($ketQua['thoi_gian_ket_thuc'])): ?>
                                                        <?php
                                                        $start = new DateTime($ketQua['thoi_gian_bat_dau']);
                                                        $end = new DateTime($ketQua['thoi_gian_ket_thuc']);
                                                        $interval = $start->diff($end);
                                                        
                                                        $minutes = $interval->days * 24 * 60;
                                                        $minutes += $interval->h * 60;
                                                        $minutes += $interval->i;
                                                        $seconds = $interval->s;
                                                        
                                                        echo $minutes . ' phút ' . $seconds . ' giây';
                                                        ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td> -->
                                                <td>
                                                    <?= isset($ketQua['so_cau_dung']) ? $ketQua['so_cau_dung'] : 0; ?>/<?= isset($baiThi['so_cau_hoi']) ? $baiThi['so_cau_hoi'] : 0; ?>
                                                    <small class="text-muted">(<?= isset($baiThi['so_cau_hoi']) && $baiThi['so_cau_hoi'] > 0 && isset($ketQua['so_cau_dung']) ? number_format($ketQua['so_cau_dung'] / $baiThi['so_cau_hoi'] * 100, 1) : 0; ?>%)</small>
                                                </td>
                                                <td>
                                                    <span class="badge <?= ($ketQua['diem'] >= 5) ? 'bg-success' : 'bg-danger'; ?>"><?= number_format($ketQua['diem'], 1); ?></span>
                                                </td>
                                                <!-- <td>
                                                   <a href="index.php?controller=ketquabaithi&action=chitietTracNghiem&id=<?= $ketQua['bai_lam_id']; ?>" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-eye"></i> Xem chi tiết
                                                    </a> 
                                                </td> -->
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4">
                                <h5>Thống kê</h5>
                                <?php
                                $tong_sv = count($ketQuaBaiThi);
                                
                                // Tính điểm trung bình
                                $diem_trung_binh = array_sum(array_column($ketQuaBaiThi, 'diem')) / $tong_sv;
                                
                                // Tính số sinh viên đạt
                                $so_sv_dat = count(array_filter($ketQuaBaiThi, function($item) {
                                    return $item['diem'] >= 5;
                                }));
                                $ty_le_dat = ($so_sv_dat / $tong_sv * 100);
                                
                                // Tính phân bố điểm
                                $diem_ranges = [
                                    '0-3.9' => 0,
                                    '4-4.9' => 0,
                                    '5-6.9' => 0,
                                    '7-7.9' => 0,
                                    '8-8.9' => 0,
                                    '9-10' => 0
                                ];
                                
                                foreach ($ketQuaBaiThi as $ketQua) {
                                    $diem = $ketQua['diem'];
                                    if ($diem < 4) {
                                        $diem_ranges['0-3.9']++;
                                    } elseif ($diem < 5) {
                                        $diem_ranges['4-4.9']++;
                                    } elseif ($diem < 7) {
                                        $diem_ranges['5-6.9']++;
                                    } elseif ($diem < 8) {
                                        $diem_ranges['7-7.9']++;
                                    } elseif ($diem < 9) {
                                        $diem_ranges['8-8.9']++;
                                    } else {
                                        $diem_ranges['9-10']++;
                                    }
                                }
                                ?>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Tổng số sinh viên làm bài:</strong> <?= $tong_sv; ?></p>
                                        <p><strong>Điểm trung bình:</strong> <?= number_format($diem_trung_binh, 2); ?></p>
                                        <p><strong>Số sinh viên đạt (điểm >= 5):</strong> <?= $so_sv_dat; ?> (<?= number_format($ty_le_dat, 2); ?>%)</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Phân bố điểm:</strong></p>
                                        <ul class="list-group">
                                            <?php foreach ($diem_ranges as $range => $count): ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <?= $range; ?>
                                                    <span class="badge bg-primary rounded-pill"><?= $count; ?> (<?= number_format($count/$tong_sv*100, 1); ?>%)</span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- <div class="mt-4">
                                <button class="btn btn-success" onclick="exportToCsv()">
                                    <i class="fas fa-download"></i> Xuất kết quả (CSV)
                                </button>
                            </div> -->
                        <?php else: ?>
                            <div class="alert alert-info">
                                Chưa có sinh viên nào làm bài.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        function exportToCsv() {
            // CSV header
            let csvContent = "data:text/csv;charset=utf-8,MSSV,Họ và tên,Thời gian làm bài,Số câu đúng,Tỷ lệ đúng,Điểm\n";
            
            <?php if (!empty($ketQuaBaiThi)): ?>
            // CSV rows
            <?php foreach ($ketQuaBaiThi as $ketQua): ?>
                <?php 
                $thoiGianLam = '';
                if (!empty($ketQua['thoi_gian_bat_dau']) && !empty($ketQua['thoi_gian_ket_thuc'])) {
                    $start = new DateTime($ketQua['thoi_gian_bat_dau']);
                    $end = new DateTime($ketQua['thoi_gian_ket_thuc']);
                    $interval = $start->diff($end);
                    
                    $minutes = $interval->days * 24 * 60;
                    $minutes += $interval->h * 60;
                    $minutes += $interval->i;
                    $seconds = $interval->s;
                    
                    $thoiGianLam = $minutes . ' phút ' . $seconds . ' giây';
                }
                
                $so_cau_hoi = isset($baiThi['so_cau_hoi']) ? $baiThi['so_cau_hoi'] : 0;
                $so_cau_dung = isset($ketQua['so_cau_dung']) ? $ketQua['so_cau_dung'] : 0;
                $tyleĐung = $so_cau_hoi > 0 ? number_format($so_cau_dung / $so_cau_hoi * 100, 1) . '%' : '0%';
                ?>
                csvContent += "<?= $ketQua['ma_so']; ?>,\"<?= str_replace('"', '""', $ketQua['ho_va_ten']); ?>\",\"<?= $thoiGianLam; ?>\",<?= $so_cau_dung; ?>/<?= $so_cau_hoi; ?>,<?= $tyleĐung; ?>,<?= number_format($ketQua['diem'], 1); ?>\n";
            <?php endforeach; ?>
            <?php endif; ?>
            
            // Create download link
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "ket_qua_trac_nghiem_<?= $baiThi['id']; ?>.csv");
            document.body.appendChild(link);
            
            // Trigger download
            link.click();
        }
    </script>
</body>
</html> 