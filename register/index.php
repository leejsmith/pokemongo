<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/code/register.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/code/functions.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/global/header.php';
?>

		<script type="text/JavaScript" src="/_includes/js/sha512.js"></script>
		<script type="text/JavaScript" src="/_includes/js/forms.js"></script>
		<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/global/header.common.php'; ?>
		<!-- Registration form to be output if the POST variables are not
		set or if the registration script caused an error. -->
		<h1>Register with us</h1>
		<?php
		if (!empty($error_msg)) {
			echo $error_msg;
		}
		?>
		<form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post" name="registration_form">
			<label for='username'>Username:</label><input type='text' name='username' id='username' /><br>
			<label for='email'>Email:</label> <input type="text" name="email" id="email" /><br>
			<label for='password'>Password:</label> <input type="password" name="password" id="password"/><br>
			<label for='confirmpwd'>Confirm password:</label> <input type="password" name="confirmpwd" id="confirmpwd" /><br>
			<input type="button" value="Register" onclick="return regformhash(this.form,this.form.username, this.form.email,this.form.password,this.form.confirmpwd);" />
		</form>
		<p>Return to the <a href="/login">login page</a>.</p>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/global/footer.php'; ?>