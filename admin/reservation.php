<?php
require_once __DIR__ . '/../inc/function.php';

$db = db_connect();

$reservations = [];
$methods = [];
$students = [];
$classes = [];
$dates = [];
$lines = [];

$line_id = $_GET['line'] ?? '';
$date = $_GET['date'] ?? '';

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

  $sql = 'SELECT 
    reservation_slots.id AS slot_id, 
    reservation_infos.id AS reservation_id, 
    reservation_slots.date, 
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
} catch (PDOException $e) {
  $err_msg = 'データの取得に失敗しました:' . $e->getMessage();
}

require_once './../inc/header_admin.php';
?>

<body>
  <div class="l-wrapper">
    <h1 class="c-title">予約情報一覧</h1>

    <form method="get" class="mb-3 w-50">
      <div class="d-flex gap-2">
        <div class="flex-fill">
          <label>日付</label>
          <select name="date" class="form-select" onchange="this.form.submit()">
            <option value="">全日程</option>
            <?php foreach ($dates as $d): ?>
              <option value="<?php echo h($d['date']) ?>" <?php echo ($d['date'] === $date) ? 'selected' : '' ?>>
                <?php echo h($d['date']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="flex-fill">
          <label>ライン</label>
          <select name="line" class="form-select" onchange="this.form.submit()">
            <option value="">全ライン</option>
            <?php foreach ($lines as $l): ?>
              <option value="<?php echo h($l['id']) ?>" <?php echo ($l['id'] == $line_id) ? 'selected' : '' ?>>
                <?php echo h($l['line']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </form>

    <table class="table">
      <thead>
        <tr>
          <th>ライン</th>
          <th>日付</th>
          <th>時間</th>
          <th>訓練生</th>
          <th>教室</th>
          <th>講師</th>
          <th>方法</th>
          <th>状態</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reservations as $row): ?>
          <tr>
            <td><?php echo h($row['reserve_line']) ?></td>
            <td><?php echo h($row['date']) ?></td>
            <td><?php echo h($row['reserve_time']) ?></td>
            <td><?php echo $row['reserve_student'] ? h($row['reserve_student']) : '-' ?></td>
            <td><?php echo $row['reserve_class'] ?: '未定' ?></td>
            <td><?php echo $row['reserve_consultant'] ?: '未定' ?></td>
            <td><?php echo $row['reserve_method'] ?: '-' ?></td>
            <td><?php echo h($row['reservation_status']) ?></td>
            <td>
              <?php if ($row['reservation_id']): ?>
                <button class="btn btn-primary edit-btn"
                  data-bs-toggle="modal" data-bs-target="#editReserveModal"
                  data-id="<?php echo $row['reservation_id'] ?>"
                  data-student-id="<?php echo $row['student_id'] ?>"
                  data-method-id="<?php echo $row['method_id'] ?>"
                  data-student-class-id="<?php echo $row['student_class_id'] ?>">
                  変更</button>
                <button class="btn btn-danger del-btn"
                  data-bs-toggle="modal" data-bs-target="#delReserveModal"
                  data-id="<?php echo $row['reservation_id'] ?>">
                  削除</button>
              <?php else: ?>
                <button class="btn btn-warning add-btn"
                  data-bs-toggle="modal" data-bs-target="#addReserveModal"
                  data-id="<?php echo $row['slot_id'] ?>">
                  追加</button>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

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
                    <option value="<?php echo $c['id'] ?>"><?php echo $c['name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label>訓練生</label>
                <select id="add-student" name="student_id" class="form-select">
                  <option value="">選択してください</option>
                  <?php foreach ($students as $s): ?>
                    <option value="<?php echo $s['id'] ?>" data-class-id="<?php echo $s['class_id'] ?>">
                      <?php echo $s['name'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label>実施方法</label>
                <select name="method_id" class="form-select">
                  <?php foreach ($methods as $m): ?>
                    <option value="<?php echo $m['id'] ?>"><?php echo $m['name'] ?></option>
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
                    <option value="<?php echo h($class['id']) ?>">
                      <?php echo h($class['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">訓練生</label>
                <select name="student_id" id="edit-student" class="form-select">
                  <option value="">選択してください</option>
                  <?php foreach ($students as $student): ?>
                    <option value="<?php echo h($student['id']) ?>" data-class-id="<?php echo h($student['class_id']) ?>">
                      <?php echo h($student['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">実施方法</label>
                <select name="method_id" id="edit-method" class="form-select">
                  <option value="">選択してください</option>
                  <?php foreach ($methods as $method): ?>
                    <option value="<?php echo h($method['id']) ?>">
                      <?php echo h($method['name']) ?>
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
        s.innerHTML = '<option value="">選択</option>';
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

    document.querySelectorAll('.del-btn').forEach(b => {
      b.onclick = () => {
        document.getElementById('del-id').value = b.dataset.id;
      };
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