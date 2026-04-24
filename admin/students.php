<?php

require_once __DIR__ . '/../inc/function.php';
check_logined();

$db = db_connect();

$students = [];
$classes = [];
$courses = [];
$statuses = [];
$err_msg = '';

$class_id = $_GET['class_id'] ?? '';
$keyword = $_GET['keyword'] ?? '';

try {
  $sql = 'SELECT 
  students.id,
  students.login_id, 
  students.name,
  students.number,
  students.password,
  students.admission_date,
  students.graduation_date,
  classes.name AS class_name,
  student_status.name AS status_name,
  students.status_id,
  courses.name AS course_name,
  courses.id AS course_id,
students.class_id,

  CASE 
    WHEN students.status_id = 1 THEN 1
    ELSE 0
  END AS is_active

FROM students 
INNER JOIN classes ON students.class_id = classes.id 
INNER JOIN student_status ON students.status_id = student_status.id 
INNER JOIN courses ON students.course_id = courses.id';

  $where = [];
  $params = [];

  if (!empty($class_id)) {
    $where[] = 'students.class_id = :class_id';
    $params[':class_id'] = $class_id;
  }

  if (!empty($keyword)) {
    $where[] = 'students.name LIKE :keyword';
    $params[':keyword'] = '%' . $keyword . '%';
  }

  if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
  }

  $sql .= ' ORDER BY classes.id ASC, students.number ASC';

  $stmt = $db->prepare($sql);

  foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
  }

  $stmt->execute();
  $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // クラス
  $stmt_classes = $db->query('SELECT id, name FROM classes ORDER BY id ASC');
  $classes = $stmt_classes->fetchAll(PDO::FETCH_ASSOC);

  // 訓練コース
  $stmt_course = $db->query('SELECT id,name FROM courses ORDER BY id ASC');
  $courses = $stmt_course->fetchAll(PDO::FETCH_ASSOC);

  // 在籍ステータス
  $stmt_status = $db->query('SELECT id,name FROM student_status ORDER BY id ASC');
  $statuses = $stmt_status->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  if ($err_msg) {
    echo $err_msg;
  }
}


// ===== 予約 =====
$reserve_sql = "SELECT 
students.id,students.name, 
reservation_slots.date, 
times.time, 
carecons.name, 
methods.name AS method_name 
FROM reservation_infos 
JOIN students ON reservation_infos.student_id=students.id 
JOIN reservation_slots ON reservation_infos.slot_id=reservation_slots.id 
JOIN methods ON reservation_infos.method_id=methods.id 
JOIN times ON reservation_slots.time_id=times.id 
JOIN carecons ON reservation_slots.carecon_id=carecons.id
ORDER BY reservation_slots.date ASC";

$reserves = $db->query($reserve_sql)->fetchAll(PDO::FETCH_ASSOC);

$reserve_by_student = [];
foreach ($reserves as $r) {
  $reserve_by_student[$r['id']][] = $r;
}

require_once './../inc/header_admin.php';
?>

