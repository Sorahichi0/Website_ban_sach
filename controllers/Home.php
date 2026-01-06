
<?php
    // Import PHPMailer
    require_once 'vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;
    require_once './app/BaseController.php';
    class Home extends BaseController{
        // Hiển thị lịch sử đơn hàng cho người dùng đã đăng nhập
        public function orderHistory() {
            if (!isset($_SESSION['user']['id'])) {
                header('Location: ' . APP_URL . '/AuthController/ShowLogin');
                exit();
            }
            $orderModel = $this->model('OrderModel');
            $orders = $orderModel->getOrdersByUser($_SESSION['user']['id']); // Thay đổi ở đây
            $this->view('homePage', [
                'page' => 'OrderHistoryView',
                'orders' => $orders
            ]);
        }
        // Lưu thông tin giao hàng, hóa đơn và chi tiết hóa đơn


   public  function show(){
        $obj=$this->model("AdProducModel");
        $data=$obj->all("tblsanpham");
        $this->view("homePage",["page"=>"HomeView","productList"=>$data]);
    }
    /**
     * Hiển thị sản phẩm theo danh mục
     */
    public function category($maLoaiSP = '') {
        if (empty($maLoaiSP)) {
            header("Location: " . APP_URL . "/Home/show");
            exit();
        }
        $productModel = $this->model("AdProducModel");
        $productTypeModel = $this->model("AdProductTypeModel");
        $products = $productModel->findByCategory($maLoaiSP);
        $category = $productTypeModel->find("tblloaisp", $maLoaiSP);
        $this->view("homePage", ["page" => "HomeView", "productList" => $products, "categoryName" => $category['tenLoaiSP'] ?? '']);
    }
    public function detail($masp){
        $obj=$this->model("AdProducModel");
        $commentModel = $this->model("CommentModel");

        $data=$obj->find("tblsanpham",$masp);
        $comments = $commentModel->getApprovedByProductId($masp);

        $this->view("homePage",["page"=>"DetailView","product"=>$data, "comments" => $comments]);
    }   

    private function getCartData() {
        if (isset($_SESSION['user'])) {
            // Nếu đã đăng nhập, lấy giỏ hàng từ DB
            $cartModel = $this->model("CartModel");
            return $cartModel->getCartByUserId($_SESSION['user']['id']);
        } else {
            // Nếu chưa đăng nhập, dùng session
            return $_SESSION['cart'] ?? [];
        }
    }

    public function addtocard($masp){
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart']=[];
        }
        
        $quantityToAdd = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        if ($quantityToAdd < 1) $quantityToAdd = 1; // Đảm bảo số lượng không âm hoặc 0

        if (isset($_SESSION['user'])) {
            // ⚠️ Kiểm tra xem user_id đã tồn tại trong session hay chưa
            if (!isset($_SESSION['user']['id'])) {
                error_log('Không tìm thấy user_id trong session!');
                return; // Hoặc chuyển hướng đến trang đăng nhập
            }
            // Đã đăng nhập, lưu vào DB
            $cartModel = $this->model("CartModel");
            $cartModel->addToCart($_SESSION['user']['id'], $masp, $quantityToAdd);
        } else {
            // Chưa đăng nhập, lưu vào session
            if(isset($_SESSION['cart'][$masp])){ // Nếu sản phẩm đã có trong giỏ hàng
                $_SESSION['cart'][$masp]['qty'] += $quantityToAdd;
            } else { // Nếu sản phẩm chưa có trong giỏ hàng
                $obj=$this->model("AdProducModel");
                $data=$obj->find("tblsanpham",$masp);
                if (!$data) {
                    header('Location: ' . APP_URL . '/Home/show'); // sản phẩm không tồn tại
                    exit();
                }
                $_SESSION['cart'][$masp] = [
                    'qty' => $quantityToAdd,
                    'masp' => $data['masp'],
                    'tensp' => $data['tensp'],
                    'hinhanh' => $data['hinhanh'],
                    'giaxuat' => $data['giaXuat'],
                    'khuyenmai' => $data['khuyenmai'],
                ];
            }
        }
        // Chuyển hướng sang trang giỏ hàng
        header('Location: ' . APP_URL . '/CartController/index');
        exit();
    }
    public function delete($masp){
        // ⚠️ Kiểm tra xem user_id đã tồn tại trong session hay chưa
        if (!isset($_SESSION['user']['id'])) {
            error_log('Không tìm thấy user_id trong session!');
            return; // Hoặc chuyển hướng đến trang đăng nhập
        }
        if (isset($_SESSION['user'])) {
            $cartModel = $this->model("CartModel");
            $cartModel->removeFromCart($_SESSION['user']['id'], $masp);
        } else {
            if(isset($_SESSION['cart'][$masp])){
                unset($_SESSION['cart'][$masp]);
            }
        }
       // Chuyển hướng về trang giỏ hàng để tải lại dữ liệu
       header('Location: ' . APP_URL . '/CartController/index');
       exit();
    }   
    public function update() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['qty'])) {
            // ⚠️ Kiểm tra xem user_id đã tồn tại trong session hay chưa
            if (!isset($_SESSION['user']['id'])) {
                error_log('Không tìm thấy user_id trong session!');
                return; // Hoặc chuyển hướng đến trang đăng nhập
            }
            if (isset($_SESSION['user'])) {
                $cartModel = $this->model("CartModel");
                foreach ($_POST['qty'] as $masp => $quantity) {
                    $cartModel->updateQuantity($_SESSION['user']['id'], $masp, (int)$quantity);
                }
            } else {
                foreach ($_POST['qty'] as $k => $v) {
                    if ((int)$v > 0) {
                        $_SESSION['cart'][$k]['qty'] = (int)$v;
                    } else {
                        unset($_SESSION['cart'][$k]);
                    }
                }
            }
        }
        // Chuyển hướng về trang giỏ hàng để tải lại dữ liệu
        header('Location: ' . APP_URL . '/CartController/index');
        exit();
    }
    public function order() {
        $promoModel = $this->model('PromoCodeModel');
        $availableCodes = $promoModel->getAvailableCodes();
        $this->view("homePage", [
            "page" => "OrderView", 
            "listProductOrder" => $this->getCartData(),
            "available_codes" => $availableCodes
        ]);
    }
    // Xử lý đặt hàng: chỉ cho phép khi đã đăng nhập
    public function checkout() {
        if (!isset($_SESSION['user'])) {
            // Nếu chưa đăng nhập, chuyển hướng sang trang đăng nhập
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        $cart = $this->getCartData();
        if (empty($cart)) {
            $this->view("homePage", [
                "page" => "OrderView",
                "listProductOrder" => [],
                "success" => "Giỏ hàng trống!"
            ]);
            return;
        }
        $orderModel = $this->model("OrderModel");
        $orderDetailModel = $this->model("OrderDetailModel");
        $user = $_SESSION['user'];
        $orderCode = 'HD' . time();
        $totalAmount = 0;
        foreach ($cart as $item) {
            $thanhtien = ($item['giaxuat'] - ($item['giaxuat'] * $item['khuyenmai'] / 100)) * $item['qty'];
            $totalAmount += $thanhtien;
        }
        // Đặt hàng nhanh, thông tin giao hàng để trống
        $orderId = $orderModel->createOrderWithShipping($user['email'], $orderCode, $totalAmount, '', '', '');
        foreach ($cart as $item) {
            $thanhtien = ($item['giaxuat'] - ($item['giaxuat'] * $item['khuyenmai'] / 100)) * $item['qty'];
            $orderDetailModel->addOrderDetail(
                $orderId,
                $item['masp'],
                $item['qty'],
                $item['giaxuat'],
                $item['giaxuat'] - ($item['giaxuat'] * $item['khuyenmai'] / 100),
                $thanhtien,
                $item['hinhanh'],
                '', // loại sp nếu có
                $item['tensp']
            );
        }
        // Xóa giỏ hàng sau khi đặt hàng
        $cartModel = $this->model("CartModel");
        $cartModel->clearCart($user['id']);
        $this->view("homePage", [
            "page" => "OrderView",
            "listProductOrder" => [],
            "success" => "Đặt hàng thành công! Mã hóa đơn: $orderCode"
        ]);
    }

        public function checkoutSave() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/Show');
            exit();
        }
        $cart = $this->getCartData();
        if (empty($cart)) {
            $this->view("homePage", [
                "page" => "OrderView",
                "listProductOrder" => [],
                "success" => "Giỏ hàng trống!"
            ]);
            return;
        }
        $receiver = isset($_POST['receiver']) ? trim($_POST['receiver']) : '';
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $address = isset($_POST['address']) ? trim($_POST['address']) : '';
        if ($receiver === '' || $phone === '' || $address === '') {
            // Tải lại dữ liệu cần thiết cho view khi có lỗi
            $shippingModel = $this->model("ShippingModel");
            $shippingMethods = $shippingModel->getActiveMethods();
            $this->view("homePage", [
                "page" => "CheckoutInfoView",
                "shipping_methods" => $shippingMethods,
                "error" => "Vui lòng nhập đầy đủ thông tin giao hàng!"
            ]);
            echo '<div class="alert alert-danger">Vui lòng nhập đầy đủ thông tin giao hàng!</div>';
            $this->view("homePage", ["page" => "CheckoutInfoView"]);
            return;
        }
        $orderModel = $this->model("OrderModel");
        $orderDetailModel =$this->model("OrderDetailModel");
        $user = $_SESSION['user'];
        $shippingModel = $this->model("ShippingModel");

        $selectedShippingId = $_POST['shipping_method_id'] ?? null;
        // Lấy thông tin phương thức vận chuyển đã chọn
        $shippingMethod = $selectedShippingId ? $shippingModel->getMethodById($selectedShippingId) : ['cost' => 0, 'name' => 'Chưa xác định'];
        $orderCode = 'HD' . time();

        // 🔹 TÍNH TOÁN LẠI TỔNG TIỀN ĐỂ ĐẢM BẢO CHÍNH XÁC
        $subTotal = 0; // Tổng tiền hàng
        foreach ($cart as $item) {
            $thanhtien = ($item['giaxuat'] - ($item['giaxuat'] * $item['khuyenmai'] / 100)) * $item['qty'];
            $subTotal += $thanhtien;
        }

        $productDiscountAmount = 0;
        $finalShippingCost = $shippingMethod['cost'];

        // 1. Áp dụng voucher giảm giá sản phẩm (percentage/fixed)
        if (isset($_SESSION['product_promo_code'])) {
            $productPromo = $_SESSION['product_promo_code'];
            if ($productPromo['type'] == 'percentage') {
                $productDiscountAmount = ($subTotal * $productPromo['value']) / 100;
            } else { // 'fixed'
                $productDiscountAmount = $productPromo['value'];
            }
        }

        // 2. Áp dụng voucher miễn phí vận chuyển
        if (isset($_SESSION['shipping_promo_code'])) {
            $shippingPromo = $_SESSION['shipping_promo_code'];
            if ($shippingPromo['type'] == 'free_shipping') {
                $finalShippingCost = 0; // Đặt phí vận chuyển về 0
            }
        }

        // Đảm bảo giảm giá không vượt quá tổng tiền hàng
        if ($productDiscountAmount > $subTotal) $productDiscountAmount = $subTotal;

        // Tính toán tổng tiền cuối cùng
        $totalAmount = $subTotal - $productDiscountAmount + $finalShippingCost;
        if ($totalAmount < 0) $totalAmount = 0; // Đảm bảo không âm

        // Lấy tên phương thức vận chuyển
        $shippingMethodName = $shippingMethod ? $shippingMethod['name'] : 'Chưa xác định';

        // Lưu đơn hàng, bổ sung thông tin giao hàng
        $orderId = $orderModel->createOrderWithShipping(
            $user['email'], $orderCode, $totalAmount, 
            $receiver, $phone, $address,
            $shippingMethodName, // Tên phương thức giao hàng
            $finalShippingCost, // Chi phí giao hàng cuối cùng
            $user['id'] // Truyền user_id vào
        );

        $productModel = $this->model("AdProducModel"); // Thêm dòng này
        foreach ($cart as $item) {
            $thanhtien = ($item['giaxuat'] - ($item['giaxuat'] * $item['khuyenmai'] / 100)) * $item['qty'];
            $orderDetailModel->addOrderDetail(
                $orderId,
                $item['masp'],
                $item['qty'],
                $item['giaxuat'],
                $item['giaxuat'] - ($item['giaxuat'] * $item['khuyenmai'] / 100),
                $thanhtien,
                $item['hinhanh'],
                '', // loại sp nếu có
                $item['tensp']
            );

            // Cập nhật số lượng sản phẩm trong kho
            $productModel->updateQuantity($item['masp'], $item['qty']);
        }
        $_SESSION['orderCode'] = $orderCode;    //mã hóa đơn
        $_SESSION['totalAmount'] = $totalAmount; //tổng tiền thanh toán của cả đơn hàng
        
        $cartModel = $this->model("CartModel");
        $cartModel->clearCart($user['id']);
        $_SESSION['cart'] = [];
        
        $payment_method=$_POST['payment_method'];
        if($payment_method=='vnpay'){
            header('Location: ' . APP_URL . '/vnpay_php/vnpay_pay.php');
            exit();
        }
        elseif($payment_method=='cod'){
            // Gửi email xác nhận
            $this->sendOrderConfirmationEmail($user['email'], $orderCode, $orderId);
            
            // Cập nhật trạng thái cho đơn hàng COD
            if ($orderId) {
                $orderModel = $this->model('OrderModel');
                $orderModel->updatePaymentStatus($orderId, 'Chờ thanh toán'); // Cập nhật trạng thái thanh toán, không phải trạng thái đơn hàng
            }
            // Chuyển hướng đến trang thành công
            header('Location: ' . APP_URL . '/Home/orderSuccess');
            exit();
        }
    }

    /**
     * Gửi email xác nhận đơn hàng cho khách hàng
     */
    private function sendOrderConfirmationEmail($customerEmail, $orderCode, $orderId) {
        $orderDetailModel = $this->model("OrderDetailModel");
        $order = $this->model("OrderModel")->getOrderById($orderId);
        $details = $orderDetailModel->getOrderDetails($orderId);

        if (!$order || !$details) {
            return; // Không gửi mail nếu không có thông tin
        }

        // Bắt đầu nội dung email
        $body = "<h1>Xác nhận đơn hàng #{$orderCode}</h1>";
        $body .= "<p>Cảm ơn bạn đã mua hàng tại BookStore!</p>";
        $body .= "<h3>Thông tin đơn hàng:</h3>";
        $body .= "<ul>";
        $body .= "<li><b>Người nhận:</b> " . htmlspecialchars($order['receiver']) . "</li>";
        $body .= "<li><b>Địa chỉ:</b> " . htmlspecialchars($order['address']) . "</li>";
        $body .= "<li><b>Điện thoại:</b> " . htmlspecialchars($order['phone']) . "</li>";
        $body .= "</ul>";
        $body .= "<h3>Chi tiết sản phẩm:</h3>";
        $body .= "<table border='1' cellpadding='10' cellspacing='0' style='width:100%; border-collapse: collapse;'>
                    <thead style='background-color:#f2f2f2;'>
                        <tr><th>Sản phẩm</th><th>Số lượng</th><th>Đơn giá</th><th>Thành tiền</th></tr>
                    </thead>
                    <tbody>";
        foreach ($details as $item) {
            $body .= "<tr>
                        <td>" . htmlspecialchars($item['product_name']) . "</td>
                        <td style='text-align:center;'>" . $item['quantity'] . "</td>
                        <td style='text-align:right;'>" . number_format($item['sale_price']) . " ₫</td>
                        <td style='text-align:right;'>" . number_format($item['total']) . " ₫</td>
                      </tr>";
        }
        $body .= "</tbody>
                  <tfoot>
                    <tr><td colspan='3' style='text-align:right; font-weight:bold;'>Tổng thanh toán:</td><td style='text-align:right; font-weight:bold; color:red;'>" . number_format($order['total_amount']) . " ₫</td></tr>
                  </tfoot>
                  </table>";
        $body .= "<p>Bạn có thể xem lại lịch sử đơn hàng của mình bất cứ lúc nào tại trang web.</p>";

        // Cấu hình PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'hoanganhtinhtien@gmail.com'; // Email của bạn
            $mail->Password = 'tmyr vlir pkfe paox';      // App Password của bạn
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom('hoanganhtinhtien@gmail.com', 'BookStore');
            $mail->addAddress($customerEmail);
            $mail->isHTML(true);
            $mail->Subject = "Xác nhận đơn hàng #{$orderCode} từ BookStore";
            $mail->Body = $body;

            $mail->send();
        } catch (Exception $e) {
            // Có thể ghi log lỗi ở đây, không cần thông báo cho người dùng
            // echo "Gửi email thất bại: {$mail->ErrorInfo}";
        }
    }

    // Hiển thị form nhập thông tin giao hàng sau khi đăng ký hoặc đăng nhập
    public function checkoutInfo() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }
        // Tải các phương thức giao hàng và truyền cho view
        $shippingModel = $this->model("ShippingModel");
        $shippingMethods = $shippingModel->getActiveMethods();

        // Tải các voucher có sẵn để hiển thị
        $promoModel = $this->model('PromoCodeModel');
        $availableCodes = $promoModel->getAvailableCodes();

        $this->view("homePage", ["page" => "CheckoutInfoView", "shipping_methods" => $shippingMethods,
            "product_promo_code" => $_SESSION['product_promo_code'] ?? null, 
            "shipping_promo_code" => $_SESSION['shipping_promo_code'] ?? null,
            "available_codes" => $availableCodes]);
    }
    // Xem chi tiết đơn hàng
