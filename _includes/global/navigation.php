<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/code/db_connect.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/code/functions.php';
sec_session_start();
?>
<header>
	<div class="title">
		<h1 class="title_head"><a href="/"><img src="/_includes/images/site/mypogo.png" alt="My Pokemon Go"/></a></h1>
	</div>
	<div class="login__link">
	<?php
		if (login_check($mysqli)) {
			echo "<p>Logged in as: " . $_SESSION['username'] . " - <a href=\"/login/logout.php\">Logout</a></p>";
		} else {
			echo "<p><a href=\"/login\">Login</a> / <a href=\"/register\">Register</a></p>";
		}
	?>
	</div>
	<nav>
		<ul class="main__menu__list">
			<li class="main__menu__list_item"><a href="/">Home</a></li>
			<li class="main__menu__list_item"><a href="/pokemon">Pokemon</a></li>
			<li class="main__menu__list_item"><a href="/moves">Moves</a></li>
			<li class="main__menu__list_item"><a href="/items">Items</a></li>
			<li class="main__menu__list_item"><a href="/mygo">My Pokemon Go</a></li>
		</ul>
	</nav>
</header>