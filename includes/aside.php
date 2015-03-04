<aside>
	<?php
	if (logged_in() === true) {
		include 'includes/widgets/loggedin.php';
		if(user_teacher($user_data['user_id']) === true) {
			include 'includes/widgets/teacher.php';
		} else {
			include 'includes/widgets/student.php';
		}
	} else {
		include 'includes/widgets/login.php';
	}
	include 'includes/widgets/user_count.php';
	?>
</aside>