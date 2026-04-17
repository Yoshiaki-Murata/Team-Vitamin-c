<?php
require_once __DIR__ . '/../inc/function.php';

// TODO: データ受け取り
if (!empty($_POST)) {
    // POST送信されたとき
    if (!empty($_POST['name']) && !empty($_POST['login_id']) && !empty($_POST['password'])) {
        $name = $_POST['name'];
        $login_id = $_POST['login_id'];
        $password = $_POST['password'];

        // DBに接続
        try {
            $db = db_connect();
            // テーブルに挿入するSQL
            $sql = 'INSERT INTO admins (name,password,login_id) VALUES (:name,:password,:login_id)';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':login_id', $login_id, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_INT);
            $stmt->execute();

            // トップページへ画面遷移
            header('location:masters.php');
            exit();
        } catch (PDOException $e) {
            exit('エラー: ' . $e->getMessage());
        }
    }
}
