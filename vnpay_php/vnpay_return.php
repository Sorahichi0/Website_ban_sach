<!DOCTYPE html>
<html lang="en">
<?php
    // Import PHPMailer
    require_once '../vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>VNPAY RESPONSE</title>
    <link href="/vnpay_php/assets/bootstrap.min.css" rel="stylesheet"/>
    <link href="/vnpay_php/assets/jumbotron-narrow.css" rel="stylesheet">         
    <script src="/vnpay_php/assets/jquery-1.11.3.min.js"></script>
</head>
<body>
<?php
// Đảm bảo session được khởi tạo
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("./config.php");

$vnp_SecureHash = $_GET['vnp_SecureHash'];
$inputData = array();
foreach ($_GET as $key => $value) {
    if (substr($key, 0, 4) == "vnp_") {
        $inputData[$key] = $value;
    }
}
unset($inputData['vnp_SecureHash']);
ksort($inputData);
$i = 0;
$hashData = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
}

$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
?>

<div class="container">
    <div class="header clearfix">
        <h3 class="text-muted">VNPAY RESPONSE</h3>
    </div>
    <div class="table-responsive">
        <div class="form-group">
            <label>Mã đơn hàng:</label>
            <label><?php echo $_GET['vnp_TxnRef'] ?></label>
        </div>    
        <div class="form-group">
            <label>Số tiền:</label>
            <label><?php echo $_GET['vnp_Amount'] /100?></label>
        </div>  
        <div class="form-group">
            <label>Nội dung thanh toán:</label>
            <label><?php echo $_GET['vnp_OrderInfo'] ?></label>
        </div> 
        <div class="form-group">
            <label>Mã phản hồi (vnp_ResponseCode):</label>
            <label><?php echo $_GET['vnp_ResponseCode'] ?></label>
        </div> 
        <div class="form-group">
            <label>Mã GD Tại VNPAY:</label>
            <label><?php echo $_GET['vnp_TransactionNo'] ?></label>
        </div> 
        <div class="form-group">
            <label>Mã Ngân hàng:</label>
            <label><?php echo $_GET['vnp_BankCode'] ?></label>
        </div> 
        <div class="form-group">
            <label>Thời gian thanh toán:</label>
            <label><?php echo $_GET['vnp_PayDate'] ?></label>
        </div> 
        <div class="form-group">
            <label>Kết quả:</label>
            <label>
                <?php
                if ($secureHash == $vnp_SecureHash) {
                    if ($_GET['vnp_ResponseCode'] == '00') {
                        echo "<span style='color:blue'>GD Thanh cong</span>";

                        // 🔽 CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG Ở ĐÂY 🔽
                        $order_code = $_GET['vnp_TxnRef']; // mã đơn hàng
                        $conn = new mysqli('localhost', 'root', '', 'website'); // đổi tên DB cho đúng

                        if ($conn->connect_error) {
                            die("<br><span style='color:red'>Lỗi kết nối CSDL: " . $conn->connect_error . "</span>");
                        }

                        // Lấy order ID từ order_code
                        $order_id = 0;
                        $sql_get_id = "SELECT id FROM orders WHERE order_code = '$order_code' LIMIT 1";
                        $result = $conn->query($sql_get_id);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $order_id = $row['id'];
                        }

                        // Cập nhật trạng thái thanh toán và trạng thái đơn hàng
                        // Giữ nguyên 'Chờ xác nhận' để admin xử lý
                        $sql = "UPDATE orders SET transaction_info = 'dathanhtoan' WHERE order_code = '$order_code'";
                        if ($conn->query($sql) === TRUE) {
                            // Chỉ cập nhật CSDL, không xử lý logic khác ở đây
                            // Gửi email xác nhận
                            sendOrderConfirmationEmail($order_id, $conn);
                            
                            // // Lưu thông báo thành công vào session
                            // $_SESSION['payment_success_message'] = "Thanh toán qua VNPAY thành công!";

                            // // Chuyển hướng về trang thành công để xử lý nốt
                            // header('Location: /MVC3/Home/orderSuccess');
                            // exit();

                        } else {
                            echo "<br><span style='color:red'>Lỗi khi cập nhật: " . $conn->error . "</span>";
                        }

                        $conn->close();
                        // 🔼 HẾT PHẦN CẬP NHẬT 🔼

                    } else {
                        echo "<span style='color:red'>GD Khong thanh cong</span>";
                    }
                } else {
                    echo "<span style='color:red'>Chu ky khong hop le</span>";
                }

                function sendOrderConfirmationEmail($orderId, $dbConnection) {
                    $sql_order = "SELECT * FROM orders WHERE id = $orderId";
                    $sql_details = "SELECT * FROM order_details WHERE order_id = $orderId";
                    
                    $order_result = $dbConnection->query($sql_order);
                    $details_result = $dbConnection->query($sql_details);

                    if ($order_result->num_rows > 0) {
                        $order = $order_result->fetch_assoc();

                        $body = "<h1>Xác nhận đơn hàng #{$order['order_code']}</h1>";
                        $body .= "<p>Cảm ơn bạn đã mua hàng tại BookStore!</p>";
                        $body .= "<h3>Thông tin đơn hàng:</h3><ul>";
                        $body .= "<li><b>Người nhận:</b> " . htmlspecialchars($order['receiver']) . "</li>";
                        $body .= "<li><b>Địa chỉ:</b> " . htmlspecialchars($order['address']) . "</li></ul>";
                        $body .= "<h3>Chi tiết sản phẩm:</h3><table border='1' cellpadding='10' cellspacing='0' style='width:100%; border-collapse: collapse;'><thead><tr><th>Sản phẩm</th><th>Số lượng</th><th>Thành tiền</th></tr></thead><tbody>";
                        
                        while($item = $details_result->fetch_assoc()) {
                            $body .= "<tr><td>" . htmlspecialchars($item['product_name']) . "</td><td style='text-align:center;'>" . $item['quantity'] . "</td><td style='text-align:right;'>" . number_format($item['total']) . " ₫</td></tr>";
                        }
                        
                        $body .= "</tbody><tfoot><tr><td colspan='2' style='text-align:right; font-weight:bold;'>Tổng thanh toán:</td><td style='text-align:right; font-weight:bold; color:red;'>" . number_format($order['total_amount']) . " ₫</td></tr></tfoot></table>";

                        $mail = new PHPMailer(true);
                        try {
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'hoanganhtinhtien@gmail.com';
                            $mail->Password = 'tmyr vlir pkfe paox';
                            $mail->SMTPSecure = 'tls';
                            $mail->Port = 587;
                            $mail->CharSet = 'UTF-8';
                            $mail->setFrom('hoanganhtinhtien@gmail.com', 'BookStore');
                            $mail->addAddress($order['user_email']);
                            $mail->isHTML(true);
                            $mail->Subject = "Xác nhận đơn hàng #{$order['order_code']} từ BookStore";
                            $mail->Body = $body;
                            $mail->send();
                        } catch (Exception $e) {
                            // Log error
                        }
                    }
                }
                ?>
            </label>
        </div> 
        <div class="form-group">
            <a href="https://uncensurable-jaylynn-nondepressingly.ngrok-free.dev/MVC3//Home/" class="btn btn-primary">Quay về trang chủ</a>
        </div>
    </div>
    <p>&nbsp;</p>
    <footer class="footer">
        <p>&copy; VNPAY <?php echo date('Y')?></p>
    </footer>
</div>  
</body>
</html>
