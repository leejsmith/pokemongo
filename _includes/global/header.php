<!DOCTYPE html>
<html>

<head>
    <title>My Pokemon Go</title>
</head>

<body>
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/global/header.php"); ?>
<header>
	<div class="login__link">
	<?php 
		if (isset($_SESSION['userid'])) {
			echo "<p>Logged in as: " . $_SESSION['username'] . " - <a href=\"Logout\">Logout</a></p>";
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
