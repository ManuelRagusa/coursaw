<?php
include 'core/init.php';
logged_in_redirect();

if (empty($_POST) === false) {
	$username = $_POST['username'];
	$password = $_POST['password'];

	if (empty($username) || empty($password)) {
		$errors[] = 'You need to enter a username and password';
	} else if (user_exists($username) === false) {
		$errors[] = 'We can\'t find that username. Have you registered?';
	} else if (user_active($username) === false) {
		$errors[] = 'You haven\'t activated your account!';
	} else {

		if (strlen($password) > 32) {
			$errors[] = 'Password too long';
		}

		$login = login($username, $password);
		if ($login === false) {
			$errors[] = 'That username/password combination is incorrect';
		} else {
			$_SESSION['user_id'] = $login;
			header('Location: index.php');
			exit();
		}
	}
} else {
	$errors[] = 'No data received';
}
include 'includes/overall/header.php';
if (empty($errors) === false) {
?>
	<h2>We tried to log you in, but...</h2>
<?php
	echo output_errors($errors);
}
include 'includes/overall/footer.php';
?>