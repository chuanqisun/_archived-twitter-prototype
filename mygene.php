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

		//dispaly any necessary mesage
		if (!empty($_GET['msg'])) {
			echo '<p class="message">' . $_GET['msg'] . '</p>';	
		}

		//connect to database
		require_once('connectvars.php');
		$dbc=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to MySQL Database');

		//get all genes under this user
		$u_id=$_SESSION['u_id'];
		$query="SELECT genes.g_name, genes_of_user_$u_id.g_expression FROM genes INNER JOIN genes_of_user_$u_id ON genes.g_id=genes_of_user_$u_id.g_id";
		$data=mysqli_query($dbc, $query) or die('Error retrieving user\'s genes');
		echo '<table border="0">';
		echo '<tr><th>Gene</th><th>Expression</th></tr>';
		while($row = mysqli_fetch_array($data)){
			echo '<tr>';
			echo '<td>';
			echo $row['g_name'];
			echo '</td>';
			echo '<td>';
			echo $row['g_expression'];
			echo '</td>';
		}	
		echo '</table>';
	}
?>

<?php

	//insert footer
	require_once('footer.php');
?>
