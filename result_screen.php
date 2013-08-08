<?php

	//Require the database and establish connection
	require('connect.php');

	function display_results()
	{
		$wine_name = $_GET['wine_name'];

		$query = 'SELECT * FROM wine WHERE wine_name LIKE \'%'. $wine_name .'%\';';

		$result = mysql_query($query);

		if($result == FALSE)
		{
			echo mysql_error();
		}

		while($row = mysql_fetch_array($result))
		{
			$result_id = $row['wine_id'];
			$result_name = $row['wine_name'];

			echo '<p>'. $result_id .' '. $result_name .'</p>';
		}
	}

?>

<html>

	<head>

		<title>Winestore Results</title>

	</head>

	<body>

		<h1>Winestore Results</h1>

		<p><?php echo display_results(); ?></p>

	</body>

</html>
