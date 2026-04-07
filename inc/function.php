<?php
session_start();
require_once __DIR__ . "/db_info.php";

// DB接続
function db_connect(){
    $dsn="mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4";
    $db=new PDO($dsn,DB_USER,DB_PASS);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
    return $db;
}

// var_dump関数
function check_array($a){
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
function he($dir){
    header("location:".$dir);
    exit();
}