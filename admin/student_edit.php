<?php
require_once __DIR__ . '/../inc/function.php';

$db = db_connect();

// id取得
$id = $_GET['id'] ?? '';

if ($id === '' || !is_numeric($id)) {
	exit('不正なアクセスです');
}

try {
	// 学生データ取得
	$sql = "SELECT * FROM students WHERE id = :id";
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
	$student = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$student) {
		exit('データが見つかりません');
	}

	$classes = $db->query("SELECT * FROM classes")->fetchAll(PDO::FETCH_ASSOC);
	$courses = $db->query("SELECT * FROM courses")->fetchAll(PDO::FETCH_ASSOC);
	$statuses = $db->query("SELECT * FROM student_status")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	exit('エラー: ' . $e->getMessage());
}

require_once './../inc/header_admin.php';
?>

<body>
	<div class="l-wrapper">

		<h1 class="c-title">訓練生 登録内容修正</h1>
		<p>※入力項目はすべて必須です</p>
		<form action="student_edit_do.php" method="post">

			<input type="hidden" name="id" value="<?php echo h($student['id']); ?>">

			<!-- 教室 -->
			<label>教室</label>
			<select name="class_id" class="form-control mb-3" required>
				<?php foreach ($classes as $class): ?>
					<option value="<?php echo $class['id']; ?>"
						<?php if ($class['id'] == $student['class_id']) echo 'selected'; ?>>
						<?php echo h($class['name']); ?>
					</option>
				<?php endforeach; ?>
			</select>

			<!-- 番号 -->
			<label>番号</label>
			<input type="text" name="number" class="form-control mb-3" required
				value="<?php echo h($student['number']); ?>" placeholder="半角数字2桁 例：01">

			<!-- 名前 -->
			<label>名前</label>
			<input type="text" name="name" class="form-control mb-3" required
				value="<?php echo h($student['name']); ?>" placeholder="例：リカレント太郎">

			<!-- 種別 -->
			<label>訓練種別</label>
			<select name="course_id" class="form-control mb-3" required>
				<?php foreach ($courses as $course): ?>
					<option value="<?php echo $course['id']; ?>"
						<?php if ($course['id'] == $student['course_id']) echo 'selected'; ?>>
						<?php echo h($course['name']); ?>
					</option>
				<?php endforeach; ?>
			</select>

			<!-- 日付 -->
			<label>入校日</label>
			<input type="date" name="admission_date" class="form-control mb-3" required
				value="<?php echo h($student['admission_date']); ?>">

			<label>修了予定日</label>
			<input type="date" name="graduation_date" class="form-control mb-3" required
				value="<?php echo h($student['graduation_date']); ?>">

			<!-- ログインID -->
			<label>ログインID</label>
			<input type="text" name="login_id" class="form-control mb-3" required
				value="<?php echo h($student['login_id']); ?>" placeholder="入校年＋入校月＋教室名＋出席番号(例：2026056A01)">

			<!-- パスワード -->
			<label>パスワード</label>
			<input type="text" name="password" class="form-control mb-3" required
				value="<?php echo h($student['password']); ?>" placeholder="数字8桁">

			<!-- 在籍状況 -->
			<label>在籍状況</label>
			<select name="status_id" class="form-control mb-4" required>
				<?php foreach ($statuses as $status): ?>
					<option value="<?php echo $status['id']; ?>"
						<?php if ($status['id'] == $student['status_id']) echo 'selected'; ?>>
						<?php echo h($status['name']); ?>
					</option>
				<?php endforeach; ?>
			</select>

			<div class="mb-5 text-center">
				<button type="submit" class="btn btn-primary">更新</button>
				<a href="students.php" class="btn btn-secondary">戻る</a>
			</div>
		</form>

	</div>
</body>

<?php require_once './../inc/footer.php'; ?>