<?php
include 'core/init.php';
protect_teacher_page($user_data['user_id']);
include 'includes/overall/header.php';

/* if the form as been submitted */
if (empty($_POST) === false) {
	$required_fields = array('title', 'description', 'category', 'start_date', 'duration');
	foreach($_POST as $key=>$value) {
		if (empty($value) && in_array($key, $required_fields) === true) {
			$errors[] = 'Fields marked with an asterisk are required';
			break 1;
		}
	}

	if (empty($errors) === true) {
		// input control
	}
}
?>

<h1>Course Creation</h1>

<?php
?>

<form action="" method="post">
	<ul>
		<li>
			Title*:<br />
			<input type="text" name="title">
		</li>
		<li>
			Description*:<br />
			<textarea name="description"></textarea>
		</li>
		<li>
			Category*:<br />
			<?php echo optionize_categories(); ?>
		</li>
		<li>
			Start Date*:<br />
			<input type="date" name="start_date" min="<?php echo date('Y-m-d'); ?>">
		</li>
		<li>
			Duration in weeks (max 12)*:<br />
			<input type="number" name="duration" min="1" max="12">
		</li>
		<li>
			<input type="submit" value="Create">
		</li>
	</ul>
</form>

<?php
if (empty($_POST) === false && empty($errors) === true) {
	$course_data = array(
		'title' => $_POST['title'],
		'description' => $_POST['description'],
		'category' => $_POST['category'],
		'start_date' => $_POST['start_date'],
		'duration' => $_POST['duration'],
		);
	$course_id = create_course($course_data, $user_data['user_id']);
	header('Location: editactivities.php?course_id=' . $course_id);
	exit();
} else if (empty($errors) === false) {
	echo output_errors($errors);
}


include 'includes/overall/footer.php'; ?>