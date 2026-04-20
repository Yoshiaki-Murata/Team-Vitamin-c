<?php
require_once __DIR__ . '/../inc/function.php';

check_logined();

require_once './../inc/header_admin.php';
?>

<body>
    <div class="container text-center mt-5">
        <div class="row g-5">
            <div class="col-4">
                <p class="p-4 bg-secondary-subtle"><a href="students.php">訓練生一覧</a></p>
            </div>
            <div class="col-4">
                <p class="p-4 bg-secondary-subtle"><a href="consuls.php">講師一覧</a></p>
            </div>
            <div class="col-4">
                <p class="p-4 bg-secondary-subtle"><a href="masters.php">管理者一覧</a></p>
            </div>
            <div class="col-4">
                <p class="p-4 bg-secondary-subtle"><a href="schedule.php">キャリコン枠作成</a></p>
            </div>
            <div class="col-4">
                <p class="p-4 bg-secondary-subtle"><a href="reservation.php">キャリコン予約状況</a></p>
            </div>
            <div class="col-4">
                <p class="p-4 bg-secondary-subtle"><a href="request.php">申請情報一覧</a></p>
            </div>
        </div>
    </div>


</body>
<?php require_once './../inc/footer.php'; ?>