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
		if( $results->num_rows > 0 ) {
			$num_likes = "SELECT totalLikes, id FROM drinks
						  WHERE name = '" . $_POST['name'] . "' AND image = '" . $_POST['image'] . "';";

			$numLikesResult = $mysqli->query($num_likes);
			$likes =  $numLikesResult->fetch_assoc();
			$totalLikes = $likes['totalLikes'] - 1;

			$drinkID = $likes['id'];


			$sql = "UPDATE drinks
			SET totalLikes = " . $totalLikes . "
			WHERE id = " . $drinkID .";";
		

			$results = $mysqli->query($sql);
			if(!$results) {
				echo $mysqli->error;
				exit();
			}

			$sql = "SELECT id FROM users
					WHERE username = '" . $_SESSION['username'] . "';";
			$result = $mysqli->query($sql);
			$row = $result->fetch_assoc();
			$userID = $row['id'];

			$sql = "DELETE FROM favorite_drinks
					WHERE users_id = " . $userID . " AND drinks_id = " . $drinkID . ";";

			$results = $mysqli->query($sql);
			
		}
	}

	// $mysqli->close();




?>
<!DOCTYPE html>
<html>
<head>
	<title>Drink Up- Cocktails</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<link rel="stylesheet" href="nav.css">
	<link rel="stylesheet" href="cocktailstyles.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body onload="loadRandom();">
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

	<h2 class="display-4 d-flex justify-content-center"></h2>

	<div class="container">
		<form action="" method="POST" id="search-form">
			
			<div class="display-5 d-flex justify-content-center">
				<label for="drink-id" class="col-sm-3 col-form-label text-sm-right">Find a Drink:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="search-id" name="search">
				</div>
			</div> 
		</form>
	</div> 

	<div id="allResults">


	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <script
	src="http://code.jquery.com/jquery-3.4.1.min.js"
	integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
	crossorigin="anonymous"></script>

    <script>

    	function loadRandom() {
    		let xhr = new XMLHttpRequest();
			xhr.open("GET", "https://www.thecocktaildb.com/api/json/v1/1/random.php");
			xhr.send();

			xhr.onreadystatechange = function() {
				if(this.readyState == this.DONE) {
					if(xhr.status == 200)
					{
						let response = JSON.parse(xhr.responseText);
						document.querySelector("h2").innerHTML = "Recommended Drink: " + response.drinks[0].strDrink;
					}
					else 
					{
						console.log("Error");
						console.log(xhr.status);
					}
				}
			}
    	}
    	// When the form is submitted, the magic happens
		document.querySelector("#search-form").onsubmit = function() {
			let searchTermInput = document.querySelector("#search-id").value.trim();
			let xhr = new XMLHttpRequest();
			xhr.open("GET", "https://www.thecocktaildb.com/api/json/v1/1/search.php?s=" + searchTermInput);
			xhr.send();
			// when some kind of response is given to us, run another function
			xhr.onreadystatechange = function() {
				if(this.readyState == this.DONE) {
					// Received some kind of response
					if(xhr.status == 200) {
						// Got a succesful response
						// Convert the responsText JSON string to JS objects
						let responseObjects = JSON.parse(xhr.responseText);
						displayResults(responseObjects);
					}
					else {
						// Got a response, but it's an error
						console.log("Error!!!");
						console.log(xhr.status);
					}
				}
			}
			// prevent form from being submitted
			return false;
		}

		$(document.body).on('click', '.material-icons', function()
		{
			let imageLink = $(this).parent().parent().parent().siblings().get(0).children[0].src;
			let insideHeading = $(this).parent().get(0).innerHTML;
			var drinkName= insideHeading.substr(0, insideHeading.indexOf('<')).trim(); 
			if($(this).css("color") == "rgb(255, 255, 0)")
			{	
				$(this).css('color', "grey");
				$.ajax({
					type: "POST",
					url: 'cocktails.php',
					data: {
			            image: imageLink,
			            name: drinkName
			        },
					success: function(data, status, xhr) {
			            if(xhr.responseText) {
			            	console.log(xhr.responseText);
			            }
			            else {
			            	alert("Successfully added to favorites.");
			          	}
			         },
		          	error: function(xhr, status, error) {
		            	alert("Error adding to favorites.");
		          	}
			   });	
			}
			else
			{
				$(this).css('color', "yellow");
				$.ajax({
					type: "POST",
					url: 'addFavorites.php',
					data: {
			            image: imageLink,
			            name: drinkName
			        },
					success: function(data, status, xhr) {
			            if(xhr.responseText) {
			            	console.log(xhr.responseText);
			            }
			            else {
			            	alert("Successfully added to favorites.");
			          	}
			         },
		          	error: function(xhr, status, error) {
		            	alert("Error adding to favorites.");
		          	}
			   });	
			}

		});

		function displayResults(results) {
			console.log(results);
		
			// Clear out all previous results that are displayed
			let mainBody= document.querySelector("#allResults");
			while( mainBody.hasChildNodes() ) {
				mainBody.removeChild( mainBody.lastChild);
			}

			for(let i = 0; i < results.drinks.length; i++) {
				let container = document.createElement("div");
				container.className = "container";

				let innerRow = document.createElement("div");
				innerRow.className = "row";

				let leftSide = document.createElement("div");
				leftSide.className = "col-md-6";

 				let drinkName = document.createElement("div");
 				drinkName.className = "drinkname";

 				let name = document.createElement("h3");
 				name.innerHTML = results.drinks[i].strDrink;


 				let star = document.createElement("i");
 				star.className = "material-icons";
 				star.innerHTML = "star";

 				let descriptions = document.createElement("div");
 				descriptions.className = "descriptions";

 				//adding all the ingredients
 				descriptions.innerHTML = "Ingredients: " + results.drinks[i].strIngredient1 + ", ";
				if(results.drinks[i].strIngredient2 != "") {
					descriptions.innerHTML += results.drinks[i].strIngredient2;
					if(results.drinks[i].strIngredient3 != "") {
						descriptions.innerHTML += ", ";
					}
				}
				if(results.drinks[i].strIngredient3 != "") {
					descriptions.innerHTML += results.drinks[i].strIngredient3;
					if(results.drinks[i].strIngredient4 != "") {
						descriptions.innerHTML += ", ";
					}
				}
				if(results.drinks[i].strIngredient4 != "") {
					descriptions.innerHTML += results.drinks[i].strIngredient4;
					if(results.drinks[i].strIngredient5 != "") {
						descriptions.innerHTML += ", ";
					}
				}
				if(results.drinks[i].strIngredient5 != "") {
					descriptions.innerHTML += results.drinks[i].strIngredient5;
					if(results.drinks[i].strIngredient6 != "") {
						descriptions.innerHTML += ", ";
					}
				}
				if(results.drinks[i].strIngredient6 != "") {
					descriptions.innerHTML += results.drinks[i].strIngredient6;
					if(results.drinks[i].strIngredient7 != "") {
						descriptions.innerHTML += ", ";
					}
				}
				if(results.drinks[i].strIngredient7 != "") {
					descriptions.innerHTML += results.drinks[i].strIngredient7;
					if(results.drinks[i].strIngredient8 != "") {
						descriptions.innerHTML += ", ";
					}
				}
				if(results.drinks[i].strIngredient8 != "") {
					descriptions.innerHTML += results.drinks[i].strIngredient8;
					if(results.drinks[i].strIngredient9 != "") {
						descriptions.innerHTML += ", ";
					}
				}
				if(results.drinks[i].strIngredient9 != "") {
					descriptions.innerHTML += results.drinks[i].strIngredient9;
					if(results.drinks[i].strIngredient10 != "") {
						descriptions.innerHTML += ", ";
					}
				}
				if(results.drinks[i].strIngredient10 != "") {
					descriptions.innerHTML += results.drinks[i].strIngredient10;
					if(results.drinks[i].strIngredient11 != "") {
						descriptions.innerHTML += ", ";
					}
				}
				if(results.drinks[i].strIngredient11 != "") {
					descriptions.innerHTML += results.drinks[i].strIngredient11;
					if(results.drinks[i].strIngredient12 != "") {
						descriptions.innerHTML += ", ";
					}
				}
				if(results.drinks[i].strIngredient12 != "") {
					descriptions.innerHTML += results.drinks[i].strIngredient12;
					if(results.drinks[i].strIngredient13 != "") {
						descriptions.innerHTML += ", ";
					}
				}
				if(results.drinks[i].strIngredient13 != "") {
					descriptions.innerHTML += results.drinks[i].strIngredient13;
					if(results.drinks[i].strIngredient14 != "") {
						descriptions.innerHTML += ", ";
					}
				}
				if(results.drinks[i].strIngredient14 != "") {
					descriptions.innerHTML += results.drinks[i].strIngredient14;
					if(results.drinks[i].strIngredient15 != "") {
						descriptions.innerHTML += ", ";
					}
				}
				if(results.drinks[i].strIngredient2 != "") {
					descriptions.innerHTML += results.drinks[i].strIngredient15;
				}
				// descriptions.innerHTML -= ",";

 				descriptions.innerHTML += "<br> Instructions: " + results.drinks[i].strInstructions;

 				let rightSide = document.createElement("div");
 				rightSide.className = "col-md-6 text-center";

 				let imgElement = document.createElement("img");
 				imgElement.src = results.drinks[i].strDrinkThumb;

 				// star.appendChild(polygon);
 				name.appendChild(star);
 				rightSide.append(imgElement);
 				drinkName.appendChild(name);
 				leftSide.appendChild(drinkName);
 				leftSide.appendChild(descriptions);
 				innerRow.appendChild(leftSide);
 				innerRow.appendChild(rightSide);
 				container.appendChild(innerRow)
				mainBody.appendChild(container);
			}
		}





    </script>


	
</body>
</html>