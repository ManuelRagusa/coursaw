<?php 
$mysqli = new mysqli('localhost', 'coursaw_user', 'eKcGZr59zAa2BEWU', 'coursaw');
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>