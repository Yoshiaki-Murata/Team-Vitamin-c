<?php
require_once __DIR__ . '/../inc/function.php';
check_logined();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $db = db_connect();

    try {

        // ① 予約が存在するかチェック
        $sql = '
            SELECT COUNT(*) 
            FROM reservation_infos 
            WHERE slot_id = :id
        ';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            // 予約あり → 削除させない
            $_SESSION["err_msg"] = "予約済みのため削除できません";
            header('Location: schedule.php');
            exit;
        }

        // ② 問題なければ削除
        $sql = "DELETE FROM reservation_slots WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();


        if ($stmt->rowCount() === 0) {
            $_SESSION["err_msg"] = "削除できませんでした";
            header('location:schedule.php');
            exit();
        } else {
            $_SESSION["msg"] = "削除完了しました";
        }


        header('Location: schedule.php');
        exit;
    } catch (PDOException $e) {
        session_err_msg("DB更新に失敗しました");
        header('Location:schedule.php');
        exit;
    }
}
