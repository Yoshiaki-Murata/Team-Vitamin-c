<?php
require_once __DIR__ . '/../inc/function.php';

$db = db_connect();

// id取得
$id = $_GET['id'] ?? '';

// 不正チェック
if ($id === '' || !is_numeric($id)) {
  exit('不正なアクセスです');
}

try {
  // 対象データ取得（JOINして表示用に）
  $sql = "SELECT students.id,students.number,students.name,students.admission_date,students.graduation_date,classes.name AS class_name,courses.name AS course_name,student_status.name AS status_name FROM students
    INNER JOIN classes ON students.class_id = classes.id
    INNER JOIN courses ON students.course_id = courses.id
    INNER JOIN student_status ON students.status_id = student_status.id
    WHERE students.id = :id";

  $stmt = $db->prepare($sql);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();

  $student = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$student) {
    exit('データが見つかりません');
  }
} catch (PDOException $e) {
  exit('データ取得失敗: ' . $e->getMessage());
}

require_once './../inc/header.php';
?>

<body>
  <div class="l-wrapper">

    <h1 class="c-title">削除 確認画面</h1>

    <table class="table">
      <thead>
        <tr>
          <th>出席番号</th>
          <th>名前</th>
          <th>訓練種別</th>
          <th>入校日</th>
          <th>終了予定日</th>
          <th>在籍状況</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo h($student['class_name'] . $student['number']); ?></td>
          <td><?php echo h($student['name']); ?></td>
          <td><?php echo h($student['course_name']); ?></td>
          <td><?php echo h($student['admission_date']); ?></td>
          <td><?php echo h($student['graduation_date']); ?></td>
          <td><?php echo h($student['status_name']); ?></td>
        </tr>
      </tbody>
    </table>

    <p>本当に削除しますか？</p>

    <div class="text-center mt-4">
      <form action="student_del_do.php" method="post" style="display:inline;">
        <input type="hidden" name="id" value="<?php echo h($student['id']); ?>">
        <button type="submit" class="btn btn-danger">
          削除する
        </button>
      </form>

      <a href="students.php" class="btn btn-secondary">
        戻る
      </a>
    </div>

  </div>
</body>

<?php require_once './../inc/footer.php'; ?>