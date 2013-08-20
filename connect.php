<?php

	//Turn on error reporting
	ini_set('display_errors', 'on');
	error_reporting(E_ALL | E_STRICT);

	//Require database credentials
	require_once('db.php');

	//Establish connection
	try
	{
		$dsn = DB_ENGINE .':host='. DB_HOST .';dbname='. DB_NAME;
		$db = new PDO($dsn, DB_USER, DB_PW);
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}
?>
