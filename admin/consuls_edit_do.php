<?php
require_once __DIR__ . '/../inc/function.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];

    // 講師の名前、漢字、ひらがな、カタカナ、長音記号を1文字以上
    //  半角または全角スペースがあってもなくても良い（0回または1回）
    if (!$name) {
        exit('名前が未入力です。');
    }
    if (mb_strlen($name) > 20 || mb_strlen($name) < 2) {
        exit('2文字以上、20文字以内で入力してください。');
    }
    if (!preg_match('/^[一-龠ぁ-んァ-ヶー]+[ 　]?[一-龠ぁ-んァ-ヶー]+$/', $name)) {
        //header('location:consuls.php');
        exit('名前を正しく入力');
    }

    try {
        $db = db_connect();

        $sql = "UPDATE consultants SET name = :name WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: consuls.php');
        exit();
    } catch (PDOException $e) {
        exit('エラー: ' . $e->getMessage());
    }
}
