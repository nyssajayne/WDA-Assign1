<?php

	//include database credentials and establish connection to database
	require('connect.php');

	//Populates grape variety in the dropdown menu below
	function populate_grape_variety()
	{
		$query = 'SELECT variety FROM grape_variety;';

		$result = mysql_query($query);

		while($row = mysql_fetch_array($result))
		{
			$option_value = $row['variety'];
			echo '<option value="'. $option_value .'">'. $option_value .'</option>';
		}	
	}

	function populate_year($order_by)
	{
		if($order_by == 'min')
		{
			$query = 'SELECT DISTINCT year FROM wine ORDER BY year';
		}
		elseif($order_by == 'max')
		{
			$query = 'SELECT DISTINCT year FROM wine ORDER BY year DESC';
		}

		$result = mysql_query($query);

		while($row = mysql_fetch_array($result))
		{
			$option_value = $row['year'];
			echo '<option value"'. $option_value .'">'. $option_value .'</option>';
		}
	}

?>

<html>

	<head>

		<title>Search Winestore Database</title>

	</head>

	<body>

		<h1>Search Winestore Database</h1>

		<form action="result_screen.php" method="GET">

			<p>Wine Name: <input type="text" name="wine_name" id="wine_name" /></p>
			<p>Winery Name: <input type="text" name="winery_name" id="winery_name" /></p>
			<p>Region: <input type="text" name="region_name" id="region_name" /></p>
			<p>Grape Variety:
				 <select>
					<?php populate_grape_variety(); ?>
				</select></p>
			<p>Year, between: 
				<select>
					<?php populate_year('min'); ?>
				</select> and 
				
				<select>
					<?php populate_year('max'); ?>
				</select></p>
			<p>Min. no. of bottles in stock: <input type="text" name="on_hand" id="on_hand" /></p>
			<p>Min. no. of bottles ordered: <input type="text" name="qty" id="qty" /></p>
			<p>Maximum cost: <input type="text" name="cost" id="cost" /></p>

			<p><input type="submit" name="submit" id="submit" value="submit" /></p>

		</form>

	</body>

</html>
