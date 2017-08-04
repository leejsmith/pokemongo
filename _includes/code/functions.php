<?php

	include_once 'psl-config.php';

	function sec_session_start() {
		$session_name = 'sec_session_id';   // Set a custom session name
		$secure = SECURE;
		// This stops JavaScript being able to access the session id.
		$httponly = true;
		// Forces sessions to only use cookies.
		if (ini_set('session.use_only_cookies', 1) === FALSE) {
			header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
			exit();
		}
		// Gets current cookies params.
		$cookieParams = session_get_cookie_params();
		session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
		// Sets the session name to the one set above.
		session_name($session_name);
		if (!isset($_SESSION)){
			session_start();			// Start the PHP session
			session_regenerate_id();	// regenerated the session, delete the old one.
		}

	}

	function login($email, $password, $mysqli) {
	// Using prepared statements means that SQL injection is not possible.
	if ($stmt = $mysqli->prepare("SELECT userID, username, password
		FROM tbl_Users
	   WHERE email = ?
		LIMIT 1")) {
			$stmt->bind_param('s', $email);  // Bind "$email" to parameter.
			$stmt->execute();	// Execute the prepared query.
			$stmt->store_result();

			// get variables from result.
			$stmt->bind_result($user_id, $username, $db_password);
			$stmt->fetch();

			if ($stmt->num_rows == 1) {
				// If the user exists we check if the account is locked
				// from too many login attempts

				if (checkbrute($user_id, $mysqli) == true) {
					// Account is locked
					// Send an email to user saying their account is locked
					return false;
				} else {
					// Check if the password in the database matches
					// the password the user submitted. We are using
					// the password_verify function to avoid timing attacks.
					if (password_verify($password, $db_password)) {
						// Password is correct!
						// Get the user-agent string of the user.
						$user_browser = $_SERVER['HTTP_USER_AGENT'];
						// XSS protection as we might print this value
						$user_id = preg_replace("/[^0-9]+/", "", $user_id);

						$_SESSION['user_id'] = $user_id;
						// XSS protection as we might print this value
						$username = preg_replace("/[^a-zA-Z0-9_\-]+/","",$username);
						$_SESSION['username'] = $username;
						$_SESSION['login_string'] = hash('sha512',$db_password . $user_browser);
						// Login successful.
						return true;
					} else {
						// Password is not correct
						// We record this attempt in the database
						$now = time();
						$mysqli->query("INSERT INTO tbl_LoginAttempts(user_id, time) VALUES ('$user_id', '$now')");
						return false;
					}
				}
			} else {
				// No user exists.
				return false;
			}
		}
	}

	function checkbrute($user_id, $mysqli) {
		// Get timestamp of current time
		$now = time();

		// All login attempts are counted from the past 2 hours.
		$valid_attempts = $now - (2 * 60 * 60);

		if ($stmt = $mysqli->prepare("SELECT time FROM login_attempts WHERE user_id = ?	AND time > '$valid_attempts'")) {
			$stmt->bind_param('i', $user_id);

			// Execute the prepared query.
			$stmt->execute();
			$stmt->store_result();

			// If there have been more than 5 failed logins
			if ($stmt->num_rows > 5) {
				return true;
			} else {
				return false;
			}
		}
	}

	function login_check($mysqli) {
		// Check if all session variables are set
		if (isset($_SESSION['user_id'],	$_SESSION['username'], $_SESSION['login_string'])) {
			$user_id = $_SESSION['user_id'];
			$login_string = $_SESSION['login_string'];
			$username = $_SESSION['username'];

			// Get the user-agent string of the user.
			$user_browser = $_SERVER['HTTP_USER_AGENT'];

			if ($stmt = $mysqli->prepare("SELECT password FROM tbl_Users WHERE userID = ? LIMIT 1")) {
				// Bind "$user_id" to parameter.
				$stmt->bind_param('i', $user_id);
				$stmt->execute();   // Execute the prepared query.
				$stmt->store_result();

				if ($stmt->num_rows == 1) {
					// If the user exists get variables from result.
					$stmt->bind_result($password);
					$stmt->fetch();
					$login_check = hash('sha512', $password . $user_browser);

					if (hash_equals($login_check, $login_string) ){
						// Logged In!!!!
						return true;
					} else {
						// Not logged in
						return false;
					}
				} else {
					// Not logged in
					return false;
				}
			} else {
				// Not logged in
				return false;
			}
		} else {
			// Not logged in
			return false;
		}
	}

	function esc_url($url) {

		if ('' == $url) {
			return $url;
		}

		$url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);

		$strip = array('%0d', '%0a', '%0D', '%0A');
		$url = (string) $url;

		$count = 1;
		while ($count) {
			$url = str_replace($strip, '', $url, $count);
		}

		$url = str_replace(';//', '://', $url);

		$url = htmlentities($url);

		$url = str_replace('&amp;', '&#038;', $url);
		$url = str_replace("'", '&#039;', $url);

		if ($url[0] !== '/') {
			// We're only interested in relative links from $_SERVER['PHP_SELF']
			return '';
		} else {
			return $url;
		}
	}

	function displayPokemon($mysqli){
		return displayPokemonV2($mysqli, "all");
	}

	function displayPokemonV2($mysqli, $type){
		$strSQLWhere = ";";
		$retVal = "";
		if ($type != "all"){
			$strSQLWhere = " where pokemonType1 = '" . $type . "' OR pokemonType2 = '" . $type . "';";
		}

		$strSQL = "SELECT * FROM tbl_Pokemon" . $strSQLWhere;
		if ($stmt = $mysqli->prepare($strSQL)) {
			$stmt->execute();   // Execute the prepared query.
			$stmt->bind_result($id, $name, $type1, $type2);
			$i=1;
			$retVal = "<table><tbody><tr>";
			while ($stmt->fetch()){
				if ($id < 10){
					$imgID = "00" . $id;
				} else if ($id < 100){
					$imgID = "0" . $id;
				} else {
					$imgID = $id;
				}
				$retVal .= "<td data--id='".$id."'><a href=\"/pokemon/?pkid=".$id."\"><span class\"number\">#" . $imgID . "</span>" . "<img src=\"/_includes/images/pokemon/".$imgID.".png\" alt=\"".$name."\"/>" . $name . "</a><a class=\"add__pokemon\" data--id=\"".$id."\" href=\"javascript:void();\">Add to MyGo</a></td>";
				if ($i % 3 === 0){
					$retVal .= "</tr><tr>";
				}
				$i++;
			}
			$retVal .= "</tbody></table>";
		}
		return $retVal;
	}
	function displaySinglePokemon($mysqli, $pkid){
		$pkid = mysqli_real_escape_string($mysqli, $pkid);

		$strSQL = "SELECT * FROM tbl_Pokemon WHERE pokemonID = ". $pkid;
		$retVal = "";
		if ($stmt= $mysqli->prepare($strSQL)){
			$stmt->execute();
			$stmt->bind_result($id, $name, $type1, $type2);
			$stmt->store_result();
			$stmt->fetch();

			if ($id < 10){
				$imgID = "00" . $id;
			} else if ($id < 100){
				$imgID = "0" . $id;
			} else {
				$imgID = $id;
			}

			$retVal .= "<h1 class=\"pokemon__name\">#" . $imgID . " - " . $name . "</h1>";

			$retVal .= "<img class=\"pokemon_image\" src=\"/_includes/images/pokemon/" . $imgID . ".png\" alt=\"". $name ."\"/>";
			$retVal .= "<h2>Moves</h2>";
			$strSQLFast = "SELECT FM.*, T.typeName FROM tbl_PokemonToFast AS PTF  LEFT JOIN tbl_FastMoves AS FM ON PTF.moveID = FM.moveID LEFT JOIN tbl_Types AS T ON T.typeID = FM.moveTypeID WHERE pokemonID = " . $id;

			if ($stmtFast = $mysqli->prepare($strSQLFast)){
				$stmtFast->execute();

				$stmtFast->bind_result($moveFastID, $moveFastName, $moveFastTypeID, $moveFastPower, $moveFastEnergy, $moveFastDPS, $moveFastEPS, $moveFastTime, $moveFastTypeName);
				$retVal .= "<h3>Fast Moves</h3>";
				$retVal .= "<table class=\"fast__moves\"><thead><tr><th>Move Name</th><th>Move Type</th><th>Move Power</th><th>Move Energy Fill</th><th>Move Damage PS</th><th>Move Energy PS</th><th>Move Time</th></tr></thead><tbody>";
				$stmtFast->store_result();
				while ($stmtFast->fetch()){
					$retVal .= "<tr><td>" . $moveFastName ."</td><td><span class=\"moveFasttype moveFasttype--" . strtolower($moveFastTypeName) ."\">" . $moveFastTypeName . "</span></td><td>" . $moveFastPower."</td><td>" . $moveFastEnergy ."</td><td>". $moveFastDPS ."</td><td>". $moveFastEPS ."</td><td>". $moveFastTime ."</td></tr>";
				}
				$retVal .= "</tbody></table>";
			}

			$strSQLCharged = "SELECT CM.*, T.typeName FROM tbl_PokemonToCharged AS PTC  LEFT JOIN tbl_ChargedMoves AS CM ON PTC.moveID = CM.moveID LEFT JOIN tbl_Types AS T ON T.typeID = CM.moveTypeID WHERE pokemonID = " . $id;
			if ($stmtCharged = $mysqli->prepare($strSQLCharged)){
				$stmtCharged->execute();

				$stmtCharged->bind_result($moveChargedID, $moveChargedName, $moveChargedTypeID, $moveChargedCharges, $moveChargedPower, $moveChargedDuration, $moveChargedActive, $moveChargedTypeName);
				$retVal .= "<h3>Charged Moves</h3>";
				$retVal .= "<table class=\"fast__moves\"><thead><tr><th>Move Name</th><th>Move Type</th><th>Move Charges</th><th>Move Power</th><th>Move Duration</th><th>Move Time till hits</th></tr></thead><tbody>";
				$stmtCharged->store_result();
				while ($stmtCharged->fetch()){
					$retVal .= "<tr><td>" . $moveChargedName ."</td><td><span class=\"movetype movetype--" . strtolower($moveChargedTypeName) ."\">" . $moveChargedTypeName . "</span></td><td>" . $moveChargedCharges."</td><td>" . $moveChargedPower ."</td><td>". $moveChargedDuration ."</td><td>". $moveChargedActive ."</td></tr>";
				}
				$retVal .= "</tbody></table>";
			}
		}
		$retVal .= "<h2>Evolutions</h2>";
		$evolutionTree = getPokemonEvolutions($mysqli, $pkid);
		$isFirst = 1;
		foreach (explode(":",$evolutionTree) AS $evoGroup){
			$retVal .= "<div class=\"level level--" . $isFirst . "\">";
			foreach (explode(",",$evoGroup) AS $evoPoke){
				$retVal .= getPokemonShort($mysqli, $evoPoke, $isFirst);
			}
			$retVal .="</div>";
			$isFirst++;
		}
		return $retVal;
	}

	function getPokemonShort($mysqli, $pkid, $isFirst){
		$strSQL = "SELECT P.pokemonID, P.pokemonName, E.candy, E.item, E.description FROM tbl_Pokemon AS P LEFT JOIN tbl_Evolution AS E ON P.pokemonID = E.pokemon2 WHERE P.pokemonID = " . $pkid . ";";
		$retVal = "";
		if ($stmt = $mysqli->prepare($strSQL)){
			$stmt->execute();
			$stmt->bind_result($id, $name, $candy, $item, $desc);
			$stmt->store_result();
			$stmt->fetch();
			if ($id < 10){
				$imgID = "00" . $id;
			} else if ($id < 100){
				$imgID = "0" . $id;
			} else {
				$imgID = $id;
			}
			$retVal = "<div class=\"pokemon__evo\">";
			if ($isFirst != 1){
				$retVal .= "<div class=\"arrow\"></div>";
			}

			$retVal .= "<div class=\"pokemon__details\">";
			$retVal .= "<div class=\"pokemon__img\"><img src=\"/_includes/images/pokemon/".$imgID.".png\" alt=\"".$name."\"/></div>";
			$retVal .= "<div class=\"pokemon__id\">#" . $imgID . "</div>";
			$retVal .= "<div class=\"pokemon__name\"><p>" . $name . "</p></div>";
			$retVal .= "</div></div>";

			return $retVal;
		}
	}

	function getPokemonEvolutions($mysqli, $pkid){
		$thisEvo = $pkid;
		$firstEvo = "";
		$evoCount = 0;
		while(true){
			 $thisEvo = getFirstEvolution($mysqli, $thisEvo);
			 if ($thisEvo == "false") {
				 if ($evoCount == 0){
					 $firstEvo = $pkid;
				 }
				break;
			 } else {
				 $evoCount++;
				 $firstEvo = $thisEvo;
			 }
		}
		return getAllEvolutions($mysqli, $firstEvo);
	}

	function getFirstEvolution($mysqli, $pkid){
		$strSQL = "SELECT pokemon1 FROM tbl_Evolution WHERE pokemon2 IN (" . $pkid . ");";
		if ($stmt = $mysqli->prepare($strSQL)){
			$stmt->execute();
			$stmt->bind_result($pk1);
			$stmt->store_result();
			if ($stmt->num_rows > 0){
				$stmt->fetch();
				return $pk1;
			} else {
				return "false";
			}
		}
	}

	function getAllEvolutions($mysqli, $pkid){
		$retVal = $pkid;
		$retVal .= getEvolutions($mysqli, $pkid, "");
		return $retVal;
	}

	function getEvolutions($mysqli, $pkid, $retIn){
		$retVal = "";
		$addPkm = "";
		$strSQL = "SELECT * FROM tbl_Evolution WHERE pokemon1 IN (" . $pkid . ");";
		if ($stmtEvo = $mysqli->prepare($strSQL)){
			$stmtEvo->execute();
			$stmtEvo->bind_result($pk1, $pk2, $candy, $item, $desc);
			$stmtEvo->store_result();
			if ($stmtEvo->num_rows >= 1){
				while($stmtEvo->fetch()){
					if ($addPkm != ""){
						$addPkm .= ",";
					}
					$addPkm .= $pk2;
				}
				$retVal .=  ":" . $addPkm ;
					$retVal .= getEvolutions($mysqli, $pk2, $retVal);
				return $retVal;
			} else {
				return false;
			}
		}
	}



?>