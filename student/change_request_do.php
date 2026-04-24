<?php
require_once __DIR__ . '/../inc/function.php';
check_logined_student();

if (!empty($_POST)) {
  if (!empty($_POST['text']) && !empty($_POST['reserve_id'])) {
    $text = $_POST['text'];
    $reserve_id = $_POST['reserve_id'];

    try {
      $db = db_connect();

      //申請時点の情報をすべて取得
      $sql_select = "SELECT 
                rs.date AS res_date, 
                t.time AS res_time, 
                l.line AS res_line, 
                s.name AS res_student_name, 
                c.name AS res_class_name, 
                con.name AS res_consultant_name, 
                m.name AS res_method_name,
                rs.carecon_id
                FROM reservation_infos ri
                INNER JOIN reservation_slots rs ON ri.slot_id = rs.id
                INNER JOIN times t ON rs.time_id = t.id
                INNER JOIN carecon_lines l ON rs.lines_id = l.id
                INNER JOIN students s ON ri.student_id = s.id
                INNER JOIN methods m ON ri.method_id = m.id
                LEFT JOIN classes c ON rs.class_id = c.id
                LEFT JOIN consultants con ON rs.consultant_id = con.id
                WHERE ri.id = :reserve_id";

      $stmt_select = $db->prepare($sql_select);
      $stmt_select->execute([':reserve_id' => $reserve_id]);
      $snapshot = $stmt_select->fetch(PDO::FETCH_ASSOC);
      check_array($snapshot);

      //取得したコピー情報を apply_lists に挿入

      $sql_insert = "INSERT INTO apply_lists (
                reserve_info_id, apply_detail, apply_status_id, apply_datetime,
                res_date, res_time, res_line, res_student_name, 
                res_class_name, res_consultant_name, res_method_name, carecon_id
            ) VALUES (
                :reserve_id, :text, 1, :apply_datetime,
                :res_date, :res_time, :res_line, :res_student_name, 
                :res_class_name, :res_consultant_name, :res_method_name, :carecon_id
            )";

      $stmt = $db->prepare($sql_insert);


      date_default_timezone_set('Asia/Tokyo');
      $stmt->execute([
        ':reserve_id'           => $reserve_id,
        ':text'                 => $text,
        ':apply_datetime'       => date('Y-m-d H:i:s'),
        ':res_date'             => $snapshot['res_date'],
        ':res_time'             => $snapshot['res_time'],
        ':res_line'             => $snapshot['res_line'],
        ':res_student_name'     => $snapshot['res_student_name'],
        ':res_class_name'       => $snapshot['res_class_name'] ?? "",
        ':res_consultant_name'  => $snapshot['res_consultant_name'] ?? "",
        ':res_method_name'      => $snapshot['res_method_name'],
        ':carecon_id'           => $snapshot['carecon_id']
      ]);
      header('location:complete.php');
      exit();
    } catch (PDOException $e) {
      exit('エラー: ' . $e->getMessage());
    }
  }
}
