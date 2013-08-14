<?php

	//Require the database and establish connection
	require('connect.php');

	function display_results()
	{
		$wine_name = $_GET['wine_name'];
		$grape_variety = $_GET['grape_variety'];
		$winery_name = $_GET['winery_name'];
		$region_name = $_GET['region_name'];
		$min_year = $_GET['min_year'];
		$max_year = $_GET['max_year'];
		$on_hand = $_GET['on_hand'];
		$qty = $_GET['qty'];
		$cost = $_GET['cost'];

		if($cost!=null)
		{
			$query_cost = 'AND inventory.cost < \''. $cost .'\'';
		}
		else
		{
			$query_cost = '';
		}

		if($qty!=null)
		{
			$query_qty = 'AND items.qty > \''. $qty .'\'';
		}
		else
		{
			$query_qty = '';
		}

		$query = 'SELECT wine.wine_id, wine.wine_name, winery.winery_name, region.region_name, wine.year 
			FROM wine, wine_variety, grape_variety, winery, region, inventory, items
			WHERE wine.wine_id = wine_variety.wine_id
			AND wine.wine_id = inventory.wine_id
			AND wine.wine_id = items.wine_id
			AND wine_variety.variety_id = grape_variety.variety_id
			AND wine.winery_id = winery.winery_id
			AND winery.region_id = region.region_id
			AND wine.wine_name LIKE \'%'. $wine_name .'%\' 
			AND grape_variety.variety LIKE \'%'. $grape_variety  .'%\'
			AND winery.winery_name LIKE \'%'. $winery_name .'%\'
			AND region.region_name LIKE \'%'. $region_name .'%\'
			AND wine.year BETWEEN \''. $min_year .'\' AND \''. $max_year .'\'
			AND inventory.on_hand > \''. $on_hand .'\'
			'. $query_cost .'
			'. $query_qty .'
			GROUP BY wine.wine_id;
			;';

		$result = mysql_query($query);

		if($result == FALSE)
		{
			echo mysql_error();
		}

		if(mysql_num_rows($result) <= 0)
		{
			echo '<p>No results</p>';
		}
		else
		{
			echo '<table>';
			echo '<tr>';
			echo '<td>Wine Name</td>';
			echo '<td>Winery</td>';
			echo '<td>Region</td>';

			while($row = mysql_fetch_array($result))
			{
				$result_id = $row['wine_id'];
				$result_name = $row['wine_name'];
				$result_winery = $row['winery_name'];
				$result_region = $row['region_name'];
				$result_year = $row['year'];

				echo '<p>'. $result_id .' '. $result_name .', '. $result_winery .', '. $result_region .', ';
			
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

				echo $result_year .', ';

				$query_s = 'SELECT SUM(qty) AS qty, SUM(price) AS price
					FROM items
					WHERE wine_id = \''. $result_id  .'\';';

				$result_s = mysql_query($query_s);

				while($row_s = mysql_fetch_array($result_s))
				{
					$result_qty = $row_s['qty'];
					$result_price = $row_s['price'];

					echo $result_qty .', '. $result_price .'</p>';
				}
			}
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
