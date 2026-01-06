<div class="container py-4">
    <h3 class="text-primary mb-4">Chỉnh sửa bài viết</h3>

    <form action="<?= APP_URL ?>/Post/edit/<?= $data['editItem']['id'] ?>" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label for="txt_tieude" class="form-label fw-bold">Tiêu đề:</label>
            <input type="text" name="txt_tieude" id="txt_tieude" 
                   value="<?= htmlspecialchars($data['editItem']['title']) ?>" 
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="txt_noidung" class="form-label fw-bold">Nội dung:</label>
            <textarea name="txt_noidung" id="txt_noidung" class="form-control" rows="5" required><?= htmlspecialchars($data['editItem']['content']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Ảnh hiện tại:</label><br>
            <?php if (!empty($data['editItem']['image'])): ?>
                <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($data['editItem']['image']) ?>" 
                     width="160" class="rounded shadow-sm mb-2" alt="Ảnh bài viết">
            <?php else: ?>
                <p class="text-muted fst-italic">Chưa có ảnh</p>
            <?php endif; ?>
            <input type="hidden" name="old_image" value="<?= htmlspecialchars($data['editItem']['image']) ?>">
            <input type="file" name="uploadfile" class="form-control mt-2">
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="txt_trangthai" class="form-check-input" id="txt_trangthai"
                <?= $data['editItem']['status'] ? 'checked' : '' ?>>
            <label for="txt_trangthai" class="form-check-label">Hiển thị bài viết</label>
        </div>

        <div class="d-flex justify-content-between">
            <a href="<?= APP_URL ?>/Post/show" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Lưu thay đổi
            </button>
        </div>
    </form>
</div>
