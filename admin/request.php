<?php

require_once __DIR__ . '/../inc/function.php';

$db = db_connect();

$requests = [];
$statuses = [];

try {
  $sql = 'SELECT 
apply_lists.id,
apply_lists.apply_detail,
reservation_slots.date,
reservation_slots.carecon_id,
apply_status.id AS status_id,
apply_status.name AS apply_status_name,
students.name AS student_name,
methods.name AS method_name,
times.time AS reserve_time,
classes.name AS reserve_class,
consultants.name AS reserve_consultant,
carecons.name AS reserve_carecon,
carecon_lines.line AS reserve_line
FROM apply_lists 
INNER JOIN apply_status ON apply_lists.apply_status_id= apply_status.id
INNER JOIN reservation_infos ON apply_lists.reserve_info_id = reservation_infos.id
INNER JOIN students ON reservation_infos.student_id = students.id
INNER JOIN methods ON reservation_infos.method_id = methods.id
INNER JOIN reservation_slots ON reservation_infos.slot_id = reservation_slots.id
INNER JOIN times ON reservation_slots.time_id = times.id
LEFT JOIN classes ON reservation_slots.class_id = classes.id
LEFT JOIN consultants ON reservation_slots.consultant_id = consultants.id
INNER JOIN carecons ON reservation_slots.carecon_id = carecons.id
INNER JOIN carecon_lines ON reservation_slots.lines_id = carecon_lines.id
ORDER BY
reservation_slots.date ASC,
reservation_slots.lines_id ASC,
reservation_slots.time_id ASC';

  $stmt = $db->prepare($sql);
  $stmt->execute();
  $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $sql_status = 'SELECT * FROM apply_status ORDER BY id ASC';
  $stmt_status = $db->prepare($sql_status);
  $stmt_status->execute();
  $statuses = $stmt_status->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $err_msg = 'データの取得に失敗しました:' . $e->getMessage();
};

// check_array($requests);

require_once './../inc/header_admin.php';
?>


<body>
  <div class="l-wrapper">
    <h1 class="c-title">予約変更・キャンセル申請一覧</h1>
    <h2 class="mb-3">キャリコン変更申請一覧</h2>
    <table class="table mb-5">
      <thead>
        <tr>
          <th scope="col">日付</th>
          <th scope="col">時間</th>
          <th scope="col">ライン</th>
          <th scope="col">訓練生名</th>
          <th scope="col">教室</th>
          <th scope="col">担当講師</th>
          <th scope="col">対応ステータス</th>
          <th scope="col">詳細</th>
          <th scope="col">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($requests as $request):
          if ($request['carecon_id'] == 1): ?>
            <tr>
              <td><?php echo h($request['date']); ?></td>
              <td><?php echo h($request['reserve_time']); ?></td>
              <td><?php echo h($request['reserve_line']); ?></td>
              <td><?php echo h($request['student_name']); ?></td>
              <td><?php echo h($request['reserve_class']); ?></td>
              <td><?php echo h($request['reserve_consultant']); ?></td>
              <td><?php echo h($request['apply_status_name']); ?></td>
              <td><button type="button" class="btn btn-info">詳細</button></td>
              <td>
                <button type="button"
                  class="btn btn-primary edit-btn"
                  data-bs-toggle="modal"
                  data-bs-target="#editStatusModal"
                  data-id="<?php echo h($request['id']); ?>"
                  data-status-id="<?php echo h($request['status_id']); ?>">
                  更新
                </button>
                <button type="button"
                  class="btn btn-danger del-btn"
                  data-bs-toggle="modal"
                  data-bs-target="#delStatusModal"
                  data-id="<?php echo h($request['id']); ?>">
                  削除
                </button>
              </td>
            </tr>
        <?php endif;
        endforeach; ?>
      </tbody>
    </table>

    <h2 class="mb-3">キャリコンプラスキャンセル申請一覧</h2>
    <table class="table mb-5">
      <thead>
        <tr>
          <th scope="col">日付</th>
          <th scope="col">時間</th>
          <th scope="col">ライン</th>
          <th scope="col">訓練生名</th>
          <th scope="col">教室</th>
          <th scope="col">担当講師</th>
          <th scope="col">対応ステータス</th>
          <th scope="col">詳細</th>
          <th scope="col">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($requests as $request):
          if ($request['carecon_id'] == 2): ?>
            <tr>
              <td><?php echo h($request['date']); ?></td>
              <td><?php echo h($request['reserve_time']); ?></td>
              <td><?php echo h($request['reserve_line']); ?></td>
              <td><?php echo h($request['student_name']); ?></td>
              <td><?php echo h($request['reserve_class']); ?></td>
              <td><?php echo h($request['reserve_consultant']); ?></td>
              <td><?php echo h($request['apply_status_name']); ?></td>
              <td><button type="button" class="btn btn-info">詳細</button></td>
              <td>
                <button type="button"
                  class="btn btn-primary edit-btn"
                  data-bs-toggle="modal"
                  data-bs-target="#editStatusModal"
                  data-id="<?php echo h($request['id']); ?>"
                  data-status-id="<?php echo h($request['status_id']); ?>">
                  更新
                </button>
                <button type="button"
                  class="btn btn-danger del-btn"
                  data-bs-toggle="modal"
                  data-bs-target="#delStatusModal"
                  data-id="<?php echo h($request['id']); ?>">
                  削除
                </button>
              </td>
            </tr>
        <?php endif;
        endforeach; ?>
      </tbody>
    </table>

    <!-- 更新モーダル -->
    <div class="modal fade" id="editStatusModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">対応ステータス</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <form action="./request_edit_do.php" method="post">
            <div class="modal-body px-4">
              <input type="hidden" name="id" id="edit-id">
              <div class="mb-3">
                <label class="form-label fw-bold">対応ステータス</label>
                <select name="status_id" id="edit-status" class="form-select" required>
                  <?php foreach ($statuses as $status): ?>
                    <option value="<?php echo h($status['id']); ?>">
                      <?php echo h($status['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
              <button type="submit" class="btn btn-primary">更新</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- 編集モーダルここまで -->
    <!-- 削除モーダル -->
    <div class="modal fade" id="delStatusModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">削除確認</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <form action="./request_del_do.php" method="post">
            <div class="modal-body">
              <p>この申請を削除しますか？</p>
              <input type="hidden" name="id" id="delete-id">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">いいえ</button>
              <button type="submit" class="btn btn-danger">はい(削除)</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- 削除モーダルここまで -->
  </div>
</body>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const editBtns = document.querySelectorAll('.edit-btn');

    editBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('edit-id').value = btn.dataset.id;
        document.getElementById('edit-status').value = btn.dataset.statusId;
      });
    });
  });

  document.addEventListener('DOMContentLoaded', () => {
    const deleteBtn = document.querySelectorAll('.del-btn');

    deleteBtn.forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-id');
        document.getElementById('delete-id').value = id;
      });
    });
  });
</script>
<?php require_once './../inc/footer.php'; ?>