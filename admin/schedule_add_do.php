<?php require_once __DIR__ . '/../inc/function.php';
check_logined();

$db = db_connect();

// POSTチェック
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: schedule.php');
    exit;
}

// POST取得
$date = $_POST['date'] ?? '';
$time_id = $_POST['time_id'] ?? '';
$lines_id = $_POST['lines_id'] ?? '';
$class_id = !empty($_POST['class_id']) ? $_POST['class_id'] : null;
$consultant_id = !empty($_POST['consultant_id']) ? $_POST['consultant_id'] : null;
$carecon_id = $_POST['carecon_id'] ?? '';
$reserve_status_id = $_POST['reserve_status_id'] ?? 1;

// バリデーション（最低限）
if (
    empty($date) ||
    empty($time_id) ||
    empty($lines_id) ||
    empty($carecon_id)
) {
    exit('入力項目に不備があります');
}

try {
    // 重複チェック
    $sql_check = 'SELECT COUNT(*) FROM reservation_slots WHERE date = :date AND time_id = :time_id AND lines_id = :lines_id';

    $stmt_check = $db->prepare($sql_check);

    $stmt_check->bindValue(':date', $date, PDO::PARAM_STR);
    $stmt_check->bindValue(':time_id', $time_id, PDO::PARAM_INT);
    $stmt_check->bindValue(':lines_id', $lines_id, PDO::PARAM_INT);

    $stmt_check->execute();

    $count = $stmt_check->fetchColumn();

    if ($count > 0) {
        exit('この日時・ラインはすでに登録されています');
    }

    $sql = 'INSERT INTO reservation_slots
    (date,time_id,class_id,consultant_id,carecon_id,lines_id,reserve_status_id) VALUES
    (:date,:time_id,:class_id,:consultant_id,:carecon_id,:lines_id,:reserve_status_id)';

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':date', $date, PDO::PARAM_STR);
    $stmt->bindValue(':time_id', $time_id, PDO::PARAM_INT);
    $stmt->bindValue(':class_id', $class_id, is_null($class_id) ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindValue(':consultant_id', $consultant_id, is_null($consultant_id) ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindValue(':carecon_id', $carecon_id, PDO::PARAM_INT);
    $stmt->bindValue(':lines_id', $lines_id, PDO::PARAM_INT);
    $stmt->bindValue(':reserve_status_id', $reserve_status_id, PDO::PARAM_INT);

    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        $_SESSION["err_msg"] = "追加できませんでした";
        header('location:schedule.php');
        exit();
    } else {
        $_SESSION["msg"] = "追加完了しました";
    }


    header('Location:schedule.php');
    exit;
} catch (PDOException $e) {
    echo '登録失敗: ' . $e->getMessage();
}
