<?php
session_start();
require_once __DIR__ . ('/../inc/function.php');

//POSTが受け取れるか
if (!empty($_POST)) {
    if (!empty($_POST['login_id']) && !empty($_POST['password'])) {
        $login_id = $_POST['login_id'];
        $password = $_POST['password'];

        try {
            $db = db_connect();
            $sql = 'SELECT * FROM admins 
            WHERE login_id=:login_id';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':login_id', $login_id, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);


            if ($result) {
                if ($password == $result['password']) {
                    $_SESSION['login_id'] = $result['login_id'];
                    header('location:index.php');
                    exit();
                }
            }
        } catch (PDOException $e) {
            exit('エラー: ' . $e->getMessage());
        }
    }
}
header('location:login.php');
exit();
