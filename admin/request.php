<?php

require_once __DIR__ . '/../inc/function.php';

$db = db_connect();

$requests = [];

try {
  $sql = 'SELECT 
apply_lists.id,
apply_lists.apply_detail,
reservation_slots.date,
reservation_slots.carecon_id,
apply_status.name AS apply_status_name,
students.name AS student_name,
methods.name AS method_name,
times.time AS reserve_time,
classes.name AS reserve_class,
consultants.name AS reserve_consultant,
carecons.name AS reserve_carecon,
carecon_lines.line AS reserve_line
FROM apply_lists 
INNER JOIN apply_status ON apply_lists.apply_status_id= apply_status.id
INNER JOIN reservation_infos ON apply_lists.reserve_info_id = reservation_infos.id
INNER JOIN students ON reservation_infos.student_id = students.id
INNER JOIN methods ON reservation_infos.method_id = methods.id
INNER JOIN reservation_slots ON reservation_infos.slot_id = reservation_slots.id
INNER JOIN times ON reservation_slots.time_id = times.id
INNER JOIN classes ON reservation_slots.class_id = classes.id
INNER JOIN consultants ON reservation_slots.consultant_id = consultants.id
INNER JOIN carecons ON reservation_slots.carecon_id = carecons.id
INNER JOIN carecon_lines ON reservation_slots.lines_id = carecon_lines.id
ORDER BY
reservation_slots.date ASC,
reservation_slots.lines_id ASC,
reservation_slots.time_id ASC';

  $stmt = $db->prepare($sql);
  $stmt->execute();
  $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $err_msg = 'データの取得に失敗しました:' . $e->getMessage();
};

// check_array($requests);

require_once './../inc/header.php';
?>


<body>
  <div class="l-wrapper">
    <h1 class="c-title">予約変更・キャンセル申請一覧</h1>
    <h2>キャリコン変更申請一覧</h2>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">日付</th>
          <th scope="col">時間</th>
          <th scope="col">ライン</th>
          <th scope="col">訓練生名</th>
          <th scope="col">教室</th>
          <th scope="col">担当講師</th>
          <th scope="col">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($requests as $request):
          if ($request['carecon_id'] == 1): ?>
            <tr>
              <td><?php echo h($request['date']); ?></td>
              <td><?php echo h($request['reserve_time']); ?></td>
              <td><?php echo h($request['reserve_line']); ?></td>
              <td><?php echo h($request['student_name']); ?></td>
              <td><?php echo h($request['reserve_class']); ?></td>
              <td><?php echo h($request['reserve_consultant']); ?></td>
              <td>
                <button type="button" class="btn btn-primary">更新</button>
                <button type="button" class="btn btn-danger">削除</button>
              </td>
            </tr>
        <?php endif;
        endforeach; ?>
      </tbody>
    </table>

    <h2>キャリコンプラスキャンセル申請一覧</h2>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">日付</th>
          <th scope="col">時間</th>
          <th scope="col">ライン</th>
          <th scope="col">訓練生名</th>
          <th scope="col">教室</th>
          <th scope="col">担当講師</th>
          <th scope="col">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($requests as $request):
          if ($request['carecon_id'] == 2): ?>
            <tr>
              <td><?php echo h($request['date']); ?></td>
              <td><?php echo h($request['reserve_time']); ?></td>
              <td><?php echo h($request['reserve_line']); ?></td>
              <td><?php echo h($request['student_name']); ?></td>
              <td><?php echo h($request['reserve_class']); ?></td>
              <td><?php echo h($request['reserve_consultant']); ?></td>
              <td>
                <button type="button" class="btn btn-primary">更新</button>
                <button type="button" class="btn btn-danger">削除</button>
              </td>
            </tr>
        <?php endif;
        endforeach; ?>
      </tbody>
    </table>
  </div>
</body>

<?php require_once './../inc/footer.php'; ?>