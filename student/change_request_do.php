<?php

require_once __DIR__ . '/../inc/function.php';

// TODO: データ受け取り
if (!empty($_POST)) {
    // POST送信されたとき
    if (!empty($_POST['text']) && !empty($_POST['reserve_id'])) {
        // TODO: 必須項目チェック（空の場合）
        $text = $_POST['text'];
        $reserve_id = $_POST['reserve_id'];

        // DBに接続
        try {
            $db = db_connect();
            // テーブルに挿入するSQL
            $sql = 'INSERT INTO apply_lists (reserve_info_id,apply_detail,apply_status_id,apply_datetime) VALUES (:reserve_id,:text,:apply_status_id,:apply_datetime)';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':reserve_id', $reserve_id, PDO::PARAM_INT);
            $stmt->bindParam(':text', $text, PDO::PARAM_STR);
            // ステータスIDは常に未
            $status = 1;
            $stmt->bindValue(':apply_status_id', $status, PDO::PARAM_INT);
            // 申請日時
            date_default_timezone_set('Asia/Tokyo');
            $apply_datetime = date('Y-m-d H:i:s');
            $stmt->bindValue(':apply_datetime', $apply_datetime, PDO::PARAM_STR);


            $stmt->execute();
            // トップページへ画面遷移
            header('location:complete.php');
            exit();
        } catch (PDOException $e) {
            exit('エラー: ' . $e->getMessage());
        }
    }
}
