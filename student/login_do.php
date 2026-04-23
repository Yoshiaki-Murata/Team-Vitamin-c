<?php
require_once __DIR__ . ('/../inc/function.php');
session_start();

if (!empty($_POST)) {
    if (!empty($_POST['login_id']) && !empty($_POST['password'])) {
        $login_id = $_POST['login_id'];
        $password = $_POST['password'];

        try {
            $db = db_connect();
            $sql = 'SELECT * FROM students 
            WHERE login_id=:login_id';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':login_id', $login_id, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            check_array($result);

            if ($result) {
                if ($password == $result['password']) {
                    $_SESSION['user_id'] = $result['id'];
                    $_SESSION['user_name'] = $result['name'];
                    $_SESSION["user_class_id"] = $result["class_id"];
                    $_SESSION['user_course_id'] = $result['course_id'];
                    header('location:index.php');
                    exit();
                }
            }
            session_err_msg("ログインに失敗しました");
            header('location:login.php');
            exit();
        } catch (PDOException $e) {
            exit('エラー: ' . $e->getMessage());
        }
    }
}
header('location:login.php');
exit();
