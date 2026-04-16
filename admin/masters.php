<?php

require_once __DIR__ . '/../inc/function.php';

$db = db_connect();
$sql = "SELECT * FROM admins";
$stmt = $db->prepare($sql);
$stmt->execute();
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);


require_once './../inc/header_admin.php';
?>
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
                    <td class="col js-name"><?php echo $person["name"]; ?></td>
                    <td class="col"><?php echo $person["login_id"] ?></td>
                    <td class="col"><a href="master_edit.php?id=<?php echo $person["id"]; ?>" class="btn btn-warning">編集</a></td>
                    <td class="col"><a class="btn btn-danger js-del-btn" data-id="<?php echo $person["id"]; ?>">削除</a></td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>

<?php require_once './../inc/footer.php'; ?>
<script>
    const delBtns = document.querySelectorAll('.js-del-btn');
    // const names = document.querySelectorAll('.js-name');

    delBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            let confirmText = confirm('削除してよろしいですか？');
            if (confirmText) {
                // はいを押したらdel_doに飛ぶ
                const delUser = btn.dataset.id;
                location.href = 'master_del_do.php?id=' + delUser;
            }
        })
    });
</script>

</html>