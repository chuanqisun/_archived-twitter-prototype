<?php

	//Start the session
	require_once('startsession.php');

	//Insert header
	$page_title='New Gene';
	require_once('header.php');


	//Show the navigation menu
	require_once('navmenu.php');
?>

<?php
	
	//prepare erorr message var
	$error_msg="";
	//if not even logged in
	if (!isset($_SESSION['id'])){
		$error_msg= 'Sorry Please login to add new gene.';
	}

	//if logged in and already posted
	else if (isset($_POST['submit'])){
		//parse and insert to database here
		//Connect to database
		require_once('connectvars.php');
		$dbc=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to MySQL Database');
	}

	//print error
	echo '<p class="error">' . $error_msg . '</p>';
	
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset>
		<legend>New Gene</legend>
		<label for="genename">Gene Name:</label>
		<input type="text" name="genename" />
		<label for="expression">Gene Expression:</label>
		<input type="text" name="geneexpression" />
	</fieldset>
	<input type="submit" value="Add Gene" name="submit" />
</form>

<?php
	//insert footer
	require_once('footer.php');
?>




