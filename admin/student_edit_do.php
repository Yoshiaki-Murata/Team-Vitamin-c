<?php
require_once __DIR__ . '/../inc/function.php';

$db = db_connect();

// POSTチェック
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: student.php');
    exit;
}

// 値取得
$id = $_POST['id'] ?? '';
$class_id = $_POST['class_id'] ?? '';
$number = $_POST['number'] ?? '';
$name = $_POST['name'] ?? '';
$course_id = $_POST['course_id'] ?? '';
$admission_date = $_POST['admission_date'] ?? '';
$graduation_date = $_POST['graduation_date'] ?? '';
$login_id = $_POST['login_id'] ?? '';
$password = $_POST['password'] ?? '';
$status_id = $_POST['status_id'] ?? '';

// バリデーション
if ($id === '' || !is_numeric($id)) {
    exit('不正なIDです');
}

try {
    $sql = "UPDATE students SET
        class_id = :class_id,
        number = :number,
        name = :name,
        course_id = :course_id,
        admission_date = :admission_date,
        graduation_date = :graduation_date,
        login_id = :login_id,
        password = :password,
        status_id = :status_id
    WHERE id = :id";

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
    $stmt->bindValue(':number', $number, PDO::PARAM_STR);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':course_id', $course_id, PDO::PARAM_INT);
    $stmt->bindValue(':admission_date', $admission_date, PDO::PARAM_STR);
    $stmt->bindValue(':graduation_date', $graduation_date, PDO::PARAM_STR);
    $stmt->bindValue(':login_id', $login_id, PDO::PARAM_STR);
    $stmt->bindValue(':password', $password, PDO::PARAM_STR);
    $stmt->bindValue(':status_id', $status_id, PDO::PARAM_INT);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    header('Location: student.php');
    exit;
} catch (PDOException $e) {
    echo '更新失敗: ' . $e->getMessage();
}
