<?php
require_once __DIR__ . "/../inc/function.php";

session_start();

// ログインしていない場合はログイン画面に戻す（セキュリティ対策）
if (!isset($_SESSION['login_id'])) {
    header('Location: login.php');
    exit;
}
?>

<?php include __DIR__ . "/../inc/header_student.php" ?>
<main class="container mt-5">
    <h1 class="mb-5 text-center">キャンセル申請</h1>
    <div class="text-center">
        <p>ようこそ<?php echo htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8'); ?>さん</p>
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
                    <?php foreach ($result_must as $rm): ?>
                        <tr class="row">
                            <td class="col-1">
                                <!-- 5/17 -->
                                <?php echo $rm["date"]; ?>
                            </td>
                            <td class="col-2">
                                <!-- 10:00～ -->
                                <?php echo $rm["time"]; ?>
                            </td>
                        <?php endforeach; ?>
                </tbody>
            </table>
            <form action="./reserve.php" method="post" id="cancelForm">
                <input type="hidden" name="reserve-id" id="reserve-id" value="<?php echo $rm["reserve_id"] ?>">
                <label>キャンセル申請理由</label>
                <textarea name="body" class="form-control" rows="5"></textarea>

                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal">
                    キャンセル申請
                </button>
            </form>
        </div>
    </div>
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">キャンセル確認</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>本当に予約をキャンセルしますか？</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">いいえ</button>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('cancelForm').submit();">はい</button>
                </div>
            </div>
        </div>
    </div>
</main>