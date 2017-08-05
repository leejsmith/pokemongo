<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/code/db_connect.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/code/functions.php');
sec_session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/global/header.php"); ?>
<script type="text/javascript" src="/_includes/js/pokemon.js"></script>
<link rel="stylesheet" href="/_includes/css/pokemon.css" type="text/css"/>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/global/header.common.php");
?>
<div class="notification__area"><span></span></div>
<?php
$logged = false;
if(login_check($mysqli) == true) {
	$logged = true;
}

if (isset($_GET['pkid'])){
    echo displaySinglePokemon($mysqli, $_GET['pkid']);
} else {
    echo displayPokemon($mysqli, $logged);
}

?>
<div class="add__pokemon__dialog"></div>



<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/global/footer.php");?>
<script type="text/javascript">
    $('.add__pokemon').on('click', function(e){
        e.preventDefault();
        var pokemonID = $(this).data('pokemon');
        $('.wrapper').addClass('pokemonadd');
        $('.add__pokemon__dialog').load('/_includes/code/addPokemon.php?pokemon=' + pokemonID);
    })
</script>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/_includes/global/footer.common.php");
?>