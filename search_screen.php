<?php

	//include database credentials and establish connection to database
	require_once('connect.php');
	
	//MiniTemplator
	require_once('MiniTemplator.class.php');

	$t = new MiniTemplator;

	$t->readTemplateFromFile('search_screen_template.html');

	$query = 'SELECT variety FROM grape_variety;';

	$result = mysql_query($query);

	while($row = mysql_fetch_array($result))
	{
		$option_value = $row['variety'];
		$t->setVariable("variety", $option_value);
		$t->addBlock("grape_variety");
	}	

	$query_min = 'SELECT DISTINCT year FROM wine ORDER BY year';
	
	$result_min = mysql_query($query_min);

	while($row_min = mysql_fetch_array($result_min))
	{
		$option_value = $row_min['year'];
		$t->setVariable("min_year", $option_value);
		$t->addBlock("min_year");
	}

	$query_max = 'SELECT DISTINCT year FROM wine ORDER BY year DESC;';

	$result_max = mysql_query($query_max);

	while($row_max = mysql_fetch_array($result_max))
	{
		$option_value = $row_max['year'];
		$t->setVariable("max_year", $option_value);
		$t->addBlock("max_year");
	}

	$t->generateOutput();
?>
