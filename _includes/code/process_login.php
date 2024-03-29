<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/code/db_connect.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/code/functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['email'], $_POST['p'])) {
	$email = $_POST['email'];
	$password = $_POST['p']; // The hashed password.
	if (login($email, $password, $mysqli) == true) {
		// Login success
		header('Location: /');
	} else {
		// Login failed
		header('Location: /login/index.php?error=1');
	}
} else {
	// The correct POST variables were not sent to this page.
	echo 'Invalid Request';
}
?>