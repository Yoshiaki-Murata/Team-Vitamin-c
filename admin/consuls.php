<?php

require_once __DIR__ . '/../inc/function.php';

$db = db_connect();
require_once './../inc/header.php';
?>

<body>
  <div class="l-wrapper">

    <h1 class="c-title">キャリアコンサルタント一覧</h1>
    <button type="button" class="btn btn-info mb-3" onclick="location.href='consuls_add.php'">
      新規consultant登録
    </button>
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
                data-status="<?php echo h($student['status_name']); ?>">
                詳細
              </button>

            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </div>
</body>


<?php require_once './../inc/footer.php'; ?>