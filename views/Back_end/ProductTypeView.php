<?php
    // Hàm đệ quy để xây dựng cây danh mục cho dropdown
    function buildCategoryTree($categories, $parentId = null, $prefix = '') {
        $result = '';
        foreach ($categories as $category) {
            if ($category['parent_id'] == $parentId) {
                $result .= '<option value="' . htmlspecialchars($category['maLoaiSP']) . '">' . $prefix . htmlspecialchars($category['tenLoaiSP']) . '</option>';
                $result .= buildCategoryTree($categories, $category['maLoaiSP'], $prefix . '— ');
            }
        }
        return $result;
    }
?>
<div class="container mt-5">
    <h2 class="mb-4">📦 Quản lý danh mục loại sản phẩm</h2>
    <!-- Nút Thêm sản phẩm -->
    <?php
        // Nếu tồn tại biến $data["editItem"] thì đang ở chế độ sửa
        $isEdit = isset($data["editItem"]);
        $edit = $isEdit ? $data["editItem"] : null;
    ?>
    <form 
        action="<?= $isEdit ? APP_URL . "/ProductType/update/" .
         $edit["maLoaiSP"] : APP_URL ."/ProductType/create" ?>" 
        method="post" 
        class="bg-light p-3 rounded shadow-sm mb-4"
    >
    <div class="row align-items-start gx-3 gy-2">
        <!-- Mã loại sản phẩm -->
        <div class="col-md-3">
            <label for="txt_maloaisp" class="form-label">Mã loại SP</label>
            <input type="text"  name="txt_maloaisp" id="txt_maloaisp" class="form-control" 
            required value="<?= $isEdit ? htmlspecialchars($edit["maLoaiSP"]) : '' ?>" 
                <?= $isEdit ? 'readonly' : '' ?> />
        </div>

        <!-- Tên loại sản phẩm -->
        <div class="col-md-3">
            <label for="txt_tenloaisp" class="form-label">Tên loại SP</label>
            <input type="text" 
                name="txt_tenloaisp" 
                id="txt_tenloaisp" 
                class="form-control"
                value="<?= $isEdit ? htmlspecialchars($edit["tenLoaiSP"]) : '' ?>" />
        </div>

        <!-- Danh mục cha -->
        <div class="col-md-3">
            <label for="parent_id" class="form-label">Danh mục cha</label>
            <select name="parent_id" id="parent_id" class="form-select">
                <option value="">-- Là danh mục gốc --</option>
                <?php 
                    foreach($data['productList'] as $type) {
                        // Khi sửa, không cho chọn chính nó làm danh mục cha
                        if($isEdit && $type['maLoaiSP'] == $edit['maLoaiSP']) continue;
                        $selected = ($isEdit && $type['maLoaiSP'] == $edit['parent_id']) ? 'selected' : '';
                        echo "<option value='".htmlspecialchars($type['maLoaiSP'])."' $selected>".htmlspecialchars($type['tenLoaiSP'])."</option>";
                    }
                ?>
            </select>
        </div>

        <!-- Mô tả loại sản phẩm -->
        <div class="col-md-3">
            <label for="txt_motaloaisp" class="form-label">Mô tả</label>
            <input type="text" 
                name="txt_motaloaisp" 
                id="txt_motaloaisp" 
                class="form-control"
                value="<?= $isEdit ? htmlspecialchars($edit["moTaLoaiSP"]) : '' ?>" />
        </div>

        <!-- Nút hành động -->
        <div class="col-12 mt-3">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-<?= $isEdit ? 'warning' : 'primary' ?>">
                    💾 <?= $isEdit ? "Cập nhật" : "Thêm mới" ?>
                </button>
                <?php if ($isEdit): ?>
                    <a href="<?= APP_URL ?>/ProductType/show" class="btn btn-secondary">
                        🔁 Huỷ 
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>  
    </form>

    <?php if (!empty($data["productList"])): ?>
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
        <tr>
            <th>Mã loại SP</th>
            <th>Tên loại SP</th>
            <th>Mô tả</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php
            $categoryMap = [];
            foreach ($data["productList"] as $category) {
                $categoryMap[$category['parent_id']][] = $category;
            }
            function displayCategories($categories, $parentId = null, $prefix = '') {
                if (!isset($categories[$parentId])) return;
                foreach ($categories[$parentId] as $category) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($category['maLoaiSP']) . '</td>';
                    echo '<td>' . $prefix . htmlspecialchars($category['tenLoaiSP']) . '</td>';
                    echo '<td>' . htmlspecialchars($category['moTaLoaiSP']) . '</td>';
                    echo '<td><a href="' . APP_URL . '/ProductType/edit/' . $category['maLoaiSP'] . '" class="btn btn-warning btn-sm">✏️ Sửa</a> <a href="' . APP_URL . '/ProductType/delete/' . $category['maLoaiSP'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Bạn có chắc muốn xoá? Thao tác này có thể ảnh hưởng đến các danh mục con.\');">🗑️ Xoá</a></td>';
                    echo '</tr>';
                    displayCategories($categories, $category['maLoaiSP'], $prefix . '— ');
                }
            }
            displayCategories($categoryMap);
        ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="alert alert-info text-center">Chưa có loại sản phẩm nào.</div>
    <?php endif; ?>
</div>
