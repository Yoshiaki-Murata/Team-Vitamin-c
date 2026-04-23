<?php
require_once __DIR__ . '/../inc/function.php';
check_logined();

// TODO: データ受け取り
if (!empty($_POST)) {
    // POST送信されたとき
    if (!empty($_POST['name']) && !empty($_POST['login_id']) && !empty($_POST['password'])) {
        $name = $_POST['name'];
        $login_id = $_POST['login_id'];
        $password = $_POST['password'];
        $id = $_POST['id'];
    }
    // DBに接続
    try {
        $db = db_connect();
        // テーブルに挿入するSQL
        $sql = 'UPDATE admins SET name=:name,password=:password,login_id=:login_id WHERE id=:id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':login_id', $login_id, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_INT);
        $stmt->execute();


        if ($stmt->rowCount() === 0) {
            $_SESSION["err_msg"] = "編集できませんでした";
            header('location:masters.php');
            exit();
        } else {
            $_SESSION["msg"] = "編集完了しました";
        }


        // トップページへ画面遷移
        header('location:masters.php');
        exit();
    } catch (PDOException $e) {
        exit('エラー: ' . $e->getMessage());
    }
}
