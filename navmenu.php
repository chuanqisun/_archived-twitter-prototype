<?php

	//generating navigation bar
	echo '<hr />';
	if(isset($_SESSION['u_name'])){
		echo '<a href="index.php">Home</a>';
		echo ' | ';
		echo '<a href="evolve.php">Evolve</a>';
		echo ' | ';
		echo '<a href="compare.php">Compare</a>';
		echo ' | ';
		echo '<a href="explore.php">Explore</a>';
		echo ' | ';
		echo '<a href="mygene.php">My Genes</a>';
		echo ' | ';
		echo '<a href="logout.php">Logout('.$_SESSION['u_name'].')</a>';
	}
	else{
		echo '<a href="login.html">Log In</a>';
		echo ' | ';
		echo '<a href="signup.html">Sign Up</a>';
	}
	echo '<hr />';
?>
