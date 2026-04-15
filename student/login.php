<?php
require_once __DIR__ . "/../inc/function.php";
?>

<?php require_once './../inc/header.php'; ?>

<body>
    <main class="l-wrapper">
        <h1 class="c-title">ログイン</h1>
        <form action="./login_do.php" method="post">
            <div class="row justify-content-center">
                <div class="mb-3 col-6">
                    <label for="login_id" class="form-laber">ログインID</label>
                    <input type="text" name="login_id" id="login_id" class="form-control" autocomplete="login_id" placeholder="半角英数字●●字以上">
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="mb-4 col-6">
                    <label for="password" class="form-laber">パスワード</label>
                    <input type="password" name="password" id="password" class="form-control" autocomplete="password" placeholder="半角英数字●●字以上">
                </div>
            </div>
            <div class="text-center">
                <input type="submit" value="ログイン" class="btn btn-primary">
            </div>

        </form>
    </main>
</body>

