<?php

require_once __DIR__ . '/../inc/function.php';

$db = db_connect();
$sql = "SELECT name,login_id FROM admins";
$stmt = $db->prepare($sql);
$stmt->execute();
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);


require_once './../inc/header_admin.php';
?>

<body>
    <div class="l-wrapper">
        <h1 class="c-title">管理者一覧</h1>
        <a href="master_add.php" class="btn btn-primary">管理者新規登録</a>
        <table class="table">
            <thead>
                <tr class="row">
                    <th class="col">名前</th>
                    <th class="col">ログインID</th>
                    <th class="col"></th>
                    <th class="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $person): ?>
                    <tr class="row">
                        <td class="col"><?php echo $person["name"]; ?></td>
                        <td class="col"><?php echo $person["login_id"] ?></td>
                        <td><a class="btn btn-warning col">編集</a></td>
                        <td><a class="btn btn-danger col">削除</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

<?php require_once './../inc/footer.php'; ?>