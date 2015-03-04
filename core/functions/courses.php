<?php
function update_activities($update_data, $course_id) {
	global $mysqli;
	array_walk($update_data, 'array_sanitize');
	foreach ($update_data as $field => $data) {
		$stmt = $mysqli->prepare("UPDATE activities SET link = ? WHERE course_id = ? AND week = ?");
		$stmt->bind_param('sii', $data, $course_id, $field);
		$stmt->execute();
		$stmt->close();
	}
}

function list_activities($course_id) {
	global $mysqli;
	$list = array();
	$activities = get_activities($course_id);
	foreach ($activities as $data) {
		$list[] = '<li>Week ' . $data[0] . ': ' . $data[1] . '</li>';
	}
	return implode('', $list);
}

function create_activities_input($course_id) {
	global $mysqli;
	$inputs = array();
	$activities = get_activities($course_id);
	foreach ($activities as $data) {
		$inputs[] = '<li>Week ' . $data[0] . ': <input type="text" name="' . $data[0] . '" class="inputlink" value="' . $data[1] . '"></li>';
	}
	return implode('', $inputs);
}

function get_activities($course_id) {
	global $mysqli;
	$activities = array();

	$stmt = $mysqli->prepare("SELECT week, link FROM activities WHERE course_id = ? ORDER BY week");
	$stmt->bind_param('i', $course_id);
	$stmt->execute();
	$result = $stmt->get_result();
	while ($data = $result->fetch_array(MYSQLI_NUM)) {
		$activities[] = $data;
	}
	$stmt->free_result();
	$stmt->close();

	return $activities;
}

function category_name_from_id($category_id) {
	global $mysqli;

	$stmt = $mysqli->prepare("SELECT name FROM categories WHERE category_id = ?");
	$stmt->bind_param('i', $category_id);
	$stmt->execute();
	$stmt->bind_result($name);
	$stmt->fetch();
	$stmt->close();

	return $name;
}

function optionize_categories() {
	$categories = get_categories();
	$options = array();
	foreach ($categories as $data) {
		$options[] = '<option value="' . $data[0] . '">' . $data[1] . '</option>';
	}
	return '<select name="category">' . implode('', $options) . '</select>';
}

function get_categories() {
	global $mysqli;
	$categories = array();

	$query = "SELECT category_id, name FROM categories ORDER BY name";
	$result = $mysqli->query($query);
	while ($data = $result->fetch_array(MYSQLI_NUM)) { 
		$categories[] = $data;
	}

	return $categories;
}

function list_course($course_id) {
	global $user_data;

	$subscription_count = subscription_count($course_id);
	$suffix = ($subscription_count != 1) ? 's' : '';
	$title = course_title_from_id($course_id);
	$html_output = '<p><a href="course.php?course_id=' . $course_id . '">' . $title . '</a>';
	if ($user_data['teacher'] == true) {
		$html_output .= ': ' . $subscription_count . ' student' . $suffix . '</p>';
	} else {
		$html_output .= '</p>';
	}

	return $html_output;
}

function get_subscribed_courses($user_id) {
	global $mysqli;
	$courses = array();

	$stmt = $mysqli->prepare("SELECT course_id FROM participants WHERE user_id = ?");
	$stmt->bind_param('i', $user_id);
	$stmt->execute();
	$result = $stmt->get_result();
	while ($data = $result->fetch_array(MYSQLI_NUM)) {
		$courses[] = $data[0];
	}
	$stmt->close();

	return $courses;
}

function get_created_courses($creator) {
	global $mysqli;
	$courses = array();

	$stmt = $mysqli->prepare("SELECT course_id FROM courses WHERE creator = ?");
	$stmt->bind_param('i', $creator);
	$stmt->execute();
	$result = $stmt->get_result();
	while ($data = $result->fetch_array(MYSQLI_NUM)) {
		$courses[] = $data[0];
	}
	$stmt->close();

	return $courses;
}

function subscription_count($course_id) {
	global $mysqli;
	
	$stmt = $mysqli->prepare("SELECT COUNT(user_id) FROM participants WHERE course_id = ? AND teacher = 0");
	$stmt->bind_param('i', $course_id);
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();

	return $count;
}

function course_creator($creator, $course_id) {
	global $mysqli;
	
	$stmt = $mysqli->prepare("SELECT COUNT(course_id) FROM courses WHERE course_id = ? AND creator = ?");
	$stmt->bind_param('ii', $course_id, $creator);
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();

	return ($count == 1) ? true : false;
}

function course_exists($course_id) {
	global $mysqli;
	
	$stmt = $mysqli->prepare("SELECT COUNT(course_id) FROM courses WHERE course_id = ?");
	$stmt->bind_param('i', $course_id);
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();

	return ($count == 1) ? true : false;
}

function course_id_from_title($title) {
	global $mysqli;
	$title = sanitize($title);

	$stmt = $mysqli->prepare("SELECT course_id FROM courses WHERE title = ?");
	$stmt->bind_param('s', $title);
	$stmt->execute();
	$stmt->bind_result($course_id);
	$stmt->fetch();
	$stmt->close();

	return $course_id;
}

function course_title_from_id($course_id) {
	global $mysqli;

	$stmt = $mysqli->prepare("SELECT title FROM courses WHERE course_id = ?");
	$stmt->bind_param('i', $course_id);
	$stmt->execute();
	$stmt->bind_result($title);
	$stmt->fetch();
	$stmt->close();

	return $title;
}

function course_data($course_id) {
	global $mysqli;
	$data = array();
	$course_id = (int)$course_id;

	$func_num_args = func_num_args();
	$func_get_args = func_get_args();

	if ($func_num_args > 0) {
		unset($func_get_args[0]);
		$fields = implode(', ', $func_get_args);
		$query = "SELECT " . $fields . " FROM courses WHERE course_id = ?";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param('i', $course_id);
		$stmt->execute();
		$data = $stmt->get_result()->fetch_assoc();
		$stmt->close();

		return $data;
	}
}

function course_toggle_visible($course_id) {
	global $mysqli;
	$stmt = $mysqli->prepare("UPDATE courses SET visible = !visible WHERE course_id = ?");
	$stmt->bind_param('i', $course_id);
	$stmt->execute();
	$stmt->close();
}

function delete_course($course_id) {
	global $mysqli;
	$stmt = $mysqli->prepare("DELETE FROM courses WHERE course_id = ?");
	$stmt->bind_param('i', $course_id);
	$stmt->execute();
	$stmt->close();
}

function create_course($course_data, $creator){
	global $mysqli;
	array_walk($course_data, 'array_sanitize');
	
	$fields = implode(', ', array_keys($course_data));
	$data = '\'' . implode('\', \'', $course_data) . '\'';

	$query = "INSERT INTO courses (" . $fields . ", creator) VALUES (" . $data . ", " . $creator . ");";
	// Qua non uso prepared statement, non permettono il bind di array
	$mysqli->query($query);
	$course_id = $mysqli->insert_id;
	$weeks = (int)$course_data['duration'];

	for ($i=1; $i <= $weeks; $i++) { 
		$query = "INSERT INTO activities (course_id, week) VALUES (" . $course_id . ", " . $i . ");";
		$mysqli->query($query);
	}

	return $course_id;
}
?>