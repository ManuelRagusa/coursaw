<?php
include 'core/init.php';
protect_course_page($user_data['user_id'], $_GET['course_id']);
include 'includes/overall/header.php';

/* if the form as been submitted */
if (empty($_POST) === false) {
	foreach($_POST as $key=>$value) {
		if (empty($value) === true) {
			$errors[] = 'Fields marked with an asterisk are required';
			break 1;
		}
	}

	if (empty($errors) === true) {
		// input control
	}
}
?>

<h1>Course: <?php echo course_title_from_id($_GET['course_id']) ?> / edit activities</h1>

<?php
?>

<form action="editactivities.php?course_id=<?php echo $_GET['course_id']; ?>" method="post">
	<ul>
		<?php echo create_activities_input($_GET['course_id']); ?>
		<li>
			<input type="submit" value="Update">
		</li>
	</ul>
</form>

<?php
if (empty($_POST) === false && empty($errors) === true) {
	$update_data = array();
	$i = 0;
	foreach ($_POST as $key => $value) {
		$update_data[$key] = $value;
	}
	update_activities($update_data, $_GET['course_id']);
	header('Location: course.php?course_id=' . $_GET['course_id']);
	exit();
} else if (empty($errors) === false) {
	echo output_errors($errors);
}


include 'includes/overall/footer.php'; ?>