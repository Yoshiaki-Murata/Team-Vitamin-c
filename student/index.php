<?php
require_once __DIR__ . "/../inc/function.php";

$name = $_SESSION["user_name"];
$login_id = $_SESSION["user_id"];
$db = db_connect();
try {
  // 必須キャリコンの情報を取得
  $sql_must = "SELECT ri.id,ti.time,rsl.date,mt.name AS method_name,cl.name AS class_name FROM reservation_infos ri 
            INNER JOIN reservation_slots rsl ON ri.slot_id = rsl.id
            INNER JOIN methods mt ON ri.method_id= mt.id
            INNER JOIN times ti ON rsl.time_id = ti.id
            LEFT JOIN classes cl ON rsl.class_id = cl.id
            LEFT JOIN carecons cr ON rsl.carecon_id =cr.id
            WHERE ri.student_id=:login_id AND rsl.carecon_id=1";
  $stmt_must = $db->prepare($sql_must);
  $stmt_must->bindParam(":login_id", $login_id, PDO::PARAM_INT);
  $stmt_must->execute();
  $result_must = $stmt_must->fetchAll(PDO::FETCH_ASSOC);

  // キャリコンプラスの情報を取得
  $sql_plus = "SELECT ri.id,ti.time,rsl.date,mt.name AS method_name,cl.name AS class_name FROM reservation_infos ri 
            INNER JOIN reservation_slots rsl ON ri.slot_id = rsl.id
            INNER JOIN methods mt ON ri.method_id= mt.id
            INNER JOIN times ti ON rsl.time_id = ti.id
            LEFT JOIN classes cl ON rsl.class_id = cl.id
            LEFT JOIN carecons cr ON rsl.carecon_id =cr.id
            WHERE ri.student_id=:login_id AND rsl.carecon_id=2";

  $stmt_plus = $db->prepare($sql_plus);
  $stmt_plus->bindParam(":login_id", $login_id, PDO::PARAM_INT);
  $stmt_plus->execute();
  $result_plus = $stmt_plus->fetchAll(PDO::FETCH_ASSOC);

  // 必須キャリコンの開催日の情報を取得する
  $sql_cr = "SELECT DISTINCT rs.date
    FROM reservation_slots rs 
    WHERE rs.carecon_id=1";
  $stmt_cr = $db->prepare($sql_cr);
  $stmt_cr->execute();
  $cr = $stmt_cr->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "エラ‐" . $e->getMessage();
}
?>

<?php include __DIR__ . "/../inc/header_student.php" ?>

<body>
  <main class="l-wrapper">
    <div class="mb-5">
      <h1 class="c-title">訓練生トップページ</h1>
      <p>ようこそ<?php echo "  " . $_SESSION["user_name"] . "  "; ?>さん</p>
    </div>
    <div class="mb-5">
      <div class="d-flex gap-3">
        <h2 class="mb-3 c-title_carecon">
          キャリコン実施日
        </h2>
        <button type="button" class="btn btn-primary mb-3" id="mReserveBtn">予約状況確認</button>
      </div>

      <div>
        <?php if ($result_must): ?>
          <table class="table ms-4">
            <thead>
              <tr class="row">
                <th class="col-2">日付</th>
                <th class="col-2">開始時間</th>
                <th class="col-3">面談方法</th>
                <th class="col-2">教室</th>
                <th class="col-3">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($result_must as $rm): ?>
                <tr class="row">
                  <td class="col-2">
                    <?php echo $rm["date"]; ?>
                  </td>
                  <td class="col-2">
                    <?php echo $rm["time"]; ?>
                  </td>
                  <td class="col-3">
                    <?php echo $rm["method_name"]; ?>
                  </td>
                  <td class="col-2">
                    <?php echo $rm["class_name"] ?? "未定"; ?>
                  </td>
                  <td class="col-3">
                    <form action="./change_request.php" method="post">
                      <input type="hidden" name="reserve-id" id="reserve-id" value="<?php echo $rm["id"] ?>">
                      <input type="submit" value="変更申請" class="btn btn-sm btn-danger">
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>実施の予定はありません</p>
        <?php endif; ?>
      </div>
    </div>

    <div class="mb-4">
      <div class="d-flex gap-3">
        <h2 class="mb-3 c-title_plus">キャリコンプラス予約状況</h2>
        <a href="./request.php" class="btn btn-warning mb-3">予約する</a>
      </div>

      <div class="mb-3">
        <?php if ($result_plus): ?>
          <table class="table ms-4">
            <thead>
              <tr class="row">
                <th class="col-2">日付</th>
                <th class="col-2">開始時間</th>
                <th class="col-3">面談方法</th>
                <th class="col-2">教室</th>
                <th class="col-3">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($result_plus as $rp): ?>
                <tr class="row">
                  <td class="col-2">
                    <?php echo $rp["date"]; ?>
                  </td>
                  <td class="col-2">
                    <?php echo $rp["time"]; ?>
                  </td>
                  <td class="col-3">
                    <?php echo $rp["method_name"]; ?>
                  </td>
                  <td class="col-2">
                    <?php echo $rp["class_name"] ?? "未定"; ?>
                  </td>
                  <td class="col-3">
                    <form action="./cancel_request.php" method="post">
                      <input type="hidden" name="reserve-id" id="reserve-id" value="<?php echo $rp["id"] ?>">
                      <input type="submit" value="キャンセル等申請" class="btn btn-sm btn-danger">
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>予約はありません</p>
        <?php endif; ?>
      </div>

    </div>

    <dialog class="dialog">
      <div class="mb-4">
        <h2 class="text-center mb-5">
          キャリコン　予約情報
        </h2>
        <select name="modalDate" id="modalDate" class="form-select mx-auto w-50 mb-4">
          <?php foreach ($cr as $c): ?>
            <option value="<?php echo $c["date"]; ?>"><?php echo $c["date"]; ?></option>
          <?php endforeach; ?>
        </select>
        <table class="table" id="modalTable">
          <thead>
            <th class="text-center">時間帯</th>
            <th class="text-center">予約者</th>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <button class="btn btn-secondary mx-auto" id="closeModal">閉じる</button>
    </dialog>

  </main>
  <script src="./../js/student.js"></script>
</body>