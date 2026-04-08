<!-- <?php
        require_once __DIR__ . "/../inc/function.php";

        $name = $_SESSION["user_name"];
        $id = $_SESSION["user_id"];
        $db = db_connect();
        try {
            // 必須キャリコンの情報を取得
            $sql_must = "SELECT ri.id AS reserve_id,rs.date,t.time,m.name AS method_name,c.name AS class_name FROM `reservation_infos` ri
INNER JOIN reservation_slots rs ON ri.slot_id=rs.id
INNER JOIN times t ON rs.time_id = t.id
INNER JOIN methods m ON ri.method_id=m.id
INNER JOIN classes c ON rs.class_id = c.id
INNER JOIN carecons ON rs.carecon_id= carecons.id
WHERE ri.student_id=:id
AND carecons.id=1";
            $stmt_must = $db->prepare($sql_must);
            $stmt_must->bindParam(":user_id", $id, PDO::PARAM_INT);
            $stmt_must->execute();
            $result_must = $stmt_must->fetchAll(PDO::FETCH_ASSOC);

            // キャリコンプラスの情報を取得
            $sql_plus = "SELECT ri.id AS reserve_id,rs.date,t.time,m.name AS method_name,c.name AS class_name FROM `reservation_infos` ri
INNER JOIN reservation_slots rs ON ri.slot_id=rs.id
INNER JOIN times t ON rs.time_id = t.id
INNER JOIN methods m ON ri.method_id=m.id
INNER JOIN classes c ON rs.class_id = c.id
INNER JOIN carecons ON rs.carecon_id= carecons.id
WHERE ri.student_id=:id
AND rs.carecon_id=2";
            $stmt_plus = $db->prepare($sql_plus);
            $stmt_plus->bindParam(":user_id", $id, PDO::PARAM_INT);
            $stmt_plus->execute();
            $result_plus = $stmt_plus->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "エラ‐" . $e->getMessage();
        }

        ?> -->

<?php include __DIR__ . "/../inc/header.php" ?>
<main>
    <div class="mb-5">
        <h1>トップページ</h1>
        <p>ようこそ●●さん</p>
    </div>

    <div class="mb-5">
        <div class="row">
            <h2 class="mb-3 col-auto">キャリコン予約状況</h2>
            <button class="btn btn-primary btn-sm col-auto" id="mReserveBtn">予約状況確認</button>
        </div>

        <div>
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
                    <tr class="row">
                        <td class="col-2">5/17</td>
                        <td class="col-2">10:00～</td>
                        <td class="col-3">対面</td>
                        <td class="col-2">6C</td>
                        <td class="col-3">
                            <form action="./reserve_del.php" method="post">
                                <input type="hidden" name="reserve-id" id="reserve-id">
                                <input type="submit" value="変更申請" class="btn btn-sm btn-danger">
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mb-4">
        <h2 class="mb-3">キャリコンプラス予約状況</h2>
        <div class="mb-3">
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
                    <tr class="row">
                        <td class="col-2">5/17</td>
                        <td class="col-2">10:00～</td>
                        <td class="col-3">対面</td>
                        <td class="col-2">6C</td>
                        <td class="col-3">
                            <form action="./reserve_del.php" method="post">
                                <input type="hidden" name="reserve-id" id="reserve-id">
                                <input type="submit" value="キャンセル申請" class="btn btn-sm btn-danger">
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-center">
            <a href="./reserve_add.php" class="btn btn-warning">予約する</a>
        </div>
    </div>

    <!-- モ‐ダル -->
    <dialog class="modal">
        <div class="modal-content">
            <select name="date" id="date" class="mb-3 d-inline-block form-select w-auto">
                <option value="2026-05-09">2026/5/9</option>
                <option value="2026-05-16">2026/5/16</option>
                <option value="2026-05-23">2026/5/23</option>
            </select>
            <table>
                <thead>
                    <tr>
                        <th>時間</th>
                        <th>予約者</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>10:00</td>
                        <td>田中</td>
                    </tr>
                    <tr>
                        <td>11:00</td>
                        <td>田中</td>
                    </tr>
                    <tr>
                        <td>12:00</td>
                        <td>田中</td>
                    </tr>
                    <tr>
                        <td>13:00</td>
                        <td>田中</td>
                    </tr>
                    <tr>
                        <td>14:00</td>
                        <td>田中</td>
                    </tr><tr>
                        <td>15:00</td>
                        <td>田中</td>
                    </tr>
                    <tr>
                        <td>16:00</td>
                        <td>田中</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </dialog>
</main>