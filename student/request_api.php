<?php
require_once __DIR__ . "/../inc/function.php";
$db = db_connect();

// パラメータの取得
$date = $_GET['date'] ?? '';
$time = $_GET['time'] ?? '';
$user_id = $_SESSION["user_id"];

try {
    if ($date && $time) {
        // --- 1. 特定の時間帯の詳細情報を返す処理 ---
        $sql = "SELECT 
                    rs.id AS slot_id,
                    rs.date,
                    t.time,
                    c.name AS class_name,
                    con.name AS consultant_name
                FROM reservation_slots rs
                LEFT JOIN times t ON rs.time_id = t.id
                LEFT JOIN classes c ON rs.class_id = c.id
                LEFT JOIN consultants con ON rs.consultant_id = con.id
                WHERE rs.date = :date 
                  AND t.time = :time 
                  AND rs.carecon_id= 2
                  AND rs.reserve_status_id = 1";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':date', $date, PDO::PARAM_STR);
        $stmt->bindValue(':time', $time, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else if ($date) {
        // --- 2. その日の時間ごとの空き枠数を返す処理  ---
        $sql = "SELECT 
                    t.time,
                    COUNT(rs.id) AS reserve_count
                FROM times t
                LEFT JOIN reservation_slots rs ON t.id = rs.time_id 
                    AND rs.date = :date 
                    AND rs.reserve_status_id = 1
                    AND rs.carecon_id= 2
                GROUP BY t.id, t.time
                ORDER BY t.id ASC";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':date', $date, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $data = ["error" => "Date parameter is missing"];
    }

    header('Content-Type: application/json');
    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

