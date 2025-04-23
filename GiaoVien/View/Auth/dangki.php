<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản giáo viên</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Đăng ký tài khoản giáo viên</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success">
                                <?php echo $success; ?>
                                <div class="mt-2">
                                    <a href="index.php?controller=auth&action=login" class="btn btn-primary">Đăng nhập ngay</a>
                                </div>
                            </div>
                        <?php else: ?>
                        
                        <form method="POST" action="index.php?controller=auth&action=register" id="registerForm" novalidate>
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="mb-3">Thông tin cá nhân</h4>
                                    <div class="mb-3">
                                        <label for="ma_so" class="form-label">Mã số giáo viên <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="ma_so" name="ma_so" value="<?php echo isset($_POST['ma_so']) ? htmlspecialchars($_POST['ma_so']) : ''; ?>" required>
                                        <div class="invalid-feedback">Vui lòng nhập mã số giáo viên</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="ho_va_ten" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="ho_va_ten" name="ho_va_ten" value="<?php echo isset($_POST['ho_va_ten']) ? htmlspecialchars($_POST['ho_va_ten']) : ''; ?>" required>
                                        <div class="invalid-feedback">Vui lòng nhập họ và tên</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                                        <div class="invalid-feedback" id="email-feedback">Vui lòng nhập email hợp lệ</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mat_khau" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="mat_khau" name="mat_khau" placeholder="Nhập 6 chữ số" required pattern="[0-9]{6}" maxlength="6">
                                        <div class="form-text">Mật khẩu phải là 6 chữ số.</div>
                                        <div class="invalid-feedback" id="password-feedback">Mật khẩu phải là 6 chữ số</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="xac_nhan_mat_khau" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="xac_nhan_mat_khau" name="xac_nhan_mat_khau" placeholder="Nhập lại 6 chữ số" required pattern="[0-9]{6}" maxlength="6">
                                        <div class="invalid-feedback" id="password-match-feedback">Mật khẩu xác nhận phải khớp với mật khẩu</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h4 class="mb-3">Thông tin chuyên môn</h4>
                                    <div class="mb-3">
                                        <label for="hoc_vi" class="form-label">Học vị</label>
                                        <select class="form-select" id="hoc_vi" name="hoc_vi">
                                            <option value="">-- Chọn học vị --</option>
                                            <option value="Cử Nhân" <?php echo (isset($_POST['hoc_vi']) && $_POST['hoc_vi'] == 'Cử Nhân') ? 'selected' : ''; ?>>Cử Nhân</option>
                                            <option value="Thạc Sĩ" <?php echo (isset($_POST['hoc_vi']) && $_POST['hoc_vi'] == 'Thạc Sĩ') ? 'selected' : ''; ?>>Thạc Sĩ</option>
                                            <option value="Tiến Sĩ" <?php echo (isset($_POST['hoc_vi']) && $_POST['hoc_vi'] == 'Tiến Sĩ') ? 'selected' : ''; ?>>Tiến Sĩ</option>
                                            <option value="Phó Giáo Sư" <?php echo (isset($_POST['hoc_vi']) && $_POST['hoc_vi'] == 'Phó Giáo Sư') ? 'selected' : ''; ?>>Phó Giáo Sư</option>
                                            <option value="Giáo Sư" <?php echo (isset($_POST['hoc_vi']) && $_POST['hoc_vi'] == 'Giáo Sư') ? 'selected' : ''; ?>>Giáo Sư</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="chuyen_nganh" class="form-label">Chuyên ngành</label>
                                        <input type="text" class="form-control" id="chuyen_nganh" name="chuyen_nganh" value="<?php echo isset($_POST['chuyen_nganh']) ? htmlspecialchars($_POST['chuyen_nganh']) : ''; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="mo_ta" class="form-label">Mô tả bản thân</label>
                                        <textarea class="form-control" id="mo_ta" name="mo_ta" rows="4"><?php echo isset($_POST['mo_ta']) ? htmlspecialchars($_POST['mo_ta']) : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="dong_y" name="dong_y" required>
                                    <label class="form-check-label" for="dong_y">
                                        Tôi đồng ý với các điều khoản và điều kiện của hệ thống.
                                    </label>
                                    <div class="invalid-feedback">Bạn phải đồng ý với điều khoản và điều kiện</div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" name="submit" value="register" id="submitBtn">Đăng ký</button>
                                <a href="index.php?controller=auth&action=login" class="btn btn-outline-secondary">Đã có tài khoản? Đăng nhập</a>
                            </div>
                        </form>
                        
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Kiểm tra form trước khi submit
        document.getElementById('registerForm').addEventListener('submit', function(event) {
            let isValid = true;
            
            // Xóa các class invalid cũ
            const inputs = this.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.classList.remove('is-invalid');
            });
            
            // Kiểm tra các trường bắt buộc
            const requiredFields = ['ma_so', 'ho_va_ten', 'email', 'mat_khau', 'xac_nhan_mat_khau'];
            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                }
            });
            
            // Kiểm tra email
            const email = document.getElementById('email');
            if (email.value.trim() && !isValidEmail(email.value.trim())) {
                email.classList.add('is-invalid');
                document.getElementById('email-feedback').textContent = 'Email không đúng định dạng';
                isValid = false;
            }
            
            // Kiểm tra mật khẩu là 6 chữ số
            const password = document.getElementById('mat_khau');
            if (password.value && !isValidPassword(password.value)) {
                password.classList.add('is-invalid');
                document.getElementById('password-feedback').textContent = 'Mật khẩu phải là 6 chữ số';
                isValid = false;
            }
            
            // Kiểm tra mật khẩu khớp nhau
            const confirmPassword = document.getElementById('xac_nhan_mat_khau');
            if (password.value && confirmPassword.value && password.value !== confirmPassword.value) {
                confirmPassword.classList.add('is-invalid');
                document.getElementById('password-match-feedback').textContent = 'Mật khẩu xác nhận không khớp';
                isValid = false;
            }
            
            // Kiểm tra checkbox đồng ý
            const agree = document.getElementById('dong_y');
            if (!agree.checked) {
                agree.classList.add('is-invalid');
                isValid = false;
            }
            
            if (!isValid) {
                event.preventDefault();
            }
        });
        
        // Hàm kiểm tra email hợp lệ
        function isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        
        // Kiểm tra mật khẩu 6 chữ số
        function isValidPassword(password) {
            const re = /^\d{6}$/;
            return re.test(password);
        }
        
        // Kiểm tra mật khẩu khi nhập
        document.getElementById('mat_khau').addEventListener('input', function() {
            if (this.value && !isValidPassword(this.value)) {
                this.classList.add('is-invalid');
                document.getElementById('password-feedback').textContent = 'Mật khẩu phải là 6 chữ số';
            } else {
                this.classList.remove('is-invalid');
            }
            
            // Kiểm tra lại mật khẩu xác nhận nếu đã nhập
            const confirmPassword = document.getElementById('xac_nhan_mat_khau');
            if (confirmPassword.value) {
                if (this.value !== confirmPassword.value) {
                    confirmPassword.classList.add('is-invalid');
                    document.getElementById('password-match-feedback').textContent = 'Mật khẩu xác nhận không khớp';
                } else {
                    confirmPassword.classList.remove('is-invalid');
                }
            }
        });
        
        // Kiểm tra email khi nhập
        document.getElementById('email').addEventListener('input', function() {
            if (this.value && !isValidEmail(this.value)) {
                this.classList.add('is-invalid');
                document.getElementById('email-feedback').textContent = 'Email không đúng định dạng';
            } else {
                this.classList.remove('is-invalid');
            }
        });
        
        // Kiểm tra mật khẩu xác nhận khi nhập
        document.getElementById('xac_nhan_mat_khau').addEventListener('input', function() {
            const password = document.getElementById('mat_khau').value;
            if (this.value && password && this.value !== password) {
                this.classList.add('is-invalid');
                document.getElementById('password-match-feedback').textContent = 'Mật khẩu xác nhận không khớp';
            } else {
                this.classList.remove('is-invalid');
            }
        });
    </script>
</body>
</html>
