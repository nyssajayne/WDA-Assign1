<?php

	//include database credentials and establish connection to database
	require_once('connect.php');
	
	//MiniTemplator
	require_once('MiniTemplator.class.php');

	session_start();

	$t = new MiniTemplator;

	$t->readTemplateFromFile('search_screen_template.html');

	$query = 'SELECT variety FROM grape_variety;';

	foreach($db->query($query) as $row)
	{
		$option_value = $row['variety'];
		$t->setVariable("variety", $option_value);
		$t->addBlock("grape_variety");
	}	

	$query_min = 'SELECT DISTINCT year FROM wine ORDER BY year';
	
	foreach($db->query($query_min) as $row_min)
	{
		$option_value = $row_min['year'];
		$t->setVariable("min_year", $option_value);
		$t->addBlock("min_year");
	}

	$query_max = 'SELECT DISTINCT year FROM wine ORDER BY year DESC;';

	foreach($db->query($query_max) as $row_max)
	{
		$option_value = $row_max['year'];
		$t->setVariable("max_year", $option_value);
		$t->addBlock("max_year");
	}

	$t->generateOutput();
?>
