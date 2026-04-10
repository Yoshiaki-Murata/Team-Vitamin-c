<?php
require_once __DIR__ . '/../inc/function.php';

$db = db_connect();

$classes = [];
$courses = [];
$statuses = [];

try {
  $sql_class = 'SELECT * FROM classes ORDER BY id ASC';
  $stmt_class = $db->prepare($sql_class);
  $stmt_class->execute();
  $classes = $stmt_class->fetchAll(PDO::FETCH_ASSOC);

  $sql_course = 'SELECT * FROM courses ORDER BY id ASC';
  $stmt_course = $db->prepare($sql_course);
  $stmt_course->execute();
  $courses = $stmt_course->fetchAll(PDO::FETCH_ASSOC);

  $sql_status = 'SELECT * FROM student_status ORDER BY id ASC';
  $stmt_status = $db->prepare($sql_status);
  $stmt_status->execute();
  $statuses = $stmt_status->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $err_msg = 'データの取得に失敗しました:' . $e->getMessage();
}

// check_array($statuses);

require_once './../inc/header.php';
?>

<body>
  <div class="l-wrapper">

    <h1 class="c-title">訓練生新規登録</h1>
    <form action="./student_add_do.php" method="post">
      <div class="form-group">
        <label class="mb-3 fw-bold">教室</label>
        <select name="class_id" id="class_id" class="form-control form-control-sm mb-5" aria-label="Small select example" required>
          <option value="" class="text-secondary">教室</option>
          <?php foreach ($classes as  $class): ?>
            <option value="<?php echo h($class["id"]); ?>">
              <?php echo h($class["name"]); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label class="mb-3 fw-bold">番号</label>
        <input type="text" name="number" class="form-control mb-5" placeholder="00" required>
      </div>
      <div class="form-group">
        <label class="mb-3 fw-bold">名前</label>
        <input type="text" name="name" class="form-control mb-5" maxlength="255" placeholder="リカレント太郎" required>
      </div>
      <div class="form-group">
        <label class="mb-3 fw-bold">訓練種別</label>
        <select name="course_id" id="course_id" class="form-control form-control-sm mb-5" aria-label="Small select example" required>
          <option value="" class="text-secondary">種別を選択</option>
          <?php foreach ($courses as  $course): ?>
            <option value="<?php echo h($course["id"]); ?>">
              <?php echo h($course["name"]); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label class="mb-3 fw-bold">入校日</label>
        <input type="date" name="admission-date" class="form-control mb-5" required>
      </div>
      <div class="form-group">
        <label class="mb-3 fw-bold">終了予定日</label>
        <input type="date" name="graduation-date" class="form-control mb-5" required>
      </div>
      <div class="form-group">
        <label class="mb-3 fw-bold">ログインID</label>
        <input type="text" name="login-id" class="form-control mb-5" maxlength="255" placeholder="年月教室番号2026026a00" required>
      </div>
      <div class="form-group">
        <label class="mb-3 fw-bold">パスワード</label>
        <input type="text" name="password" class="form-control mb-5" maxlength="8" placeholder="数字8桁" required>
      </div>
      <div class="form-group">
        <label class="mb-3 fw-bold">在籍状況</label>
        <select name="status_id" id="status_id" class="form-control form-control-sm mb-5" aria-label="Small select example" required>
          <option value="" class="text-secondary">在籍状況</option>
          <?php foreach ($statuses as  $status): ?>
            <option value="<?php echo h($status["id"]); ?>">
              <?php echo h($status["name"]); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-5 text-center">
        <input type="submit" value="登録" class="btn btn-primary">
      </div>


    </form>
  </div>
</body>

<?php
require_once './../inc/footer.php';
?>