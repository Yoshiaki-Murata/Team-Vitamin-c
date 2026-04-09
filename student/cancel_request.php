<?php
require_once __DIR__ . "/../inc/function.php";
?>

<?php include __DIR__ . "/../inc/header.php" ?>
<main class="container mt-5">
    <h1 class="mb-5 text-center">キャンセル申請</h1>
    <div class="text-center">
        <p>ようこそ●●さん</p>
    </div>
    <div class="mb-4">
        <h2 class="mb-3">キャリコンプラス予約状況</h2>
        <div class="mb-3">
            <table class="table ms-4">
                <thead>
                    <tr class="row">
                        <th class="col-1">日付</th>
                        <th class="col-2">開始時間</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($result_must as $rm): ?>
                    <!-- <tr class="row">
                        <td class="col-1">5/17</td>
                        <td class="col-2">10:00～</td>
                    </tr> -->
                    <tr class="row">
                        <td class="col-1">
                            <?php echo $rm["date"]; ?>
                        </td>
                        <td class="col-2">
                            <?php echo $rm["time"]; ?>
                        </td>
                    <?php endforeach; ?>    
                </tbody>
            </table>
            <form action="./reserve.php" method="post">
                <input type="hidden" name="reserve-id" id="reserve-id" value="<?php echo $rm["reserve_id"] ?>">
                <label>キャンセル申請理由</label>
                <textarea name="body" class="form-control" rows="5"></textarea>
                <input type="submit" value="キャンセル申請" class="btn btn-sm btn-danger">
            </form>
        </div>
    </div>
</main>