<div class="l-wrapper">

  <h1 class="c-title">訓練生一覧</h1>
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

  <button class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#addStudentModal">
    ＋ 新規登録
  </button>

  <!-- 検索 -->
  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
      <input type="text" name="keyword" class="form-control" placeholder="名前検索" value="<?php echo h($keyword); ?>">
    </div>
    <div class="col-md-3">
      <select name="class_id" class="form-select">
        <option value="">全クラス</option>
        <?php foreach ($classes as $c): ?>
          <option value="<?php echo $c['id']; ?>" <?php echo $c['id'] == $class_id ? 'selected' : ''; ?>>
            <?php echo h($c['name']); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2 d-flex gap-1">
      <button class="btn btn-primary w-100">検索</button>
      <a href="./students.php" class="btn btn-secondary w-100">リセット</a>
    </div>
  </form>

  <!-- テーブル -->
  <div class="table-responsive">
    <table class="table table-hover ">
      <thead class="table-light">
        <tr>
          <th>番号</th>
          <th>名前</th>
          <th>コース</th>
          <th>在籍状況</th>
          <th>予約</th>
          <th>詳細</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($students)): ?>
          <tr>
            <td colspan="6" class="text-center text-muted">データがありません</td>
          </tr>
        <?php endif; ?>
        <?php foreach ($students as $s):
          $hasReserve = isset($reserve_by_student[$s['id']]);
        ?>
          <tr style="cursor:pointer;">
            <td><?php echo h($s['class_name'] . $s['number']); ?></td>
            <td><?php echo h($s['name']); ?></td>
            <td><?php echo h($s['course_name']); ?></td>
            <td>
              <?php if ($s['is_active']): ?>
                <span class="badge bg-success"><?php echo h($s['status_name']); ?></span>
              <?php else: ?>
                <span class="badge bg-danger"><?php echo h($s['status_name']); ?></span>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($hasReserve): ?>
                <span class="badge bg-info">あり</span>
              <?php else: ?>
                <span class="badge bg-light text-dark">なし</span>
              <?php endif; ?>
            </td>
            <td>
              <button class="btn btn-sm btn-warning"
                data-bs-toggle="modal"
                data-bs-target="#studentModal"
                data-id="<?php echo h($s['id']); ?>"
                data-name="<?php echo h($s['name']); ?>"
                data-number="<?php echo h($s['number']); ?>"
                data-display-number="<?php echo h($s['class_name'] . $s['number']); ?>"
                data-course-id="<?php echo $s['course_id']; ?>"
                data-course-name="<?php echo h($s['course_name']); ?>"
                data-status-id="<?php echo h($s['status_id']); ?>"
                data-status-name="<?php echo h($s['status_name']); ?>" data-admission="<?php echo h($s['admission_date']); ?>"
                data-graduation="<?php echo h($s['graduation_date']); ?>"
                data-pass="<?php echo h($s['password']); ?>"
                data-login="<?php echo h($s['login_id']); ?>">
                詳細
              </button>
            </td>
            <td>
              <button type="button"
                class="btn btn-primary btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#editStudentModal"
                data-id="<?php echo h($s['id']); ?>"
                data-name="<?php echo h($s['name']); ?>"
                data-number="<?php echo h($s['number']); ?>"
                data-display-number="<?php echo h($s['class_name'] . $s['number']); ?>"
                data-course-id="<?php echo h($s['course_id']); ?>"
                data-course-name="<?php echo h($s['course_name']); ?>"
                data-status-id="<?php echo h($s['status_id']); ?>"
                data-status-name="<?php echo h($s['status_name']); ?>" data-admission="<?php echo h($s['admission_date']); ?>"
                data-graduation="<?php echo h($s['graduation_date']); ?>"
                data-pass="<?php echo h($s['password']); ?>"
                data-login="<?php echo h($s['login_id']); ?>"
                data-class="<?php echo h($s['class_id']); ?>" data-status="<?php echo h($s['status_id']); ?>">
                編集
              </button>
              <button type="button"
                class="btn btn-danger btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#delStudentModal"
                data-id="<?php echo h($s['id']); ?>"
                data-name="<?php echo h($s['name']); ?>"
                data-number="<?php echo h($s['class_name'] . $s['number']); ?>"
                data-course-id="<?php echo h($s['course_id']); ?>"
                data-course_name="<?php echo h($s['course_name']); ?>">
                削除
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- 新規作成モーダル -->
  <div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">訓練生新規登録</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="./student_add_do.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="fw-bold">教室</label>
              <select name="class_id" id="class_id" class="form-control form-control-sm mb-3" aria-label="Small select example" required>
                <option value="" class="text-secondary">教室</option>
                <?php foreach ($classes as  $class): ?>
                  <option value="<?php echo h($class["id"]); ?>">
                    <?php echo h($class["name"]); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="fw-bold">番号</label>
              <input type="text" name="number" class="form-control mb-3" placeholder="半角数字2桁 例：01" required>
            </div>
            <div class="form-group">
              <label class="fw-bold">名前</label>
              <input type="text" name="name" class="form-control mb-3" maxlength="255" placeholder="リカレント太郎" required>
            </div>
            <div class="form-group">
              <label class="fw-bold">訓練種別</label>
              <select name="course_id" id="course_id" class="form-control form-control-sm mb-3" aria-label="Small select example" required>
                <option value="" class="text-secondary">種別を選択</option>
                <?php foreach ($courses as  $course): ?>
                  <option value="<?php echo h($course["id"]); ?>">
                    <?php echo h($course["name"]); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="fw-bold">入校日</label>
              <input type="date" name="admission_date" class="form-control mb-3" required>
            </div>
            <div class="form-group">
              <label class="fw-bold">終了予定日</label>
              <input type="date" name="graduation_date" class="form-control mb-3" required>
            </div>
            <div class="form-group">
              <label class="fw-bold">ログインID</label>
              <p>入校年(例：2026)＋入校月(例：5月→05)＋教室名(例：6a)＋出席番号(例：01)</p>
              <input type="text" name="login_id" class="form-control mb-3" maxlength="255" placeholder="2026056a01" required>
            </div>
            <div class="form-group">
              <label class="fw-bold">パスワード</label>
              <input type="text" name="password" class="form-control mb-3" maxlength="8" placeholder="数字8桁" required>
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
  <!-- ここまで -->

  <!-- 詳細モーダル -->
  <div class="modal fade" id="studentModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">訓練生詳細</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <h6 class="text-muted">●基本情報</h6>
          <p>番号：<span id="modal-number"></span></p>
          <p>名前：<span id="modal-name"></span></p>
          <p>コース：<span id="modal-course"></span></p>
          <p>在籍状況：<span id="modal-status"></span></p>

          <h6 class="text-muted">●期間</h6>
          <p>入校：<span id="modal-admission"></span></p>
          <p>修了：<span id="modal-graduation"></span></p>

          <h6 class="text-muted">●アカウント</h6>
          <p>ID：<span id="modal-login"></span></p>
          <p>PASS：<span id="modal-pass"></span></p>

          <h6 class="text-muted">●予約</h6>
          <div id="modal-reserve"></div>
        </div>
      </div>
    </div>
  </div>
  <!-- ここまで -->

  <!-- 編集モーダル -->
  <div class="modal fade" id="editStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">訓練生編集</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="./student_edit_do.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="fw-bold">教室</label>
              <select name="class_id" id="class_id" class="form-control form-control-sm mb-3" aria-label="Small select example" required>
                <option value="" class="text-secondary">教室</option>
                <?php foreach ($classes as  $class): ?>
                  <option value="<?php echo h($class["id"]); ?>">
                    <?php echo h($class["name"]); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="fw-bold">番号</label>
              <input type="text" name="number" class="form-control mb-3" placeholder="半角数字2桁 例：01" required>
            </div>
            <div class="form-group">
              <label class="fw-bold">名前</label>
              <input type="text" name="name" class="form-control mb-3" maxlength="255" placeholder="リカレント太郎" required>
            </div>
            <div class="form-group">
              <label class="fw-bold">訓練種別</label>
              <select name="course_id" id="course_id" class="form-control form-control-sm mb-3" aria-label="Small select example" required>
                <option value="" class="text-secondary">種別を選択</option>
                <?php foreach ($courses as  $course): ?>
                  <option value="<?php echo h($course["id"]); ?>">
                    <?php echo h($course["name"]); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="fw-bold">入校日</label>
              <input type="date" name="admission_date" class="form-control mb-3" required>
            </div>
            <div class="form-group">
              <label class="fw-bold">終了予定日</label>
              <input type="date" name="graduation_date" class="form-control mb-3" required>
            </div>
            <div class="form-group">
              <label class="fw-bold">ログインID</label>
              <p>入校年(例：2026)＋入校月(例：5月→05)＋教室名(例：6a)＋出席番号(例：01)</p>
              <input type="text" name="login_id" class="form-control mb-3" maxlength="255" placeholder="2026056a01" required>
            </div>
            <div class="form-group">
              <label class="fw-bold">パスワード</label>
              <input type="text" name="password" class="form-control mb-3" maxlength="8" placeholder="数字8桁" required>
            </div>
            <div class="form-group">
              <label class="fw-bold">在籍状況</label>
              <select name="status_id" id="status_id" class="form-control form-control-sm mb-3" aria-label="Small select example" required>
                <?php foreach ($statuses as  $status): ?>
                  <option value="<?php echo h($status["id"]); ?>"
                    <?php if ($status["id"] == 1) echo 'selected'; ?>>
                    <?php echo h($status["name"]); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="id">
            <input type="submit" value="更新" class="btn btn-primary">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- ここまで -->

  <!-- 削除モーダル -->
  <div class="modal fade" id="delStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">削除確認</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="./student_del_do.php" method="post">
          <div class="modal-body">
            <dl class="row">
              <dt class="col-sm-3">番号</dt>
              <dd class="col-sm-9" id="del-number"></dd>
              <dt class="col-sm-3">訓練生名</dt>
              <dd class="col-sm-9" id="del-name"></dd>
              <dt class="col-sm-3">訓練コース</dt>
              <dd class="col-sm-9" id="del-course"></dd>
            </dl>

            <p>この訓練生を削除しますか?</p>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="id" id="del-id">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">戻る</button>
            <button type="submit" class="btn btn-danger">削除</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- ここまで -->

