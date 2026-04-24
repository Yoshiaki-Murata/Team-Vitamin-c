<?php
require_once __DIR__ . '/../inc/function.php';
check_logined();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $id         = $_POST['id'];
  $student_id = $_POST['student_id'];
  $method_id  = $_POST['method_id'];

  $db = db_connect();

  try {
    // slot_id を取得
    $sql = 'SELECT slot_id FROM reservation_infos WHERE id = :id';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $slot = $stmt->fetch(PDO::FETCH_ASSOC);

    $slot_id = $slot['slot_id'];

    // 同日チェック（自分除外）
    $sql = '
      SELECT COUNT(*) 
      FROM reservation_infos ri
      INNER JOIN reservation_slots rs ON ri.slot_id = rs.id
      WHERE ri.student_id = :student_id
      AND rs.date = (
        SELECT date FROM reservation_slots WHERE id = :slot_id
      )
      AND ri.id != :id
    ';

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindValue(':slot_id', $slot_id, PDO::PARAM_INT);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
      header('Location: reservation.php?error=duplicate');
      exit;
    }

    // UPDATE
    $sql = '
      UPDATE reservation_infos
      SET student_id = :student_id,
          method_id  = :method_id
      WHERE id = :id
    ';

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindValue(':method_id', $method_id, PDO::PARAM_INT);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    //  ステータス再判定
    $sql = '
      SELECT COUNT(*) 
      FROM reservation_infos
      WHERE slot_id = :slot_id
      AND student_id IS NOT NULL
      AND method_id IS NOT NULL
    ';

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':slot_id', $slot_id, PDO::PARAM_INT);
    $stmt->execute();

    $status = ($stmt->fetchColumn() > 0) ? 2 : 1;

    // ステータス更新
    $sql = '
      UPDATE reservation_slots
      SET reserve_status_id = :status
      WHERE id = :slot_id
    ';

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':status', $status, PDO::PARAM_INT);
    $stmt->bindValue(':slot_id', $slot_id, PDO::PARAM_INT);
    $stmt->execute();


    if ($stmt->execute()) {

      $_SESSION["msg"] = "編集完了しました";
    } else {
      $_SESSION["err_msg"] = "編集できませんでした";
      exit();
    }
  } catch (PDOException $e) {
    die('エラー: ' . $e->getMessage());
  }

  header('Location: reservation.php');
  exit;
}
