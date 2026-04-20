<?php

require_once __DIR__ . '/../inc/function.php';

$db = db_connect();

$slots = [];

$times = [];
$classes = [];
$consultants = [];
$carecons = [];
$lines = [];
$statuses = [];
$dates = [];

$err_mgs = '';

// ★ GET修正（キー統一）
$line_id = $_GET['line'] ?? '';
$date = $_GET['date'] ?? '';

try {

  // ▼ WHERE条件
  $where = [];
  $params = [];

  if (!empty($date)) {
    $where[] = 'DATE(reservation_slots.date) = :date';
    $params[':date'] = $date;
  }

  if (!empty($line_id)) {
    $where[] = 'reservation_slots.lines_id = :line_id';
    $params[':line_id'] = $line_id;
  }

  $sql = 'SELECT 
    reservation_slots.id,
    reservation_slots.date,
    reservation_slots.time_id,
    reservation_slots.lines_id,
    reservation_slots.class_id,
    reservation_slots.consultant_id,
    reservation_slots.carecon_id,
    reservation_slots.reserve_status_id,

    times.time AS time_slot,
    classes.name AS class_name,
    consultants.name AS consultant_name,
    carecons.name AS carecon_name,
    carecon_lines.line AS line_number,
    reserve_status.name AS reserve_status_name

  FROM reservation_slots

  INNER JOIN times 
    ON reservation_slots.time_id = times.id
  LEFT JOIN classes 
    ON reservation_slots.class_id = classes.id
  LEFT JOIN consultants 
    ON reservation_slots.consultant_id = consultants.id
  INNER JOIN carecons 
    ON reservation_slots.carecon_id = carecons.id
  INNER JOIN carecon_lines 
    ON reservation_slots.lines_id = carecon_lines.id
  INNER JOIN reserve_status 
    ON reservation_slots.reserve_status_id = reserve_status.id
  ';

  // ★ WHEREはここで一括付与（これが一番重要）
  if (!empty($where)) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
  }

  $sql .= ' ORDER BY 
    reservation_slots.date ASC,
    reservation_slots.lines_id ASC,
    reservation_slots.time_id ASC';

  $stmt = $db->prepare($sql);

  // ★ bindまとめて
  foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val, PDO::PARAM_STR);
  }

  $stmt->execute();
  $slots = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // ▼ 各マスタ取得（そのまま）
  $times = $db->query('SELECT * FROM times ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
  $classes = $db->query('SELECT * FROM classes ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
  $consultants = $db->query('SELECT * FROM consultants ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
  $carecons = $db->query('SELECT * FROM carecons ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
  $lines = $db->query('SELECT * FROM carecon_lines ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
  $statuses = $db->query('SELECT * FROM reserve_status ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);

  // ▼ 日付一覧
  $sql_dates = 'SELECT DISTINCT DATE(date) as date FROM reservation_slots ORDER BY date ASC';
  $dates = $db->query($sql_dates)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $err_msg = 'データの取得に失敗しました:' . $e->getMessage();
};

require_once './../inc/header_admin.php';
?>

<body>
  <div class="l-wrapper">
    <h1 class="c-title">キャリコン枠作成</h1>

    <button type="button" class="btn btn-info mb-3 add-btn" data-bs-toggle="modal" data-bs-target="#addSlotModal">
      新規キャリコン枠登録
    </button>

    <!-- モーダル -->
    <div class="modal fade" id="addSlotModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">キャリコン枠作成</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <form action="./schedule_add_do.php" method="post">
            <div class="modal-body px-4">
              <div class="mb-3">
                <label class="form-label fw-bold">日付</label>
                <input type="date" name="date" class="form-control" required>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">時間</label>
                <select name="time_id" class="form-select" required>
                  <option value="" disabled>時間</option>
                  <?php foreach ($times as $time): ?>
                    <option value="<?php echo h($time['id']); ?>">
                      <?php echo h($time['time']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">ライン</label>
                <select name="lines_id" class="form-select" required>
                  <option value="" disabled>ライン</option>
                  <?php foreach ($lines as $line): ?>
                    <option value="<?php echo h($line['id']); ?>">
                      <?php echo h($line['line']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">教室</label>
                <select name="class_id" class="form-select">
                  <option value="">未定</option>
                  <?php foreach ($classes as $class): ?>
                    <option value="<?php echo h($class['id']); ?>">
                      <?php echo h($class['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">担当</label>
                <select name="consultant_id" class="form-select">
                  <option value="">未定</option>
                  <?php foreach ($consultants as $consultant): ?>
                    <option value="<?php echo h($consultant['id']); ?>">
                      <?php echo h($consultant['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">キャリコン種別</label>
                <select name="carecon_id" class="form-select" required>
                  <option value="" disabled>キャリコン種別</option>
                  <?php foreach ($carecons as $carecon): ?>
                    <option value="<?php echo h($carecon['id']); ?>">
                      <?php echo h($carecon['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">予約状況</label>
                <select name="reserve_status_id" class="form-select" required>
                  <?php foreach ($statuses as $status): ?>
                    <option value="<?php echo h($status['id']); ?>" <?php echo $status['id'] == 1 ? 'selected' : '' ?>>
                      <?php echo h($status['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

            </div>

            <div class="modal-footer">
              <input type="submit" value="登録" class="btn btn-primary">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
            </div>

          </form>

        </div>
      </div>
    </div>
    <!-- ここまでモーダル -->

    <!-- フィルター -->
    <form method="get" class="mb-3 w-25 d-flex gap-2">

      <div class="w-100">
        <label class="form-label">日付で絞り込み</label>
        <select name="date" class="form-select" onchange="this.form.submit()">
          <option value="">全日程</option>
          <?php foreach ($dates as $d): ?>
            <option value="<?php echo h($d['date']); ?>"
              <?php if ($d['date'] === $date) echo 'selected'; ?>>
              <?php echo h($d['date']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="w-100">
        <label class="form-label">ラインで絞り込み</label>
        <select name="line" class="form-select" onchange="this.form.submit()">
          <option value="">全ライン</option>
          <?php foreach ($lines as $line_select): ?>
            <option value="<?php echo h($line_select['id']); ?>"
              <?php if ($line_select['id'] == $line_id) echo 'selected'; ?>>
              <?php echo h($line_select['line']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

    </form>
    <!-- ここまで -->

    <table class="table">
      <thead>
        <tr>
          <th>日付</th>
          <th>時間</th>
          <th>ライン</th>
          <th>教室</th>
          <th>担当</th>
          <th>キャリコン種別</th>
          <th>予約状況</th>
          <th>操作</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($slots as $slot): ?>
          <tr>
            <td><?php echo h($slot['date']); ?></td>
            <td><?php echo h($slot['time_slot']); ?></td>
            <td><?php echo h($slot['line_number']); ?></td>
            <td><?php echo h($slot['class_name'] ?? '未定'); ?></td>
            <td><?php echo h($slot['consultant_name'] ?? '未定'); ?></td>
            <td><?php echo h($slot['carecon_name']); ?></td>
            <td><?php echo h($slot['reserve_status_name']); ?></td>
            <td>
              <button type="button"
                class="btn btn-primary edit-btn"
                data-bs-toggle="modal"
                data-bs-target="#editSlotModal"
                data-id="<?= h($slot['id']); ?>"
                data-date="<?= h($slot['date']); ?>"
                data-time-id="<?= h($slot['time_id']); ?>"
                data-line-id="<?= h($slot['lines_id']); ?>"
                data-class-id="<?= h($slot['class_id']); ?>"
                data-consul-id="<?= h($slot['consultant_id']); ?>"
                data-carecon-id="<?= h($slot['carecon_id']); ?>"
                data-status-id="<?= h($slot['reserve_status_id']); ?>">
                編集
              </button>

              <button type="button"
                class="btn btn-danger del-btn"
                data-bs-toggle="modal"
                data-bs-target="#delSlotModal"
                data-id="<?= h($slot['id']); ?>">
                削除
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- 編集モーダル -->
    <div class="modal fade" id="editSlotModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">キャリコン枠編集</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <form action="./schedule_edit_do.php" method="post">
            <div class="modal-body px-4">
              <input type="hidden" name="id" id="edit-id">
              <div class="mb-3">
                <label class="form-label fw-bold">日付</label> <input type="date" name="date" class="form-control" id="edit-date" required>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">時間</label> <select name="time_id" class="form-select" id="edit-time" required>
                  <option value="" disabled>時間</option>
                  <?php foreach ($times as $time): ?>
                    <option value="<?php echo h($time['id']); ?>">
                      <?php echo h($time['time']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">ライン</label> <select name="lines_id" class="form-select" id="edit-line" required>
                  <option value="" disabled>ライン</option>
                  <?php foreach ($lines as $line): ?>
                    <option value="<?php echo h($line['id']); ?>">
                      <?php echo h($line['line']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">教室</label> <select name="class_id" class="form-select" id="edit-class">
                  <option value="">未定</option>
                  <?php foreach ($classes as $class): ?>
                    <option value="<?php echo h($class['id']); ?>">
                      <?php echo h($class['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">担当</label> <select name="consultant_id" class="form-select" id="edit-consul">
                  <option value="">未定</option>
                  <?php foreach ($consultants as $consultant): ?> <option value="<?php echo h($consultant['id']); ?>">
                      <?php echo h($consultant['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">キャリコン種別</label>
                <select name="carecon_id" class="form-select" id="edit-carecon" required>
                  <option value="" disabled>キャリコン種別</option>
                  <?php foreach ($carecons as $carecon): ?>
                    <option value="<?php echo h($carecon['id']); ?>">
                      <?php echo h($carecon['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">予約状況</label> <select name="reserve_status_id" class="form-select" id="edit-status" required>
                  <?php foreach ($statuses as $status): ?>
                    <option value="<?php echo h($status['id']); ?>"> <?php echo h($status['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <input type="submit" value="更新" class="btn btn-primary">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- ここまで -->
    <!-- 削除モーダル -->
    <div class="modal fade" id="delSlotModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">削除確認</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <form action="./schedule_del_do.php" method="post">
            <div class="modal-body">
              <p>このキャリコン枠を削除しますか？</p>
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
    <!-- ここまで -->

  </div>
</body>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.edit-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('edit-id').value = btn.dataset.id;
        document.getElementById('edit-date').value = btn.dataset.date;
        document.getElementById('edit-time').value = btn.dataset.timeId;
        document.getElementById('edit-line').value = btn.dataset.lineId;
        document.getElementById('edit-class').value = btn.dataset.classId ?? '';
        document.getElementById('edit-consul').value = btn.dataset.consulId ?? '';
        document.getElementById('edit-carecon').value = btn.dataset.careconId;
        document.getElementById('edit-status').value = btn.dataset.statusId;
      });
    });

    document.querySelectorAll('.del-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('delete-id').value = btn.dataset.id;
      });
    });
  });
</script>

<?php require_once './../inc/footer.php'; ?>