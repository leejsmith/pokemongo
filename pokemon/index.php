<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/code/db_connect.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/code/functions.php');
sec_session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/global/header.php"); ?>
<script type="text/javascript" src="/_includes/js/pokemon.js"></script>
<link rel="stylesheet" href="/_includes/css/pokemon.css" type="text/css"/>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/global/header.common.php");

$logged = false;
if(login_check($mysqli) == true) {
	$logged = true;
}

if (isset($_GET['pkid'])){
    echo displaySinglePokemon($mysqli, $_GET['pkid']);
} else {
    echo displayPokemon($mysqli, $logged);
}





require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/global/footer.php");
?>