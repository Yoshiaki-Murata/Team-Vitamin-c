<?php

require_once __DIR__ . '/../inc/function.php';

$db = db_connect();
$id = $_GET["id"];
$sql = "SELECT name,login_id FROM admins WHERE id=:id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$user = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once './../inc/header_admin.php';
?>

<body>
    <div class="l-wrapper">
        <h1 class="c-title">管理者名編集</h1>
        <form action="master_add_do.php" method="post">
            <table class="table mx-auto">
                <thead>
                    <tr class="row">
                        <th class="col"></th>
                        <th class="col">現在の登録</th>
                        <th class="col">変更後</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($user as $cat): ?>
                        <tr class="row p-4">
                            <th class="fw-bold col">管理者名</th>
                            <td class="col"><?php echo $cat["name"]; ?></td>
                            <td class="col">
                                <label for="name">
                                    <input type="text" class="form-control" id="name" name="name">
                                </label>
                            </td>
                        </tr>
                        <tr class="row p-4">
                            <th class="fw-bold col">ログインID</th>
                            <td class="col"><?php echo $cat["login_id"]; ?></td>
                            <td class="col">
                                <label for="login-id">
                                    <input type="text" class="form-control" id="login-id" name="login_id">
                                </label>
                            </td>
                        </tr>
                        <tr class="row p-4">
                            <th class="fw-bold col">パスワード 数字8桁</th>
                            <td class="col">********</td>
                            <td class="col">
                                <label for="pass">
                                    <input type="text" class="form-control" id="pass" name="pass">
                                </label>
                            </td>
                        </tr>
                        <tr class="row p-2">
                            <td class="text-center">
                                <input type="submit" value="変更" class="btn btn-primary col">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center"><a href="masters.php" class="btn btn-info">管理者一覧に戻る</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>

    </div>
</body>

<?php require_once './../inc/footer.php'; ?>