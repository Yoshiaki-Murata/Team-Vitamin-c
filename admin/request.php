<?php
require_once __DIR__ . '/../inc/function.php';

$db = db_connect();

$applys = [];
$statuses = [];

try {

  $sql = 'SELECT 
  apply_lists.id,
  apply_lists.apply_detail,
  apply_lists.apply_datetime,
  apply_lists.res_time,
  apply_lists.res_date,
  apply_lists.res_line,
  apply_lists.res_student_name,
  apply_lists.res_class_name,
  apply_lists.res_consultant_name,
  apply_lists.res_method_name,
  apply_lists.carecon_id,
  apply_lists.apply_status_id,
carecons.name AS carecon_name,
apply_status.name AS status_name
FROM apply_lists
INNER JOIN apply_status ON apply_lists.apply_status_id=apply_status.id
INNER JOIN carecons ON apply_lists.carecon_id=carecons.id
ORDER BY apply_lists.apply_datetime ASC';

  $stmt = $db->prepare($sql);
  $stmt->execute();
  $applys = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $sql_status = 'SELECT * FROM apply_status ORDER BY id ASC';
  $stmt_status = $db->prepare($sql_status);
  $stmt_status->execute();
  $statuses = $stmt_status->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $err_msg = 'データの取得に失敗しました:' . $e->getMessage();
}



require_once './../inc/header_admin.php';
?>

