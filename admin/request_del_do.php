<?php
require_once __DIR__ . '/../inc/function.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];


    try {
        $db = db_connect();

        $sql = "DELETE FROM apply_lists WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();


        if ($stmt->rowCount() === 0) {
            $_SESSION["err_msg"] = "削除できませんでした";
            header('location:masters.php?id=' . $_POST["id"]);
            exit();
        } else {
            $_SESSION["msg"] = "削除完了しました";
        }


        header('Location: request.php');
        exit();
    } catch (PDOException $e) {
        exit('エラー: ' . $e->getMessage());
    }
}
