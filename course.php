<?php
include 'core/init.php';
protect_page();
include 'includes/overall/header.php';
?>

<div class="course">
	<?php
	if (isset($_GET['course_id']) === true && empty($_GET['course_id']) === false) {
		$course_id = $_GET['course_id'];
		if (course_exists($course_id) === true) {
			$course_data = course_data($course_id, 'title', 'description', 'category', 'start_date', 'duration', 'creator', 'visible');
			$teacher_data = user_data($course_data['creator'], 'username', 'first_name', 'last_name');
			if (user_teacher($user_data['user_id']) === true && $user_data['user_id'] === $course_data['creator']) {
				$is_creator = true;
			} else {
				$is_creator = false;
			}
			?>
			<p>
				<a href="index.php">&lt;&lt; Back</a>
			</p>
			<h1 class="coursetitle"><?php echo $course_data['title']; ?></h1>
			<?php
			if ($is_creator === true) {
				echo '<button onclick="delete_confirm()">Delete Course</button>';
				if ($course_data['visible'] == true) {
					echo ' <button onclick="delete_confirm()">Hide Course</button>';
				} else {
					echo ' <button onclick="delete_confirm()">Publish Course</button>';
				}
			}
			?>
			<br />
			<p>Description: <?php echo $course_data['description']; ?></p>
			<ul>
				<li>Teacher: <?php echo $teacher_data['first_name'], ' ', $teacher_data['last_name']; ?></li>
				<li>Category: <?php echo category_name_from_id($course_data['category']); ?></li>
				<li>Start date: <?php echo $course_data['start_date']; ?></li>
			</ul>
			<p>
				<ul>
					<li>
						<b>Activities</b>:
						<?php
						if ($is_creator === true) {
							echo ' <a href="editactivities.php?course_id=' . $course_id . '">Edit</a>';
						} 
						?>
					</li>
					<?php echo list_activities($course_id); ?>
				</ul>
			</p>
			<p id="demo"></p>
			<?php
		} else {
			echo 'Sorry, that course doesn\'t exist.';
		}
	} else {
		header('Location: index.php');
		exit();
	}
	?>
</div>
<?php include 'includes/overall/footer.php'; ?>