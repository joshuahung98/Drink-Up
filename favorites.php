<?php
	require 'config.php';

	if(!isset($_SESSION['username']) || empty($_SESSION['username']))
	{
		$error = "Not logged in.";
	}
	else
	{
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ( $mysqli->connect_errno ) {
			echo $mysqli->connect_error;
			exit();
		}

		$sql = "SELECT id FROM users
						WHERE username = '" . $_SESSION['username'] . "';";
		$result = $mysqli->query($sql);
		$row = $result->fetch_assoc();
		$userID = $row['id'];

		$sql = "SELECT drinks.name AS name, drinks.totalLikes AS likes, drinks.image AS image
				FROM favorite_drinks
				LEFT JOIN drinks
					ON drinks.id = favorite_drinks.drinks_id
				WHERE users_id = " . $userID . ";";

		$results = $mysqli->query($sql);
		if(!$results) {
			echo $mysqli->error;
			exit();
		}

		$mysqli->close();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Drink Up- Favorites</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="nav.css">
	<link rel="stylesheet" href="favoritesstyles.css">
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark">
		<div id="logo">
			<img src="images/logo.png" id="logo-img">
			<h1> <a href="index.php">Drink Up</a> </h1>
		</div>
		  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
		    <span class="navbar-toggler-icon"></span>
		  </button>
		  <div class="navbar-collapse collapse w-100 order-3 dual-collapse2" id="navbarNav">
		    <ul class="navbar-nav ml-auto">

		      <li class="nav-item">
		        <a class="nav-link" href="cocktails.php">Cocktails</a>
		      </li>
		      <?php if( !isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>

				<li class="nav-item">
			        <a class="nav-link" href="register.php">Register <span class="sr-only">(current)</span></a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link" href="login.php">Login</a>
			    </li>

			<?php else: ?>
				<li class="nav-item">
			        <a class="nav-link" href="favorites.php">Favorites</a>
			    </li>
			    <li class="nav-item">
			    	<a class="nav-link" href="logout.php">Log out</a>
			    </li>
			<?php endif; ?>
		    </ul>
		  </div>

	</nav>
		
	<div class="container-fluid">
		<div class="row">
			<?php if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>
				NOT LOGGED IN
			<?php else: ?>
				<?php while( $row = $results->fetch_assoc() ) : ?>
					<div class="col-6 col-md-4 col-lg-4 indiv-drink">
						<div class="drinkInfo">
							<img src="<?php echo $row['image']; ?>">
							<p class="drinkname"><?php echo $row['name'] ?> </p>
							<p class="likes">Likes: <?php echo $row['likes'] ?></p>
						</div>
					</div>

				<?php endwhile;?>
			<?php endif; ?>
		</div>
	</div>


	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>
</html>