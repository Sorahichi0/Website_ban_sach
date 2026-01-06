    
<?php
require_once 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './app/BaseController.php';
class AuthController extends BaseController {
    // Hiển thị form đăng ký
    //http://localhost/MVC3/AuthController/Show
    public function Show() {
        $this->view("homePage",["page"=>"RegisterView"]);
    }

    // Xử lý đăng ký, gửi OTP
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            if ($fullname === '' || $email === '' || $password === '') {
                echo '<div class="container mt-5"><div class="alert alert-danger">Vui lòng nhập đầy đủ thông tin!</div></div>';
                $this->view("homePage",["page"=>"RegisterView"]);
                return;
            }

            // 🔹 KIỂM TRA EMAIL TỒN TẠI SỚM
            $userModel = $this->model('UserModel');
            if ($userModel->emailExists($email)) {
                $this->view("homePage", [
                    "page" => "RegisterView",
                    "error" => "Email này đã được đăng ký. Vui lòng sử dụng email khác!"
                ]);
                return;
            }

            // Tạo mã OTP
            $otp = rand(100000, 999999);
            $_SESSION['register'] = [
                'fullname' => $fullname,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'otp' => $otp
            ];
            // Gửi OTP qua email
            $this->sendOtpEmail($email, $otp);

            // Hiển thị form nhập OTP
            $this->view("homePage",["page"=>"OtpView"]);
        }
    }

    // Gửi OTP qua Gmail
    private function sendOtpEmail($email, $otp) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'hoanganhtinhtien@gmail.com'; // Thay bằng Gmail của bạn
            $mail->Password = 'tmyr vlir pkfe paox'; // Thay bằng App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('hoanganhtinhtien@gmail.com', 'Your App');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Mã OTP xác thực đăng ký";
            $mail->Body = "Mã OTP của bạn là: <b>$otp</b>";

            $mail->send();
        } catch (Exception $e) {
            echo "Gửi email thất bại: {$mail->ErrorInfo}";
        }
    }

    // Xác thực OTP
    public function verifyOtp() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ép kiểu cả hai giá trị về số nguyên để so sánh chính xác
            $inputOtp = (int) ($_POST['otp'] ?? 0);
            $sessionOtp = (int) ($_SESSION['register']['otp'] ?? -1);

            if (isset($_SESSION['register']) && $sessionOtp === $inputOtp) {
                // Lưu user vào DB
                $user = $this->model('UserModel');
                $email = $_SESSION['register']['email'];
                $user->email = $email;
                $user->password = $_SESSION['register']['password'];
                $user->fullname = $_SESSION['register']['fullname'];
                $user->create();
                
                // Lấy lại thông tin user vừa tạo (bao gồm cả ID) để lưu vào session
                $newUser = $user->findByEmail($email);
                unset($_SESSION['register']);

                // Lưu đầy đủ thông tin user vào session
                $_SESSION['user'] = [ // Giữ khóa 'id' trong session để nhất quán
                    'id'       => $newUser['user_id'], // Sử dụng user_id từ DB
                    'email'    => $newUser['email'],
                    'fullname' => $newUser['fullname'],
                    'phone'    => $newUser['phone'],
                    'address'  => $newUser['address']
                ];
                header('Location: ' . APP_URL . '/Home'); // Chuyển hướng về trang chủ
                exit();
            } else {
                echo '<div class="container mt-5"><div class="alert alert-danger">Mã OTP không đúng!</div></div>';
                $this->view("homePage",["page"=>"OtpView"]);
            }
        }
    }
    // Hiển thị form đăng nhập

    // Hiển thị form cập nhật thông tin cá nhân
    public function showProfile() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $userModel = $this->model('UserModel');
        $userEmail = $_SESSION['user']['email'] ?? null;

        // Nếu không có email trong session, đăng xuất người dùng
        if (!$userEmail) {
            session_destroy();
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        // Tải lại thông tin người dùng từ CSDL để đảm bảo dữ liệu luôn mới nhất
        $user = $userModel->findByEmail($userEmail);

        if (!$user) {
            // Nếu không tìm thấy user trong CSDL, đăng xuất
            session_destroy();
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        // Cập nhật lại session với thông tin đầy đủ, đảm bảo 'id' trong session là 'user_id' từ DB
        $_SESSION['user'] = [
            'id'       => $user['user_id'], // Ánh xạ user_id từ DB vào khóa 'id' trong session
            'email'    => $user['email'],
            'fullname' => $user['fullname'],
            'phone'    => $user['phone'],
            'address'  => $user['address']
        ];
        $this->view("homePage", ["page" => "ProfileView", "user" => $user]);
    }

    // Xử lý cập nhật thông tin cá nhân
    public function updateProfile() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy email từ session để đảm bảo an toàn và lấy ID mới nhất từ DB
            $userEmail = $_SESSION['user']['email'] ?? null;
            if (!$userEmail) {
                // Nếu không có email trong session, đăng xuất người dùng
                session_destroy();
                header('Location: ' . APP_URL . '/AuthController/ShowLogin');
                exit();
            }
            $userModel = $this->model('UserModel');
            // Lấy thông tin người dùng đầy đủ từ CSDL để có ID chính xác
            $currentUser = $userModel->findByEmail($userEmail);
            $userId = $currentUser['user_id'] ?? null; // Lấy user_id từ dữ liệu mới nhất
            $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
            $address = isset($_POST['address']) ? trim($_POST['address']) : '';

            if ($fullname === '' || $phone === '' || $address === '') {
                $this->view("homePage", [
                    "page" => "ProfileView",
                    "user" => $_SESSION['user'], // Pass current session user data back
                    "error" => "Vui lòng nhập đầy đủ thông tin!"
                ]);
                return;
            }
            $userModel = $this->model('UserModel');
            if ($userModel->updateUser($userId, $fullname, $phone, $address)) {
                // Cập nhật lại session user
                $_SESSION['user']['fullname'] = $fullname; // Cập nhật session với dữ liệu mới
                $_SESSION['user']['phone'] = $phone;       // Cập nhật session với dữ liệu mới
                $_SESSION['user']['address'] = $address;    // Cập nhật session với dữ liệu mới

                $this->view("homePage", [
                    "page" => "ProfileView",
                    "user" => $userModel->getUserByUserId($userId), // Lấy dữ liệu người dùng đã cập nhật bằng phương thức mới
                    "success" => "Cập nhật thông tin thành công!"
                ]);
            } else {
                $this->view("homePage", [
                    "page" => "ProfileView",
                    "user" => $userModel->getUserById($userId),
                    "error" => "Có lỗi xảy ra khi cập nhật thông tin."
                ]);
            }
        }
    }

    // Hiển thị form đổi mật khẩu khi đã đăng nhập
    public function showChangePassword() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        $this->view("homePage", ["page" => "ChangePasswordView"]);
    }

    // Xử lý đổi mật khẩu khi đã đăng nhập
    public function changePassword() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_SESSION['user']['email'];
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmNewPassword = $_POST['confirm_new_password'] ?? '';

            $userModel = $this->model('UserModel');
            $user = $userModel->findByEmail($email);

            if (!$user || !password_verify($currentPassword, $user['password'])) {
                $this->view("homePage", ["page" => "ChangePasswordView", "error" => "Mật khẩu hiện tại không đúng!"]);
                return;
            }
            if ($newPassword !== $confirmNewPassword) {
                $this->view("homePage", ["page" => "ChangePasswordView", "error" => "Mật khẩu mới và xác nhận mật khẩu không khớp!"]);
                return;
            }
            if (strlen($newPassword) < 6) { // Example minimum length
                $this->view("homePage", ["page" => "ChangePasswordView", "error" => "Mật khẩu mới phải có ít nhất 6 ký tự!"]);
                return;
            }

            $userModel->updatePassword($email, password_hash($newPassword, PASSWORD_DEFAULT));
            $this->view("homePage", ["page" => "ChangePasswordView", "success" => "Đổi mật khẩu thành công!"]);
        }
    }

    public function ShowLogin() {
      //  $this->view("Font_end/LoginView");
      $this->view("homePage",["page"=>"LoginView"]);
    }

        // Xử lý đăng nhập
    public function login() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $userModel = $this->model('UserModel');
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // 🔹 Lưu thông tin người dùng vào session
            $_SESSION['user'] = [
                'id'       => $user['user_id'], // Sử dụng user_id từ DB, ánh xạ vào khóa 'id' trong session
                'email'    => $user['email'],
                'fullname' => $user['fullname'],
                'phone'    => $user['phone'],
                'address'  => $user['address']
            ];

            // Hợp nhất giỏ hàng session vào giỏ hàng DB
            if (!empty($_SESSION['cart'])) {
                $cartModel = $this->model('CartModel');
                $userId = $user['user_id'];
                foreach ($_SESSION['cart'] as $masp => $item) {
                    $cartModel->addToCart($userId, $masp, $item['qty']);
                }
                // Xóa giỏ hàng session sau khi hợp nhất
                unset($_SESSION['cart']);
            }

            header('Location: ' . APP_URL . '/Home');
            exit();
        } else {
            echo '<div class="container mt-5"><div class="alert alert-danger">Email hoặc mật khẩu không đúng!</div></div>';
            $this->view("homePage", ["page" => "LoginView"]);
        }
    }
}

    // Đăng xuất
    public function logout() {
        session_destroy();
        header('Location: ' . APP_URL . '/Home');
        exit();
    }

    // Hiển thị form yêu cầu đặt lại mật khẩu (nhập email)
    public function forgotPassword() {
        //$this->view("Font_end/ForgotPasswordView");
        $this->view("homePage",["page"=>"ForgotPasswordView"]);
    }

    // Xử lý gửi lại mật khẩu mới qua email
    // Xử lý yêu cầu đặt lại mật khẩu: gửi OTP
    public function requestPasswordResetOtp() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $userModel = $this->model('UserModel');
            $user = $userModel->findByEmail($email);

            if ($user) {
                $otp = rand(100000, 999999);
                $_SESSION['reset_password'] = [
                    'email' => $email,
                    'otp' => $otp,
                    'timestamp' => time() // Để kiểm tra thời gian hết hạn OTP
                ];
                $this->sendResetOtpEmail($email, $otp);
                $this->view("homePage", ["page" => "VerifyResetOtpView", "email" => $email, "message" => "Mã OTP đã được gửi đến email của bạn."]);
            } else {
                $this->view("homePage", ["page" => "ForgotPasswordView", "error" => "Email không tồn tại trong hệ thống!"]);
            }
        }
    }

    // Hiển thị form nhập OTP để đặt lại mật khẩu
    public function showVerifyResetOtpForm() {
        if (!isset($_SESSION['reset_password']['email'])) {
            header('Location: ' . APP_URL . '/AuthController/forgotPassword');
            exit();
        }
        $this->view("homePage", ["page" => "VerifyResetOtpView", "email" => $_SESSION['reset_password']['email']]);
    }

    // Xác thực OTP để đặt lại mật khẩu
    public function verifyResetOtp() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ép kiểu cả hai giá trị về số nguyên để so sánh chính xác
            $inputOtp = (int) ($_POST['otp'] ?? 0);
            $sessionOtp = (int) ($_SESSION['reset_password']['otp'] ?? -1);

            if (isset($_SESSION['reset_password']) && $sessionOtp === $inputOtp) {
                // Kiểm tra thời gian hết hạn OTP (ví dụ: 15 phút)
                if (time() - $_SESSION['reset_password']['timestamp'] > 900) { // 900 seconds = 15 minutes
                    unset($_SESSION['reset_password']);
                    $this->view("homePage", ["page" => "ForgotPasswordView", "error" => "Mã OTP đã hết hạn. Vui lòng thử lại!"]);
                    return;
                }
                
                $_SESSION['can_set_new_password'] = $_SESSION['reset_password']['email']; // Lưu email để xác nhận quyền đặt mật khẩu mới
                unset($_SESSION['reset_password']); // Xóa OTP sau khi xác thực thành công
                header('Location: ' . APP_URL . '/AuthController/showSetNewPasswordForm');
                exit();
            } else {
                $this->view("homePage", ["page" => "VerifyResetOtpView", "email" => $_SESSION['reset_password']['email'] ?? '', "error" => "Mã OTP không đúng!"]);
            }
        }
    }

    // Hiển thị form đặt mật khẩu mới sau khi xác thực OTP
    public function showSetNewPasswordForm() {
        if (!isset($_SESSION['can_set_new_password'])) {
            header('Location: ' . APP_URL . '/AuthController/forgotPassword');
            exit();
        }
        $this->view("homePage", ["page" => "SetNewPasswordView"]);
    }

    // Xử lý đặt mật khẩu mới
    public function setNewPassword() {
        if (!isset($_SESSION['can_set_new_password'])) {
            header('Location: ' . APP_URL . '/AuthController/forgotPassword');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_SESSION['can_set_new_password'];
            $newPassword = $_POST['new_password'] ?? '';
            $confirmNewPassword = $_POST['confirm_new_password'] ?? '';

            if ($newPassword !== $confirmNewPassword) {
                $this->view("homePage", ["page" => "SetNewPasswordView", "error" => "Mật khẩu mới và xác nhận mật khẩu không khớp!"]);
                return;
            }
            if (strlen($newPassword) < 6) {
                $this->view("homePage", ["page" => "SetNewPasswordView", "error" => "Mật khẩu mới phải có ít nhất 6 ký tự!"]);
                return;
            }
            $userModel = $this->model('UserModel');
            $userModel->updatePassword($email, password_hash($newPassword, PASSWORD_DEFAULT));
            unset($_SESSION['can_set_new_password']);
            $this->view("homePage", ["page" => "LoginView", "success" => "Mật khẩu của bạn đã được đặt lại thành công. Vui lòng đăng nhập!"]);
        }
    }

    // Gửi OTP đặt lại mật khẩu qua email
    private function sendResetOtpEmail($email, $otp) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'hoanganhtinhtien@gmail.com';
            $mail->Password = 'tmyr vlir pkfe paox';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('hoanganhtinhtien@gmail.com', 'Your App');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Mã OTP đặt lại mật khẩu của bạn";
            $mail->Body = "Mã OTP để đặt lại mật khẩu của bạn là: <b>$otp</b>. Mã này có hiệu lực trong 15 phút.";
            $mail->send();
        } catch (Exception $e) {
            // Không echo lỗi ra ngoài
        }
    }

}
