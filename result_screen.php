<?php

	//Require the database and establish connection
	require('connect.php');

	function display_results()
	{
		$wine_name = $_GET['wine_name'];
		$grape_variety = $_GET['grape_variety'];

		$query = 'SELECT wine.wine_id, wine.wine_name 
			FROM wine, wine_variety, grape_variety
			WHERE wine.wine_id = wine_variety.wine_id
			AND wine_variety.variety_id = grape_variety.variety_id 
			AND wine.wine_name LIKE \'%'. $wine_name .'%\' 
			AND grape_variety.variety LIKE \''. $grape_variety  .'\';';

		$result = mysql_query($query);

		if($result == FALSE)
		{
			echo mysql_error();
		}

		while($row = mysql_fetch_array($result))
		{
			$result_id = $row['wine_id'];
			$result_name = $row['wine_name'];

			echo '<p>'. $result_id .' '. $result_name .', ';
			
			$query_v = 'SELECT grape_variety.variety
				FROM wine, wine_variety, grape_variety
				WHERE wine.wine_id = \''. $result_id  .'\'
				AND wine.wine_id = wine_variety.wine_id
				AND wine_variety.variety_id = grape_variety.variety_id;';

			$result_v = mysql_query($query_v);
			
			while($row_v = mysql_fetch_array($result_v))
			{
				$result_variety = $row_v['variety'];

				echo $result_variety .', ';
			}

			echo '</p>';
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
