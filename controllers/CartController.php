<?php
require_once 'models/OrderModel.php';
require_once 'models/OrderDetailModel.php';
require_once 'models/UserModel.php';

class CartController extends Controller {
    public function index() {
        // Hợp nhất logic lấy giỏ hàng
        $cartData = [];
        if (isset($_SESSION['user']['id'])) {
            // Nếu đã đăng nhập, lấy giỏ hàng từ DB
            $cartModel = $this->model("CartModel");
            $cartData = $cartModel->getCartByUserId($_SESSION['user']['id']);
        } else {
            // Nếu chưa đăng nhập, dùng session
            $cartData = $_SESSION['cart'] ?? [];
        }

        // Tải lại dữ liệu voucher
        $promoModel = $this->model('PromoCodeModel');
        $availableCodes = $promoModel->getAvailableCodes();

        $this->view("homePage", [
            "page" => "OrderView",
            "listProductOrder" => $cartData, // Truyền dữ liệu giỏ hàng cho view
            "available_codes" => $availableCodes // Thêm lại dữ liệu voucher
        ]);
    }

    public function checkout() {
        if (!isset($_SESSION['user'])) {
            header('Location: /AuthController/login');
            exit();
        }
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if (empty($cart)) {
            header('Location: /CartController/index');
            exit();
        }
        $user = $_SESSION['user'];
        $orderModel = $this->model("OrderModel");
        $orderDetailModel =$this->model("OrderDetailModel");
        $productModel = $this->model("ProductModel");
        $orderCode = 'HD' . time();
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += ($item['sale_price'] ?? $item['price']) * $item['quantity'];
        }
        $orderId = $orderModel->createOrder($user['id'], $orderCode, $totalAmount);
        foreach ($cart as $item) {
            $orderDetailModel->addOrderDetail(
                $orderId,
                $item['id'],
                $item['quantity'],
                $item['price'],
                $item['sale_price'] ?? 0,
                ($item['sale_price'] ?? $item['price']) * $item['quantity'],
                $item['image'],
                $item['type'],
                $item['name']
            );
            $productModel->updateProductQuantity($item['id'], $item['quantity']);
        }
        unset($_SESSION['cart']);
       // $this->render('Font_end/OrderView.php', ['success' => true, 'order_code' => $orderCode]);
        $this->view("homePage",["page"=>"OrderView",'success' => true, 'order_code' => $orderCode]);
    }
}
