<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/code/db_connect.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/code/functions.php';

sec_session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/global/header.php");
$logged = false;
if(login_check($mysqli) == true) {
	$logged = true;
}
?>
<link rel="stylesheet" href="/_includes/css/mygo.css" type="text/css" />
<script type="text/javascript" src="/_includes/js/mygo.js"></script>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/global/header.common.php';
?>