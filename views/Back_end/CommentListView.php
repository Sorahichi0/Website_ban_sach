<div class="container mt-5">
    <h2 class="text-primary mb-4">Quản lý Bình luận</h2>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Người dùng</th>
                <th>Sản phẩm</th>
                <th>Nội dung</th>
                <th>Đánh giá</th>
                <th>Ngày gửi</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($data['comments'])): foreach ($data['comments'] as $comment): ?>
            <tr>
                <td><?= $comment['id'] ?></td>
                <td><?= htmlspecialchars($comment['user_name']) ?></td>
                <td>
                    <a href="<?= APP_URL ?>/Home/detail/<?= $comment['product_id'] ?>" target="_blank">
                        <?= htmlspecialchars($comment['product_name']) ?>
                    </a>
                </td>
                <td style="max-width: 300px;"><?= htmlspecialchars($comment['content']) ?></td>
                <td class="text-warning"><?= str_repeat('⭐', $comment['rating']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?></td>
                <td>
                    <span class="badge <?= $comment['status'] ? 'bg-success' : 'bg-warning text-dark' ?>">
                        <?= $comment['status'] ? 'Đã duyệt' : 'Chờ duyệt' ?>
                    </span>
                </td>
                <td>
                    <?php if ($comment['status'] == 0): ?>
                        <a href="<?= APP_URL ?>/CommentController/approve/<?= $comment['id'] ?>" class="btn btn-success btn-sm">
                            Duyệt
                        </a>
                    <?php else: ?>
                        <a href="<?= APP_URL ?>/CommentController/unapprove/<?= $comment['id'] ?>" class="btn btn-secondary btn-sm">
                            Bỏ duyệt
                        </a>
                    <?php endif; ?>
                    <a href="<?= APP_URL ?>/CommentController/delete/<?= $comment['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xoá bình luận này?');">
                        Xoá
                    </a>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="8" class="text-center">Chưa có bình luận nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>