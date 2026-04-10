<?php
session_start();
require_once __DIR__ . "/db_info.php";

// DB接続
function db_connect()
{
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $db = new PDO($dsn, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    return $db;
}

// var_dump関数
function check_array($a)
{
    echo "<pre>";
    var_dump($a);
    echo "</pre>";
}

// エスケープ処理
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// header関数
function he($dir)
{
    header("location:" . $dir);
    exit();
}

// カラム取得関数（引数にテーブル名とカラム名を入力すると連想配列として取得する）
// ＜例＞getColumn($db, 'times', 'time');で時間を連想配列として取得
function getColumn($db, $table, $column)
{
    $sql = "SELECT $column FROM $table";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
