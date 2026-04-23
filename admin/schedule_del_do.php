<?php
require_once __DIR__ . '/../inc/function.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $db = db_connect();

    $sql = "DELETE FROM reservation_slots WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();


    if ($stmt->rowCount() === 0) {
        $_SESSION["err_msg"] = "削除できませんでした";
        header('location:schedule.php');
        exit();
    } else {
        $_SESSION["msg"] = "削除完了しました";
    }


    header('Location: schedule.php');
    exit;
}
