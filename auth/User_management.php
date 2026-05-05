<?php
require_once '../DB/dbInfo.php';

class UserManagement {
    private $user_id = null;
    private $name = null;
    private $email = null; 
    private $password = null; 
    private $role = null; 

    public function __construct($name = null, $email = null, $password = null, $role = null, $user_id = null) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $user_id;
        $this->user_id = $user_id;
    }

    public static function registerUser($name, $email, $password) {
        try {
            $conn = DbInfo::connectDB();

            // Làm sạch dữ liệu đầu vào
            $name = htmlspecialchars(strip_tags(trim($name)), ENT_QUOTES, 'UTF-8');
            $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
            $password = trim($password); // Mật khẩu sẽ được mã hóa, không cần htmlspecialchars

            // Kiểm tra dữ liệu đầu vào
            if (empty($name) || empty($email) || empty($password)) {
                throw new Exception("All fields are required.");
            }

            // Kiểm tra độ dài tên (tối đa 100 ký tự)
            if (strlen($name) > 100) {
                throw new Exception("Name must not exceed 100 characters.");
            }

            // Kiểm tra định dạng email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format.");
            }

            // Kiểm tra độ dài mật khẩu (tối thiểu 8 ký tự)
            if (strlen($password) < 8) {
                throw new Exception("Password must be at least 8 characters long.");
            }

            // Kiểm tra email đã tồn tại
            $sql_check = "SELECT COUNT(*) FROM Users WHERE email = :email";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->execute([':email' => $email]);
            if ($stmt_check->fetchColumn() > 0) {
                throw new Exception("Email already exists.");
            }

            // Mã hóa mật khẩu
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Thêm người dùng vào bảng Users với role cố định là job_seeker
            $sql = "INSERT INTO Users (name, email, password_hash, role)
                    VALUES (:name, :email, :password_hash, 'job_seeker')";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password_hash' => $password_hash
            ]);

            // Đóng kết nối
            $conn = null;

            return ["success" => true, "message" => "Registration successful!"];
        } catch (Exception $e) {
            // Đóng kết nối nếu có lỗi
            if (isset($conn)) {
                $conn = null;
            }
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public static function addUser($name, $email, $password, $role) {
        try {
            $name = htmlspecialchars(strip_tags(trim($name)), ENT_QUOTES, 'UTF-8');
            $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
            $password = trim($password); 

            // Kiểm tra dữ liệu đầu vào
            if (empty($name) || empty($email) || empty($password) || empty($role)) {
                throw new Exception("All fields are required.");
            }

            // Kiểm tra độ dài tên (tối đa 100 ký tự)
            if (strlen($name) > 100) {
                throw new Exception("Name must not exceed 100 characters.");
            }

            // Kiểm tra định dạng email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format.");
            }

            // Kiểm tra độ dài mật khẩu (tối thiểu 8 ký tự)
            if (strlen($password) < 8) {
                throw new Exception("Password must be at least 8 characters long.");
            }

            // Kiểm tra email đã tồn tại
            $conn = DbInfo::connectDB();
            $sql_check = "SELECT COUNT(*) FROM Users WHERE email = :email";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->execute([':email' => $email]);
            if ($stmt_check->fetchColumn() > 0) {
                throw new Exception("Email already exists.");
            }

            // Mã hóa mật khẩu
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO Users (name, email, password_hash, role)
                    VALUES (:name, :email, :password_hash, :role)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password_hash' => $password_hash,
                ':role' => $role
            ]);

            // Đóng kết nối
            $conn = null;

            return ["success" => true, "message" => "Đăng ký thành công!"];
        } catch (Exception $e) {
            // Đóng kết nối nếu có lỗi
            if (isset($conn)) {
                $conn = null;
            }
            return ["success" => false, "message" => $e->getMessage()];
        } 
    }

    public function deleteUser($id) {
        try {
            $conn = DbInfo::connectDB();
            $sql = 'DELETE FROM Companies WHERE company_id = :id';
            $stmt = $conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $conn = null;
        } catch (Exception $e) {
            echo 'Lỗi: ' . $e->getMessage();
        }
    }


    public static function getUsers() {
        try {
            $conn = DbInfo::connectDB();
            $sql = "SELECT * FROM Users ORDER BY created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $conn = null;
            return $users;
        } catch (Exception $e) {
            echo 'Lỗi: ' . $e->getMessage();
            return [];
        }
    }
}
?>