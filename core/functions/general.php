<?php
function logged_in_redirect() {
	if (logged_in() === true) {
		header('Location: index.php');
		exit();
	}
}

function protect_course_page($user_id, $course_id) {
	protect_teacher_page($user_id);
	if (course_creator($user_id, $course_id) === false) {
		header('Location: denied.php');
		exit();
	}
}

function protect_teacher_page($user_id) {
	protect_page();
	if (user_teacher($user_id) === false) {
		header('Location: denied.php');
		exit();
	}
}

function protect_page() {
	if(logged_in() === false) {
		header('Location: protected.php');
		exit();
	}
}

function array_sanitize(&$item) {
	global $mysqli;
	$item = htmlentities(strip_tags(mysqli_real_escape_string($mysqli, $item)));
}

function sanitize($data) {
	global $mysqli;
	return htmlentities(strip_tags(mysqli_real_escape_string($mysqli, $data)));
}

function output_errors($errors) {
	return '<ul><li>' . implode('</li><li>', $errors) . '</li></ul>';
}
?>