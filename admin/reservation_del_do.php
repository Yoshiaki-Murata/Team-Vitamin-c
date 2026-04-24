<?php
require_once __DIR__ . '/../inc/function.php';
check_logined();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $id = $_POST['id'] ?? null;

  if (!$id) {
    exit('不正なリクエスト');
  }

  $db = db_connect();

  try {
    $db->beginTransaction();

    //  slot_id取得（これ重要）
    $stmt = $db->prepare("
      SELECT slot_id FROM reservation_infos WHERE id = :id
    ");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $slot = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$slot) {
      throw new Exception('データが存在しません');
    }

    //  削除
    $stmt = $db->prepare("
      DELETE FROM reservation_infos WHERE id = :id
    ");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    //  ステータス更新（空に戻す）
    $stmt = $db->prepare("
      UPDATE reservation_slots
      SET reserve_status_id = 1
      WHERE id = :slot_id
    ");
    $stmt->bindValue(':slot_id', $slot['slot_id'], PDO::PARAM_INT);
    $stmt->execute();

    $db->commit();


    if ($stmt->rowCount() === 0) {
      $_SESSION["err_msg"] = "削除できませんでした";
      header('location:reservation.php?id=' . $_POST["id"]);
      exit();
    } else {
      $_SESSION["msg"] = "削除完了しました";
    }


    header('Location: reservation.php');
    exit;
  } catch (Exception $e) {
    $db->rollBack();
    exit('エラー: ' . $e->getMessage());
  }
}
