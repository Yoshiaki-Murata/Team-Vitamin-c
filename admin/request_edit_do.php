<?php
require_once __DIR__ . '/../inc/function.php';

$db = db_connect();

// POSTチェック
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: request.php');
    exit;
}

// 値取得
$id = $_POST['id'] ?? '';
$status_id = $_POST['status_id'] ?? '';

try {
    $sql = 'UPDATE apply_lists SET apply_status_id=:status_id WHERE id=:id';

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':status_id', $status_id, PDO::PARAM_INT);

    $stmt->execute();

    header('Location:request.php');
    exit;
} catch (PDOException $e) {
    echo '更新失敗: ' . $e->getMessage();
}
