<?php
require_once __DIR__ . "/../inc/function.php";

$db = db_connect();
try {
    // 予約可能な枠がある日の情報を取得する
    $sql_reserve = "SELECT DISTINCT rs.date
    FROM reservation_slots rs
    WHERE rs.reserve_status_id=1
    AND rs.carecon_id = 2";
    $stmt_reserve = $db->prepare($sql_reserve);
    $stmt_reserve->execute();
    $result = $stmt_reserve->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "エラー" . $e->getMessage();
}
?>

<?php include __DIR__ . "/../inc/header_student.php" ?>
<!-- <?php check_array($date); ?> -->
<main class="l-wrapper">
    <h1 class="c-title">キャリコンプラス予約画面</h1>
    <div class="text-center">
        <select name="date" id="dateSelect" class="mb-3 d-inline-block form-select w-auto">
            <?php foreach ($result as $r): ?>
                <option value="<?php echo $r["date"]; ?>">
                    <?php echo $r["date"]; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <ul id=reserveInfo class="row mx-auto list-unstyled justify-content-center">
        </ul>
    </div>
    <div class="text-center">
        <a href="./index.php" class="btn btn-warning">戻る</a>
    </div>





    <dialog>
        <div>
            <h2 class="text-center mb-4">予約情報</h2>
            <table class="table" id="modalTable">
                <thead>
                    <th class="text-center">日付</th>
                    <th class="text-center">時間</th>
                    <th class="text-center">クラス</th>
                    <th class="text-center">コンサルタント名</th>
                    <th class="text-center">実施方法</th>
                    <th class="text-center">予約</th>
                </thead>
                <tbody>
                </tbody>
            </table>
            <button class="btn btn-warning" id="modalClose">
                閉じる
            </button>
        </div>
    </dialog>

</main>
<script src="./../js/reserve.js"></script>
<?php require_once './../inc/footer.php'; ?>