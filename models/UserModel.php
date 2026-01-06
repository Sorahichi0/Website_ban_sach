 <?php
 require_once 'BaseModel.php';

class UserModel extends BaseModel {

    private $table = "tbluser"; // Đổi thành 'tbluser' nếu tên bảng của bạn là vậy
    public $email;
    public $password;
    public $fullname;
    public $phone;
    public $address;

    public function create() {
        // Giả định user_id là AUTO_INCREMENT, nên không cần đưa vào câu lệnh INSERT
        $query = "INSERT INTO {$this->table} (fullname, email, password, phone, address, role_id) VALUES (:fullname, :email, :password, :phone, :address, :role_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":fullname", $this->fullname);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindValue(":role_id", 3); // Mặc định là Customer (ID=3)
        $stmt->bindValue(":phone", ''); // Giá trị mặc định
        $stmt->bindValue(":address", ''); // Giá trị mặc định
        return $stmt->execute();
    }

    public function verify($token) {
        $query = "SELECT * FROM {$this->table} WHERE verification_token = :token AND is_verified = 0";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        return $stmt;
    }

    public function setVerified($token) {
        $query = "UPDATE {$this->table} SET is_verified = 1, verification_token = NULL WHERE verification_token = :token";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":token", $token);
        return $stmt->execute();
    }

        // Kiểm tra email đã tồn tại chưa
    public function emailExists($email) {
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
       public function findByEmail($email) {
        $query = "SELECT u.user_id, u.fullname, u.email, u.phone, u.address, u.password, u.is_active, r.role_name 
                  FROM {$this->table} u
                  LEFT JOIN roles r ON u.role_id = r.id
                  WHERE u.email = :email 
                  LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về mảng dữ liệu hoặc false
    }

       // Đặt lại mật khẩu mới cho user
    public function updatePassword($email, $newPasswordHash) {
        $query = "UPDATE {$this->table} SET password = :password WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":password", $newPasswordHash);
        $stmt->bindParam(":email", $email);
        return $stmt->execute();
    }

    // Lấy thông tin người dùng theo ID
    public function getUserByUserId($user_id) { // Đổi tên phương thức và tham số
        $sql = "SELECT u.user_id, u.fullname, u.email, u.phone, u.address, u.is_active, u.role_id, r.role_name 
                FROM {$this->table} u
                LEFT JOIN roles r ON u.role_id = r.id
                WHERE u.user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id); // Bind tham số user_id
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật thông tin người dùng (không bao gồm mật khẩu)
    public function updateUser($user_id, $fullname, $phone, $address, $role_id = null) {
        // Kiểm tra xem ID có hợp lệ không
        if (empty($user_id)) {
            return false;
        }
        $role_update_sql = $role_id ? ", role_id = :role_id" : "";
        $sql = "UPDATE {$this->table} SET fullname = :fullname, phone = :phone, address = :address {$role_update_sql} WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':user_id', $user_id); // Bind tham số user_id
        if ($role_id) {
            $stmt->bindParam(':role_id', $role_id);
        }
        return $stmt->execute();
    }

    // Cập nhật trạng thái (active/locked) của người dùng
    public function updateUserStatus($user_id, $is_active) {
        $sql = "UPDATE {$this->table} SET is_active = :is_active WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':is_active' => $is_active, ':user_id' => $user_id]);
    }

    public function getAllUsers() {
    // Câu lệnh SQL được cập nhật để JOIN với bảng orders và order_details
    $sql = "SELECT 
                u.user_id, u.fullname, u.email, u.phone, u.address, u.is_verified, u.created_at, u.is_active, r.role_name,
                COUNT(DISTINCT o.id) as order_count,
                COALESCE(SUM(o.total_amount), 0) as total_spent
            FROM tbluser u
            LEFT JOIN orders o ON u.email = o.user_email AND o.transaction_info = 'dathanhtoan'
            LEFT JOIN roles r ON u.role_id = r.id
            GROUP BY u.user_id
            ORDER BY u.user_id DESC";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function searchUserByPhone($phone) {
    $sql = "SELECT 
                u.user_id, u.fullname, u.email, u.phone, u.address, u.is_verified, u.created_at, u.is_active, r.role_name,
                COUNT(DISTINCT o.id) as order_count,
                COALESCE(SUM(o.total_amount), 0) as total_spent
            FROM tbluser u
            LEFT JOIN orders o ON u.email = o.user_email AND o.transaction_info = 'dathanhtoan'
            LEFT JOIN roles r ON u.role_id = r.id
            WHERE u.phone LIKE :phone
            GROUP BY u.user_id
            ORDER BY u.user_id DESC";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':phone' => "%$phone%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function deleteUser($user_id) {
        $sql = "DELETE FROM {$this->table} WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    // Lấy tất cả các vai trò
    public function getAllRoles() {
        $sql = "SELECT * FROM roles";
        return $this->select($sql);
    }

    // Lấy tất cả tài khoản quản trị
    public function getAdminAccounts() {
        $sql = "SELECT u.*, r.role_name FROM {$this->table} u JOIN roles r ON u.role_id = r.id WHERE r.role_name != 'Customer' ORDER BY u.user_id DESC";
        return $this->select($sql);
    }
}
