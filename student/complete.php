<?php
require_once __DIR__ . "/../inc/function.php";

$name = $_SESSION["user_name"];
$login_id = $_SESSION["user_id"];
$db = db_connect();

?>

<?php include __DIR__ . "/../inc/header_student.php" ?>

<body>
    <main class="l-wrapper">
        <div class="d-flex align-items-center flex-column">
            <p>送信完了しました！</p>
            <a href="index.php" class="btn btn-primary">TOPへ戻る</a>
        </div>
    </main>
    <script src="./../js/student.js"></script>
</body>