<?php
require_once __DIR__ . '/../inc/function.php';
check_logined();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $slot_id    = $_POST['slot_id'];
  $student_id = $_POST['student_id'];
  $method_id  = $_POST['method_id'];

  $db = db_connect();

  try {
    // 同日チェック
    $sql = '
      SELECT COUNT(*) 
      FROM reservation_infos ri
      INNER JOIN reservation_slots rs ON ri.slot_id = rs.id
      WHERE ri.student_id = :student_id
      AND rs.date = (
        SELECT date FROM reservation_slots WHERE id = :slot_id
      )
    ';

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindValue(':slot_id', $slot_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
      header('Location: reservation.php?error=duplicate');
      exit;
    }

    // INSERT
    $sql = '
      INSERT INTO reservation_infos (slot_id, student_id, method_id)
      VALUES (:slot_id, :student_id, :method_id)
    ';

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':slot_id', $slot_id, PDO::PARAM_INT);
    $stmt->bindValue(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindValue(':method_id', $method_id, PDO::PARAM_INT);
    $stmt->execute();

    // ★ ステータス更新
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

    $sql = '
      UPDATE reservation_slots
      SET reserve_status_id = :status
      WHERE id = :slot_id
    ';

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':status', $status, PDO::PARAM_INT);
    $stmt->bindValue(':slot_id', $slot_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
      $_SESSION["err_msg"] = "追加できませんでした";
      header('location:reservation.php');
      exit();
    } else {
      $_SESSION["msg"] = "追加完了しました";
    }
  } catch (PDOException $e) {
    die('エラー: ' . $e->getMessage());
  }

  header('Location: reservation.php');
  exit;
}
