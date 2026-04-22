<?php
require_once __DIR__ . "/../inc/function.php";
?>
<?php include __DIR__ . "/../inc/header_student.php" ?>

<?php
$db = db_connect();
// クリックされた予約情報を取得
$reserve_id = $_POST['reserve-id'];
// $reserve_id = 4;
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

    <main class="container mt-5 l-wrapper">
        <h1 class="mb-5 text-center">変更申請</h1>

        <table class="table mb-8">
            <thead>
                <tr>
                    <th>日付</th>
                    <th>時間</th>
                    <th>実施方法</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="col-3"><?php echo $reservation["date"] ?>
                    </td>
                    <td class="col-3"><?php echo $reservation["time"] ?>
                    </td>
                    <td class="col-3"><?php echo $reservation["name"] ?></td>
                </tr>
            </tbody>
        </table>
        <p id="description">希望日時、枠を交換する場合は相手の名前をご記入ください。また、補足の連絡事項があればご記入ください。</p>
        <form action="./change_request_do.php" method="post" id="change-form">
            <textarea name="text" id="js-text" class="form-control" rows="3" required></textarea>
            <div class="mt-3">
                <button type="button" class="btn btn-primary" id="js-open">内容を確認</button>
                <a href="./index.php" class="btn btn-secondary">TOPへ戻る</a>
        </form>

        </div>
    </main>

    <!-- modal -->
    <dialog id="js-modal" class="modal-dialog p-3 border rounded shadow">

        <div class="modal-content p-3">
            <div class="modal-header">

                <h2 class="fs-5 border-bottom pb-2 mb-3">
                    変更希望内容
                </h2>
            </div>
            <div class="modal-body">
                <p id="js-text-write"></p>
            </div>

            <div class="modal-footer mt-3 gap-2">

                <input type="hidden" name="reserve_id" value="<?php echo $reserve_id; ?>">
                <div class="d-flex align-items-center">
                    <button class="btn btn-primary" type="submit" form="change-form">送信</button>

                    <button class="btn btn-secondary" id="js-close" type="button">閉じる</button>
                </div>
            </div>
        </div>
    </dialog>


    <script>
        // change_request
        const desc = document.getElementById('description');
        const openBtn = document.getElementById('js-open');
        const closeBtn = document.getElementById('js-close');
        const modal = document.getElementById('js-modal');
        const error = document.createElement('div');


        openBtn.addEventListener('click', () => {

            const element = document.getElementById('js-text');
            if (element.value) {
                modal.showModal();
                const writeArea = document.getElementById('js-text-write');
                writeArea.textContent = element.value;
            } else {
                error.innerHTML = '';
                error.innerHTML = '<p class=text-danger>※変更内容をテキストで入力してください。</p>';
                desc.appendChild(error);
            }
        });
        closeBtn.addEventListener('click', () => {
            modal.close();
        })
    </script>
</body>

</html>

<?php require_once './../inc/footer.php'; ?>