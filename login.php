<?php
	require 'config.php';

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ( $mysqli->connect_errno ) {
		echo $mysqli->connect_error;
		exit();
	}

	if ( isset($_POST['username']) && isset($_POST['password']) ) {
		// If login form was submitted, was username and password filled out?
		if ( empty($_POST['username']) || empty($_POST['password']) ) {
			$error = "Please enter username and password.";
		}
		else {
			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if($mysqli->connect_errno) {
				echo $mysqli->connect_error;
				exit();
			}

			$passwordInput = hash('sha256', $_POST['password']);

			$sql = "SELECT * FROM users
						WHERE username = '" . $_POST['username'] . "' AND password = '" . $passwordInput . "';";
			// echo "<hr>" . $sql . "<hr>";
			
			$results = $mysqli->query($sql);
			var_dump($results);

			if(!$results) {
				echo $mysqli->error;
				exit();
			}

			if($results->num_rows > 0) {
				// If a result is found, that means the username/pw combo is correct!
				$_SESSION['logged_in'] = true;
				$_SESSION['username'] = $_POST['username'];
				// redirect user to the home page
				header('Location: index.php');
			
			}
			else {
				$error = "Invalid username or password.";
			}
		} 
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Drink Up- Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="nav.css">
	<link rel="stylesheet" href="loginstyles.css">
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

		<div class="container">
			<div class="row">
				<h2 class="col-12 text-center">Login</h2>
			</div> <!-- .row -->
		</div> <!-- .container -->

		<div class="container">
			<form action="login.php" method="POST">

				<div class="row mb-3">
					<div class="font-italic text-danger col-sm-9 ml-sm-auto">					
					</div>
				</div> <!-- .row -->

				<div class="form-group row">
					<label for="username-id" class="col-sm-3 col-form-label text-sm-right">Username: <span class="text-danger">*</span></label>
					<div class="col-sm-6">
						<input type="text" class="form-control" id="username-id" name="username">
						<small id="username-error" class="invalid-feedback">Username is required.</small>
					</div>
				</div> <!-- .form-group -->

				<div class="form-group row">
					<label for="password-id" class="col-sm-3 col-form-label text-sm-right">Password: <span class="text-danger">*</span></label>
					<div class="col-sm-6">
						<input type="password" class="form-control" id="password-id" name="password">
						<small id="password-error" class="invalid-feedback">Password is required.</small>
					</div>
				</div> <!-- .form-group -->


				<div class="form-group row">
					<div class="col-sm-3"></div>
					<div class="col-sm-6 mt-2">
						<button type="submit" class="btn btn-info btn-block">Login</button>
						<a href="" role="button" class="btn btn-light btn-block">Cancel</a>
					</div>
				</div> <!-- .form-group -->
			</form>

			<div class="row">
				<div class="col-sm-9 ml-sm-auto">
					<a href="register.php">Create an account</a>
				</div>
			</div> <!-- .row -->
		</div>

	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>

    <script>
    	document.querySelector('form').onsubmit = function(){
			
			if ( document.querySelector('#username-id').value.trim().length == 0 ) {
				document.querySelector('#username-id').classList.add('is-invalid');
			} else {
				document.querySelector('#username-id').classList.remove('is-invalid');
			}
			
			if ( document.querySelector('#password-id').value.trim().length == 0 ) {
				document.querySelector('#password-id').classList.add('is-invalid');
			} else {
				document.querySelector('#password-id').classList.remove('is-invalid');
			}
			return ( !document.querySelectorAll('.is-invalid').length > 0 );
		}
    </script>
</body>
</html>