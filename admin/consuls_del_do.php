<?php
require_once __DIR__ . '/../inc/function.php';
check_logined();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];


    try {
        $db = db_connect();
        // 削除する講師が予約に入っていたら、エラ‐出して追い返す
        $sql_search="SELECT COUNT(consultant_id) FROM reservation_slots
        WHERE consultant_id=:c_id";
        $stmt_search=$db->prepare($sql_search);
        $stmt_search->execute([
            ":c_id"=>$id
        ]);
        $result=$stmt_search->fetchColumn();

        if($result>0){
            session_err_msg("講師がキャリコン予定の為削除できません");
            header('location:consuls.php');
            exit();
        }

        $sql = "DELETE FROM consultants WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();


        if ($stmt->rowCount() === 0) {
            $_SESSION["err_msg"] = "削除できませんでした";
            header('location:consuls.php');
            exit();
        } else {
            $_SESSION["msg"] = "削除完了しました";
        }


        header('Location: consuls.php');
        exit();
    } catch (PDOException $e) {
        exit('エラー: ' . $e->getMessage());
    }
}
