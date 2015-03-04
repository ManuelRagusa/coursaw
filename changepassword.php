<?php
include 'core/init.php';
protect_page();
include 'includes/overall/header.php';

if (empty($_POST) === false) {
	$required_fields = array('current_password', 'password', 'password_again');
	foreach($_POST as $key=>$value) {
		if (empty($value) && in_array($key, $required_fields) === true) {
			$errors[] = 'Fields marked with an asterisk are required';
			break 1;
		}
	}

	if (hash('sha512', $_POST['current_password']) === $user_data['password']) {
		if (trim($_POST['password']) !== trim($_POST['password_again'])) {
			$errors[] = 'Your passwords do not match';
		} else if(strlen($_POST['password']) < 6) {
			$errors[] = 'Your password must be at least 6 characters';
		}
	} else {
		$errors[] = 'Your current password is incorrect';
	}
}
?>

<h1>Change password</h1>

<?php
if (isset($_GET['success']) && empty($_GET['success'])) {
	echo 'Your password has been changed.';
} else {
	?>

	<form action="" method="post">
		<ul>
			<li>
				Current password*:<br />
				<input type="password" name="current_password">
			</li>
			<li>
				New password*:<br />
				<input type="password" name="password">
			</li>
			<li>
				New password again*:<br />
				<input type="password" name="password_again">
			</li>
			<li>
				<input type="submit" value="Change password">
			</li>
		</ul>
	</form>

<?php
	if (empty($_POST) === false && empty($errors) === true) {
		change_password($user_data['user_id'], $_POST['password']);
		header('Location: changepassword.php?success');
		exit();
	} else if (empty($errors) === false) {
		echo output_errors($errors);
	}
}

include 'includes/overall/footer.php'; ?>