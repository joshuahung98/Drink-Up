<?php
	
	session_start(); // "initiate" session before you can destroy it
	header('Location: index.php');
	session_destroy();
	// To log out, i need "clear" out SESSION
?>