public function orderDetail($id) {
    if (!isset($_SESSION['user']['id'])) { // Kiểm tra id trong session
        header('Location: ' . APP_URL . '/AuthController/ShowLogin');
        exit();
    }

    $orderModel = $this->model("OrderModel");
    $orderDetailModel = $this->model("OrderDetailModel");

    // Lấy thông tin đơn hàng
    $order = $orderModel->getOrderById($id);
    // Lấy chi tiết sản phẩm trong đơn hàng
    $details = $orderDetailModel->getOrderDetails($id);

    $this->view("homePage", [
        "page"    => "OrderDetailView",
        "orderId" => $id,
        "order"   => $order,
        "details" => $details
    ]);
}

    /**
     * Người dùng xác nhận đã nhận hàng
     */
    public function confirmDelivery($orderId) {
        // 1. Kiểm tra người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user']['id'])) {
            header('Location: ' . APP_URL . '/AuthController/ShowLogin');
            exit();
        }

        $orderModel = $this->model("OrderModel");
        $order = $orderModel->getOrderById($orderId);

        // 2. Kiểm tra đơn hàng có tồn tại và thuộc về người dùng này không
        if ($order && $order['user_id'] == $_SESSION['user']['id'] && $order['order_status'] == 'Đang giao') {
            // 3. Cập nhật trạng thái đơn hàng
            $orderModel->updateOrderStatus($orderId, 'Hoàn thành');
        }

        // 4. Quay lại trang chi tiết đơn hàng
        header('Location: ' . APP_URL . '/Home/orderDetail/' . $orderId);
        exit();
    }

    // Áp dụng mã khuyến mại
    public function applyPromoCode() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['promo_code'])) {
            $code = trim($_POST['promo_code']);
            $currentOrderValue = (float)($_POST['current_order_value'] ?? 0); // Cần truyền tổng tiền hàng
            $promoModel = $this->model('PromoCodeModel');
            $result = $promoModel->findAndValidateCode($code, $currentOrderValue);

            if ($result['valid']) {
                $promo = $result['promo'];
                $isCheckoutPage = isset($_POST['redirect_to']) && $_POST['redirect_to'] === 'checkout';

                // Phân loại voucher và lưu vào session tương ứng
                if ($promo['type'] === 'free_shipping') {
                    if ($isCheckoutPage) {
                        $_SESSION['shipping_promo_code'] = $promo;
                        $_SESSION['promo_success'] = "Áp dụng mã miễn phí vận chuyển '".htmlspecialchars($code)."' thành công!";
                    } else {
                        $_SESSION['promo_error'] = "Mã vận chuyển chỉ có thể áp dụng ở trang thanh toán.";
                    }
                } elseif ($promo['type'] === 'percentage' || $promo['type'] === 'fixed') {
                    // Nếu không phải trang checkout (tức là trang giỏ hàng)
                    if (!$isCheckoutPage) {
                        $_SESSION['product_promo_code'] = $promo;
                        $_SESSION['promo_success'] = "Áp dụng mã giảm giá '".htmlspecialchars($code)."' thành công!";
                    } else {
                        $_SESSION['promo_error'] = "Mã giảm giá sản phẩm chỉ áp dụng được ở trang giỏ hàng.";
                    }
                } else {
                    $_SESSION['promo_error'] = "Loại voucher không hợp lệ.";
                }
            } else {
                $_SESSION['promo_error'] = $result['message'];
            }
        }
        // Kiểm tra xem nên chuyển hướng về đâu
        if (isset($_POST['redirect_to']) && $_POST['redirect_to'] == 'checkout') {
            header('Location: ' . APP_URL . '/Home/checkoutInfo');
        } else {
            header('Location: ' . APP_URL . '/CartController/index');
        }
        exit();
    }

    // Xóa mã khuyến mại đã áp dụng
    public function removePromoCode() {
        if (isset($_GET['type']) && $_GET['type'] == 'shipping') {
            unset($_SESSION['shipping_promo_code']);
        } elseif (isset($_GET['type']) && $_GET['type'] == 'product') {
            unset($_SESSION['product_promo_code']);
        } else {
            unset($_SESSION['product_promo_code']);
        }
        unset($_SESSION['promo_success']);
        unset($_SESSION['promo_error']);
        
        // Chuyển hướng về trang tương ứng
        if (isset($_GET['type']) && $_GET['type'] == 'shipping') {
            header('Location: ' . APP_URL . '/Home/checkoutInfo');
        } else {
            header('Location: ' . APP_URL . '/CartController/index');
        }
        exit();
    }

    // Áp dụng voucher vận chuyển ở trang thanh toán
    public function applyShippingPromoCode() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['shipping_promo_code'])) {
            // Logic tương tự applyPromoCode nhưng chỉ cho 'free_shipping'
            // (Để đơn giản, tạm thời chỉ cần lưu vào session và xử lý ở checkoutSave)
        }
        header('Location: ' . APP_URL . '/Home/checkoutInfo');
        exit();
    }

    /**
     * Xử lý yêu cầu áp dụng mã khuyến mại bằng AJAX
     */
    public function ajaxApplyPromoCode() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['promo_code'])) {
            echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
            return;
        }

        $code = trim($_POST['promo_code']);
        $subTotal = (float)($_POST['sub_total'] ?? 0); // Tổng tiền hàng
        $currentShippingCost = (float)($_POST['shipping_cost'] ?? 0); // Phí vận chuyển hiện tại

        $promoModel = $this->model('PromoCodeModel');
        $result = $promoModel->findAndValidateCode($code, $subTotal);

        if ($result['valid']) {
            $promo = $result['promo'];
            $productDiscountAmount = 0;
            $shippingDiscountAmount = 0;
            $newShippingCost = $currentShippingCost;

            if ($promo['type'] === 'free_shipping') {
                $_SESSION['shipping_promo_code'] = $promo;
                $newShippingCost = 0; // Phí vận chuyển mới
                $shippingDiscountAmount = $currentShippingCost; // Số tiền được giảm từ phí vận chuyển
            } else { // percentage hoặc fixed
                $_SESSION['product_promo_code'] = $promo;
                if ($promo['type'] == 'percentage') {
                    $productDiscountAmount = ($subTotal * $promo['value']) / 100;
                } else {
                    $productDiscountAmount = $promo['value'];
                }
            }

            // Đảm bảo giảm giá sản phẩm không vượt quá tổng tiền hàng
            if ($productDiscountAmount > $subTotal) $productDiscountAmount = $subTotal;

            $finalTotal = $subTotal - $productDiscountAmount + $newShippingCost;
            if ($finalTotal < 0) $finalTotal = 0;

            echo json_encode(['success' => true, 'message' => "Áp dụng mã '{$promo['code']}' thành công!", 'product_discount' => $productDiscountAmount, 'shipping_discount' => $shippingDiscountAmount, 'new_shipping_cost' => $newShippingCost, 'final_total' => $finalTotal, 'promo' => $promo]);
        } else {
            echo json_encode(['success' => false, 'message' => $result['message']]);
        }
    }

    public function addComment($productId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user'])) {
            header('Location: ' . APP_URL . '/Home/detail/' . $productId);
            exit();
        }

        $userId = $_SESSION['user']['id'];
        $rating = $_POST['rating'] ?? 5;
        $content = trim($_POST['content'] ?? '');

        if (!empty($content)) {
            $commentModel = $this->model('CommentModel');
            $commentModel->create($productId, $userId, $rating, $content);
        }

        header('Location: ' . APP_URL . '/Home/detail/' . $productId);
        exit();
    }

    public function advancedSearch()
    {
        $productModel = $this->model('ProductModel');
        
        // Lấy các giá trị filter từ query string
        $filters = [
            'tensp'     => $_GET['tensp'] ?? null,
            'author'    => $_GET['author'] ?? null,
            'maLoaiSP'  => $_GET['maLoaiSP'] ?? null,
            'publisher' => $_GET['publisher'] ?? null,
            'min_price' => $_GET['min_price'] ?? null,
            'max_price' => $_GET['max_price'] ?? null,
            'rating'    => $_GET['rating'] ?? null,
        ];

        // Xác định có tìm kiếm hay không
        $searchPerformed = false;
        foreach ($filters as $value) {
            if ($value !== null && $value !== '') {
                $searchPerformed = true;
                break;
            }
        }

        $products = [];
        if ($searchPerformed) {
            // Lấy danh sách sản phẩm dựa trên bộ lọc
            $products = $productModel->getAdvancedSearch($filters);
        }

        // Lấy dữ liệu cho các dropdown của form
        $categories = $productModel->getProductTypes();
        $publishers = $productModel->getPublishers();

        $this->view('homePage', [
            'page'            => 'advancedSearch',
            'products'        => $products,
            'categories'      => $categories,
            'publishers'      => $publishers,
            'old_inputs'      => $_GET,
            'searchPerformed' => $searchPerformed
        ]);
    }
}
