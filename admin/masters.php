<?php

require_once __DIR__ . '/../inc/function.php';

$db = db_connect();
$masters = [];

try {
    $sql = 'SELECT * FROM admins ORDER BY id ASC';
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $masters = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $err_msg = 'データの取得に失敗しました:' . $e->getMessage();
}


require_once './../inc/header_admin.php';
?>

<body>
    <div class="l-wrapper">

        <h1 class="c-title">管理者一覧</h1>
        <button type="button" class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#addMasterModal">
            新規管理者登録
        </button>

        <!-- 追加モーダル -->
        <div class="modal fade" id="addMasterModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">管理者登録</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="master_add_do.php" method="post">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">名前</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ログインID</label>
                                <input type="text" name="login_id" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">パスワード</label>
                                <input type="text" name="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                            <input type="submit" value="登録" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($masters as $master): ?>
                    <tr>
                        <td><?php echo h($master['name']) ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <button
                                    type="button"
                                    class="btn btn-primary mb-3 edit-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editMasterModal"
                                    data-id="<?php echo h($master['id']); ?>"
                                    data-name="<?php echo h($master['name']); ?>">
                                    編集
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-danger mb-3 delete-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#delMasterModal"
                                    data-id="<?php echo h($master['id']); ?>">
                                    削除
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- 編集モーダル -->
        <div class="modal fade" id="editMasterModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">管理者編集</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="master_edit_do.php" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit-id">
                            <div class="mb-3">
                                <label class="form-label">名前</label>
                                <input type="text" name="name" id="edit-name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ログインID</label>
                                <input type="text" name="login_id" id="edit-login_id" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">パスワード</label>
                                <input type="text" name="password" id="edit-password" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                            <input type="submit" value="登録" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 削除モーダル -->
        <div class="modal fade" id="delMasterModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">削除確認</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="master_del_do.php" method="post">
                        <div class="modal-body">
                            <p>このコンサルタントを削除しますか？</p>

                            <!-- idを送る -->
                            <input type="hidden" name="id" id="delete-id">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">戻る</button>
                            <button type="submit" class="btn btn-danger">削除</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</body>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const editButtons = document.querySelectorAll('.edit-btn');

        editButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                const name = btn.getAttribute('data-name');

                document.getElementById('edit-id').value = id;
                document.getElementById('edit-name').value = name;
            });
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                document.getElementById('delete-id').value = id;
            });
        });
    });
</script>

<?php require_once './../inc/footer.php'; ?>