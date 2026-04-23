<?php
require_once __DIR__ . '/../inc/function.php';

$db = db_connect();

// POSTチェック
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: student.php');
    exit;
}

// 値取得
$id = $_POST['id'] ?? '';
$date = $_POST['date'] ?? '';
$time_id = $_POST['time_id'] ?? '';
$lines_id = $_POST['lines_id'] ?? '';
$class_id = !empty($_POST['class_id']) ? $_POST['class_id'] : null;
$consultant_id = !empty($_POST['consultant_id']) ? $_POST['consultant_id'] : null;
$carecon_id = $_POST['carecon_id'] ?? '';
$status_id = $_POST['reserve_status_id'] ?? '';

try {

    //  重複チェック（自分以外）
    $sql = '
        SELECT COUNT(*) 
        FROM reservation_slots
        WHERE date = :date
        AND time_id = :time_id
        AND lines_id = :lines_id
        AND id != :id
    ';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':date', $date, PDO::PARAM_STR);
    $stmt->bindValue(':time_id', $time_id, PDO::PARAM_INT);
    $stmt->bindValue(':lines_id', $lines_id, PDO::PARAM_INT);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
        // 重複あり
        exit('この日時・ラインはすでに登録されています');
    }

    //  UPDATE
    $sql = '
        UPDATE reservation_slots 
        SET 
            date = :date,
            time_id = :time_id,
            lines_id = :lines_id,
            class_id = :class_id,
            consultant_id = :consultant_id,
            carecon_id = :carecon_id,
            reserve_status_id = :reserve_status_id
        WHERE id = :id
    ';

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':date', $date, PDO::PARAM_STR);
    $stmt->bindValue(':time_id', $time_id, PDO::PARAM_INT);
    $stmt->bindValue(':lines_id', $lines_id, PDO::PARAM_INT);
    $stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
    $stmt->bindValue(':consultant_id', $consultant_id, PDO::PARAM_INT);
    $stmt->bindValue(':carecon_id', $carecon_id, PDO::PARAM_INT);
    $stmt->bindValue(':reserve_status_id', $status_id, PDO::PARAM_INT);

    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        $_SESSION["err_msg"] = "編集できませんでした";
        header('location:schedule.php');
        exit();
    } else {
        $_SESSION["msg"] = "編集完了しました";
    }
    header('Location:schedule.php');
    exit;
} catch (PDOException $e) {
    echo '更新失敗: ' . $e->getMessage();
}
