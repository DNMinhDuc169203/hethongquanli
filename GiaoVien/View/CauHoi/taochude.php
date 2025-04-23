<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm chủ đề mới</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            padding: 10px 0;
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

    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <h3>Quản lý câu hỏi</h3>
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
                     </i> Đăng xuất
                    </a>
                </div>
            </div>
            
            <!-- Main content -->
            <div class="col-md-9 content">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?controller=cauhoi">Danh sách môn học</a></li>
                        <li class="breadcrumb-item"><a href="index.php?controller=cauhoi&action=viewBySubject&id=<?= $monHoc['id'] ?>">
                            Chủ đề môn <?= htmlspecialchars($monHoc['ten_mon']) ?>
                        </a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thêm chủ đề mới</li>
                    </ol>
                </nav>

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-folder me-2"></i>Danh sách chủ đề môn <?= htmlspecialchars($monHoc['ten_mon']) ?></h5>
                            <a href="index.php?controller=cauhoi&action=createTopic&mon_hoc_id=<?= $monHoc['id'] ?>" class="btn btn-sm btn-light">
                                <i class="fas fa-plus"></i> Thêm chủ đề mới
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="index.php?controller=cauhoi&action=createTopic&mon_hoc_id=<?= $monHoc['id'] ?>" method="POST">
                            <input type="hidden" name="mon_hoc_id" value="<?= $monHoc['id'] ?>">
                            
                            <div class="mb-3">
                                <label for="ten_chu_de" class="form-label">Tên chủ đề *</label>
                                <input type="text" class="form-control" id="ten_chu_de" name="ten_chu_de" required>
                                <div class="form-text">Nhập tên chủ đề để phân loại các câu hỏi trong môn học.</div>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Lưu chủ đề
                                </button>
                                <a href="index.php?controller=cauhoi&action=viewBySubject&id=<?= $monHoc['id'] ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
