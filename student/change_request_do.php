<?php
require_once __DIR__ . './../inc/function.php';

// TODO: データ受け取り
if (!empty($_POST)) {
    // POST送信されたとき
    if (!empty($_POST['date']) && !empty($_POST['time']) && !empty($_POST['method'])) {
        // TODO: 必須項目チェック（空の場合）
        $date = $_POST['date'];
        $time = $_POST['time'];
        $method = $_POST['method'];

        // DBに接続
        try {
            $db = db_connect();
            // 一致するスロットを探す
            $sql = 'SELECT date,time_id FROM reservation_slots WHERE date=:date AND time_id=:time';


            // テーブルに挿入するSQL
            $sql2 = 'INSERT INTO reservation_infos (date,time,method) VALUES (:date,:time,:method)';
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt2->bindParam(':time', $time, PDO::PARAM_STR);
            $stmt2->bindParam(':method', $method, PDO::PARAM_STR);
            $stmt2->execute();

            // トップページへ画面遷移
            header('location:index.php');
            exit();
        } catch (PDOException $e) {
            exit('エラー: ' . $e->getMessage());
        }
    }
}
