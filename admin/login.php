<?php
require_once __DIR__ . "/../inc/function.php";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- リセットCSS -->
    <link rel="stylesheet" href="https://unpkg.com/destyle.css@3.0.2/destyle.min.css">
    <link rel="stylesheet" href="./../css/style.css">
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <title>管理ログイン</title>
</head>

<body>
    <main class="l-wrapper">
        <!-- ログインメッセージ -->
        <?php if (!empty($_SESSION["msg"])): ?>
            <p class="alert alert-danger text-center mx-auto col-6" role="alert">
                <?php echo h($_SESSION["msg"]);
                unset($_SESSION["msg"]);
                ?>
            </p>
        <?php endif ?>
        <!-- ここまで -->
        <h1 class="c-title">管理者ログイン</h1>
        <form action="login_do.php" method="post">
            <div class="row justify-content-center">
                <div class="mb-3 col-6">
                    <label for="login_id" class="form-laber">ログインID</label>
                    <input type="text" name="login_id" id="login_id" class="form-control" autocomplete="login_id" placeholder="半角英数字10字">
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="mb-4 col-6">
                    <label for="login_id" class="form-laber">パスワード</label>
                    <input type="password" name="password" id="password" class="form-control" autocomplete="password" placeholder="半角数字8字">
                </div>
            </div>
            <div class="text-center">
                <input type="submit" value="ログイン" class="btn btn-primary">
            </div>

        </form>
    </main>
</body>

<?php require_once './../inc/footer.php'; ?>