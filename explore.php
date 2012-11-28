<?php
	  
	//start session
	require_once('startsession.php');
	
	//Insert header
	$page_title='Explore';
	require_once('header.php');

	//Insert nabigation menu
	require_once('navmenu.php');
?>


<?php
	//do something here
	$error_msg="";
	//if not logged in
	if (!isset($_SESSION['u_id'])){
		$error_msg= 'Sorry, please login to start exploring.';
	}

	//if logged in and already posted
	else if (isset($_GET['submit'])){
		//connect to database
		require_once('connectvars.php');
		$dbc=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die ('Error connecting to MySQL DB');
		
		//parse info
		$type=$_GET['type'];
		//explore user
		if (($type == 'u_name') && ($_GET['name'] != '')){
			$u_name=$_GET['name'];

			//get u_id of tartget user name
			$query="SELECT u_id FROM users WHERE u_name='$u_name'";
			$data=mysqli_query($dbc, $query);
			$checkuser=mysqli_num_rows($data);
			if ($checkuser == 0) {
				$error_msg="User doesn't exist.";
			}else{
				$row=mysqli_fetch_array($data);
				$u_id=$row['u_id'];
	
				$query= "SELECT genes.g_name, genes_of_user_$u_id.g_expression FROM genes INNER JOIN genes_of_user_$u_id" . 
						" ON genes_of_user_$u_id.g_id = genes.g_id";
				$data=mysqli_query($dbc, $query) or die ('Error retrieving user\'s genes');
				echo '<p> Genes of ' . $u_name ;
				echo '<a href="compare.php?u_name=' . $u_name . '&submit=Compare">(compare)</a>';
				echo '</p>';
				echo '<table boarder="0">';
				echo '<tr><th>Gene</th><th>Expression</th>';
				while($row = mysqli_fetch_array($data)){
					echo '<tr>';
					echo '<td>';
					echo $row['g_name'];
					echo '(<a href="evolve.php?g_name=' . $row['g_name'] . '">evolve</a>)';
					echo '</td>';
					echo '<td>'. $row['g_expression'] . '</td>';
					echo '</tr>';
				}
				echo '</table>';
			}
		}

		//explore gene
		else if (($type == 'g_name') && ($_GET['name'] != '')){
			$g_name=$_GET['name'];
	
			//get g_id of tartget gene name
			$query="SELECT g_id FROM genes WHERE g_name='$g_name'";
			$data=mysqli_query($dbc, $query);
			$checkgene=mysqli_num_rows($data);
			if ($checkgene == 0) {
				$error_msg='Gene ' . $g_name . ' doesn\'t exist.' .
						   ' <a href="evolve.php?g_name=' . $g_name . '">Create</a> it?';
			}else{
				$row=mysqli_fetch_array($data);
				$g_id=$row['g_id'];

				$query= "SELECT users.u_id, users.u_name FROM users INNER JOIN users_of_gene_$g_id" . 
						" ON users_of_gene_$g_id.u_id = users.u_id";
				$data=mysqli_query($dbc, $query) or die ('Error retrieving user\'s genes');
				echo '<p> Owners of ' . $g_name;
				echo '<a href="evolve.php?g_name=' . $g_name . '">(evolve)</a>';
				echo '</p>';
				echo '<table boarder="0">';
				echo '<tr><th>User</th><th>Expression<th>';
				while($row = mysqli_fetch_array($data)){
					echo '<tr>';
					echo '<td>';
					echo $row['u_name'];
					echo '(<a href="compare.php?u_name=' . $row['u_name'] . '&submit=Compare">compare</a>';
					echo '|';
					echo '<a href="explore.php?type=u_name&name=' . $row['u_name'] . '&submit=Explore">explore</a>)';
					echo '</td>';
					//get the last expression of u_name on this gene
					$u_id=$row['u_id'];
					$inner_query="SELECT g_expression FROM genes_of_user_$u_id WHERE g_id=$g_id ORDER BY g_expression DESC LIMIT 1";  //fix: order by time
					$inner_data=mysqli_query($dbc, $inner_query) or die ('Error retrieving expression');
					$inner_row=mysqli_fetch_array($inner_data);
					$g_expression=$inner_row['g_expression'];
					echo '<td>';
					echo $g_expression;
					echo '</td>';
					echo '</tr>';
				}
				echo '</table>';
			}
		}

		//invalid explore
		else{
			$error_msg= "Please select the type and provide a name.";
		}

		//display error
		echo '<p class="error">' . $error_msg . '</p>';
	}
?>

<!-- html explore form -->

<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset>
		<legend>Explore</legend>
		<input type="radio" name="type" value="u_name">Human<br>
		<input type="radio" name="type" value="g_name">Gene<br>
		<label for="name">Name</label>
		<input type="text" name="name" />
	</fieldset>
	<input type="submit" value="Explore" name="submit" />
</form>

<?php

	//insert footer
	require_once('footer.php');
?>
