<?php

require_once __DIR__ . '/../inc/function.php';

$db = db_connect();

$students = [];
$classes = [];
$err_msg = '';

$class_id = $_GET['class_id'] ?? '';

try {
  $sql = 'SELECT students.id,students.name,students.number,students.password,students.admission_date,students.graduation_date,classes.name AS class_name,
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
<div class="l-wrapper">

  <h1 class="c-title">学生一覧</h1>
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
        <th scope="col">入校日</th>
        <th scope="col">終了予定日</th>
        <th scope="col">パスワード</th>
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
          <td><?php echo h($student['admission_date']); ?></td>
          <td><?php echo h($student['graduation_date']); ?></td>
          <td><?php echo h($student['password']); ?></td>
          <td><?php echo h($student['status_name']); ?></td>
          <td><button type="button" class="btn btn-warning">詳細</button></td>
        </tr>
      <?php endforeach; ?>
</div>

</tbody>
</table>