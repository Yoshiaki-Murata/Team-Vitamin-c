<!-- <?php
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
WHERE ri.student_id=:login_id AND ri.method_id=1";
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
WHERE ri.student_id=:login_id AND ri.method_id=2";
            $sql_plus = "SELECT ri.id AS reserve_id,rs.date,t.time,m.name AS method_name,c.name AS class_name FROM `reservation_infos` ri
INNER JOIN reservation_slots rs ON ri.slot_id=rs.id
INNER JOIN times t ON rs.time_id = t.id
INNER JOIN methods m ON ri.method_id=m.id
INNER JOIN classes c ON rs.class_id = c.id
INNER JOIN carecons ON rs.carecon_id= carecons.id
WHERE ri.student_id=:login_id
AND rs.carecon_id=2";
            $stmt_plus = $db->prepare($sql_plus);
            $stmt_plus->bindParam(":login_id", $login_id, PDO::PARAM_INT);
            $stmt_plus->execute();
            $result_plus = $stmt_plus->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "エラ‐" . $e->getMessage();
        }
        ?> -->

<?php include __DIR__ . "/../inc/header_student.php" ?>

<body>
    <!-- <?php check_array($result_must); ?> -->
    <!-- <?php check_array($result_plus); ?> -->
    <main class="l-wrapper">
        <div class="mb-5">
            <h1 class="c-title">トップページ</h1>
            <p>ようこそ<?php echo "  ".$_SESSION["user_name"]."  "; ?>さん</p>
        </div>
        <div class="mb-5">
            <div class="row">
                <h2 class="mb-3 col-auto">キャリコン予約状況</h2>
                <button class="btn btn-primary btn-sm col-auto" id="mReserveBtn">予約状況確認</button>
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
                    <p>予約はありません</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-4">
            <h2 class="mb-3">キャリコンプラス予約状況</h2>
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
                    <p>予約はありません</p>
                <?php endif; ?>
            </div>
            <div class="text-center">
                <a href="./request.php" class="btn btn-warning">予約する</a>
            </div>
        </div>
    </main>
    <script src="./../js/script.js"></script>
</body>