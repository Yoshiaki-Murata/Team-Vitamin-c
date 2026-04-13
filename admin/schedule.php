<?php

require_once __DIR__ . '/../inc/function.php';

$db = db_connect();

$slots = [];

$err_mgs = '';

try {
  $sql = 'SELECT reservation_slots.id,reservation_slots.date,times.time AS time_slot, classes.name AS class_name,consultants.name AS consultant_name, carecons.name AS carecon_name, carecon_lines.line AS line_number, reserve_status.name AS reserve_status_name FROM reservation_slots
INNER JOIN times ON reservation_slots.time_id = times.id
INNER JOIN classes ON reservation_slots.class_id = classes.id
INNER JOIN consultants ON reservation_slots.consultant_id = consultants.id
INNER JOIN carecons ON reservation_slots.carecon_id = carecons.id
INNER JOIN carecon_lines ON reservation_slots.lines_id = carecon_lines.id
INNER JOIN reserve_status ON reservation_slots.reserve_status_id = reserve_status.id ORDER BY reservation_slots.date ASC, reservation_slots.lines_id ASC';

  $stmt = $db->prepare($sql);
  $stmt->execute();
  $slots = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $err_msg = 'データの取得に失敗しました:' . $e->getMessage();
};

require_once './../inc/header.php';
?>

<body>
  <div class="l-wrapper">
    <h1 class="c-title">キャリコン枠作成</h1>
    <button type="button" class="btn btn-info mb-3" data-bs-toggle="modal" da-bs-target="#addReserveModal">
      新規キャリコン枠登録
    </button>

    <!-- モーダル -->
    <!-- ここまでモーダル -->

    <table class="table">
      <thead>
        <tr>
          <th scope="col">日付</th>
          <th scope="col">時間</th>
          <th scope="col">ライン</th>
          <th scope="col">教室</th>
          <th scope="col">担当</th>
          <th scope="col">キャリコン種類</th>
          <th scope="col">予約状況</th>
          <th scope="col">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($slots as $slot): ?>
          <tr>
            <td><?php echo $slot['date'] ?></td>
            <td><?php echo $slot['time_slot'] ?></td>
            <td><?php echo $slot['line_number'] ?></td>
            <td><?php echo $slot['class_name'] ?></td>
            <td><?php echo $slot['consultant_name'] ?></td>
            <td><?php echo $slot['carecon_name'] ?></td>
            <td><?php echo $slot['reserve_status_name'] ?></td>
            <td>
              <button type="button" class="btn btn-primary">編集</button>
              <button type="button" class="btn btn-danger">削除</button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</body>

<?php require_once './../inc/footer.php'; ?>