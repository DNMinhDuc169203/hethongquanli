<?php
require_once __DIR__ . '/../Config/Database.php';

class UserModel {
    private $db;
    private $table = 'nguoi_dung';

    public function __construct($db = null) {
        if ($db) {
            $this->db = $db;
        } else {
            $database = new Database();
            $this->db = $database->getConnection();
        }
    }

    public function getAllUsers() {
        try {
            // Kiểm tra xem cột trang_thai có tồn tại trong bảng nguoi_dung không
            $checkColumnSql = "SHOW COLUMNS FROM nguoi_dung LIKE 'trang_thai'";
            $checkStmt = $this->db->prepare($checkColumnSql);
            $checkStmt->execute();
            $columnExists = $checkStmt->rowCount() > 0;
            
            // Xây dựng câu truy vấn SQL dựa trên sự tồn tại của cột trang_thai
            $sql = "SELECT 
                    id, 
                    ma_so, 
                    ho_va_ten, 
                    email, 
                    vai_tro";
            
            // Thêm cột trang_thai vào câu truy vấn nếu nó tồn tại
            if ($columnExists) {
                $sql .= ", trang_thai";
            }
            
            $sql .= ", ngay_tao
                FROM 
                    nguoi_dung 
                ORDER BY 
                    ngay_tao DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Đảm bảo có giá trị mặc định cho trang_thai nếu cột không tồn tại
            if (!$columnExists) {
                foreach ($users as &$user) {
                    $user['trang_thai'] = 1; // Giá trị mặc định
                }
            }
            
            return $users;
        } catch (PDOException $e) {
            error_log('Lỗi truy vấn danh sách người dùng: ' . $e->getMessage());
            return [];
        }
    }

    public function getUserById($id) {
        try {
            // Kiểm tra xem cột trang_thai có tồn tại trong bảng người dùng không
            $checkColumnSql = "SHOW COLUMNS FROM nguoi_dung LIKE 'trang_thai'";
            $checkStmt = $this->db->prepare($checkColumnSql);
            $checkStmt->execute();
            $columnExists = $checkStmt->rowCount() > 0;
            
            // Xây dựng câu truy vấn SQL dựa trên sự tồn tại của cột trang_thai
            $sql = "SELECT 
                    id, 
                    ma_so, 
                    ho_va_ten, 
                    email, 
                    vai_tro";
            
            // Thêm cột trang_thai vào câu truy vấn nếu nó tồn tại
            if ($columnExists) {
                $sql .= ", trang_thai";
            }
            
            $sql .= ", ngay_tao
                FROM 
                    nguoi_dung 
                WHERE 
                    id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Đảm bảo có giá trị mặc định cho trang_thai nếu cột không tồn tại
            if ($user && !$columnExists) {
                $user['trang_thai'] = 1; // Giá trị mặc định
            }
            
            return $user;
        } catch (PDOException $e) {
            error_log('Lỗi truy vấn dữ liệu người dùng: ' . $e->getMessage());
            return null;
        }
    }

    public function getTeacherInfo($userId) {
        try {
            $sql = "SELECT 
                    gv.id as giao_vien_id,
                    gv.hoc_vi,
                    gv.chuyen_nganh,
                    gv.mo_ta,
                    COUNT(DISTINCT lh.id) as so_lop_hoc
                FROM 
                    giao_vien gv
                LEFT JOIN
                    lop_hoc lh ON gv.id = lh.giao_vien_id
                WHERE 
                    gv.nguoi_dung_id = ?
                GROUP BY
                    gv.id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Lỗi truy vấn dữ liệu giáo viên: ' . $e->getMessage());
            return null;
        }
    }

