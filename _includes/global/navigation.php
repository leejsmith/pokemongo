<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/code/db_connect.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/code/functions.php';
sec_session_start();
?>
<header>
	<div class="login__link">
	<?php
		if (login_check($mysqli)) {
			echo "<p>Logged in as: " . $_SESSION['username'] . " - <a href=\"/login/logout.php\">Logout</a></p>";
		} else {
			echo "<p><a href=\"/login\">Login</a> / <a href=\"/register\">Register</a></p>";
		}
	?>
	</div>
	<div class="menu__button">
		<button class="" data-toggle=".wrapper--menu">
			<span class="menu__btn--open"><img src="/_includes/images/site/menu-open.png"/>Close</span>
			<span class="menu__btn--closed"><img src="/_includes/images/site/menu-close.png"/>Menu</span>
		</button>
	</div>
	<div class="title">
		<h1 class="title_head"><img src="/_includes/images/site/mypogo.png" alt="My Pokemon Go"/></h1>
	</div>


	<nav class="menu">
		<ul class="main__menu__list">
			<li class="main__menu__list__item"><a href="/">Home</a></li><li class="main__menu__list__item"><a href="/pokemon">Pokemon</a></li><li class="main__menu__list__item"><a href="/moves">Moves</a></li><li class="main__menu__list__item"><a href="/items">Items</a></li><?php if (login_check($mysqli)) {echo "<li class=\"main__menu__list__item\"><a href=\"/mygo\">MyGo</a></li>";}?>
		</ul>
	</nav>
</header>