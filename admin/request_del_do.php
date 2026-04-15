<?php
require_once __DIR__ . '/../inc/function.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'] ?? null;

    // バリデーション
    if (empty($id)) {
        header('Location: request.php');
        exit;
    }

    try {
        $db = db_connect();

        $sql = "DELETE FROM apply_lists WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        header('Location: request.php');
        exit;
    } catch (PDOException $e) {
        echo '削除失敗: ' . $e->getMessage();
    }
}
