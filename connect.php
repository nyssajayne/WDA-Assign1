<?php

	//Turn on error reporting
	ini_set('display_errors', 'on');
	error_reporting(E_ALL | E_STRICT);

	//Require database credentials
	require('db.php');

	//Establish connection to MySQL
	$conn = mysql_connect(DB_HOST, DB_USER, DB_PW);

	if(!$conn)
	{
		echo '<p>Could not connect to MySQL on ' . DB_HOST . '<br />';
		echo mysql_error();
		echo '</p>';
		exit;
	}
	
	//Establish connection to database
	$dbconn = mysql_select_db(DB_NAME, $conn);

	if(!$dbconn)
	{
		echo '<p>Could not connect to database ' .DB_NAME . '<br />';
		echo mysql_error();
		echo '</p>';
		exit;
	}
