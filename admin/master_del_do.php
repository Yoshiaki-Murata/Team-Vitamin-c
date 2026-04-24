<?php
require_once __DIR__ . '/../inc/function.php';
check_logined();

// TODO: データ受け取り
if (!empty($_POST)) {

    $id = $_POST['id'];

    // DBに接続
    try {
        $db = db_connect();
        // 削除するSQL
        $sql = 'DELETE FROM admins WHERE id=:id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();


        if ($stmt->rowCount() === 0) {
            $_SESSION["err_msg"] = "削除できませんでした";
            header('location:masters.php');
            exit();
        } else {
            $_SESSION["msg"] = "削除完了しました";
        }

        // トップページへ画面遷移
        header('location:masters.php');
        exit();
    } catch (PDOException $e) {
        session_err_msg("DB更新に失敗しました");
        header('location:masters.php');
        exit();
    }
}
