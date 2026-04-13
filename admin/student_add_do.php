<?php
require_once __DIR__ . '/../inc/function.php';

$db = db_connect();

// POSTチェック
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: student_add.php');
    exit;
}

// 値受け取り
$class_id = $_POST['class_id'] ?? '';
$number = $_POST['number'] ?? '';
$name = $_POST['name'] ?? '';
$course_id = $_POST['course_id'] ?? '';
$admission_date = $_POST['admission-date'] ?? '';
$graduation_date = $_POST['graduation-date'] ?? '';
$login_id = $_POST['login-id'] ?? '';
$password = $_POST['password'] ?? '';
$status_id = $_POST['status_id'] ?? '';

// 最低限バリデーション
if ($status_id === '') {
    exit('在籍状況を選択してください');
}

if ($password === '') {
    exit('パスワードを入力してください');
}

// 出席番号が数字2桁でない時
if (!preg_match('/^\d{2}$/', $number)) {
    header('location:student_add.php');
    exit();
}

// 訓練生の名前、漢字、ひらがな、カタカナ、長音記号を1文字以上
//  半角または全角スペースがあってもなくても良い（0回または1回）
if (!preg_match('/^[一-龠ぁ-んァ-ヶー]+[ 　]?[一-龠ぁ-んァ-ヶー]+$/', $name)) {
    header('location:student_add.php');
    exit();
}

// ログインID


// パスワード
if (!preg_match('/^\d{8}$/', $password)) {
    header('location:student_add.php');
    exit();
}

try {
    $sql = "INSERT INTO students 
    (class_id, number, name, course_id, admission_date, graduation_date, login_id, password, status_id)
    VALUES 
    (:class_id, :number, :name, :course_id, :admission_date, :graduation_date, :login_id, :password, :status_id)";

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

    $stmt->execute();

    header('Location: students.php');
    exit;
} catch (PDOException $e) {
    echo '登録失敗: ' . $e->getMessage();
}
