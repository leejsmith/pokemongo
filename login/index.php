<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/code/db_connect.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/code/functions.php';
sec_session_start();
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/global/header.php"); ?>

		<script type="text/JavaScript" src="/_includes/js/sha512.js"></script>
		<script type="text/JavaScript" src="/_includes/js/forms.js"></script>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/global/header.common.php"); ?>

		<?php
		if (isset($_GET['error'])) {
			echo '<p class="error">Error Logging In!</p>';
		}
		?>
		<form class="login" action="/_includes/code/process_login.php" method="post" name="login_form">
			<label for="email">Email:</label><input type="text" name="email"/>
			<label for="password">Password:</label><input type="password" name="password" id="password"/>
			<input class="submit" type="button" value="Login" onclick="formhash(this.form, this.form.password);" />
		</form>

<?php
		if (login_check($mysqli)) {
						echo '<p>Currently logged in as ' . htmlentities($_SESSION['username']) . '.</p>';

			echo '<p>Do you want to change user? <a href="/_includes/code/logout.php">Log out</a>.</p>';
		} else {
						echo '<p>Currently logged out</p>';
						echo "<p>If you don't have a login, please <a href='/register'>register</a></p>";
				}
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/global/footer.php"); ?>