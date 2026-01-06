<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-4" style="max-width: 700px;">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Thêm bài viết mới</h4>
        </div>

        <div class="card-body">
            <form action="<?= APP_URL ?>/Post/create" method="POST" enctype="multipart/form-data">

                <!-- Tiêu đề -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tiêu đề</label>
                    <input type="text" name="txt_tieude" class="form-control" placeholder="Nhập tiêu đề bài viết..." required>
                </div>

                <!-- Nội dung -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nội dung</label>
                    <textarea name="txt_noidung" rows="5" class="form-control" placeholder="Nhập nội dung bài viết..." required></textarea>
                </div>

                <!-- Hình ảnh -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Hình ảnh</label>
                    <input type="file" name="uploadfile" class="form-control" accept="image/*" onchange="previewImage(event)">
                    <div class="mt-3 text-center">
                        <img id="preview" src="#" alt="Chưa chọn ảnh" style="display:none; max-height: 200px; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.2);"/>
                    </div>
                </div>

                <!-- Trạng thái -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Trạng thái</label>
                    <select name="txt_trangthai" class="form-select">
                        <option value="1">Hiển thị</option>
                        <option value="0">Ẩn</option>
                    </select>
                </div>

                <!-- Nút lưu -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-save"></i> Lưu bài viết
                    </button>
                    <a href="<?= APP_URL ?>/Post/show" class="btn btn-secondary ms-2">
                        <i class="bi bi-arrow-left-circle"></i> Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script preview ảnh -->
<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const preview = document.getElementById('preview');
        preview.src = reader.result;
        preview.style.display = 'block';
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>
