<?php

	//generating navigation bar
	echo '<hr />';
	if(isset($_SESSION['u_name'])){
		echo '<a href="index.php">home</a>';
		echo ' | ';
		echo '<a href="evolve.php">evolve</a>';
		echo ' | ';
		echo '<a href="compare.php">compare</a>';
		echo ' | ';
		echo '<a href="explore.php">explore</a>';
		echo ' | ';
		echo '<a href="mygene.php">my genes</a>';
		echo ' | ';
		echo '<a href="logout.php">logout('.$_SESSION['u_name'].')</a>';
	}
	else{
		echo '<a href="login.php">login</a>';
		echo ' | ';
		echo '<a href="signup.php">sign-up</a>';
	}
	echo '<hr />';
?>
