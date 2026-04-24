<?php
require_once __DIR__ . "/../inc/function.php";
check_logined_student();

$date = $_GET["date"];
$class_id = $_SESSION["user_class_id"];
$login_id = $_SESSION["user_id"];
$db = db_connect();
try {
    $sql = "SELECT rs.date,t.time,c.name AS class_name,s.name
            FROM reservation_infos ri 
            INNER JOIN reservation_slots rs ON ri.slot_id=rs.id
            INNER JOIN  students s ON ri.student_id = s.id
            INNER JOIN methods m ON ri.method_id= m.id
            INNER JOIN classes c ON s.class_id=c.id
            INNER JOIN student_status ss ON s.status_id =ss.id
            INNER JOIN carecons cr ON rs.carecon_id = cr.id
            INNER JOIN times t ON rs.time_id = t.id
            WHERE cr.id=1
            AND rs.date=:date 
            AND c.id=:class_id
            AND rs.reserve_status_id=2
            AND ri.student_id !=:login_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    $stmt->bindParam(":class_id", $class_id, PDO::PARAM_INT);
    $stmt->bindParam(":login_id", $login_id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
