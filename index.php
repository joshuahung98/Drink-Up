<?php
	require 'config.php';

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ( $mysqli->connect_errno ) {
		echo $mysqli->connect_error;
		exit();
	}
	$mysqli->close();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Drink Up</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="nav.css">
	<link rel="stylesheet" href="styles.css">
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

	<div class="d-flex align-items-center justify-content-center" id="main">
		<div>
			<h2 class="col-12 text-center"> Find Your Drink Today </h2>
			<h3 class="col-12 text-center"> Recommending Bars and Drinks since 2019 </h3>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>


</body>
</html>