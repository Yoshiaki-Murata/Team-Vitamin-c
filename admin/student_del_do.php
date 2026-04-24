<?php
require_once __DIR__ . '/../inc/function.php';
check_logined();

$db = db_connect();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: students.php');
    exit;
}

$id = $_POST['id'] ?? '';

if ($id === '' || !is_numeric($id)) {
    exit('不正なアクセスです');
}

try {

    //  影響するslotを先に取得
    $sql = '
        SELECT DISTINCT slot_id 
        FROM reservation_infos 
        WHERE student_id = :student_id
    ';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':student_id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $slots = $stmt->fetchAll(PDO::FETCH_COLUMN);

    //  reservation_infos も削除（←これ重要）
    $sql = 'DELETE FROM reservation_infos WHERE student_id = :student_id';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':student_id', $id, PDO::PARAM_INT);
    $stmt->execute();

    //  学生削除
    $sql = "DELETE FROM students WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    //  slotごとにstatus再計算
    foreach ($slots as $slot_id) {

        $sql = '
            SELECT COUNT(*) 
            FROM reservation_infos 
            WHERE slot_id = :slot_id
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
    }


    if ($stmt->rowCount() === 0) {
        $_SESSION["err_msg"] = "削除できませんでした";
        header('location:students.php?id=' . $_POST["id"]);
        exit();
    } else {
        $_SESSION["msg"] = "削除完了しました";
    }


    // 一覧へ戻る
    header('Location: students.php');
    exit;
} catch (PDOException $e) {
    session_err_msg("DB更新に失敗しました");
    header('Location: students.php');
    exit;
}
