<?php
require_once __DIR__ . '/../inc/function.php';

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

        // トップページへ画面遷移
        header('location:masters.php');
        exit();
    } catch (PDOException $e) {
        exit('エラー: ' . $e->getMessage());
    }
}
