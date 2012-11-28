<?php

	//start session
	require_once('startsession.php');

	//insert header
	$page_title='Compare Genes';
	require_once('header.php');

	//insert navigation
	require_once('navmenu.php');
?>

<!--compare genes here-->
<?php

	//prepare error message var
	$error_msg="";
	//if not logged in
	if (!isset($_SESSION['u_id'])){
		$error_msg= 'Sorry Please login to compare genes.';
	}

	//if logged in and already posted
	else if (isset($_GET['submit'])){
		//connect to database
		require_once('connectvars.php');
		$dbc=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to MySQL DB');

		//parse info
		$u_id1=$_SESSION['u_id']; //my id
		$u_name1=$_SESSION['u_name'];

		$u_name2=$_GET['u_name'];
		
		//get target id
		$query="SELECT u_id FROM users WHERE u_name='$u_name2'";
		$data=mysqli_query($dbc, $query) or die('Error finding target user');
		$row=mysqli_fetch_array($data);
		$u_id2=$row['u_id'];
		
		if(empty($u_id2)){
			$error_msg= 'Sorry, targer user doesn\'t exist.';
		}else if($u_id1 == $u_id2){
			$error_msg= 'Sorry, cannot compare genes with yourself.';
		}else{
			//fetch common genes into $data
			$query=	"SELECT genes.g_name," .
					" genes_of_user_$u_id1.g_expression AS g_expression1," .
					" genes_of_user_$u_id2.g_expression AS g_expression2" . 
					" FROM genes" .
					" INNER JOIN genes_of_user_$u_id1 ON genes.g_id = genes_of_user_$u_id1.g_id" .
					" INNER JOIN genes_of_user_$u_id2 ON genes_of_user_$u_id1.g_id = genes_of_user_$u_id2.g_id" .
					" GROUP BY genes.g_name";
			$data=mysqli_query($dbc, $query) or die('Error fetching common genes from MySQL');  
		
			//display common genes here
			echo '<table border="0">';
			echo '<tr><th>Gene</th><th>'.$u_name2. '\'s expressions</th><th>your expressions</th></tr>';
			while($row = mysqli_fetch_array($data)){
				echo '<tr>';
				echo '<td>' . $row['g_name'] . '</td>';
				echo '<td>' . $row['g_expression1'] . '</td>';
				echo '<td>' . $row['g_expression2'] . '</td>';
				echo '</tr>';
			}
		echo '</table>';
		}
	}

	//print error
	echo '<p class="error">' . $error_msg . '</p>';

?>

<!--html compare genes form-->

<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset>
		<legend>Compare Genes:</legend>
		<label for="u_name">Target User</label>
		<input type="text" name="u_name" />
	</fieldset>
	<input type="submit" value="Compare" name="submit" />
</form>


<?php

	//insert footer
	require_once('footer.php');
?>
