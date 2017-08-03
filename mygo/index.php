<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/code/db_connect.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/code/functions.php';

// Include database connection and functions here.  See 3.1.
sec_session_start();
if(login_check($mysqli) == true) {
        echo "This is a test";
} else {
        echo 'You are not authorized to access this page, please login.';
}
?>