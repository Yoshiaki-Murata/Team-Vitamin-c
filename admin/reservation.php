<?php
require_once __DIR__ . '/../inc/function.php';
$db = db_connect();
$reservations = [];
$methods = [];
$students = [];
$classes = [];
try {
  // 予約一覧 
  $sql = 'SELECT 
  reservation_slots.id AS slot_id, 
  reservation_infos.id AS reservation_id, 
  reservation_slots.date, 
  times.time AS reserve_time,
   classes.name AS reserve_class, 
   consultants.name AS reserve_consultant, 
   students.name AS reserve_student, 
   students.class_id AS student_class_id,
   methods.name AS reserve_method, 
   carecons.name AS reserve_carecon, 
   carecon_lines.line AS reserve_line, 
   reserve_status.name AS reservation_status ,
   students.id AS student_id,
  methods.id AS method_id,
  classes.id AS class_id
   FROM reservation_slots
    LEFT JOIN reservation_infos ON reservation_infos.slot_id = reservation_slots.id 
    INNER JOIN times ON reservation_slots.time_id = times.id 
    LEFT JOIN classes ON reservation_slots.class_id = classes.id 
    LEFT JOIN consultants ON reservation_slots.consultant_id = consultants.id 
    LEFT JOIN students ON reservation_infos.student_id = students.id 
    LEFT JOIN methods ON reservation_infos.method_id = methods.id 
    INNER JOIN carecons ON reservation_slots.carecon_id = carecons.id 
    INNER JOIN carecon_lines ON reservation_slots.lines_id = carecon_lines.id 
    INNER JOIN reserve_status ON reservation_slots.reserve_status_id = reserve_status.id
     ORDER BY reservation_slots.date ASC, 
     reservation_slots.lines_id ASC, 
     reservation_slots.time_id ASC';
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // 実施方法 
  $stmt = $db->prepare('SELECT * FROM methods ORDER BY id ASC');
  $stmt->execute();
  $methods = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // 訓練生（class_id含む） 
  $sql_student = 'SELECT students.id, students.name, students.class_id FROM students';
  $stmt = $db->prepare($sql_student);
  $stmt->execute();
  $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // クラス 
  $stmt = $db->prepare('SELECT * FROM classes ORDER BY id ASC');
  $stmt->execute();
  $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $err_msg = 'データの取得に失敗しました:' . $e->getMessage();
}

require_once './../inc/header_admin.php'; ?>

