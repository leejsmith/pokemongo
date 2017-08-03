<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_includes/global/psl-config.php";
// Create connection
$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

function register($username, $email, $password) {

	$sql = "INSERT INTO tbl_Users";
}

function get_salt($username) {
	$sql = "SELECT salt FROM tbl_Users WHERE username ='" . mysqli_real_escape_string($username) . "';";
	echo $sql;
}

?>