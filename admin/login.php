<?php
require_once __DIR__ . "/../inc/function.php";
?>

<?php require_once './../inc/header.php'; ?>

<body>
    <main class="l-wrapper">
        <h1 class="c-title">ログイン</h1>
        <form action="./login.php" method="post">
            <div class="row justify-content-center">
                <div class="mb-3 col-6">
                    <label for="user_name" class="form-laber">ユーザー名</label>
                    <input type="text" name="user_name" id="user_name" class="form-control" autocomplete="user_name" placeholder="半角英数字●●字以上">
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="mb-4 col-6">
                    <label for="user_name" class="form-laber">パスワード</label>
                    <input type="text" name="password" id="password" class="form-control" autocomplete="password" placeholder="半角英数字●●字以上">
                </div>
            </div>
            <div class="text-center">
                <input type="submit" value="ログイン" class="btn btn-primary">
            </div>

        </form>
    </main>
</body>

<?php require_once './../inc/footer.php'; ?>