<?php
require_once __DIR__ . "/../inc/function.php";

$db = db_connect();
try {
    // 予約可能な枠がある日の情報を取得する
    $sql_reserve = "SELECT rs.date
FROM reservation_infos ri
INNER JOIN reservation_slots rs ON ri.slot_id=rs.id
WHERE rs.reserve_status_id=1";
    $stmt_reserve = $db->prepare($sql_reserve);
    $stmt_reserve->execute();
    $result = $stmt_reserve->fetchAll(PDO::FETCH_ASSOC);

    // 予約されている日付情報を取得
    $sql_date = "SELECT 
    t.time, 
    COUNT(CASE WHEN rs.reserve_status_id = 1 THEN 1 END) AS reserve_count
FROM times t
LEFT JOIN reservation_slots rs 
    ON t.id = rs.time_id 
    AND rs.date = '2026-04-11'
GROUP BY t.id
ORDER BY t.time ASC;";
    $stmt_date = $db->prepare($sql_date);
    $stmt_date->execute();
    $date = $stmt_date->fetchAll(PDO::FETCH_ASSOC);

    // header("Content-Type:application/json");
    // echo json_encode($date);
} catch (PDOException $e) {
    echo "エラー" . $e->getMessage();
}
?>

<?php include __DIR__ . "/../inc/header.php" ?>
<!-- <?php check_array($date); ?> -->
<main class="container mt-5">
    <h1 class="mb-5 text-center">予約画面</h1>
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
</main>
<script src="./../js/reserve.js"></script>