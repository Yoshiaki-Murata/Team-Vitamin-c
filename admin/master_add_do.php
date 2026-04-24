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

        // ログインID 数字とアルファベット 4字以上10字以下
        if (!preg_match('/^[0-9a-z]{4,10}$/', $login_id)) {
            header('masters.php');
            exit('ID登録不可 数字とアルファベット 4字以上10字以下');
        }



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

            if ($stmt->rowCount() === 0) {
                $_SESSION["err_msg"] = "追加できませんでした";
                header('location:masters.php');
                exit();
            } else {
                $_SESSION["msg"] = "追加完了しました";
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
}
