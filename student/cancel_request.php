<?php
require_once __DIR__ . "/../inc/function.php";
?>


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
<?php include __DIR__ . "/../inc/header_student.php" ?>

<!DOCTYPE html>
<html lang="ja">

<body>
  <main class=" l-wrapper">
    <h1 class="mb-5 c-title">キャンセル申請</h1>
    <div>
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
            <td><?php echo $reservation["date"] ?>
            </td>
            <td><?php echo $reservation["time"] ?>
            </td>
            <td><?php echo $reservation["name"] ?></td>
          </tr>
        </tbody>
      </table>

      <p id="description">キャンセル理由(必須)をご記入下さい。<br>
        面談方法の変更(対面/zoom)については面談前日午前中までに、LINE又は事務局まで直接お申し出下さい。</p>

      <form action="./cancel_request_do.php" method="post" id="cancelForm">

        <textarea name="text" id="js-text" class="form-control mb-3" rows="3" required></textarea>

        <div class="mt-3 text-center">
          <button type="button"
            class="btn btn-primary"
            id="js-open">
            内容を確認
          </button>
          <a href="./index.php" class="btn btn-secondary">
            TOPへ戻る
          </a>
        </div>
        <div class="modal fade" id="js-modal" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">入力内容の確認</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <p class="fw-bold mb-2">キャンセル理由</p>
                <p id="js-text-write" class="border p-3 rounded bg-light"></p>
              </div>
              <div class="modal-footer">
                <input type="hidden" name="reserve_id" value="<?= $reserve_id ?>">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                  戻る
                </button>
                <button type="submit" form="cancelForm" class="btn btn-primary">
                  送信する
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>



  </main>

  <!-- modal -->
  <!-- <dialog id="js-modal" class="modal-dialog p-3 border rounded shadow">
    <div class="modal-content p-3">

      <h2 class="modal-header fs-5 border-bottom pb-2 mb-3">
        変更希望内容
      </h2>
      <h2 class="modal-header fs-5 border-bottom pb-2 mb-3">
        キャンセル理由・変更希望内容等
      </h2>

      <div class="modal-body">
        <p id="js-text-write"></p>
      </div>

      <div class="modal-footer mt-3">

        <input type="hidden" name="reserve_id" value="<//?php echo $reserve_id; ?>">
        <button class="btn btn-primary" type="submit">送信</button>

        <button class="btn btn-secondary" id="js-close" type="button">閉じる</button>
      </div>
    </div>
  </dialog> -->

  <script>
    // change_request
    // const openBtn = document.getElementById('js-open');
    // const closeBtn = document.getElementById('js-close');
    // const modal = document.getElementById('js-modal');
    // const textarea = document.getElementById('js-text');
    // const form = document.getElementById('cancelForm');

    // openBtn.addEventListener('click', () => {
    //   modal.showModal();
    //   const element = document.getElementById('js-text');
    //   const writeArea = document.getElementById('js-text-write');
    //   writeArea.textContent = element.value;
    // });
    // closeBtn.addEventListener('click', () => {
    //   modal.close();
    // });
    // 最終的な送信時のチェック（念のため）
    // form.addEventListener('submit', (event) => {
    //   if (textarea.value.trim() === "") {
    //     alert("入力してください");
    //     event.preventDefault();
    //   }
    // });

    document.addEventListener('DOMContentLoaded', function() {
      const openBtn = document.getElementById('js-open');
      const textarea = document.getElementById('js-text');
      const writeArea = document.getElementById('js-text-write');
      const modalEl = document.getElementById('js-modal');

      const modal = new bootstrap.Modal(modalEl);

      openBtn.addEventListener('click', () => {
        const text = textarea.value.trim();

        if (text === "") {
          alert("入力してください");
          return;
        }

        writeArea.textContent = text;
        modal.show(); // ← ここで開く
      });
    });
  </script>
</body>

<?php require_once './../inc/footer.php'; ?>