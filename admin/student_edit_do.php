<?php
require_once __DIR__ . '/../inc/function.php';
check_logined();

$db = db_connect();

// POSTチェック
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: students.php');
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
// 教室
if (empty($class_id)) {
    exit('教室は必須です');
}
// 番号
if (empty($number)) {
    exit('番号は必須です');
}
if (!ctype_digit($number) || mb_strlen($number) !== 2) {
    exit('番号は半角数字2文字で入力してください');
}
// 名前
if (empty($name)) {
    exit('名前は必須です');
}
if (20 < mb_strlen($name)) {
    exit('名前は20文字以内で入力してください。');
}
// 訓練種別
if (empty($course_id)) {
    exit('訓練種別は必須です');
}
// 入校日
if (empty($admission_date)) {
    exit('入校日は必須です');
}
// 修了予定日
if (empty($graduation_date)) {
    exit('修了予定日は必須です');
}
// ログインID
if (empty($login_id)) {
    exit('ログインIDは必須です');
}
// パスワード
if (empty($password)) {
    exit('パスワードは必須です');
}
if (!ctype_digit($password) || mb_strlen($password) !== 8) {
    exit('パスワードは半角数字8文字で入力してください');
}
// 在籍状況
if (empty($status_id)) {
    exit('在籍状況は必須です');
}

try {

    $db->beginTransaction();

    // ① 更新前status取得
    $sql = 'SELECT status_id FROM students WHERE id = :id';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $before_status = $stmt->fetchColumn();

    // ② 学生更新
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

    // ③ 在籍 → 退校になったときだけ
    if ($before_status == 1 && $status_id != 1) {

        $sql = 'SELECT slot_id FROM reservation_infos WHERE student_id = :student_id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':student_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $slots = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $sql = 'DELETE FROM reservation_infos WHERE student_id = :student_id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':student_id', $id, PDO::PARAM_INT);
        $stmt->execute();

        foreach ($slots as $slot_id) {

            $sql = 'SELECT COUNT(*) FROM reservation_infos WHERE slot_id = :slot_id';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':slot_id', $slot_id, PDO::PARAM_INT);
            $stmt->execute();

            $status = ($stmt->fetchColumn() > 0) ? 2 : 1;

            $sql = 'UPDATE reservation_slots SET reserve_status_id = :status WHERE id = :slot_id';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':status', $status, PDO::PARAM_INT);
            $stmt->bindValue(':slot_id', $slot_id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    $db->commit();

    if ($stmt->rowCount() === 0) {
        $_SESSION["err_msg"] = "編集できませんでした";
        header('location:masters.php');
        exit();
    } else {
        $_SESSION["msg"] = "編集完了しました";
    }


    header('Location: students.php');
    exit;
} catch (PDOException $e) {
    $db->rollBack();
    echo '更新失敗: ' . $e->getMessage();
}
