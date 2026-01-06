<!-- Thêm Bootstrap Icons (tùy chọn nếu muốn có icon) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="container py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-primary mb-0">Quản lý bài viết</h3>
        <a href="<?= APP_URL ?>/Post/create" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Thêm bài viết
        </a>
    </div>

    <!-- Danh sách bài viết -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <strong>Danh sách bài viết</strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="5%">ID</th>
                            <th width="15%">Ảnh</th>
                            <th width="30%">Tiêu đề</th>
                            <th width="15%">Trạng thái</th>
                            <th width="15%">Ngày tạo</th>
                            <th width="15%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($data['postList'])) {
                            foreach ($data['postList'] as $index => $post) {
                        ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $post["id"] ?></td>
                            <td>
                                <?php if (!empty($post["image"])): ?>
                                    <img src="<?= APP_URL ?>/public/images/<?= htmlspecialchars($post['image']) ?>" 
                                        style="max-height: 120px; width: auto; border-radius: 6px;"/>
                                <?php else: ?>
                                    <span class="text-muted">Không có ảnh</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-start">
                              <?= htmlspecialchars($post["title"]) ?> 
                            </td>
                            <td>
                                <span class="badge <?= $post["status"] ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $post["status"] ? "Hiển thị" : "Ẩn" ?>
                                </span>
                            </td>
                            <td>
                                <?= htmlspecialchars($post["created_at"]) ?>
                            </td>
                            <td>
                                <a href="<?= APP_URL ?>/Post/edit/<?= $post["id"] ?>" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Sửa
                                </a>
                                <a href="<?= APP_URL ?>/Post/delete/<?= $post["id"] ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Bạn có chắc muốn xoá bài viết này?');">
                                   <i class="bi bi-trash"></i> Xoá
                                </a>
                            </td>
                        </tr>
                        <?php 
                            } 
                        } else {
                        ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Không có bài viết nào trong hệ thống.
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>