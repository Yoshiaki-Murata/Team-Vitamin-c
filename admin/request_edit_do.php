<?php
require_once __DIR__ . '/../inc/function.php';
check_logined();

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


    if ($stmt->rowCount() === 0) {
        $_SESSION["msg"] = "変更はありませんでした";
        header('location:request.php');
        exit();
    } else {
        $_SESSION["msg"] = "編集完了しました";
        header('Location: request.php');
        exit;
    }
} catch (PDOException $e) {
    session_err_msg('DB更新に失敗しました:');
    header('Location: request.php');
    exit;
}

// 完了後リダイレクト
header('Location: request.php');
exit;
