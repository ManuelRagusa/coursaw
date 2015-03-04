<?php
include 'core/init.php';
protect_page();
include 'includes/overall/header.php';
?>
<?php
if (user_teacher($user_data['user_id']) === true) {
	include 'includes/teacher.php';
} else {
	include 'includes/student.php';
}
include 'includes/overall/footer.php';
?>