<body>
  <div class="l-wrapper">
    <h1 class="c-title">予約情報一覧</h1>
    <table class="table mb-5">
      <thead>
        <tr>
          <th>ライン</th>
          <th>日付</th>
          <th>時間</th>
          <th>訓練生名</th>
          <th>教室</th>
          <th>担当講師</th>
          <th>実施方法</th>
          <th>予約ステータス</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reservations as $row): ?> <tr>
            <td><?php echo h($row['reserve_line']) ?></td>
            <td><?php echo h($row['date']) ?></td>
            <td><?php echo h($row['reserve_time']) ?></td>
            <td>
              <?php echo $row['reserve_student'] ? h($row['reserve_student']) : '-' ?>
            </td>
            <td>
              <?php echo $row['reserve_class'] ? h($row['reserve_class']) : '未定' ?>
            </td>
            <td>
              <?php echo $row['reserve_consultant'] ? h($row['reserve_consultant']) : '未定' ?>
            </td>
            <td>
              <?php echo $row['reserve_method'] ? h($row['reserve_method']) : '-' ?>
            </td>
            <td><?php echo h($row['reservation_status']) ?></td>
            <td>
              <?php if ($row['reservation_id']): ?>
                <button class="btn btn-primary edit-btn"
                  data-bs-toggle="modal"
                  data-bs-target="#editReserveModal"
                  data-id="<?php echo h($row['reservation_id']) ?>"
                  data-student-id="<?php echo h($row['student_id']) ?>"
                  data-method-id="<?php echo h($row['method_id']) ?>"
                  data-student-class-id="<?php echo h($row['student_class_id']) ?>">
                  変更
                </button>
                <button class="btn btn-danger del-btn" data-bs-toggle="modal" data-bs-target="#delReserveModal"
                  data-id="<?php echo h($row['reservation_id']) ?>">
                  削除
                </button>
              <?php else: ?>
                <button class="btn btn-warning add-btn" data-bs-toggle="modal" data-bs-target="#addReserveModal"
                  data-id="<?php echo h($row['slot_id']) ?>">
                  追加
                </button>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- 予約追加モーダル -->
    <div class="modal fade" id="addReserveModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="./reservation_add_do.php" method="post">
            <div class="modal-header">
              <h5 class="modal-title">新規予約追加</h5>
              <button type="button"
                class="btn-close"
                data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="slot_id" id="add-slot-id">

              <!-- クラス -->
              <div class="mb-3">
                <label>クラス</label>
                <select name="class_id" class="class-select form-select">
                  <option value="">選択してください</option>
                  <?php foreach ($classes as $class): ?>
                    <option value="<?php echo h($class['id']) ?>">
                      <?php echo h($class['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- 訓練生 -->
              <div class="mb-3">
                <label>訓練生</label>
                <select name="student_id" class="student-select form-select">
                  <option value="">選択してください</option>
                  <?php foreach ($students as $student): ?>
                    <option value="<?php echo h($student['id']) ?>" data-class-id="<?php echo h($student['class_id']) ?>">
                      <?php echo h($student['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- 実施方法 -->
              <div class="mb-3">
                <label>実施方法</label>
                <select name="method_id" class="form-select">
                  <option value="">選択してください</option>
                  <?php foreach ($methods as $method): ?>
                    <option value="<?php echo h($method['id']) ?>"> <?php echo h($method['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                キャンセル
              </button>
              <button type="submit" class="btn btn-primary">
                追加
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- ここまで -->

    <!-- 変更モーダル -->
    <div class="modal fade" id="editReserveModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">予約情報変更</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <form action="./reservation_edit_do.php" method="post">
            <div class="modal-body px-4">
              <input type="hidden" name="id" id="edit-id">
              <!-- クラス -->
              <div class="mb-3">
                <label class="form-label">クラス</label>
                <select name="class_id" class="class-select form-select">
                  <option value="" disabled>選択してください</option> <?php foreach ($classes as $class): ?>
                    <option value="<?php echo h($class['id']) ?>">
                      <?php echo h($class['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <!-- 訓練生 -->
              <div class="mb-3">
                <label>訓練生</label>
                <select name="student_id" class="student-select form-select">
                  <option value="">選択してください</option>
                  <?php foreach ($students as $student): ?>
                    <option value="<?php echo h($student['id']) ?>" data-class-id="<?php echo h($student['class_id']) ?>">
                      <?php echo h($student['name']) ?>
                    </option> <?php endforeach; ?>
                </select>
              </div>
              <!-- 実施方法 -->
              <div class="mb-3">
                <label>実施方法</label>
                <select name="method_id" class="form-select">
                  <option value="">選択してください</option>
                  <?php foreach ($methods as $method): ?>
                    <option value="<?php echo h($method['id']) ?>">
                      <?php echo h($method['name']) ?>
                    </option> <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <input type="submit" value="更新" class="btn btn-primary">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                閉じる
              </button>
            </div>
        </div>
      </div>
      </form>
    </div>
  </div>
  </div>
  <!-- ここまで -->

  <!-- 削除モーダル -->
  <div class="modal fade" id="delReserveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="./reservation_del_do.php" method="post">
          <div class="modal-header">
            <h5 class="modal-title">削除確認</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>この予約を削除しますか？</p>
            <input type="hidden" name="id" id="del-id">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              いいえ
            </button>
            <button type="submit" class="btn btn-danger">
              削除
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- ここまで -->
  </div>

  <script>
    document.querySelectorAll('.class-select').forEach(classSelect => {
      classSelect.addEventListener('change', function() {

        const selected = this.value;

        // 同じモーダル内だけ
        const modal = this.closest('.modal');
        const studentSelect = modal.querySelector('.student-select');

        studentSelect.querySelectorAll('option').forEach(opt => {
          if (!opt.value) return;

          opt.style.display =
            (!selected || opt.dataset.classId === selected) ? '' : 'none';
        });

        studentSelect.value = '';
      });
    });

    // 追加 
    document.querySelectorAll('.add-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('add-slot-id').value = btn.getAttribute('data-id');
      });
    });

    // 変更
    document.querySelectorAll('.edit-btn').forEach(btn => {
      btn.addEventListener('click', () => {

        const modal = document.getElementById('editReserveModal');

        const classId = btn.dataset.studentClassId;
        const studentId = btn.dataset.studentId;

        const classSelect = modal.querySelector('.class-select');
        const studentSelect = modal.querySelector('.student-select');

        // クラスセット
        classSelect.value = classId;

        // フィルタ発動
        studentSelect.querySelectorAll('option').forEach(opt => {
          if (!opt.value) return;

          opt.style.display =
            (!classId || opt.dataset.classId == classId) ? '' : 'none';
        });
        // 訓練生セット
        studentSelect.value = studentId;
        // method
        modal.querySelector('[name="method_id"]').value = btn.dataset.methodId;
        // id
        modal.querySelector('#edit-id').value = btn.dataset.id;
      });
    });

    // 削除 
    document.querySelectorAll('.del-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('del-id').value = btn.getAttribute('data-id');
      });
    });
  </script> <?php require_once './../inc/footer.php'; ?>