<?php
function change_profile_image($user_id, $file_temp, $file_extn, $profile) {
	global $mysqli;
	if(empty($profile) === false) {
		// Delete the old image
		unlink($profile);
	}
	$file_path = 'images/profile/' . substr(md5($user_id . time()), 0, 10) . '.' . $file_extn;
	move_uploaded_file($file_temp, $file_path);

	$stmt = $mysqli->prepare("UPDATE users SET profile = ? WHERE user_id = ?");
	$stmt->bind_param('si', $file_path, $user_id);
	$stmt->execute();
	$stmt->close();
}

function update_user($update_data) {
	global $mysqli;
	global $session_user_id;
	$update = array();
	array_walk($update_data, 'array_sanitize');
	foreach ($update_data as $field => $data) {
		$update[] = $field . ' = \'' . $data . '\'';
	}

	$query = "UPDATE users SET " . implode (', ', $update) . " WHERE user_id = " . $session_user_id;
	// Qua non uso prepared statement, non permettono il bind di array
	$mysqli->query($query);
}

function change_password($user_id, $password) {
	global $mysqli;
	$user_id = (int)$user_id;
	$password = hash('sha512', $password);

	$stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE user_id = ?");
	$stmt->bind_param('si', $password, $user_id);
	$stmt->execute();
	$stmt->close();
}

function register_user($register_data) {
	global $mysqli;
	array_walk($register_data, 'array_sanitize');
	$register_data['password'] = hash('sha512', $register_data['password']);
	
	$fields = implode(', ', array_keys($register_data));
	$data = '\'' . implode('\', \'', $register_data) . '\'';

	$query = "INSERT INTO users (" . $fields . ") VALUES (" . $data . ")";
	// Qua non uso prepared statement, non permettono il bind di array
	$mysqli->query($query);
}

function user_count() {
	global $mysqli;
	
	$stmt = $mysqli->prepare("SELECT COUNT(user_id) FROM users WHERE active = 1");
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();

	return $count;
}

function user_data($user_id) {
	global $mysqli;
	$data = array();
	$user_id = (int)$user_id;

	$func_num_args = func_num_args();
	$func_get_args = func_get_args();

	if ($func_num_args > 0) {
		unset($func_get_args[0]);
		$fields = implode(', ', $func_get_args);
		$query = "SELECT " . $fields . " FROM users WHERE user_id = ?";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		$data = $stmt->get_result()->fetch_assoc();
		$stmt->close();

		return $data;
	}
}

function logged_in() {
	return (isset($_SESSION['user_id'])) ? true : false;
}

function user_exists($username) {
	global $mysqli;
	$username = sanitize($username);
	
	$stmt = $mysqli->prepare("SELECT COUNT(user_id) FROM users WHERE username = ?");
	$stmt->bind_param('s', $username);
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();

	return ($count == 1) ? true : false;
}

function email_exists($email) {
	global $mysqli;
	$email = sanitize($email);
	
	$stmt = $mysqli->prepare("SELECT COUNT(user_id) FROM users WHERE email = ?");
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();

	return ($count == 1) ? true : false;
}

function user_teacher($user_id) {
	global $mysqli;

	$stmt = $mysqli->prepare("SELECT COUNT(user_id) FROM users WHERE user_id = ? AND teacher = 1");
	$stmt->bind_param('i', $user_id);
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();

	return ($count == 1) ? true : false;
}

function user_active($username) {
	global $mysqli;
	$username = sanitize($username);

	$stmt = $mysqli->prepare("SELECT COUNT(user_id) FROM users WHERE username = ? AND active = 1");
	$stmt->bind_param('s', $username);
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();

	return ($count == 1) ? true : false;
}

function user_id_from_username($username) {
	global $mysqli;
	$username = sanitize($username);

	$stmt = $mysqli->prepare("SELECT user_id FROM users WHERE username = ?");
	$stmt->bind_param('s', $username);
	$stmt->execute();
	$stmt->bind_result($user_id);
	$stmt->fetch();
	$stmt->close();

	return $user_id;
}

function login($username, $password) {
	global $mysqli;
	$user_id = user_id_from_username($username);
	$username = sanitize($username);
	$password = hash('sha512', $password);

	$stmt = $mysqli->prepare("SELECT COUNT(user_id) FROM users WHERE username = ? AND password = ?");
	$stmt->bind_param('ss', $username, $password);
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();

	return ($count == 1) ? $user_id : false;
}
?>