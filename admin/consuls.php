<?php

require_once __DIR__ . '/../inc/function.php';

$db = db_connect();
$consultants = [];

try {
  $sql = 'SELECT * FROM consultants ORDER BY id ASC';
  $stmt = $db->prepare($sql);
  $stmt->execute();

  $consultants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $err_msg = 'データの取得に失敗しました:' . $e->getMessage();
}


require_once './../inc/header_admin.php';
?>

<body>
  <div class="l-wrapper">

    <h1 class="c-title">講師一覧</h1>

    <?php if (!empty($_SESSION["msg"])): ?>
      <p class="alert alert-success" role="alert">
        <?php echo $_SESSION["msg"];
        unset($_SESSION["msg"]);
        ?>
      </p>
    <?php endif; ?>
    <?php if (!empty($_SESSION["err_msg"])): ?>
      <p class="alert alert-danger" role="alert">
        <?php echo $_SESSION["msg"];
        unset($_SESSION["msg"]);
        ?>
      </p>
    <?php endif; ?>
    <div class="mb-3">
      <button type="button"
        class="btn btn-info"
        data-bs-toggle="modal"
        data-bs-target="#addConsultantModal">
        ＋ 新規登録
      </button>
    </div>

    <!-- モーダル -->
    <div class="modal fade" id="addConsultantModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">コンサルタント登録</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <form action="consuls_add_do.php" method="post">
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">名前</label>
                <input type="text" name="name" class="form-control" required>
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

    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>名前</th>
            <th class="text-center">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($consultants)): ?>
            <tr>
              <td colspan="2" class="text-center text-muted py-4">
                データがありません
              </td>
            </tr>
          <?php endif; ?>
          <?php foreach ($consultants as $consul): ?>
            <tr>
              <td>
                <span class="fw-semibold">
                  <?php echo h($consul['name']) ?>
                </span>
              </td>
              <td class="text-center">
                <div class="d-flex gap-2 justify-content-center">
                  <button
                    class="btn btn-primary btn-sm edit-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#editConsultantModal"
                    data-id="<?php echo h($consul['id']); ?>"
                    data-name="<?php echo h($consul['name']); ?>">
                    編集
                  </button>

                  <button
                    class="btn btn-danger btn-sm delete-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#delConsultantModal"
                    data-id="<?php echo h($consul['id']); ?>"
                    data-name="<?php echo h($consul['name']); ?>">
                    削除
                  </button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- 編集モーダル -->
    <div class="modal fade" id="editConsultantModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">コンサルタント編集</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <form action="consuls_edit_do.php" method="post">
            <div class="modal-body">
              <input type="hidden" name="id" id="edit-id">
              <div class="mb-3">
                <label class="form-label">名前</label>
                <input type="text" name="name" id="edit-name" class="form-control" required>
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
    <div class="modal fade" id="delConsultantModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">削除確認</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <form action="consuls_del_do.php" method="post">
            <div class="modal-body">
              <dl class="row">
                <dt class="col-sm-3">講師名</dt>
                <dd class="col-sm-9" id="del-name"></dd>
              </dl>

              <p>この講師を削除しますか？</p>

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
  // 編集
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

  // 削除
  document.addEventListener('DOMContentLoaded', () => {
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name');

        document.getElementById('delete-id').value = id;
        document.getElementById('del-name').textContent = name;
      });
    });
  });
</script>

<?php require_once './../inc/footer.php'; ?>