<?php

	//generating navigation bar
	echo '<hr />';
	if(isset($_SESSION['username'])){
		echo 'Welcome '. $_SESSION['username'];
		echo '<br />';
		echo '<a href="newgene.php">New Gene</a>';
		echo ' | ';
		echo '<a href="logout.php">Log Out</a>';
	}
	else{
		echo '<a href="login.html">Log In</a>';
		echo ' | ';
		echo '<a href="signup.html">Sign Up</a>';
	}
	echo '<hr />';
?>