<body>
  <div class="l-wrapper">
    <h1 class="c-title">予約変更申請一覧</h1>
    <div class="mb-5">
      <h2 class="c-title_carecon">キャリコン変更申請</h2>
      <table class="table">
        <thead>
          <tr>
            <th>日付</th>
            <th>時間</th>
            <th>申請者</th>
            <th>申請日時</th>
            <th>対応ステータス</th>
            <th>詳細</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($applys as $apply):
            if ($apply['carecon_id'] == 1): ?>
              <tr>
                <td><?php echo h($apply['res_date']); ?></td>
                <td><?php echo h($apply['res_time']); ?></td>
                <td><?php echo h($apply['res_student_name']); ?></td>
                <td><?php echo h($apply['apply_datetime']); ?></td>
                <td><?php echo h($apply['status_name']); ?></td>
                <td>
                  <button type="button"
                    class="btn btn-warning"
                    data-bs-toggle="modal"
                    data-bs-target="#detailApplyModal"
                    data-id="<?php echo h($apply['id']); ?>"
                    data-student="<?php echo h($apply['res_student_name']) ?>"
                    data-datetime="<?php echo h($apply['apply_datetime']); ?>"
                    data-detail="<?php echo h($apply['apply_detail']); ?>"
                    data-date="<?php echo h($apply['res_date']); ?>"
                    data-time="<?php echo h($apply['res_time']); ?>"
                    data-line="<?php echo h($apply['res_line']); ?>"
                    data-consultant="<?php echo h($apply['res_consultant_name']); ?>"
                    data-class="<?php echo h($apply['res_class_name']); ?>"
                    data-carecon="<?php echo h($apply['carecon_name']); ?>"
                    data-status="<?php echo h($apply['status_name']); ?>"
                    data-method="<?php echo h($apply['res_method_name']); ?>">
                    詳細
                  </button>
                </td>
                <td>
                  <button type="button"
                    class="btn btn-primary edit-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#editApplyModal"
                    data-id="<?php echo h($apply['id']); ?>"
                    data-status-id="<?php echo h($apply['apply_status_id']); ?>">
                    変更
                  </button>

                  <button type="button"
                    class="btn btn-danger del-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#delApplyModal"
                    data-id="<?php echo h($apply['id']); ?>">
                    削除
                  </button>
                </td>
              </tr>
          <?php endif;
          endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="mb-5">
      <h2 class="c-title_plus">キャリコンプラス変更申請</h2>
      <table class="table">
        <thead>
          <tr>
            <th>日付</th>
            <th>時間</th>
            <th>申請者</th>
            <th>申請日時</th>
            <th>対応ステータス</th>
            <th>詳細</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($applys as $apply):
            if ($apply['carecon_id'] == 2): ?>
              <tr>
                <td><?php echo h($apply['res_date']); ?></td>
                <td><?php echo h($apply['res_time']); ?></td>
                <td><?php echo h($apply['res_student_name']); ?></td>
                <td><?php echo h($apply['apply_datetime']); ?></td>
                <td><?php echo h($apply['status_name']); ?></td>
                <td>
                  <button type="button"
                    class="btn btn-warning"
                    data-bs-toggle="modal"
                    data-bs-target="#detailApplyModal"
                    data-id="<?php echo h($apply['id']); ?>"
                    data-student="<?php echo h($apply['res_student_name']) ?>"
                    data-datetime="<?php echo h($apply['apply_datetime']); ?>"
                    data-detail="<?php echo h($apply['apply_detail']); ?>"
                    data-date="<?php echo h($apply['res_date']); ?>"
                    data-time="<?php echo h($apply['res_time']); ?>"
                    data-line="<?php echo h($apply['res_line']); ?>"
                    data-consultant="<?php echo h($apply['res_consultant_name']  ?? ''); ?>"
                    data-class="<?php echo h($apply['res_class_name']  ?? ''); ?>"
                    data-carecon="<?php echo h($apply['carecon_name']); ?>"
                    data-status="<?php echo h($apply['status_name']); ?>"
                    data-method="<?php echo h($apply['res_method_name']); ?>">
                    詳細
                  </button>
                </td>
                <td>
                  <button type="button"
                    class="btn btn-primary edit-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#editApplyModal"
                    data-id="<?php echo h($apply['id']); ?>"
                    data-status-id="<?php echo h($apply['apply_status_id']); ?>">
                    変更
                  </button>
                  <button type="button"
                    class="btn btn-danger del-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#delApplyModal"
                    data-id="<?php echo h($apply['id']); ?>">
                    削除
                  </button>
                </td>
              </tr>
          <?php endif;
          endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- 詳細モーダル -->
    <div class="modal fade" id="detailApplyModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">変更申請詳細</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <h6>●申請情報</h6>
            <dl class="row mb-3">
              <dt class="col-sm-3">申請者</dt>
              <dd class="col-sm-9 modal-student"></dd>

              <dt class="col-sm-3">申請日時</dt>
              <dd class="col-sm-9 modal-datetime"></dd>

              <dt class="col-sm-3">変更詳細</dt>
              <dd class="col-sm-9 modal-detail"></dd>
            </dl>

            <h6>●予約情報</h6>
            <dl class="row mb-3">
              <dt class="col-sm-3">予約日</dt>
              <dd class="col-sm-9 modal-date"></dd>

              <dt class="col-sm-3">予約時間</dt>
              <dd class="col-sm-9 modal-time"></dd>

              <dt class="col-sm-3">予約ライン</dt>
              <dd class="col-sm-9 modal-line"></dd>

              <dt class="col-sm-3">実施方法</dt>
              <dd class="col-sm-9 modal-method"></dd>

              <dt class="col-sm-3">担当講師</dt>
              <dd class="col-sm-9 modal-consultant"></dd>

              <dt class="col-sm-3">教室</dt>
              <dd class="col-sm-9 modal-class"></dd>

              <dt class="col-sm-3">キャリコン種別</dt>
              <dd class="col-sm-9 modal-carecon"></dd>
            </dl>

            <dl class="row mb-3">
              <dt class="col-sm-3">対応ステータス</dt>
              <dd class="col-sm-9 modal-status"></dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
    <!-- ここまで -->

    <!-- 変更モーダル -->
    <div class="modal fade" id="editApplyModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="./request_edit_do.php" method="post">
            <div class="modal-header">
              <h5 class="modal-title">対応ステータス変更</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id" id="edit-id">
              <select name="status_id" id="edit-status" class="form-select">
                <?php foreach ($statuses as $status): ?>
                  <option value="<?php echo h($status['id']); ?>">
                    <?php echo h($status['name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                キャンセル
              </button>
              <button type="submit" class="btn btn-primary">
                更新
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- ここまで -->

    <!-- 削除モーダル -->
    <div class="modal fade" id="delApplyModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="./request_del_do.php" method="post">
            <div class="modal-header">
              <h5 class="modal-title">削除確認</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <p>この申請を削除しますか?</p>
              <input type="hidden" name="id" class="del-id">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">いいえ</button>
              <button type="submit" class="btn btn-danger">削除</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- ここまで -->


  </div>
</body>

<script>
  document.addEventListener('DOMContentLoaded', function() {

    // =========================
    // 共通：値セット関数（null/undefined対策）
    // =========================
    function setText(parent, selector, value) {
      const el = parent.querySelector(selector);
      if (!el) return;
      el.textContent = (value !== undefined && value !== null && value !== '') ?
        value :
        '未設定';
    }

    // =========================
    // 削除モーダル
    // =========================
    document.querySelectorAll('.del-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const input = document.querySelector('#delApplyModal .del-id');
        if (input) input.value = id || '';
      });
    });

    // =========================
    // 編集モーダル
    // =========================
    document.querySelectorAll('.edit-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const statusId = this.dataset.statusId;

        // hiddenにIDセット
        const idInput = document.querySelector('#edit-id');
        if (idInput) idInput.value = id || '';

        // selectの選択状態セット
        const select = document.querySelector('#edit-status');
        if (select) {
          Array.from(select.options).forEach(option => {
            option.selected = (option.value === statusId);
          });
        }
      });
    });

    // =========================
    // 詳細モーダル
    // =========================
    document.querySelectorAll('[data-bs-target="#detailApplyModal"]').forEach(btn => {
      btn.addEventListener('click', function() {

        const modal = document.querySelector('#detailApplyModal');
        if (!modal) return;

        // 申請情報
        setText(modal, '.modal-student', this.dataset.student);
        setText(modal, '.modal-datetime', this.dataset.datetime);
        setText(modal, '.modal-detail', this.dataset.detail);

        // 予約情報
        setText(modal, '.modal-date', this.dataset.date);
        setText(modal, '.modal-time', this.dataset.time);
        setText(modal, '.modal-line', this.dataset.line);
        setText(modal, '.modal-method', this.dataset.method);
        setText(modal, '.modal-consultant', this.dataset.consultant);
        setText(modal, '.modal-class', this.dataset.class);
        setText(modal, '.modal-carecon', this.dataset.carecon);

        // ステータス
        setText(modal, '.modal-status', this.dataset.status);

      });
    });

  });
</script>

<?php require_once './../inc/footer.php'; ?>