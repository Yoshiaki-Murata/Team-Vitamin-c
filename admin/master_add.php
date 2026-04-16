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
        <h1 class="c-title">管理者新規登録</h1>
        <form action="master_add_do.php" method="post">
            <table class="table mx-auto">
                <tbody>
                    <tr class="row p-4">
                        <td class="text-center">
                            <label for="name" class="col">
                                <p class="fw-bold">管理者名</p>
                                <input type="text" class="form-control" id="name" name="name">
                            </label>
                        </td>
                    </tr>
                    <tr class="row p-4">
                        <td class="text-center">
                            <label for="login-id" class="col">
                                <p class="fw-bold">ログインID</p>
                                <input type="text" class="form-control" id="login-id" name="login_id">
                            </label>
                        </td>
                    </tr>
                    <tr class="row p-4">
                        <td class="text-center">
                            <label for="pass" class="col">
                                <p class="fw-bold">パスワード<span class="fw-normal"> ※数字8桁</span></p>
                                <input type="text" class="form-control" id="pass" name="pass">
                            </label>
                        </td>
                    </tr>
                    <tr class="row p-2">
                        <td class="text-center">
                            <input type="submit" value="登録" class="btn btn-primary col">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center"><a href="masters.php" class="btn btn-info">管理者一覧に戻る</a></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</body>

<?php require_once './../inc/footer.php'; ?>