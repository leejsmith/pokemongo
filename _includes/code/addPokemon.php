<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/code/db_connect.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/code/functions.php';

sec_session_start();
$logged = false;
if(login_check($mysqli) == true) {

    if (isset($_GET['pokemon'])){
        $pkid = mysqli_real_escape_string($mysqli ,$_GET['pokemon']);
    }
    echo "<div class=\"add__pokemon__overlay\">";
    echo "<div class=\"add__pokemon__wrapper\">";
    echo "<div class=\"add__pokemon__close\"><i class=\"fa fa-close\"></i></div>";
    echo "<h2>Add to MyGo</h2>";
    if ($pkid < 10){
        $imgID = "00" . $pkid;
    } else if ($pkid < 100){
        $imgID = "0" . $pkid;
    } else {
        $imgID = $pkid;
    }
    echo getPokemonImage($mysqli, $pkid, true, 0, false);
    echo "<input class=\"add__pokemon__id\" type=\"hidden\" name=\"pokemon\" value=\"".$pkid."\"/>";
    echo "<label for=\"cp\">Enter Pokemon CP</label><input class=\"add__pokemon__cp\" type=\"number\" name=\"cp\" min=\"0\" value=\"0\"/><br/>";
    echo "<label for=\"candy\">Enter Total Candy</label><input class=\"add__pokemon__candy\" type=\"number\" min=\"0\" name=\"candy\" value=\"0\"/><br/>";

    echo "<label for=\"fast\">Select Fast Move</label><select class=\"add__pokemon__fast\" name=\"fast\">";
    echo getPokemonFastMoves($mysqli, $pkid);
    echo "</select><br/>";

    echo "<label for=\"charged\">Select Charged Move</label><select class=\"add__pokemon__charged\" name=\"charged\">";
    echo getPokemonChargedMoves($mysqli, $pkid);
    echo "</select><br/>";
    echo "<button class=\"add__pokemon__button\">Add Pokemon</button>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<p>You are not currently Logged in</p>";
}
?>

<script type="type/javascript">
    var windowWidth = $(window).width();
    var windowHeight = $(window).height();
    if (windowWidth < 350) {
        width = windowWidth - 30;
    } else {
        width = 350;
    }
    if (windowHeight < 500) {
        height = windowHeight - 30;
    } else {
        height = 500;
    }
    var marginW = (windowWidth - (width + 30)) / 2;
    var marginH = (windowHeight - height) / 2;
    $('.add__pokemon__wrapper').css('width', width + 'px');
    $('.add__pokemon__wrapper').css('height', height + 'px');
    $('.add__pokemon__wrapper').css('margin', marginH + 'px ' + marginW + 'px');

    $('.add__pokemon__close').on('click', function(){
        $('.add__pokemon__overlay').hide();
    });

    $('.add__pokemon__button').on('click', function(){
        $pokemon = $('.add__pokemon__id').val();
        $cp = $('.add__pokemon__cp').val();
        $candy = $('.add__pokemon__candy').val();
        $fast = $('.add__pokemon__fast option').filter(':selected').val();
        $charged = $('.add__pokemon__charged option').filter(':selected').val();
        $.ajax({
            url:"/pokemon/proc.php",
            method: "POST",
            data:{
                pokemon:$pokemon,
                candy:$candy,
                cp:$cp,
                fast:$fast,
                charged:$charged,
                action:"new"
            },
            dataType: 'text',
            success: function(data) {
                var $retVal = $.parseJSON(data);
                $('.add__pokemon__overlay').hide();
                $('.notification__area').append('<div class="notification__wrapper"><div class="pokemon"><img src="/_includes/images/pokemon/<?php echo $imgID; ?>.png"/></div><div class="message"><p>Pokemon Added</p></div></div>');
                $('.notification__wrapper').delay(2000).slideUp('slow');
                setTimeout(function(){$('.notification__wrapper').remove()}, 4000);
            },
            error: function(data) {
                var $retVal = $.parseJSON(data);
            }
        });
    });
</script>
