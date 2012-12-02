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
		//display 20 latest updated genes
	}else{
		echo '<p>Welcome to Project G.</p>';
	}
?>

<?php

	//insert footer
	require_once('footer.php');
?>
