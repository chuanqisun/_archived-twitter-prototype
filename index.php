<?php
	  
	//start session
	require_once('startsession.php');
	
	//Insert header
	$page_title='Home';
	require_once('header.php');

	//Insert nabigation menu
	require_once('navmenu.php');
?>

	<!--display genes here-->
<?php

	//check if logged in
	if (isset($_SESSION['u_id'])){
		echo '<p>Welcome ' . $_SESSION['u_name'] . '.</p>';
		//display 10 most popular genes
		require_once('connectvars.php');
		$dbc=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to MySQL Database');

		$query="SELECT g_name, g_expression_count, g_update_time FROM genes ORDER BY g_expression_count DESC LIMIT 10";
		$data=mysqli_query($dbc, $query) or die ('Error fetching genes');
		echo '<p>Most Popular</p>';
		echo '<table boarder="0">';
		echo '<tr><th>Gene</th><th>Population</th><th>Last Mutation</th>';
		while($row = mysqli_fetch_array($data)){
			echo '<tr>';
			echo '<td>';
			echo '(<a href="evolve.php?g_name=' . $row['g_name'] . '">evolve</a>';
			echo '|';
			echo '<a href="explore.php?type=g_name&name=' . $row['g_name'] . '&submit=Explore">explore</a>)';
			echo $row['g_name'];
			echo '</td>';
			echo '<td>';
			echo $row['g_expression_count'];
			echo '</td>';
			echo '<td>';
			echo $row['g_update_time'];
			echo '</td>';
			echo '</tr>';
		}
		echo '</table>';

		//display 10 latest updates
		$query="SELECT g_id, g_name, g_update_time FROM genes ORDER BY g_update_time DESC LIMIT 10"; 
		$data=mysqli_query($dbc, $query) or die ('Error fecting genes');
		echo '<p>Lastest Updates</p>';
		echo '<table boarder="0">';
		echo '<tr><th>Gene</th><th>Last Expression</th><th>Last Mutation</th>';
		while($row = mysqli_fetch_array($data)){
			$g_name=$row['g_name'];
			echo '<tr>';
			echo '<td>';
			echo '(<a href="evolve.php?g_name=' . $g_name . '">evolve</a>';
			echo '|';
			echo '<a href="explore.php?type=g_name&name=' . $g_name . '&submit=Explore">explore</a>)';
			echo $g_name;
			echo '</td>';
			echo '<td>';
			//inner query to get the gene's own table
			$inner_query="SELECT g_id FROM genes WHERE g_name='$g_name'";
			$inner_data=mysqli_query($dbc, $inner_query);
			$inner_row=mysqli_fetch_array($inner_data);
			$g_id=$inner_row['g_id'];
			//inner query to get the latest user of this gene
			$inner_query="SELECT u_id FROM users_of_gene_$g_id ORDER BY g_update_time DESC LIMIT 1";
			$inner_data=mysqli_query($dbc, $inner_query);
			$inner_row=mysqli_fetch_array($inner_data);
			$u_id=$inner_row['u_id'];
			//inner query to get the latest expression from this user
			$inner_query="SELECT g_expression FROM genes_of_user_$u_id WHERE g_id=$g_id ORDER BY g_update_time DESC";
			$inner_data=mysqli_query($dbc, $inner_query);
			$inner_row=mysqli_fetch_array($inner_data);
			$g_expression=$inner_row['g_expression'];
			echo $g_expression;
			echo '</td>';
			echo '<td>';
			echo $row['g_update_time'];
			echo '</td>';
			echo '</tr>';
		}
		echo '</table>';



	}else{
		echo '<p>Welcome to Project G.</p>';
	}
?>

<?php

	//insert footer
	require_once('footer.php');
?>