</div>


<script>
  const reserveData = <?php echo json_encode($reserve_by_student) ?>;

  // ===== 詳細モーダル =====
  const studentModal = document.getElementById('studentModal');

  studentModal.addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;

    document.getElementById('modal-number').textContent = btn.dataset.displayNumber;
    document.getElementById('modal-name').textContent = btn.dataset.name;
    document.getElementById('modal-course').textContent = btn.dataset.courseName;
    document.getElementById('modal-status').textContent = btn.dataset.statusName;
    document.getElementById('modal-admission').textContent = btn.dataset.admission;
    document.getElementById('modal-graduation').textContent = btn.dataset.graduation;
    document.getElementById('modal-login').textContent = btn.dataset.login;
    document.getElementById('modal-pass').textContent = btn.dataset.pass;

    const list = reserveData[btn.dataset.id];

    let html = '';

    if (list) {
      list.forEach(r => {
        html += `
        <div class="card p-2 mb-2">
          <div>${r.date} ${r.time}</div>
          <div>${r.name}</div>
          <div>${r.method_name}</div>
        </div>`;
      });
    } else {
      html = '予約なし';
    }

    document.getElementById('modal-reserve').innerHTML = html;
  });


  // ===== 編集モーダル =====
  const editModal = document.getElementById('editStudentModal');

  editModal.addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    const form = editModal.querySelector('form');

    // 各フィールドセット
    form.querySelector('[name="class_id"]').value = btn.dataset.class;
    form.querySelector('[name="number"]').value = btn.dataset.number;
    form.querySelector('[name="name"]').value = btn.dataset.name;
    form.querySelector('[name="course_id"]').value = btn.dataset.courseId;
    form.querySelector('[name="status_id"]').value = btn.dataset.statusId;
    form.querySelector('[name="admission_date"]').value = btn.dataset.admission;
    form.querySelector('[name="graduation_date"]').value = btn.dataset.graduation;
    form.querySelector('[name="login_id"]').value = btn.dataset.login;
    form.querySelector('[name="password"]').value = btn.dataset.pass;

    // hidden id
    let hidden = form.querySelector('[name="id"]');
    if (!hidden) {
      hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = 'id';
      form.appendChild(hidden);
    }
    hidden.value = btn.dataset.id;
  });

  // ===== 削除モーダル =====
  const delModal = document.getElementById('delStudentModal');

  delModal.addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;

    // 表示
    document.getElementById('del-number').textContent = btn.dataset.number;
    document.getElementById('del-name').textContent = btn.dataset.name;
    document.getElementById('del-course').textContent = btn.dataset.course_name;

    // hidden id
    document.getElementById('del-id').value = btn.dataset.id;
  });
</script>

<?php require_once './../inc/footer.php'; ?>