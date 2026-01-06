<?php
class BaseController {
    public function model($model) {
        require_once "./models/" . $model . ".php";
        return new $model;
    }

    /**
     * Ghi đè phương thức view để luôn tải dữ liệu chung cho layout.
     * @param string $view Tên của layout view (ví dụ: "homePage", "adminPage").
     * @param array $data Dữ liệu cụ thể cho trang con.
     */
    public function view($view, $data = []) {
        // Tải dữ liệu chung cho tất cả các trang người dùng
        // Chỉ tải categoryTree nếu nó chưa được controller cụ thể cung cấp
        if ($view == "homePage" && !isset($data['categoryTree'])) {
            $productTypeModel = $this->model("AdProductTypeModel"); // Đảm bảo model đã được gọi
            if ($productTypeModel) {
                $allTypes = $productTypeModel->all("tblloaisp");
    
                // Xây dựng cây danh mục
                $categoryTree = [];
                $typeMap = [];
                foreach ($allTypes as $type) {
                    $typeMap[$type['maLoaiSP']] = $type;
                }
                foreach ($allTypes as $type) {
                    if ($type['parent_id'] && isset($typeMap[$type['parent_id']])) { // Thêm kiểm tra isset
                        $typeMap[$type['parent_id']]['children'][] =& $typeMap[$type['maLoaiSP']];
                    } else {
                        $categoryTree[] =& $typeMap[$type['maLoaiSP']];
                    }
                }
                // Gộp dữ liệu chung vào dữ liệu của trang
                $data['categoryTree'] = $categoryTree;
            }
        }

        require_once "./views/" . $view . ".php";
    }
}