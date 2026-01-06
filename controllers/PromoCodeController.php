<?php
class PromoCodeController extends Controller {

    public function __construct() {
        // Giả sử bạn có một cơ chế kiểm tra quyền admin
        // if (!isAdmin()) {
        //     header('Location: ' . APP_URL . '/');
        //     exit();
        // }
    }

    public function show() {
        $searchTerm = $_GET['search'] ?? '';
        $promoModel = $this->model("PromoCodeModel");
        $codes = $promoModel->getAll($searchTerm);
        $this->view("adminPage", [
            "page" => "PromoCodeListView",
            "promo_codes" => $codes,
            "searchTerm" => $searchTerm
        ]);
    }

    public function create() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $code = $_POST["code"];
            $type = $_POST["type"];
            $value = $_POST["value"];
            $min_order_value = $_POST["min_order_value"] ?? 0;
            $start_date = $_POST["start_date"];
            $end_date = $_POST["end_date"];
            $usage_limit = $_POST["usage_limit"] ?? 0;
            $status = isset($_POST["status"]) ? 1 : 0;

            $promoModel = $this->model("PromoCodeModel");
            $promoModel->create($code, $type, $value, $min_order_value, $start_date, $end_date, $usage_limit, $status);

            header("Location: " . APP_URL . "/PromoCodeController/show");
            exit();
        } else {
            $this->view("adminPage", ["page" => "PromoCodeFormView"]);
        }
    }

    public function edit($id) {
        $promoModel = $this->model("PromoCodeModel");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $code = $_POST["code"];
            $type = $_POST["type"];
            $value = $_POST["value"];
            $min_order_value = $_POST["min_order_value"] ?? 0;
            $start_date = $_POST["start_date"];
            $end_date = $_POST["end_date"];
            $usage_limit = $_POST["usage_limit"] ?? 0;
            $status = isset($_POST["status"]) ? 1 : 0;

            $promoModel->update($id, $code, $type, $value, $min_order_value, $start_date, $end_date, $usage_limit, $status);
            header("Location: " . APP_URL . "/PromoCodeController/show");
            exit();
        } else {
            $promo = $promoModel->getById($id);
            $this->view("adminPage", [
                "page" => "PromoCodeFormView",
                "editItem" => $promo
            ]);
        }
    }

    public function delete($id) {
        $promoModel = $this->model("PromoCodeModel");
        $promoModel->delete($id);
        header("Location: " . APP_URL . "/PromoCodeController/show");
        exit();
    }
}
?>