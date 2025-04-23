<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> sửa bài thi trắc nghiệm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
        .exam-form {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
                <h3>Quản lý bài thi</h3>
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
                    <a href="index.php?controller=auth&action=logout" class="btn btn-danger w-100">Đăng xuất</a>
                </div>
            </div>
            
            <!-- Main content -->
            <div class="col-md-9 content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Chỉnh sửa bài thi</h2>
                    <a href="index.php?controller=baithi" class="btn btn-secondary">Quay lại</a>
                </div>
                
                <div class="exam-form">
                    <form action="index.php?controller=baithi&action=edit&id=<?php echo $baiThi['id']; ?>" method="POST">
                        <div class="mb-3">
                            <label for="tieu_de" class="form-label">Tiêu đề bài thi</label>
                            <input type="text" class="form-control" id="tieu_de" name="tieu_de" 
                                   value="<?php echo htmlspecialchars($baiThi['tieu_de']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="mo_ta" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="mo_ta" name="mo_ta" rows="3"><?php echo htmlspecialchars($baiThi['mo_ta']); ?></textarea>
                        </div>
                        
                        <!-- Chế độ bài thi -->
                        <div class="mb-3">
                            <label class="form-label">Chế độ bài thi</label>
                            <?php 
                            // Xác định chế độ thi dựa vào dữ liệu
                            $cheDoBTVN = empty($baiThi['thoi_gian_lam']) && (!empty($baiThi['thoi_gian_bat_dau']) || !empty($baiThi['thoi_gian_ket_thuc']));
                            ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="che_do_thi" id="che_do_tren_lop" value="tren_lop" <?php echo !$cheDoBTVN ? 'checked' : ''; ?> onchange="toggleCheDoThi()">
                                <label class="form-check-label" for="che_do_tren_lop">
                                    Làm bài trên lớp
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="che_do_thi" id="che_do_btvn" value="btvn" <?php echo $cheDoBTVN ? 'checked' : ''; ?> onchange="toggleCheDoThi()">
                                <label class="form-check-label" for="che_do_btvn">
                                    Bài tập về nhà
                                </label>
                            </div>
                        </div>
                        
                        <!-- Phần thời gian làm bài trên lớp -->
                        <div id="thoi_gian_tren_lop" class="mb-3" <?php echo $cheDoBTVN ? 'style="display: none;"' : ''; ?>>
                            <label for="thoi_gian_lam" class="form-label">Thời gian làm bài (phút)</label>
                            <input type="number" class="form-control" id="thoi_gian_lam" name="thoi_gian_lam" 
                                   value="<?php echo $baiThi['thoi_gian_lam']; ?>" min="1">
                        </div>
                        
                        <!-- Phần thời gian làm bài tập về nhà -->
                        <div id="thoi_gian_btvn" class="mb-3" <?php echo !$cheDoBTVN ? 'style="display: none;"' : ''; ?>>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="thoi_gian_bat_dau" class="form-label">Thời gian bắt đầu</label>
                                    <input type="datetime-local" class="form-control" id="thoi_gian_bat_dau" name="thoi_gian_bat_dau" 
                                           value="<?php echo !empty($baiThi['thoi_gian_bat_dau']) ? date('Y-m-d\TH:i', strtotime($baiThi['thoi_gian_bat_dau'])) : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="thoi_gian_ket_thuc" class="form-label">Thời gian kết thúc</label>
                                    <input type="datetime-local" class="form-control" id="thoi_gian_ket_thuc" name="thoi_gian_ket_thuc" 
                                           value="<?php echo !empty($baiThi['thoi_gian_ket_thuc']) ? date('Y-m-d\TH:i', strtotime($baiThi['thoi_gian_ket_thuc'])) : ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="tron_cau_hoi" name="tron_cau_hoi" value="1" 
                                           <?php echo $baiThi['tron_cau_hoi'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="tron_cau_hoi">
                                        Trộn câu hỏi
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="tron_dap_an" name="tron_dap_an" value="1" 
                                           <?php echo $baiThi['tron_dap_an'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="tron_dap_an">
                                        Trộn đáp án
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="hien_thi_dap_an" name="hien_thi_dap_an" value="1" 
                                           <?php echo $baiThi['hien_thi_dap_an'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="hien_thi_dap_an">
                                        Hiển thị đáp án
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="so_lan_lam" class="form-label">Số lần làm tối đa</label>
                                    <input type="number" class="form-control" id="so_lan_lam" name="so_lan_lam" min="1" 
                                           value="<?php echo $baiThi['so_lan_lam']; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold">Chủ đề và câu hỏi</label>
                                <!-- <button type="button" class="btn btn-sm btn-success" id="btnThemChuDe">+ Thêm chủ đề</button> -->
                            </div>
                            
                            <div id="danhSachChuDe">
                                <!-- Danh sách chủ đề hiện tại -->
                                <?php $chuDeIndex = 0; ?>
                                <?php foreach ($cauHoiTheoChuDe as $chuDeId => $chuDeData): ?>
                                    <div class="chu-de-item border rounded p-3 mb-3">
                                        <div class="row mb-3">
                                            <div class="col-md-10">
                                                <div class="d-flex align-items-center">
                                                    <label class="form-label me-2">Chủ đề:</label>
                                                    <select class="form-select chu-de-select" name="chu_de[<?php echo $chuDeIndex; ?>][id]" required 
                                                            data-original-id="<?php echo $chuDeId; ?>">
                                                        <option value="">-- Chọn chủ đề --</option>
                                                        <!-- <option value="new">+ Tạo chủ đề mới</option> -->
                                                        <?php 
                                                        // Lấy môn học ID của chủ đề hiện tại
                                                        $currentChuDeMonHoc = null;
                                                        foreach ($danhSachChuDe as $chuDe) {
                                                            if ($chuDe['id'] == $chuDeId) {
                                                                $currentChuDeMonHoc = $chuDe['mon_hoc_id'];
                                                                break;
                                                            }
                                                        }
                                                        
                                                        // Nhóm chủ đề theo môn học
                                                        $chuDeTheoMonHoc = [];
                                                        foreach ($danhSachChuDe as $chuDe) {
                                                            $monHocId = $chuDe['mon_hoc_id'];
                                                            if (!isset($chuDeTheoMonHoc[$monHocId])) {
                                                                $chuDeTheoMonHoc[$monHocId] = [];
                                                            }
                                                            $chuDeTheoMonHoc[$monHocId][] = $chuDe;
                                                        }
                                                        
                                                        // Hiển thị chủ đề của môn học hiện tại trước
                                                        if ($currentChuDeMonHoc && isset($chuDeTheoMonHoc[$currentChuDeMonHoc])) {
                                                            foreach ($chuDeTheoMonHoc[$currentChuDeMonHoc] as $chuDe): 
                                                        ?>
                                                            <option value="<?php echo $chuDe['id']; ?>" <?php echo ($chuDe['id'] == $chuDeId) ? 'selected' : ''; ?> 
                                                                    data-mon-hoc-id="<?php echo $chuDe['mon_hoc_id']; ?>">
                                                                <?php echo htmlspecialchars($chuDe['ten_chu_de']); ?>
                                                            </option>
                                                        <?php 
                                                            endforeach;
                                                        }
                                                        
                                                        // Hiển thị các chủ đề khác môn học
                                                    
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1 text-end">
                                                <button type="button" class="btn btn-sm btn-danger btn-xoa-chu-de">Xóa</button>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-10 chu-de-moi" style="display: none; margin-bottom: 15px;">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control chu-de-ten" name="chu_de[<?php echo $chuDeIndex; ?>][ten]" placeholder="Tên chủ đề mới">
                                                </div>
                                                <div class="col-md-4">
                                                    <select class="form-select chu-de-mon-hoc" name="chu_de[<?php echo $chuDeIndex; ?>][mon_hoc_id]">
                                                        <option value="">-- Môn học --</option>
                                                        <?php foreach ($danhSachMonHoc as $monHoc): ?>
                                                            <option value="<?php echo $monHoc['id']; ?>">
                                                                <?php echo htmlspecialchars($monHoc['ten_mon']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="cau-hoi-container">
                                            <div class="border p-3 rounded">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="fw-bold">Chọn câu hỏi:</div>
                                                    <div class="d-flex align-items-center">
                                                        <input type="number" class="form-control form-control-sm me-2 auto-select-number" 
                                                               placeholder="Số câu hỏi" min="1" style="width: 100px;">
                                                        <button type="button" class="btn btn-sm btn-primary btn-auto-select">Chọn tự động</button>
                                                    </div>
                                                </div>
                                                <div class="cau-hoi-list" data-chu-de-id="<?php echo $chuDeId; ?>">
                                                    <?php 
                                                    // Lấy ID các câu hỏi đã chọn cho chủ đề này
                                                    $cauHoiIds = array_column($chuDeData['cau_hoi'], 'id');
                                                    
                                                    // Lọc danh sách câu hỏi để chỉ hiển thị câu hỏi thuộc chủ đề này
                                                    $cauHoiChuDe = [];
                                                    foreach ($danhSachCauHoi as $cauHoi) {
                                                        $chuDeInfo = $cauHoiModel->getChuDeByCauHoi($cauHoi['id']);
                                                        if (!empty($chuDeInfo) && is_array($chuDeInfo)) {
                                                            foreach($chuDeInfo as $info) {
                                                                if ($info['chu_de_id'] == $chuDeId) {
                                                                    $cauHoiChuDe[] = $cauHoi;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    
                                                    // Thêm vào những câu hỏi đã chọn thuộc chủ đề này
                                                    foreach ($chuDeData['cau_hoi'] as $chQuestion) {
                                                        $found = false;
                                                        foreach ($cauHoiChuDe as $ch) {
                                                            if ($ch['id'] == $chQuestion['id']) {
                                                                $found = true;
                                                                break;
                                                            }
                                                        }
                                                        
                                                        if (!$found) {
                                                            foreach ($danhSachCauHoi as $ch) {
                                                                if ($ch['id'] == $chQuestion['id']) {
                                                                    $cauHoiChuDe[] = $ch;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    
                                                    if (empty($cauHoiChuDe)) {
                                                        echo '<div class="alert alert-info mb-3">Không có câu hỏi nào thuộc chủ đề này</div>';
                                                    } else {
                                                        //echo '<div class="alert alert-info mb-3">Đang hiển thị ' . count($cauHoiChuDe) . ' câu hỏi thuộc chủ đề này</div>';
                                                    
                                                        // Hiển thị câu hỏi thuộc chủ đề này và câu hỏi đã chọn
                                                        foreach ($cauHoiChuDe as $cauHoi): 
                                                            $daChon = in_array($cauHoi['id'], $cauHoiIds);
                                                            $daDungOChuDeKhac = in_array($cauHoi['id'], $daCoCauHoi) && !$daChon;
                                                            $disabled = $daDungOChuDeKhac ? 'disabled' : '';
                                                            $textMuted = $daDungOChuDeKhac ? 'text-muted' : '';
                                                    ?>
                                                        <div class="form-check mb-2 cau-hoi-item <?php echo $textMuted; ?>">
                                                            <input class="form-check-input cau-hoi-checkbox" type="checkbox" 
                                                                   name="chu_de[<?php echo $chuDeIndex; ?>][cau_hoi_ids][]" 
                                                                   value="<?php echo $cauHoi['id']; ?>" 
                                                                   id="chu_de_<?php echo $chuDeIndex; ?>_cauhoi_<?php echo $cauHoi['id']; ?>"
                                                                   <?php echo $daChon ? 'checked' : ''; ?> 
                                                                   <?php echo $disabled; ?>>
                                                            <label class="form-check-label" for="chu_de_<?php echo $chuDeIndex; ?>_cauhoi_<?php echo $cauHoi['id']; ?>">
                                                                <?php echo htmlspecialchars($cauHoi['noi_dung']); ?>
                                                            </label>
                                                        </div>
                                                    <?php 
                                                        endforeach;
                                                    } 
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $chuDeIndex++; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                            <a href="index.php?controller=baithi" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Template cho chủ đề mới -->
    <template id="templateChuDe">
        <div class="chu-de-item border rounded p-3 mb-3">
            <div class="row mb-3">
                <div class="col-md-10">
                    <div class="d-flex align-items-center">
                        <label class="form-label me-2">Chủ đề:</label>
                        <select class="form-select chu-de-select" name="chu_de[__INDEX__][id]" required>
                            <option value="">-- Chọn chủ đề --</option>
                            <!-- <option value="new">+ Tạo chủ đề mới</option> -->
                            <?php foreach ($danhSachChuDe as $chuDe): ?>
                                <option value="<?php echo $chuDe['id']; ?>" data-mon-hoc-id="<?php echo $chuDe['mon_hoc_id']; ?>">
                                    <?php echo htmlspecialchars($chuDe['ten_chu_de']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-sm btn-danger btn-xoa-chu-de">Xóa</button>
                </div>
            </div>
            
            <div class="col-md-10 chu-de-moi" style="display: none; margin-bottom: 15px;">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" class="form-control chu-de-ten" name="chu_de[__INDEX__][ten]" placeholder="Tên chủ đề mới">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select chu-de-mon-hoc" name="chu_de[__INDEX__][mon_hoc_id]">
                            <option value="">-- Môn học --</option>
                            <?php foreach ($danhSachMonHoc as $monHoc): ?>
                                <option value="<?php echo $monHoc['id']; ?>"><?php echo htmlspecialchars($monHoc['ten_mon']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="cau-hoi-container">
                <div class="border p-3 rounded">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-bold">Chọn câu hỏi:</div>
                        <div class="d-flex align-items-center">
                            <input type="number" class="form-control form-control-sm me-2 auto-select-number" 
                                   placeholder="Số câu hỏi" min="1" style="width: 100px;">
                            <button type="button" class="btn btn-sm btn-primary btn-auto-select">Chọn tự động</button>
                        </div>
                    </div>
                    <div class="cau-hoi-list">
                        <p class="text-muted">Vui lòng chọn chủ đề để xem danh sách câu hỏi</p>
                    </div>
                </div>
            </div>
        </div>
    </template>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let chuDeIndex = <?php echo count($cauHoiTheoChuDe); ?>;
            const danhSachCauHoi = <?php echo json_encode(array_map(function($cauHoi) { 
                return ['id' => $cauHoi['id'], 'noi_dung' => $cauHoi['noi_dung']]; 
            }, $danhSachCauHoi)); ?>;
            const danhSachChuDe = document.getElementById('danhSachChuDe');
            const templateChuDe = document.getElementById('templateChuDe');
            const baiThiId = <?php echo $baiThi['id']; ?>;
            
            // Array to store selected question IDs
            let daCoCauHoi = <?php echo json_encode($daCoCauHoi); ?>;
            // Array to store selected questions with more details
            let selectedCauHois = [];
            
            // Thêm chủ đề
            document.getElementById('btnThemChuDe').addEventListener('click', function() {
                themChuDe();
            });
            
            // Hàm thêm chủ đề mới
            function themChuDe() {
                const chuDeItem = document.importNode(templateChuDe.content, true);
                
                // Cập nhật index
                chuDeItem.querySelectorAll('[name*="__INDEX__"]').forEach(el => {
                    el.name = el.name.replace('__INDEX__', chuDeIndex);
                });
                
                chuDeItem.querySelectorAll('[for*="__INDEX__"]').forEach(el => {
                    el.setAttribute('for', el.getAttribute('for').replace('__INDEX__', chuDeIndex));
                });
                
                chuDeItem.querySelectorAll('[id*="__INDEX__"]').forEach(el => {
                    el.id = el.id.replace('__INDEX__', chuDeIndex);
                });
                
                // Xử lý sự kiện chọn chủ đề
                const chuDeSelect = chuDeItem.querySelector('.chu-de-select');
                const chuDeMoiDiv = chuDeItem.querySelector('.chu-de-moi');
                
                // Lấy môn học được chọn
                const monHocId = document.getElementById('mon_hoc_id').value;
                
                // Lọc các chủ đề theo môn học 
                if (monHocId) {
                    // Tạo bản sao của tất cả các option
                    const allOptions = Array.from(chuDeSelect.options);
                    
                    // Tạo optgroup cho chủ đề của môn học hiện tại
                    const currentMonHocOptgroup = document.createElement('optgroup');
                    currentMonHocOptgroup.label = "Chủ đề của môn học hiện tại";
                    
                    // Tạo optgroup cho chủ đề của môn học khác
                    const otherMonHocOptgroup = document.createElement('optgroup');
                    otherMonHocOptgroup.label = "Chủ đề của môn học khác";
                    
                    // Giữ lại các option đặc biệt (empty và new)
                    const specialOptions = [];
                    
                    // Xóa tất cả các option hiện có
                    while (chuDeSelect.options.length > 0) {
                        const option = chuDeSelect.options[0];
                        
                        // Lưu lại các option đặc biệt (empty và new)
                        if (option.value === "" || option.value === "new") {
                            specialOptions.push(option);
                        }
                        
                        chuDeSelect.remove(0);
                    }
                    
                    // Thêm lại các option đặc biệt
                    specialOptions.forEach(option => {
                        chuDeSelect.appendChild(option);
                    });
                    
                    // Phân loại các option theo môn học
                    allOptions.forEach(option => {
                        if (option.value !== "" && option.value !== "new") {
                            const optionMonHocId = option.getAttribute('data-mon-hoc-id');
                            
                            if (optionMonHocId == monHocId) {
                                currentMonHocOptgroup.appendChild(option.cloneNode(true));
                            } else if (optionMonHocId) {
                                otherMonHocOptgroup.appendChild(option.cloneNode(true));
                            }
                        }
                    });
                    
                    // Thêm các optgroup vào select
                    if (currentMonHocOptgroup.childElementCount > 0) {
                        chuDeSelect.appendChild(currentMonHocOptgroup);
                    } else {
                        // Nếu không có chủ đề nào cho môn học hiện tại
                        const emptyOption = document.createElement('option');
                        emptyOption.textContent = "Không có chủ đề nào cho môn học này";
                        emptyOption.disabled = true;
                        chuDeSelect.appendChild(emptyOption);
                    }
                    
                    // Chỉ thêm optgroup của môn học khác nếu có ít nhất một chủ đề
                    if (otherMonHocOptgroup.childElementCount > 0) {
                        chuDeSelect.appendChild(otherMonHocOptgroup);
                    }
                }
                
                chuDeSelect.addEventListener('change', function() {
                    if (this.value === 'new') {
                        chuDeMoiDiv.style.display = 'block';
                    } else {
                        chuDeMoiDiv.style.display = 'none';
                        
                        // Tải danh sách câu hỏi nếu đã chọn chủ đề
                        if (this.value) {
                            taiDanhSachCauHoi(this.value, chuDeIndex-1);
                        }
                    }
                    
                    // Lưu lại giá trị chủ đề đã chọn cho template này
                    const currentIndex = chuDeIndex - 1;
                    this.setAttribute('data-index', currentIndex);
                });
                
                // Xử lý sự kiện xóa chủ đề
                chuDeItem.querySelector('.btn-xoa-chu-de').addEventListener('click', function() {
                    const chuDeItem = this.closest('.chu-de-item');
                    // Trước khi xóa, bỏ chọn tất cả các câu hỏi để mở khóa chúng
                    chuDeItem.querySelectorAll('.cau-hoi-checkbox:checked').forEach(cb => {
                        cb.checked = false;
                        quanLyCauHoi(cb);
                    });
                    chuDeItem.remove();
                });
                
                // Xử lý sự kiện chọn tự động
                chuDeItem.querySelector('.btn-auto-select').addEventListener('click', function() {
                    const chuDeContainer = this.closest('.chu-de-item');
                    const numberInput = chuDeContainer.querySelector('.auto-select-number');
                    const numToSelect = parseInt(numberInput.value);
                    
                    if (isNaN(numToSelect) || numToSelect <= 0) {
                        alert('Vui lòng nhập số lượng câu hỏi hợp lệ.');
                        return;
                    }
                    
                    // Lấy tất cả câu hỏi khả dụng (chưa bị disabled)
                    const availableQuestions = Array.from(
                        chuDeContainer.querySelectorAll('.cau-hoi-checkbox:not(:disabled):not(:checked)')
                    );
                    
                    if (availableQuestions.length === 0) {
                        alert('Không còn câu hỏi khả dụng để chọn.');
                        return;
                    }
                    
                    if (numToSelect > availableQuestions.length) {
                        alert(`Chỉ có ${availableQuestions.length} câu hỏi khả dụng. Sẽ chọn tất cả câu hỏi khả dụng.`);
                    }
                    
                    // Lấy số lượng câu hỏi ngẫu nhiên theo số đã nhập
                    const questionsToSelect = shuffleArray(availableQuestions)
                        .slice(0, Math.min(numToSelect, availableQuestions.length));
                    
                    // Chọn các câu hỏi
                    questionsToSelect.forEach(checkbox => {
                        checkbox.checked = true;
                        quanLyCauHoi(checkbox);
                    });
                });
                
                danhSachChuDe.appendChild(chuDeItem);
                chuDeIndex++;
            }
            
            // Hàm hiển thị danh sách câu hỏi
            function taiDanhSachCauHoi(chuDeId, chuDeIndex) {
                if (!chuDeId) {
                    return;
                }
                
                // Tìm container cho câu hỏi
                const cauHoiContainer = document.querySelector(`.chu-de-item:nth-child(${chuDeIndex + 1}) .cau-hoi-container`);
                if (!cauHoiContainer) {
                    return;
                }
                
                // Hiển thị trạng thái đang tải
                cauHoiContainer.innerHTML = '<div class="loading p-3">Đang tải câu hỏi...</div>';
                
                // Gọi AJAX để lấy danh sách câu hỏi
                fetch(`index.php?controller=BaiThi&action=getCauHoiByChuDe&chu_de_id=${chuDeId}&bai_thi_id=${baiThiId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length === 0) {
                            cauHoiContainer.innerHTML = '<div class="no-questions p-3">Không có câu hỏi nào cho chủ đề này</div>';
                            return;
                        }
                        
                        // Tạo container cho danh sách câu hỏi
                        let cauHoiListHTML = `
                            <div class="border p-3 rounded">
                                <div class="fw-bold mb-2">Chọn câu hỏi:</div>
                                <div class="cau-hoi-list">
                                    <div class="alert alert-info mb-3">Đang hiển thị ${data.length} câu hỏi thuộc chủ đề này</div>
                        `;
                        
                        // Thêm từng câu hỏi vào danh sách
                        data.forEach(cauHoi => {
                            const cauHoiId = cauHoi.id;
                            const checked = cauHoi.is_selected ? 'checked' : '';
                            const disabled = daCoCauHoi.includes(cauHoiId.toString()) && !cauHoi.is_selected ? 'disabled' : '';
                            const textMuted = disabled ? 'text-muted' : '';
                            
                            // Thêm vào danh sách đã chọn nếu đã được chọn trước đó
                            if (cauHoi.is_selected && !daCoCauHoi.includes(cauHoiId.toString())) {
                                daCoCauHoi.push(cauHoiId.toString());
                            }
                            
                            // Hiển thị thông tin chủ đề gốc nếu khác với chủ đề hiện tại
                            let chuDeInfo = '';
                            if (cauHoi.original_chu_de_id && cauHoi.original_chu_de_id != chuDeId && cauHoi.original_chu_de_ten) {
                                chuDeInfo = ` <span class="badge bg-warning">Chủ đề gốc: ${cauHoi.original_chu_de_ten}</span>`;
                            }
                            
                            cauHoiListHTML += `
                                <div class="form-check mb-2 cau-hoi-item ${textMuted}">
                                    <input class="form-check-input cau-hoi-checkbox" type="checkbox" 
                                           name="chu_de[${chuDeIndex}][cau_hoi_ids][]" value="${cauHoiId}" 
                                           id="chu_de_${chuDeIndex}_cauhoi_${cauHoiId}" ${checked} ${disabled}
                                           data-chu-de-id="${chuDeId}">
                                    <label class="form-check-label" for="chu_de_${chuDeIndex}_cauhoi_${cauHoiId}">
                                        ${cauHoi.noi_dung}${chuDeInfo}
                                    </label>
                                    <input type="hidden" name="chu_de[${chuDeIndex}][cau_hoi_chu_de][${cauHoiId}]" value="${chuDeId}">
                                </div>
                            `;
                        });
                        
                        cauHoiListHTML += `
                                </div>
                            </div>
                        `;
                        
                        // Hiển thị danh sách câu hỏi
                        cauHoiContainer.innerHTML = cauHoiListHTML;
                        
                        // Thêm sự kiện cho các checkbox câu hỏi
                        cauHoiContainer.querySelectorAll('.cau-hoi-checkbox').forEach(checkbox => {
                            checkbox.addEventListener('change', function() {
                                quanLyCauHoi(this);
                            });
                        });
                    })
                    .catch(error => {
                        cauHoiContainer.innerHTML = '<div class="error p-3">Lỗi khi tải danh sách câu hỏi</div>';
                    });
            }
            
            // Hàm quản lý trạng thái câu hỏi
            function quanLyCauHoi(checkbox) {
                const cauHoiId = checkbox.value;
                
                if (checkbox.checked) {
                    // Thêm vào danh sách câu hỏi đã chọn
                    if (!daCoCauHoi.includes(cauHoiId)) {
                        daCoCauHoi.push(cauHoiId);
                    }
                    
                    // Vô hiệu hóa cùng câu hỏi ở các chủ đề khác
                    document.querySelectorAll(`.cau-hoi-checkbox[value="${cauHoiId}"]`).forEach(cb => {
                        if (cb !== checkbox) {
                            cb.disabled = true;
                            cb.parentElement.classList.add('text-muted');
                        }
                    });
                } else {
                    // Xóa khỏi danh sách câu hỏi đã chọn
                    daCoCauHoi = daCoCauHoi.filter(id => id !== cauHoiId);
                    
                    // Mở lại khả năng chọn ở các chủ đề khác
                    document.querySelectorAll(`.cau-hoi-checkbox[value="${cauHoiId}"]`).forEach(cb => {
                        cb.disabled = false;
                        cb.parentElement.classList.remove('text-muted');
                    });
                }
            }
            
            // Theo dõi sự kiện thay đổi chủ đề
            const chuDeSelects = document.querySelectorAll('.chu-de-select');
            chuDeSelects.forEach((select, index) => {
                const chuDeMoiDiv = select.closest('.chu-de-item').querySelector('.chu-de-moi');
                
                // Kích hoạt sự kiện change để hiển thị đúng
                if (select.value === 'new') {
                    chuDeMoiDiv.style.display = 'block';
                }
                
                // Tải danh sách câu hỏi từ dữ liệu đã có sẵn
                if (select.value && select.value !== 'new') {
                    const chuDeItem = select.closest('.chu-de-item');
                    const cauHoiList = chuDeItem.querySelector('.cau-hoi-list');
                    const chuDeId = select.value;
                    
                    // Đánh dấu tất cả các câu hỏi đã chọn trong chủ đề này
                    const existingQuestions = cauHoiList.querySelectorAll('.cau-hoi-checkbox:checked');
                    existingQuestions.forEach(checkbox => {
                        const cauHoiId = checkbox.value;
                        if (!daCoCauHoi.includes(cauHoiId)) {
                            daCoCauHoi.push(cauHoiId);
                        }
                    });
                    
                    // Đánh dấu rằng chủ đề này đã được tải
                    select.setAttribute('data-loaded', 'true');
                }
                
                select.addEventListener('change', function() {
                    if (this.value === 'new') {
                        chuDeMoiDiv.style.display = 'block';
                    } else {
                        chuDeMoiDiv.style.display = 'none';
                        
                        // Lấy thông tin môn học của chủ đề này
                        if (this.value) {
                            // Lọc các chủ đề trong dropdown của các chủ đề khác để chỉ hiển thị chủ đề thuộc cùng môn học
                            const selectedOption = this.options[this.selectedIndex];
                            const monHocId = selectedOption.getAttribute('data-mon-hoc-id');
                            
                            if (monHocId) {
                                // Cập nhật trạng thái các dropdown chủ đề khác
                                document.querySelectorAll('.chu-de-select').forEach(otherSelect => {
                                    if (otherSelect !== this && otherSelect.value === '') {
                                        // Lọc các option để chỉ hiển thị chủ đề thuộc cùng môn học
                                        Array.from(otherSelect.options).forEach(option => {
                                            const optionMonHocId = option.getAttribute('data-mon-hoc-id');
                                            if (option.value === '' || option.value === 'new' || optionMonHocId === monHocId) {
                                                option.style.display = '';
                                            } else {
                                                option.style.display = 'none';
                                            }
                                        });
                                    }
                                });
                            }
                            
                            // Nếu chưa tải thì mới tải danh sách câu hỏi mới
                            if (this.getAttribute('data-loaded') !== 'true') {
                                taiDanhSachCauHoi(this.value, index);
                                this.setAttribute('data-loaded', 'true');
                            }
                        }
                    }
                });
            });
            
            // Thiết lập sự kiện cho tất cả các checkbox đã hiển thị
            document.querySelectorAll('.cau-hoi-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    quanLyCauHoi(this);
                });
                
                // Nếu checkbox đã được chọn, cập nhật mảng daCoCauHoi
                if (checkbox.checked) {
                    const cauHoiId = checkbox.value;
                    if (!daCoCauHoi.includes(cauHoiId)) {
                        daCoCauHoi.push(cauHoiId);
                    }
                    
                    // Vô hiệu hóa cùng câu hỏi ở các chủ đề khác
                    document.querySelectorAll(`.cau-hoi-checkbox[value="${cauHoiId}"]:not(:checked)`).forEach(cb => {
                        cb.disabled = true;
                        cb.parentElement.classList.add('text-muted');
                    });
                }
            });
            
            // Kiểm tra bật/tắt các câu hỏi ở các chủ đề khác
            function toggleOtherQuestions() {
                // Vô hiệu hóa tất cả các câu hỏi đã chọn ở chủ đề khác
                daCoCauHoi.forEach(cauHoiId => {
                    // Tìm checkbox đã chọn cho câu hỏi này
                    const selectedCheckboxes = document.querySelectorAll(`.cau-hoi-checkbox[value="${cauHoiId}"]:checked`);
                    if (selectedCheckboxes.length > 0) {
                        // Nếu có checkbox được chọn, vô hiệu hóa các checkbox còn lại
                        document.querySelectorAll(`.cau-hoi-checkbox[value="${cauHoiId}"]:not(:checked)`).forEach(cb => {
                            cb.disabled = true;
                            cb.parentElement.classList.add('text-muted');
                        });
                    } else {
                        // Nếu không có checkbox nào được chọn, mở khóa tất cả
                        document.querySelectorAll(`.cau-hoi-checkbox[value="${cauHoiId}"]`).forEach(cb => {
                            cb.disabled = false;
                            cb.parentElement.classList.remove('text-muted');
                        });
                    }
                });
            }
            
            // Cập nhật số lượng câu hỏi đã chọn
            function updateSelectedQuestions() {
                // Đếm lại tổng số câu hỏi đã chọn
                let count = 0;
                document.querySelectorAll('.cau-hoi-checkbox:checked').forEach(() => {
                    count++;
                });
                
                // Cập nhật danh sách câu hỏi đã chọn
                daCoCauHoi = [];
                document.querySelectorAll('.cau-hoi-checkbox:checked').forEach(checkbox => {
                    daCoCauHoi.push(checkbox.value);
                });
                
                // Vô hiệu hóa các câu hỏi đã chọn ở các chủ đề khác
                toggleOtherQuestions();
            }
            
            // Thiết lập sự kiện chọn tự động cho các chủ đề hiện có
            document.querySelectorAll('.btn-auto-select').forEach(button => {
                button.addEventListener('click', function() {
                    const chuDeContainer = this.closest('.chu-de-item');
                    const numberInput = chuDeContainer.querySelector('.auto-select-number');
                    const numToSelect = parseInt(numberInput.value);
                    
                    if (isNaN(numToSelect) || numToSelect <= 0) {
                        alert('Vui lòng nhập số lượng câu hỏi hợp lệ.');
                        return;
                    }
                    
                    // Lấy tất cả câu hỏi khả dụng (chưa bị disabled)
                    const availableQuestions = Array.from(
                        chuDeContainer.querySelectorAll('.cau-hoi-checkbox:not(:disabled):not(:checked)')
                    );
                    
                    if (availableQuestions.length === 0) {
                        alert('Không còn câu hỏi khả dụng để chọn.');
                        return;
                    }
                    
                    if (numToSelect > availableQuestions.length) {
                        alert(`Chỉ có ${availableQuestions.length} câu hỏi khả dụng. Sẽ chọn tất cả câu hỏi khả dụng.`);
                    }
                    
                    // Lấy số lượng câu hỏi ngẫu nhiên theo số đã nhập
                    const questionsToSelect = shuffleArray(availableQuestions)
                        .slice(0, Math.min(numToSelect, availableQuestions.length));
                    
                    // Chọn các câu hỏi
                    questionsToSelect.forEach(checkbox => {
                        checkbox.checked = true;
                        quanLyCauHoi(checkbox);
                    });
                });
            });
            
            // Hàm để trộn mảng (thuật toán Fisher-Yates)
            function shuffleArray(array) {
                for (let i = array.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [array[i], array[j]] = [array[j], array[i]];
                }
                return array;
            }
        });
        
        function toggleCheDoThi() {
            const cheDoBTVN = document.getElementById('che_do_btvn').checked;
            document.getElementById('thoi_gian_tren_lop').style.display = cheDoBTVN ? 'none' : 'block';
            document.getElementById('thoi_gian_btvn').style.display = cheDoBTVN ? 'block' : 'none';
            
            // Reset values
            if (cheDoBTVN) {
                document.getElementById('thoi_gian_lam').value = '';
            } else {
                document.getElementById('thoi_gian_bat_dau').value = '';
                document.getElementById('thoi_gian_ket_thuc').value = '';
            }
        }
    </script>
</body>
</html> 