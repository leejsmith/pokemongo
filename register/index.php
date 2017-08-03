<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/global/header.php"); ?>
<form action="/register/register.php" method="post">
	<label for="username">Username:</label>
	<input type="text" name="username"/>
	<label for="email">email:</label>
	<input type="text" name="email"/>
	<label for="password">Password:</label>
	<input type="password" name="password"/>
	<label for="password_check">Re-Enter Password:</label>
	<input type="password" name="password_check"/>
	<button type="submit" value="register">Register</button>
</form>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/global/footer.php"); ?>