    public function getTeacherSubjects($userId) {
        try {
            // Lấy ID giáo viên
            $sql = "SELECT id FROM giao_vien WHERE nguoi_dung_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$teacher) {
                return [];
            }
            
            // Lấy danh sách môn học
            $sql = "SELECT 
                    id,
                    ma_mon,
                    ten_mon,
                    hoc_ky,
                    nam_hoc,
                    so_tin_chi
                FROM 
                    mon_hoc
                WHERE 
                    giao_vien_id = ?
                ORDER BY
                    nam_hoc DESC, hoc_ky ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$teacher['id']]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Lỗi truy vấn môn học giáo viên: ' . $e->getMessage());
            return [];
        }
    }

    public function getStudentInfo($userId) {
        try {
            $sql = "SELECT 
                    sv.id as sinh_vien_id,
                    sv.nam_nhap_hoc,
                    sv.nganh_hoc,
                    lh.id as lop_hoc_id,
                    lh.ma_lop,
                    lh.ten_lop,
                    gv.nguoi_dung_id as giao_vien_id,
                    nd_gv.ho_va_ten as ten_giao_vien
                FROM 
                    sinh_vien sv
                LEFT JOIN
                    lop_hoc lh ON sv.lop_hoc_id = lh.id
                LEFT JOIN
                    giao_vien gv ON lh.giao_vien_id = gv.id
                LEFT JOIN
                    nguoi_dung nd_gv ON gv.nguoi_dung_id = nd_gv.id
                WHERE 
                    sv.nguoi_dung_id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Lỗi truy vấn dữ liệu sinh viên: ' . $e->getMessage());
            return null;
        }
    }

    public function getStudentSubjects($userId) {
        try {
            // Lấy thông tin sinh viên và lớp học
            $sql = "SELECT sv.id, sv.lop_hoc_id FROM sinh_vien sv WHERE sv.nguoi_dung_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$student || !$student['lop_hoc_id']) {
                return [];
            }
            
            // Lấy danh sách môn học đã đăng ký
            $sql = "SELECT 
                    mh.id,
                    mh.ma_mon,
                    mh.ten_mon,
                    mh.hoc_ky,
                    mh.nam_hoc,
                    mh.so_tin_chi,
                    sv_mh.diem_trung_binh
                FROM 
                    mon_hoc mh
                JOIN
                    sinh_vien_mon_hoc sv_mh ON mh.id = sv_mh.mon_hoc_id
                WHERE 
                    sv_mh.sinh_vien_id = ?
                ORDER BY
                    mh.nam_hoc DESC, mh.hoc_ky ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$student['id']]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Lỗi truy vấn môn học sinh viên: ' . $e->getMessage());
            return [];
        }
    }

    public function updateUserStatus($userId, $status) {
        try {
            // Kiểm tra xem cột trang_thai có tồn tại trong bảng nguoi_dung không
            $checkColumnSql = "SHOW COLUMNS FROM nguoi_dung LIKE 'trang_thai'";
            $checkStmt = $this->db->prepare($checkColumnSql);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() == 0) {
                // Cột trang_thai không tồn tại, thử thêm cột này vào bảng
                try {
                    $alterSql = "ALTER TABLE nguoi_dung ADD COLUMN trang_thai TINYINT(1) NOT NULL DEFAULT 1";
                    $this->db->exec($alterSql);
                    error_log('Đã thêm cột trang_thai vào bảng nguoi_dung');
                } catch (PDOException $e) {
                    error_log('Không thể thêm cột trang_thai: ' . $e->getMessage());
                    return false;
                }
            }
            
            // Tiến hành cập nhật trạng thái
            $sql = "UPDATE nguoi_dung SET trang_thai = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$status, $userId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log('Lỗi cập nhật trạng thái người dùng: ' . $e->getMessage());
            return false;
        }
    }

    public function resetUserPassword($userId, $password) {
        try {
            $sql = "UPDATE nguoi_dung SET mat_khau = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$password, $userId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log('Lỗi đặt lại mật khẩu người dùng: ' . $e->getMessage());
            return false;
        }
    }

    public function createUser($ma_so, $ho_va_ten, $email, $mat_khau, $vai_tro) {
        try {
            // Kiểm tra xem cột trang_thai có tồn tại trong bảng nguoi_dung không
            $checkColumnSql = "SHOW COLUMNS FROM nguoi_dung LIKE 'trang_thai'";
            $checkStmt = $this->db->prepare($checkColumnSql);
            $checkStmt->execute();
            $columnExists = $checkStmt->rowCount() > 0;
            
            if ($columnExists) {
                $sql = "INSERT INTO nguoi_dung (ma_so, ho_va_ten, email, mat_khau, vai_tro, trang_thai, ngay_tao) 
                        VALUES (?, ?, ?, ?, ?, 1, NOW())";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$ma_so, $ho_va_ten, $email, $mat_khau, $vai_tro]);
            } else {
                $sql = "INSERT INTO nguoi_dung (ma_so, ho_va_ten, email, mat_khau, vai_tro, ngay_tao) 
                        VALUES (?, ?, ?, ?, ?, NOW())";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$ma_so, $ho_va_ten, $email, $mat_khau, $vai_tro]);
            }
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log('Lỗi tạo người dùng mới: ' . $e->getMessage());
            return false;
        }
    }

    public function updateUser($id, $ho_va_ten, $email, $vai_tro) {
        try {
            $sql = "UPDATE nguoi_dung SET ho_va_ten = ?, email = ?, vai_tro = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$ho_va_ten, $email, $vai_tro, $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log('Lỗi cập nhật thông tin người dùng: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteUser($id) {
        try {
            $sql = "DELETE FROM nguoi_dung WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log('Lỗi xóa người dùng: ' . $e->getMessage());
            return false;
        }
    }
}