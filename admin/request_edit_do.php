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

// バリデーション
if (empty($id) || empty($status_id)) {
    header('Location: request.php');
    exit;
}

try {

    // 更新SQL
    $sql = 'UPDATE apply_lists 
            SET apply_status_id = :status_id 
            WHERE id = :id';

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':status_id', $status_id, PDO::PARAM_INT);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
} catch (PDOException $e) {
    echo '更新に失敗しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    exit;
}

// 完了後リダイレクト
header('Location: request.php');
exit;
