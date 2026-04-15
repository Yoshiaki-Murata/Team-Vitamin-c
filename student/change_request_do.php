<?php
require_once __DIR__ . '/../inc/function.php';

// TODO: データ受け取り
if (!empty($_POST)) {
    // POST送信されたとき
    if (!empty($_POST['text']) && !empty($_POST['reserve-id'])) {
        // TODO: 必須項目チェック（空の場合）
        $text = $_POST['text'];
        $reserve_id = $_POST['reserve-id'];

        // DBに接続
        try {
            $db = db_connect();
            // テーブルに挿入するSQL
            $sql2 = 'INSERT INTO apply_lists (reserve_info_id,apply_detail,apply_status_id) VALUES (:reserve_id,:text,:apply_status_id)';
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindParam(':reserve_id', $reserve_id, PDO::PARAM_STR);
            $stmt2->bindParam(':text', $text, PDO::PARAM_STR);
            // ステータスIDは常に未
            $status = 1;
            $stmt2->bindValue(':apply_status_id', $status, PDO::PARAM_STR);
            $stmt2->execute();

            // トップページへ画面遷移
            header('location:index.php');
            exit();
        } catch (PDOException $e) {
            exit('エラー: ' . $e->getMessage());
        }
    }
}
