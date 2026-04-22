<?php
require_once __DIR__ . '/../inc/function.php';

check_logined();

require_once './../inc/header_admin.php';
?>

<body>
  <div class="container mt-5">
    <div class="row g-4">

      <div class="col-md-4">
        <a href="students.php" class="text-decoration-none">
          <div class="card card-students h-100 text-center p-4">
            <h5 class="fw-bold mb-2">訓練生一覧</h5>
            <p class="small mb-0">受講生の管理</p>
          </div>
        </a>
      </div>

      <div class="col-md-4">
        <a href="consuls.php" class="text-decoration-none">
          <div class="card card-consultant h-100 text-center p-4">
            <h5 class="fw-bold mb-2">講師一覧</h5>
            <p class="small mb-0">キャリアコンサルタント管理</p>
          </div>
        </a>
      </div>

      <div class="col-md-4">
        <a href="masters.php" class="text-decoration-none">
          <div class="card card-admin h-100 text-center p-4">
            <h5 class="fw-bold mb-2">管理者一覧</h5>
            <p class="small mb-0">管理ユーザー設定</p>
          </div>
        </a>
      </div>

      <div class="col-md-4">
        <a href="schedule.php" class="text-decoration-none">
          <div class="card card-schedule h-100 text-center p-4">
            <h5 class="fw-bold mb-2">キャリコン予約枠作成</h5>
            <p class="small mb-0">スケジュール作成</p>
          </div>
        </a>
      </div>

      <div class="col-md-4">
        <a href="reservation.php" class="text-decoration-none">
          <div class="card card-reserve h-100 text-center p-4">
            <h5 class="fw-bold mb-2">予約状況</h5>
            <p class="small mb-0">予約の確認・管理</p>
          </div>
        </a>
      </div>

      <div class="col-md-4">
        <a href="request.php" class="text-decoration-none">
          <div class="card card-request h-100 text-center p-4">
            <h5 class="fw-bold mb-2">申請一覧</h5>
            <p class="small mb-0">変更申請の対応</p>
          </div>
        </a>
      </div>

    </div>
  </div>


</body>
<?php require_once './../inc/footer.php'; ?>