<?php

	session_start();

	require_once('MiniTemplator.class.php');

	$stored_wines = $_SESSION['stored_wines'];

	$t = new MiniTemplator;

	$t->readTemplateFromFile("previous_results_template.html");

	foreach($stored_wines as &$value)
	{
		$t->setVariable("wine_name", $value);

		$t->addBlock("wines");
	}

	$t->generateOutput();
?>
