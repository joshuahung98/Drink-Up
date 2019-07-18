<?php
	require 'config.php';

	if ( !isset($_POST['name']) || empty($_POST['name']) || !isset($_POST['image']) || empty($_POST['image']) ) {
		$error = "Invalid URL.";
	}
	else
	{	
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if($mysqli->connect_errno) {
			echo $mysqli->connect_error;
			exit();
		}

		$sql = "SELECT * FROM drinks
			WHERE name = '" . $_POST['name'] . "' AND image = '" . $_POST['image'] . "';";

		$results = $mysqli->query($sql);
		if(!$results) {
			echo $mysqli->error;
			exit();
		}

		// If at least one result is given back to us, we need to display an error.
		if( $results->num_rows == 0 ) {
			$new_drink = "INSERT INTO drinks(name, totalLikes, image)
						  VALUES ('" . $_POST['name'] . "', 1, '" . $_POST['image'] . "')";
			$newDrinkResult = $mysqli->query($new_drink);

			if(!$newDrinkResult) {
				echo $mysqli->error;
				exit();
			}

			$sql = "SELECT id FROM users
					WHERE username = '" . $_SESSION['username'] . "';";



			$result = $mysqli->query($sql);
			$row = $result->fetch_assoc();
			$userID = $row['id'];


			$sql = "SELECT id FROM drinks
					WHERE name = '" . $_POST['name'] . "';";
			$result = $mysqli->query($sql);


			
			$row = $result->fetch_assoc();
			$drinkID = $row['id'];
			
			$sql = "INSERT INTO favorite_drinks(users_id, drinks_id)
					VALUES (" . $userID . ", " . $drinkID . ");";

						echo $sql;
	
			$result = $mysqli->query($sql);


			if(!$result) {
				echo $mysqli->error;
				exit();
			}


		}
		else
		{	
			$num_likes = "SELECT totalLikes, id FROM drinks
						  WHERE name = '" . $_POST['name'] . "' AND image = '" . $_POST['image'] . "';";

			$numLikesResult = $mysqli->query($num_likes);
			$likes =  $numLikesResult->fetch_assoc();
			$totalLikes = $likes['totalLikes'] + 1;
			$drinkID = $likes['id'];

			$sql = "UPDATE drinks
			SET totalLikes = " . $totalLikes . "
			WHERE id = " . $drinkID .";";

			$results = $mysqli->query($sql);
			if(!$results) {
				echo $mysqli->error;
				exit();
			}
		}
	}

	$mysqli->close();

?>