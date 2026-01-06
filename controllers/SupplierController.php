<?php
class SupplierController extends Controller {

    private $supplierModel;

    public function __construct() {
        $this->supplierModel = $this->model("SupplierModel");
        // Giả sử có hàm kiểm tra quyền admin
        // if (!isAdmin()) { header('Location: ...'); exit(); }
    }

    public function show() {
        $searchTerm = $_GET['search'] ?? '';
        $suppliers = $this->supplierModel->getAll($searchTerm);
        $this->view("adminPage", [
            "page" => "SupplierListView",
            "suppliers" => $suppliers,
            "searchTerm" => $searchTerm
        ]);
    }

    public function create() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = [
                'name' => $_POST["name"],
                'contact_person' => $_POST["contact_person"],
                'email' => $_POST["email"],
                'phone' => $_POST["phone"],
                'address' => $_POST["address"],
                'contract_info' => $_POST["contract_info"],
            ];
            $this->supplierModel->create($data);
            header("Location: " . APP_URL . "/SupplierController/show");
            exit();
        } else {
            $this->view("adminPage", ["page" => "SupplierFormView"]);
        }
    }

    public function edit($id) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = [
                'name' => $_POST["name"],
                'contact_person' => $_POST["contact_person"],
                'email' => $_POST["email"],
                'phone' => $_POST["phone"],
                'address' => $_POST["address"],
                'contract_info' => $_POST["contract_info"],
            ];
            $this->supplierModel->update($id, $data);
            header("Location: " . APP_URL . "/SupplierController/show");
            exit();
        } else {
            $supplier = $this->supplierModel->getById($id);
            $this->view("adminPage", ["page" => "SupplierFormView", "editItem" => $supplier]);
        }
    }

    public function delete($id) {
        $this->supplierModel->deleteById($id);
        header("Location: " . APP_URL . "/SupplierController/show");
        exit();
    }
}
?>