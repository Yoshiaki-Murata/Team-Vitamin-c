<?php
require_once __DIR__ . "/../inc/function.php";
?>
<?php include __DIR__ . "/../inc/header.php" ?>

<?php
$db = db_connect();
$reserve_id = $_POST['reserve_id'];
$sql = "SELECT reservation_infos.id AS reserve_id,reservation_slots.date,times.time, methods.name FROM reservation_infos INNER JOIN reservation_slots ON reservation_infos.slot_id = reservation_slots.id INNER JOIN times ON reservation_slots.time_id = times.id INNER JOIN methods ON reservation_infos.method_id = methods.id WHERE reservation_infos.id = :reserve_id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':reserve_id', $reserve_id, PDO::PARAM_INT);
$stmt->execute();
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

$dates = getColumn($db, 'reservation_slots', 'date');
$times = getColumn($db, 'times', 'time');
$methods = getColumn($db, 'methods', 'name');
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>変更申請</title>
</head>

<body>
    <main class="container mt-5 l-wrapper">
        <h1 class="mb-5 text-center">変更申請</h1>
        <div class="text-center">
            <table class="table mb-8">
                <tbody>
                    <tr class="row">
                        <td class="col-3">予約内容</td>
                        <td class="col-3"><?php echo $reservation["date"] ?>
                        </td>
                        <td class="col-3"><?php echo $reservation["time"] ?>
                        </td>
                        <td class="col-3"><?php echo $reservation["name"] ?></td>
                    </tr>
                    <tr class="row">
                        <td class="col-3">変更希望内容</td>
                        <td class="col-3">
                            <select name="date" class="form-select">
                                <?php foreach ($dates as $item):  ?>
                                    <option value="<?php echo $item["date"]; ?>"><?php echo $item["date"]; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="col-3">
                            <select name="time" class="form-select">
                                <?php foreach ($times as $item):  ?>
                                    <option value="<?php echo $item["time"]; ?>"><?php echo $item["time"]; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="col-3">
                            <select name="method" class="form-select">
                                <?php foreach ($methods as $item):  ?>
                                    <option value="<?php echo $item["name"]; ?>"><?php echo $item["name"]; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p>枠を交換する場合は相手の名前をご記入ください。また、補足の連絡事項があればご記入ください。</p>
            <form action=""><textarea name="change_text" id="change_text" class="form-control"></textarea></form>
            <button class="btn btn-primary">変更内容を確認</button>
            <a href="./index.php" class="btn btn-info">TOPへ戻る</a>
        </div>
    </main>
    <script src="./../js/script.js"></script>
</body>

</html>