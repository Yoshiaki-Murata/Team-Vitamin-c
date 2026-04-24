<?php
require_once __DIR__ . '/../inc/function.php';
check_logined();

$db = db_connect();

$reservations = [];
$methods = [];
$students = [];
$classes = [];
$dates = [];
$lines = [];

$line_id = $_GET['line'] ?? '';
$date = $_GET['date'] ?? '';
$student_id = $_GET['student_id'] ?? '';
$status_id = $_GET['status'] ?? '';
$method_id = $_GET['method'] ?? '';
$carecon_id = $_GET['carecon'] ?? '';

try {
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

  if (!empty($student_id)) {
    $where[] = 'students.id = :student_id';
    $params[':student_id'] = $student_id;
  }

  if (!empty($status_id)) {
    $where[] = 'reservation_slots.reserve_status_id = :status';
    $params[':status'] = $status_id;
  }

  if (!empty($method_id)) {
    $where[] = 'methods.id = :method';
    $params[':method'] = $method_id;
  }

  if (!empty($carecon_id)) {
    $where[] = 'carecons.id=:carecon';
    $params[':carecon'] = $carecon_id;
  }

  $sql = 'SELECT 
    reservation_slots.id AS slot_id, 
    reservation_infos.id AS reservation_id, 
    reservation_slots.date, 
    reservation_slots.reserve_status_id,
    times.time AS reserve_time,
    classes.name AS reserve_class, 
    consultants.name AS reserve_consultant, 
    students.name AS reserve_student, 
    students.class_id AS student_class_id,
    methods.name AS reserve_method, 
    carecons.name AS reserve_carecon, 
    carecon_lines.line AS reserve_line, 
    reserve_status.name AS reservation_status,
    students.id AS student_id,
    methods.id AS method_id
  FROM reservation_slots
    LEFT JOIN reservation_infos ON reservation_infos.slot_id = reservation_slots.id 
    INNER JOIN times ON reservation_slots.time_id = times.id 
    LEFT JOIN classes ON reservation_slots.class_id = classes.id 
    LEFT JOIN consultants ON reservation_slots.consultant_id = consultants.id 
    LEFT JOIN students ON reservation_infos.student_id = students.id 
    LEFT JOIN methods ON reservation_infos.method_id = methods.id 
    INNER JOIN carecons ON reservation_slots.carecon_id = carecons.id 
    INNER JOIN carecon_lines ON reservation_slots.lines_id = carecon_lines.id 
    INNER JOIN reserve_status ON reservation_slots.reserve_status_id = reserve_status.id';

  if (!empty($where)) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
  }

  $sql .= ' ORDER BY 
    reservation_slots.date ASC, 
    reservation_slots.lines_id ASC, 
    reservation_slots.time_id ASC';

  $stmt = $db->prepare($sql);

  foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val, PDO::PARAM_STR);
  }

  $stmt->execute();
  $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $methods = $db->query('SELECT * FROM methods ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
  $students = $db->query('SELECT id, name, class_id FROM students')->fetchAll(PDO::FETCH_ASSOC);
  $classes = $db->query('SELECT * FROM classes ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
  $lines = $db->query('SELECT * FROM carecon_lines ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
  $dates = $db->query('SELECT DISTINCT DATE(date) as date FROM reservation_slots ORDER BY date ASC')->fetchAll(PDO::FETCH_ASSOC);
  $carecons = $db->query('SELECT * FROM carecons ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $err_msg = 'データの取得に失敗しました:' . $e->getMessage();
}

require_once './../inc/header_admin.php';
?>

<body>
  <div class="l-wrapper">
    <h1 class="c-title">予約状況一覧</h1>

    <?php if (!empty($_SESSION["msg"])): ?>
      <p class="alert alert-success text-center mx-auto col-6" role="alert">
        <?php echo $_SESSION["msg"];
        unset($_SESSION["msg"]);
        ?>
      </p>
    <?php endif; ?>
    <?php if (!empty($_SESSION["err_msg"])): ?>
      <p class="alert alert-danger text-center mx-auto col-6" role="alert">
        <?php echo $_SESSION["err_msg"];
        unset($_SESSION["err_msg"]);
        ?>
      </p>
    <?php endif; ?>

    <form method="get" class="mb-3">
      <div class="row g-2">
        <!-- ライン -->
        <div class="col-md-2">
          <select name="line" class="form-select">
            <option value="">ライン</option>
            <?php foreach ($lines as $l): ?>
              <option value="<?php echo h($l['id']) ?>" <?php echo ($l['id'] == $line_id) ? 'selected' : '' ?>>
                <?php echo h($l['line']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- 日付 -->
        <div class="col-md-2">
          <select name="date" class="form-select">
            <option value="">日付</option>
            <?php foreach ($dates as $d): ?>
              <option value="<?php echo h($d['date']) ?>" <?php echo ($d['date'] === $date) ? 'selected' : '' ?>>
                <?php echo h($d['date']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- 訓練生 -->
        <div class="col-md-2">
          <select name="student_id" class="form-select">
            <option value="">訓練生</option>
            <?php foreach ($students as $s): ?>
              <option value="<?php echo h($s['id']); ?>" <?php echo ($s['id'] == $student_id) ? 'selected' : '' ?>>
                <?php echo h($s['name']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-2">
          <select name="carecon" class="form-select">
            <option value="">キャリコン種別</option>
            <?php foreach ($carecons as $carecon): ?>
              <option value="<?php echo h($carecon['id']); ?>" <?php echo ($carecon['id'] == $carecon_id) ? 'selected' : '' ?>>
                <?php echo h($carecon['name']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- 方法 -->
        <div class="col-md-2">
          <select name="method" class="form-select">
            <option value="">方法</option>
            <?php foreach ($methods as $m): ?>
              <option value="<?php echo h($m['id']); ?>" <?php echo ($m['id'] == $method_id) ? 'selected' : '' ?>>
                <?php echo h($m['name']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- 状態 -->
        <div class="col-md-2">
          <select name="status" class="form-select">
            <option value="">予約状況</option>
            <option value="1" <?php echo ($status_id == 1) ? 'selected' : '' ?>>空き</option>
            <option value="2" <?php echo ($status_id == 2) ? 'selected' : '' ?>>予約済</option>
          </select>
        </div>

        <!-- ボタン -->
        <div class="col-md-2 d-flex gap-1">
          <button class="btn btn-primary w-100">検索</button>
          <a href="reservation.php" class="btn btn-secondary w-100">リセット</a>
        </div>

      </div>
    </form>

    <div class="table-responsive" style="max-height: 500px;">
      <table class="table table-hover align-middle">
        <thead class="table-light" style="position: sticky; top:0; z-index:1;">
          <tr>
            <th>ライン</th>
            <th>日付</th>
            <th>時間</th>
            <th>訓練生</th>
            <th>教室</th>
            <th>講師</th>
            <th>キャリコン種別</th>
            <th>方法</th>
            <th>予約状況</th>
            <th></th>
          </tr>
        </thead>

        <tbody>

          <?php if (empty($reservations)): ?>
            <tr>
              <td colspan="9" class="text-center text-muted py-4">
                データがありません
              </td>
            </tr>
          <?php endif; ?>

          <?php foreach ($reservations as $row): ?>
            <tr style="cursor:pointer;">

              <td><?php echo h($row['reserve_line']); ?></td>
              <td><?php echo h($row['date']); ?></td>
              <td><?php echo h($row['reserve_time']) ?></td>
              <td><?php echo $row['reserve_student'] ? h($row['reserve_student']) : '-'; ?></td>
              <td><?php echo $row['reserve_class'] ?: '未定'; ?></td>
              <td><?php echo $row['reserve_consultant'] ?: '未定'; ?></td>
              <td><?php echo $row['reserve_carecon'];; ?></td>
              <td><?php echo $row['reserve_method'] ?: '-'; ?></td>

              <td>
                <?php if ($row['reserve_status_id'] == 1): ?>
                  <span class="badge bg-success">空き</span>
                <?php elseif ($row['reserve_status_id'] == 2): ?>
                  <span class="badge bg-danger">予約済</span>
                <?php else: ?>
                  <span class="badge bg-secondary">
                    <?php echo h($row['reservation_status']); ?>
                  </span>
                <?php endif; ?>
              </td>

              <td>
                <?php if ($row['reservation_id']): ?>
                  <button class="btn btn-sm btn-primary edit-btn"
                    data-bs-toggle="modal" data-bs-target="#editReserveModal"
                    data-id="<?php echo h($row['reservation_id']); ?>"
                    data-student-id="<?php echo h($row['student_id']); ?>"
                    data-method-id="<?php echo h($row['method_id']); ?>"
                    data-student-class-id="<?php echo h($row['student_class_id']); ?>">
                    変更</button>

                  <button class="btn btn-sm btn-danger del-btn"
                    data-bs-toggle="modal" data-bs-target="#delReserveModal"
                    data-id="<?php echo h($row['reservation_id']); ?>"
                    data-carecon="<?php echo h($row['reserve_carecon']); ?>"
                    data-date="<?php echo h($row['date']); ?>"
                    data-time="<?php echo h($row['reserve_time']); ?>"
                    data-student="<?php echo h($row['reserve_student']); ?>">
                    削除</button>

                <?php else: ?>
                  <button class="btn btn-sm btn-warning add-btn"
                    data-bs-toggle="modal" data-bs-target="#addReserveModal"
                    data-id="<?php echo h($row['slot_id']); ?>">
                    追加</button>
                <?php endif; ?>
              </td>

            </tr>
          <?php endforeach; ?>

        </tbody>
      </table>
    </div>

    <!-- 追加 -->
    <div class="modal fade" id="addReserveModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">

          <form method="post" action="./reservation_add_do.php">

            <div class="modal-header">
              <h5 class="modal-title">新規予約追加</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
              <input type="hidden" name="slot_id" id="add-slot-id">

              <div class="mb-3">
                <label>クラス</label>
                <select id="add-class" name="class_id" class="form-select">
                  <option value="">選択してください</option>
                  <?php foreach ($classes as $c): ?>
                    <option value="<?php echo h($c['id']); ?>"><?php echo h($c['name']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label>訓練生</label>
                <select id="add-student" name="student_id" class="form-select">
                  <option value="">選択してください</option>
                  <?php foreach ($students as $s): ?>
                    <option value="<?php echo h($s['id']); ?>" data-class-id="<?php echo h($s['class_id']); ?>">
                      <?php echo h($s['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label>実施方法</label>
                <select name="method_id" class="form-select">
                  <?php foreach ($methods as $m): ?>
                    <option value="<?php echo h($m['id']); ?>"><?php echo h($m['name']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
              <button type="submit" class="btn btn-primary">追加</button>
            </div>

          </form>

        </div>
      </div>
    </div>

    <!-- 編集 -->
    <div class="modal fade" id="editReserveModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">

          <form action="./reservation_edit_do.php" method="post">

            <div class="modal-header">
              <h5 class="modal-title">予約編集</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
              <input type="hidden" name="id" id="edit-id">

              <div class="mb-3">
                <label class="form-label">クラス</label>
                <select name="class_id" id="edit-class" class="form-select">
                  <option value="">選択してください</option>
                  <?php foreach ($classes as $class): ?>
                    <option value="<?php echo h($class['id']); ?>">
                      <?php echo h($class['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">訓練生</label>
                <select name="student_id" id="edit-student" class="form-select">
                  <option value="">選択してください</option>
                  <?php foreach ($students as $student): ?>
                    <option value="<?php echo h($student['id']); ?>" data-class-id="<?php echo h($student['class_id']); ?>">
                      <?php echo h($student['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">実施方法</label>
                <select name="method_id" id="edit-method" class="form-select">
                  <option value="">選択してください</option>
                  <?php foreach ($methods as $method): ?>
                    <option value="<?php echo h($method['id']); ?>">
                      <?php echo h($method['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
              <button type="submit" class="btn btn-primary">更新</button>
            </div>

          </form>

        </div>
      </div>
    </div>

    <!-- 削除 -->
    <div class="modal fade" id="delReserveModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">

          <form action="./reservation_del_do.php" method="post">

            <div class="modal-header">
              <h5 class="modal-title">削除確認</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <dl class="row">
                <dt class="col-sm-3">日付</dt>
                <dd class="col-sm-9" id="del-date"></dd>
                <dt class="col-sm-3">時間</dt>
                <dd class="col-sm-9" id="del-time"></dd>
                <dt class="col-sm-3">予約者</dt>
                <dd class="col-sm-9" id="del-student"></dd>
                <dt class="col-sm-3">キャリコン種別</dt>
                <dd class="col-sm-9" id="del-carecon"></dd>
              </dl>
              <p>この予約を削除しますか？</p>
              <input type="hidden" name="id" id="del-id">
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">いいえ</button>
              <button type="submit" class="btn btn-danger">削除</button>
            </div>

          </form>

        </div>
      </div>
    </div>

  </div>

  <script>
    function filter(classId, studentId) {
      const c = document.getElementById(classId);
      const s = document.getElementById(studentId);
      const opts = [...s.options];

      c.onchange = () => {
        const val = c.value;
        //s.innerHTML = '<option value=""></option>';
        opts.forEach(o => {
          if (!val || o.dataset.classId === val) s.appendChild(o);
        });
      };
      c.dispatchEvent(new Event('change'));
    }

    filter('add-class', 'add-student');
    filter('edit-class', 'edit-student');

    document.querySelectorAll('.add-btn').forEach(b => {
      b.onclick = () => {
        document.getElementById('add-slot-id').value = b.dataset.id;
      };
    });

    // 削除
    document.querySelectorAll('.del-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('del-id').value = btn.dataset.id;

        const student = btn.getAttribute('data-student');
        const date = btn.getAttribute('data-date');
        const time = btn.getAttribute('data-time');
        const carecon = btn.getAttribute('data-carecon');

        document.getElementById('del-student').textContent = student;
        document.getElementById('del-date').textContent = date;
        document.getElementById('del-time').textContent = time;
        document.getElementById('del-carecon').textContent = carecon;

      });
    });

    document.querySelectorAll('.edit-btn').forEach(b => {
      b.onclick = () => {
        document.getElementById('edit-id').value = b.dataset.id;
        const c = document.getElementById('edit-class');
        c.value = b.dataset.studentClassId;
        c.dispatchEvent(new Event('change'));
        setTimeout(() => {
          document.getElementById('edit-student').value = b.dataset.studentId;
        }, 0);
        document.getElementById('edit-method').value = b.dataset.methodId;
      };
    });
  </script>

  <?php require_once './../inc/footer.php'; ?>