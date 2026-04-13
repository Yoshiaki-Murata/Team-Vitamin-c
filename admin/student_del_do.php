<?php
require_once __DIR__ . '/../inc/function.php';

$db = db_connect();

// POSTチェック
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: students.php');
    exit;
}

// id取得
$id = $_POST['id'] ?? '';

// 不正チェック
if ($id === '' || !is_numeric($id)) {
    exit('不正なアクセスです');
}

try {
    // 削除処理
    $sql = "DELETE FROM students WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // 一覧へ戻る
    header('Location: students.php');
    exit;
} catch (PDOException $e) {
    echo '削除失敗: ' . $e->getMessage();
}
