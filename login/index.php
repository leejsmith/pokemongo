<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/global/header.php"); ?>
<div class="login__form">
<?php
	if (!empty($_POST)){
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		
	} else {
		if (isset($_SESSION['userid'])) {

		} else {

			require_once($_SERVER['DOCUMENT_ROOT'] . "/login/login_form.php");
		}
	}
?>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/global/footer.php"); ?>