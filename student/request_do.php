<?php
require_once __DIR__ . "/../inc/function.php";
header("Content-Type:application/json");

$date=$_GET["date"];
$db=db_connect();

try{
    // 予約されている日付情報を取得
    $sql_date = "SELECT 
    t.time, 
    COUNT(CASE WHEN rs.reserve_status_id = 1 THEN 1 END) AS reserve_count
FROM times t
LEFT JOIN reservation_slots rs 
    ON t.id = rs.time_id 
    AND rs.date = :date
    AND rs.carecon_id= 2
GROUP BY t.id
ORDER BY t.time ASC;";
    $stmt_date = $db->prepare($sql_date);
    $stmt_date->bindParam(":date",$date,PDO::PARAM_STR);
    $stmt_date->execute();
    $result_date = $stmt_date->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result_date);

}catch(PDOException $e){
    echo "エラー".$e->getMessage();
}