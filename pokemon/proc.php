<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/code/db_connect.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/_includes/code/functions.php';
    header('Content-type: application/json');
    $return = array();
    sec_session_start();
    $logged = false;
    if(login_check($mysqli) == true) {
        $userID = mysqli_real_escape_string($mysqli, $_SESSION['user_id']);
        $pokemonID = mysqli_real_escape_string($mysqli, $_POST['pokemon']);
        $cp = mysqli_real_escape_string($mysqli, $_POST['cp']);
        $candy = mysqli_real_escape_string($mysqli, $_POST['candy']);
        $fastMove = mysqli_real_escape_string($mysqli, $_POST['fast']);
        $chargedMove = mysqli_real_escape_string($mysqli, $_POST['charged']);
        $action = mysqli_real_escape_string($mysqli, $_POST['action']);
        switch ($action){
            case "new":
                $strSQL = "INSERT INTO tbl_UserPokemon (userID, pokemonID, candy, cp, fastMove, chargedMove) VALUES (" . $userID . "," . $pokemonID . "," . $candy . "," . $cp . "," . $fastMove . "," . $chargedMove . ");";
                if ($stmt = $mysqli->prepare($strSQL)){
                    $stmt->execute();
                    $stmt->store_result();
                } else {
                    $return["msg"] = "Error inserting Pokemon";
                    die(json_encode($return));
                    break;
                }

                $strSQL = "UPDATE tbl_UserPokemon SET candy = " . $candy . " WHERE userID=" . $userID . " AND pokemonID= " . $pokemonID;

                if ($stmt = $mysqli->prepare($strSQL)) {
                    $stmt->execute();
                    $stmt->store_result();
                } else {
                    $return["msg"] = "Error updating candy Pokemon";
                    die(json_encode($return));
                    break;
                }
                $return["success"] = "Pokemon Added";
                echo(json_encode($return));
                break;
            case "edit":

            case "delete":
        }
    } else {
        header("HTTP/1.0 403 Forbidden");
        echo "<p>You need to be logged in to be able to access this page</p>";
    }
?>