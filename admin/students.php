<?php

require_once __DIR__ . '/../inc/function.php';

$db = db_connect();

$students = [];
$classes = [];
$err_msg = '';

$class_id = $_GET['class_id'] ?? '';

try {
  $sql = 'SELECT students.id,students.login_id, students.name,students.number,students.password,students.admission_date,students.graduation_date,classes.name AS class_name,
student_status.name AS status_name,courses.name AS course_name FROM students INNER JOIN classes ON students.class_id = classes.id INNER JOIN student_status ON students.status_id = student_status.id INNER JOIN courses ON students.course_id = courses.id';

  if (!empty($class_id)) {
    $sql .= ' WHERE students.class_id = :class_id';
  }

  $sql .= ' ORDER BY classes.id ASC, students.number ASC';

  $stmt = $db->prepare($sql);

  if (!empty($class_id)) {
    $stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
  }

  $stmt->execute();
  $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $sql_classes = 'SELECT id, name FROM classes ORDER BY id ASC';
  $stmt_classes = $db->prepare($sql_classes);
  $stmt_classes->execute();

  $classes = $stmt_classes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $err_msg = 'データの取得に失敗しました:' . $e->getMessage();
};

require_once './../inc/header.php';
?>

<body>
  <div class="l-wrapper">

    <h1 class="c-title">訓練生一覧</h1>
    <button type="button" class="btn btn-info mb-3" onclick="location.href='student_add.php'">
      新規訓練生登録
    </button>
    <form method="GET" class="mb-3 w-25">
      <label class="form-label">クラス選択</label>
      <select name="class_id" class="form-select" onchange="this.form.submit()">
        <option value="">全クラス</option>
        <?php foreach ($classes as $class_select): ?>
          <option value="<?php echo $class_select['id']; ?>"
            <?php if ($class_select['id'] == $class_id) echo 'selected'; ?>>
            <?php echo h($class_select['name']); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </form>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">出席番号</th>
          <th scope="col">名前</th>
          <th scope="col">訓練種別</th>
          <th scope="col">在籍状況</th>
          <th scope="col">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($students as $student): ?>
          <tr>
            <th scope="row"><?php echo h($student['class_name'] . $student['number']); ?></th>
            <td><?php echo h($student['name']); ?></td>
            <td><?php echo h($student['course_name']); ?></td>
            <td><?php echo h($student['status_name']); ?></td>
            <td>
              <button
                type="button" class="btn btn-warning"
                data-bs-toggle="modal"
                data-bs-target="#studentModal"
                data-id="<?php echo $student['id']; ?>"
                data-name="<?php echo h($student['name']); ?>"
                data-number="<?php echo h($student['class_name'] . $student['number']); ?>"
                data-course="<?php echo h($student['course_name']); ?>"
                data-admission="<?php echo h($student['admission_date']); ?>"
                data-graduation="<?php echo h($student['graduation_date']); ?>"
                data-pass="<?php echo h($student['password']); ?>"
                data-status="<?php echo h($student['status_name']); ?>"
                data-login="<?php echo h($student['login_id']); ?>">
                詳細
              </button>

            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="modal fade" id="studentModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">訓練生詳細</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">

            <!-- 見やすくしたリスト -->
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between">
                <span class="fw-bold">出席番号</span>
                <span id="modal-number"></span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span class="fw-bold">名前</span>
                <span id="modal-name"></span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span class="fw-bold">訓練種別</span>
                <span id="modal-course"></span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span class="fw-bold">入校日</span>
                <span id="modal-admission"></span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span class="fw-bold">終了予定日</span>
                <span id="modal-graduation"></span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span class="fw-bold">ログインID</span>
                <span id="modal-login"></span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span class="fw-bold">パスワード</span>
                <span id="modal-pass"></span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span class="fw-bold">在籍状況</span>
                <span id="modal-status"></span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span class="fw-bold">キャリコン予約状況</span>
                <span id=""></span>
              </li>
            </ul>

            <!-- ボタンエリア -->
            <div class="d-flex justify-content-center gap-3 mt-4">
              <a href="#" id="modal-edit-btn" class="btn btn-primary">
                登録内容修正
              </a>
              <a href="#" id="modal-delete-btn" class="btn btn-danger">
                削除
              </a>
            </div>

          </div>

        </div>
      </div>
    </div>
  </div>
</body>
<script>
  const modal = document.getElementById('studentModal');

  modal.addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;

    document.getElementById('modal-number').textContent = button.dataset.number;
    document.getElementById('modal-name').textContent = button.dataset.name;
    document.getElementById('modal-course').textContent = button.dataset.course;
    document.getElementById('modal-admission').textContent = button.dataset.admission;
    document.getElementById('modal-graduation').textContent = button.dataset.graduation;
    document.getElementById('modal-pass').textContent = button.dataset.pass;
    document.getElementById('modal-status').textContent = button.dataset.status;
    document.getElementById('modal-login').textContent = button.dataset.login;

    const deleteBtn = document.getElementById('modal-delete-btn');
    deleteBtn.href = 'student_del.php?id=' + button.dataset.id;

    const editBtn = document.getElementById('modal-edit-btn');
    editBtn.href = 'student_edit.php?id=' + button.dataset.id;
  });
</script>

<?php require_once './../inc/footer.php'; ?>