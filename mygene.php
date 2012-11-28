<?php
	  
	//start session
	require_once('startsession.php');
	
	//Insert header
	$page_title='My Genes';
	require_once('header.php');

	//Insert nabigation menu
	require_once('navmenu.php');
?>

	<!--display genes here-->
<?php

	//check if logged in
	if (!isset($_SESSION['u_id'])){
		echo '<p>Please login to display your genes</p>';
	}else{
	
		//connect to database
		require_once('connectvars.php');
		$dbc=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to MySQL Database');

		//get all genes under this user
		$u_id=$_SESSION['u_id'];
		$query="SELECT genes.g_name, genes_of_user_$u_id.g_expression FROM genes INNER JOIN genes_of_user_$u_id ON genes.g_id=genes_of_user_$u_id.g_id";
		$data=mysqli_query($dbc, $query) or die('Error retrieving user\'s genes');
		while($row = mysqli_fetch_array($data)){
			echo $row['g_name'] . " " . $row['g_expression'];
			echo "<br />";
		}	
	}
?>

<?php

	//insert footer
	require_once('footer.php');
?>
