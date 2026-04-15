<?php
require_once __DIR__ . "/../inc/function.php";
?>
<?php include __DIR__ . "/../inc/header.php" ?>

<?php
$db = db_connect();
// クリックされた予約情報を取得
$reserve_id = $_POST['reserve-id'];
$sql = "SELECT 
reservation_infos.id AS reserve_id,
reservation_slots.date,times.time, methods.name 
FROM reservation_infos 
INNER JOIN reservation_slots ON reservation_infos.slot_id = reservation_slots.id 
INNER JOIN times ON reservation_slots.time_id = times.id 
INNER JOIN methods ON reservation_infos.method_id = methods.id 
WHERE reservation_infos.id = :reserve_id";

$stmt = $db->prepare($sql);
$stmt->bindParam(':reserve_id', $reserve_id, PDO::PARAM_INT);
$stmt->execute();
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

// スロットを取得
$slot_sql = "SELECT reservation_slots.id,date,time FROM reservation_slots JOIN reservation_infos ON reservation_infos.slot_id=reservation_slots.id JOIN times ON reservation_slots.time_id=times.id";
$slot_stmt = $db->prepare($slot_sql);
$slot_stmt->execute();
$slots = $slot_stmt->fetchAll(PDO::FETCH_ASSOC);

// メソッドを取得
$method_sql = "SELECT id,name FROM methods";
$method_stmt = $db->prepare($method_sql);
$method_stmt->execute();
$methods = $method_stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>変更申請</title>
</head>

<body>
    <form action="./change_request_do.php" method="post">
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
                        <!-- <tr class="row">
                            <td class="col-3">変更希望内容</td>
                            <td class="col-6">
                                <select name="slot" class="form-select" id="js-slot">
                                    <?php foreach ($slots as $item):  ?>
                                        <option value="<?php echo $item["id"]; ?>">
                                            <?php echo $item["date"] . ' ' . $item["time"]; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="col-3">
                                <select name="method" class="form-select" id="js-method">
                                    <?php foreach ($methods as $item):  ?>
                                        <option value="<?php echo $item["id"]; ?>"><?php echo $item["name"]; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr> -->
                    </tbody>
                </table>
                <p>希望日時、枠を交換する場合は相手の名前をご記入ください。また、補足の連絡事項があればご記入ください。</p>
                <textarea name="change_text" id="js-text" class="form-control"></textarea>
                <button type="button" class="btn btn-primary" id="js-open">変更内容を確認</button>
                <a href="./index.php" class="btn btn-info">TOPへ戻る</a>
            </div>
        </main>

        <!-- modal -->
        <dialog id="js-modal" class="modal-dialog p-3 border rounded shadow">
            <div class="modal-content p-3">

                <h2 class="modal-header fs-5 border-bottom pb-2 mb-3">
                    変更希望内容
                </h2>

                <div class="modal-body">
                    <table class="table text-center align-middle">
                        <tr class="row">
                            <td id="js-slot-write" class="col-4"></td>
                            <td id="js-method-write" class="col-4"></td>
                        </tr>
                    </table>
                    <p id="js-text-write"></p>
                </div>

                <div class="modal-footer mt-3">
                    <button class="btn btn-primary">送信</button>

                    <button class="btn btn-secondary" id="js-close">閉じる</button>
                </div>
            </div>
        </dialog>
    </form>

    <script>
        // change_request
        const openBtn = document.getElementById('js-open');
        const closeBtn = document.getElementById('js-close');
        const modal = document.getElementById('js-modal');

        function modalWrite(cat) {
            const element = document.getElementById(`js-${cat}`);
            const writeArea = document.getElementById(`js-${cat}-write`);
            if (cat === 'text') {
                writeArea.textContent = element.value;
            } else {
                writeArea.textContent = element.options[element.selectedIndex].text;
            }
        };

        openBtn.addEventListener('click', () => {
            modal.showModal();
            modalWrite('text');
        });
        closeBtn.addEventListener('click', () => {
            modal.close();
        })
    </script>
</body>

</html>