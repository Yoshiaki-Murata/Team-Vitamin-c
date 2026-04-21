<?php
require_once __DIR__ . '/../inc/function.php';

$db = db_connect();

$applys = [];
$statuses = [];

require_once './../inc/header_admin.php';
?>

<body>
  <div class="l-wrapper">
    <h1 class="c-title">予約変更申請一覧</h1>
    <div class="mb-5">
      <h2>キャリコン変更申請</h2>
      <table class="table">
        <thead>
          <tr>
            <th>日付</th>
            <th>時間</th>
            <th>申請者</th>
            <th>申請日時</th>
            <th>対応ステータス</th>
            <th>詳細</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>a</td>
            <td>a</td>
            <td>a</td>
            <td>a</td>
            <td>a</td>
            <td>
              <button type="button" class="btn btn-info">詳細</button>
            </td>
            <td>
              <button type="submit" class="btn btn-primary">変更</button>

              <button type="submit"
                class="btn btn-danger"
                data-bs-toggle="modal"
                data-bs-target=".delApplyModal"
                data-id="">
                削除
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- 詳細モーダル -->
    <!-- ここまで -->
    <!-- 変更モーダル -->
    <div class="modal fade editApplyModal" tabindex="-1"></div>
    <!-- ここまで -->
    <!-- 削除モーダル -->
    <div class="modal fade" id="delApplyModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="./request_del_do.php" method="post">
            <div class="modal-header">
              <h5 class="modal-title">削除確認</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <p>この申請を削除しますか?</p>
              <input type="hidden" name="id" class="del-id">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                いいえ
              </button>

              <button type="submit" class="btn btn-danger">
                削除
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- ここまで -->

    <div class="mb-5">
      <h2>キャリコンプラス変更申請</h2>
      <table class="table">
        <thead>
          <tr>
            <th>日付</th>
            <th>時間</th>
            <th>申請者</th>
            <th>申請日時</th>
            <th>対応ステータス</th>
            <th>詳細</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <td>a</td>
          <td>a</td>
          <td>a</td>
          <td>a</td>
          <td>a</td>
          <td>
            <button type="button" class="btn btn-info">詳細</button>
          </td>
          <td>
            <button type="submit" class="btn btn-primary">変更</button>

            <button type="submit"
              class="btn btn-danger"
              data-bs-toggle="modal"
              data-bs-target=".delApplyModal"
              data-id="">
              削除
            </button>
          </td>
        </tbody>
      </table>
    </div>
    <!-- 詳細モーダル -->
    <!-- ここまで -->
    <!-- 変更モーダル -->
    <!-- ここまで -->
    <!-- 削除モーダル -->
    <div class="modal fade delApplyModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="./request_del_do.php" method="post">
            <div class="modal-header">
              <h5 class="modal-title">削除確認</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <p>この申請を削除しますか?</p>
              <input type="hidden" name="id" class="del-id">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">いいえ</button>
              <button type="submit" class="btn btn-danger">削除</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- ここまで -->
  </div>
</body>

<?php require_once './../inc/footer.php'; ?>