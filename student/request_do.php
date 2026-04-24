<?php
require_once __DIR__ . "/../inc/function.php";
check_logined_student();
$db = db_connect();
$db->beginTransaction();


header('Content-Type: application/json');

// JSからのJSONデータを受け取る
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

if ($input) {
    $student_id = $_SESSION["user_id"] ?? null;

    if (!$student_id) {
        echo json_encode(["success" => false, "message" => "ログインセッションが切れました"]);
        exit;
    }

    $method_id = $input["method_id"];
    $slot_id = $input["slot_id"];

    try {
        // 予約したい日付を検索する
        $sql_date = "SELECT date FROM reservation_slots WHERE id = :slot_id";
        $stmt_date = $db->prepare($sql_date);
        $stmt_date->execute([":slot_id" => $slot_id]);
        $target_date = $stmt_date->fetchColumn();

        // 予約しようとしている日付と自分の予約している日付をチェックする
        $sql_check = "SELECT COUNT(*) 
        FROM reservation_infos ri
        INNER JOIN reservation_slots rs ON ri.slot_id = rs.id
        WHERE ri.student_id = :student_id
        AND rs.date=:target_date";
        $stmt_check = $db->prepare($sql_check);
        $stmt_check->execute([
            ":student_id" => $student_id,
            ":target_date" => $target_date
        ]);
        $result_date = $stmt_check->fetchColumn();
        // 重複チェック
        if ($result_date > 0) {
            echo json_encode(["success" => false, "message" => "同日の予約はできません"]);
            exit;
        }



        // reserve_infoに予約する。
        $sql_reserve_info = "INSERT INTO `reservation_infos`
        (`slot_id`, `student_id`, `method_id`) VALUES 
        (:slot_id, :student_id, :method_id)";

        $stmt_reserve_info = $db->prepare($sql_reserve_info);
        $stmt_reserve_info->bindParam(":slot_id", $slot_id, PDO::PARAM_INT);
        $stmt_reserve_info->bindParam(":student_id", $student_id, PDO::PARAM_INT);
        $stmt_reserve_info->bindParam(":method_id", $method_id, PDO::PARAM_INT);
        $stmt_reserve_info->execute();

        // reserve_infoに予約した情報と該当するreserve_slotを書き換える
        $sql_reserve_slot = "UPDATE `reservation_slots` SET `reserve_status_id`=2 
        WHERE id=:id";
        $stmt_reserve_slot = $db->prepare($sql_reserve_slot);
        $stmt_reserve_slot->bindParam(":id", $slot_id, PDO::PARAM_INT);


        if ($stmt_reserve_slot->execute()) {
            echo json_encode(["success" => true, "message" => "予約を確定しました！"]);
        }

        $db->commit();
    } catch (PDOException $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        echo json_encode(["success" => false, "message" => "データベースエラーが発生しました"]);
    }
}
