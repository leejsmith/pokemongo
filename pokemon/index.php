<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/code/db_connect.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/code/functions.php');
require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/global/header.php"); ?>
<script type="text/javascript" src="/_includes/js/pokemon.js"></script>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/global/header.common.php");

if (isset($_GET['pkid'])){
    echo displaySinglePokemon($mysqli, $_GET['pkid']);
} else {
    echo displayPokemon($mysqli);
}





require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/global/footer.php");
?>