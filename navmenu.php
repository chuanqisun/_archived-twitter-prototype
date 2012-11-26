<?php

	//generating navigation bar
	echo '<hr />';
	if(isset($_SESSION['username'])){
		echo 'Welcome '. $_SESSION['username'];
		echo '|';
		echo '<a href="logout.php">Log Out</a>';
	}
	else{
		echo '<a href="login.html">Log In</a>';
		echo '|';
		echo '<a href="signup.html">Sign Up</a>';
	}
	echo '<hr />';
?>
