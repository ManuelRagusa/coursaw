<h1>Your courses</h1>

<?php
$courses = get_created_courses($user_data['user_id']);
foreach ($courses as $course_id) {
	echo list_course($course_id);
}
?>
<p><a href="createcourse.php">Create new course</a></p>