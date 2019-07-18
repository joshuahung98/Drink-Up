<?php
	require 'config.php';

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ( $mysqli->connect_errno ) {
		echo $mysqli->connect_error;
		exit();
	}

	if ( !isset($_POST['firstname']) || empty($_POST['firstname'])
		|| !isset($_POST['email']) || empty($_POST['email'])
		|| !isset($_POST['username']) || empty($_POST['username'])
		|| !isset($_POST['password']) || empty($_POST['password']) ) {
		$error = "Please fill out all required fields.";
	}
	else
	{
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if($mysqli->connect_errno) {
			echo $mysqli->connect_error;
			exit();
		}

		// Check if username or email address already exists in the users table
		$sql_registered = "SELECT * FROM users
			WHERE username = '" . $_POST['username'] ."' OR email = '" . $_POST['email'] ."';
		";

		// echo "<hr>" . $sql_registered . "<hr>";
		// Submit the query
		$results_registered = $mysqli->query($sql_registered);
		if(!$results_registered) {
			echo $mysqli->error;
			exit();
		}
		// var_dump($results_registered);
		// If at least one result is given back to us, we need to display an error.
		if( $results_registered->num_rows > 0 ) {
			$error = "The username or email given has already been used. Please sign up with another account or login.";
		}
		else {
			// Run password through hashing algorithm
			$hashedPassword = hash('sha256', $_POST['password']);
			// var_dump($hashedPassword);

			//checking the optional last name
			if( isset($_POST["lastname"]) && !empty($_POST["lastname"]) ) {
				// User entered in last name
				$last_name =  $_POST["lastname"];
			}
			else {
				// User did not enter in a bytes
				$last_name = "null";
			}

			// Store user
			$sql = "INSERT INTO users(fName, lName, email, password, username)
					VALUES('" . $_POST['firstname'] . "', '" . $last_name . "', '" . $_POST['email'] . "', '" . $hashedPassword . "', '" . $_POST['username'] . "');
			";
			// echo "<hr>" . $sql . "<hr>";

			// Submit the sql statement to the DB
			$results = $mysqli->query($sql);
			if(!$results) {
				echo $mysqli->error;
				exit();
			}
			else 
			{
				$_SESSION['logged_in'] = true;
				$_SESSION['username'] = $_POST['username'];
				// redirect user to the home page
				header('Location: index.php');
			}

		}

	}

	$mysqli->close();



?>
<!DOCTYPE html>
<html>
<head>
	<title>Drink Up- Register</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="nav.css">
	<link rel="stylesheet" href="registerstyles.css">
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
			<h2 class="col-12 text-center">Join the Club</h2>
			<br> <br> <br>
		</div> <!-- .row -->
	</div> <!-- .container -->

	<div class="container">

		<form action="register.php" method="POST">

			<div class="form-group row">
				<label for="username-id" class="col-sm-3 col-form-label text-sm-right">First Name: <span class="text-danger">*</span></label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="firstname-id" name="firstname">
					<small id="username-error" class="invalid-feedback">First name is required.</small>
				</div>
			</div> <!-- .form-group -->

			<div class="form-group row">
				<label for="username-id" class="col-sm-3 col-form-label text-sm-right">Last Name: </label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="lastname-id" name="lastname">
				</div>
			</div> <!-- .form-group -->

			<div class="form-group row">
				<label for="username-id" class="col-sm-3 col-form-label text-sm-right">Username: <span class="text-danger">*</span></label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="username-id" name="username">
					<small id="username-error" class="invalid-feedback">Username is required.</small>
				</div>
			</div> <!-- .form-group -->

			<div class="form-group row">
				<label for="email-id" class="col-sm-3 col-form-label text-sm-right">Email: <span class="text-danger">*</span></label>
				<div class="col-sm-6">
					<input type="email" class="form-control" id="email-id" name="email">
					<small id="email-error" class="invalid-feedback">Email is required.</small>
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
					<button type="submit" class="btn btn-info btn-block">Register</button>
					<a href="" role="button" class="btn btn-light btn-block">Cancel</a>
				</div>
			</div> <!-- .form-group -->

			<div class="row">
				<div class="col-sm-9 ml-sm-auto">
					<a href="login.php">Already have an account</a>
				</div>
			</div> <!-- .row -->

			<div class="row">
				<div class="ml-auto col-sm-9">
					<span class="text-danger font-italic">* Required</span>
				</div>
			</div> <!-- .form-group -->

		</form>

	</div>


	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>

    <script>
    	document.querySelector('form').onsubmit = function(){
    		if ( document.querySelector('#firstname-id').value.trim().length == 0 ) {
				document.querySelector('#firstname-id').classList.add('is-invalid');
			} else {
				document.querySelector('#firstname-id').classList.remove('is-invalid');
			}
			
			if ( document.querySelector('#username-id').value.trim().length == 0 ) {
				document.querySelector('#username-id').classList.add('is-invalid');
			} else {
				document.querySelector('#username-id').classList.remove('is-invalid');
			}

			if ( document.querySelector('#email-id').value.trim().length == 0 ) {
				document.querySelector('#email-id').classList.add('is-invalid');
			} else {
				document.querySelector('#email-id').classList.remove('is-invalid');
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