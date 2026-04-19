<?php
require_once __DIR__ . "/../inc/function.php";

session_start();

// ログインしていない場合はログイン画面に戻す（セキュリティ対策）
// if (!isset($_SESSION['login_id'])) {
//     header('Location: login.php');
//     exit;
// }

$name = $_SESSION["user_name"];
$login_id = $_SESSION["user_id"];
$db = db_connect();
try {
    // キャリコンプラスの情報を取得
    $sql_plus = "SELECT ri.id,ti.time,rsl.date,mt.name AS method_name FROM reservation_infos ri 
            INNER JOIN reservation_slots rsl ON ri.slot_id = rsl.id
            INNER JOIN methods mt ON ri.method_id= mt.id
            INNER JOIN times ti ON rsl.time_id = ti.id
            LEFT JOIN classes cl ON rsl.class_id = cl.id
            LEFT JOIN carecons cr ON rsl.carecon_id =cr.id
            WHERE ri.student_id=:login_id AND ri.method_id=2";

    $stmt_plus = $db->prepare($sql_plus);
    $stmt_plus->bindParam(":login_id", $login_id, PDO::PARAM_INT);
    $stmt_plus->execute();
    $result_plus = $stmt_plus->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "エラ‐" . $e->getMessage();
}
?>

<?php include __DIR__ . "/../inc/header_student.php" ?>
<?php check_array($_SESSION); ?>
<main class="container mt-5">
  <div class="mb-4">
        <h2 class="mb-3">キャリコンプラス予約状況</h2>
        <table class="table">
            <thead>
                <tr class="row">
                    <th class="col-2">日付</th>
                    <th class="col-2">開始時間</th>
                    <th class="col-3">面談方法</th>
                    <th class="col-5"></th>
                </tr>
            </thead>
            <tbody> 
                <?php foreach ($result_plus as $rp): ?>
                    <tr class="row">
                        <td class="col-2"><?php echo htmlspecialchars($rp["date"]); ?></td>
                        <td class="col-2"><?php echo htmlspecialchars($rp["time"]); ?></td>
                        <td class="col-3"><?php echo htmlspecialchars($rp["method_name"]); ?></td>
                        <td class="col-2">
                            <form action="./cancel_request_do.php" method="post" id="cancelForm_<?php echo $rp['id']; ?>">
                                <input type="hidden" name="reserve-id" value="<?php echo $rp["id"]; ?>">
                                <textarea name="body" class="form-control mb-2" rows="2" placeholder="理由を入力"></textarea>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
  <?php require_once __DIR__ . '/inc/footer.php'; ?>