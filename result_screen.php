<?php

	//Require the database and establish connection
	require_once('connect.php');
	
	//Import MiniTemplator
	require_once('MiniTemplator.class.php');

	$t = new MiniTemplator;

	$t->readTemplateFromFile("result_screen_template.html");

	//Retrieve all of the form elements
	$wine_name = $_GET['wine_name'];
	$grape_variety = $_GET['grape_variety'];
	$winery_name = $_GET['winery_name'];
	$region_name = $_GET['region_name'];
	$min_year = $_GET['min_year'];
	$max_year = $_GET['max_year'];
	$on_hand = $_GET['on_hand'];
	$qty = $_GET['qty'];
	$cost = $_GET['cost'];

	//If min year is greater than max year, exit
	if($min_year > $max_year)
	{
		set_result_metadata($t, "The minimum year is higher than the maximum year, please go back and refine the search.");
		$t->generateOutput();
		return;
	}

	//The Monster Query.
	$query ='SELECT wine.wine_id, wine.wine_name, winery.winery_name, region.region_name, wine.year 
		FROM wine, wine_variety, grape_variety, winery, region, inventory, items
		WHERE wine.wine_id = wine_variety.wine_id
		AND wine.wine_id = inventory.wine_id
		AND wine.wine_id = items.wine_id
		AND wine_variety.variety_id = grape_variety.variety_id
		AND wine.winery_id = winery.winery_id
		AND winery.region_id = region.region_id
		AND wine.wine_name LIKE :wine_name 
		AND grape_variety.variety LIKE :grape_variety
		AND winery.winery_name LIKE :winery_name
		AND region.region_name LIKE :region_name
		AND wine.year BETWEEN :min_year AND :max_year
		AND inventory.on_hand > :on_hand';

	if($cost!=null)
	{
		$query .= ' AND inventory.cost < :cost';
	}

	if($qty!=null)
	{
		$query .= ' AND items.qty > :qty';
	}

	$query .= ' GROUP BY wine_id';
	
	$sql = $db->prepare($query);
	$sql->bindValue(':wine_name', '%' .$wine_name. '%', PDO::PARAM_STR);
	$sql->bindValue(':grape_variety', $grape_variety, PDO::PARAM_STR);
	$sql->bindValue(':winery_name', '%' .$winery_name. '%', PDO::PARAM_STR);
	$sql->bindValue(':region_name', '%' .$region_name. '%', PDO::PARAM_STR);
	$sql->bindValue(':min_year', $min_year);
	$sql->bindValue(':max_year', $max_year);
	$sql->bindValue(':on_hand', $on_hand);

	if($cost!=null)
	{
		$sql->bindValue(':cost', $cost);
	}

	if($qty!=null)
	{
		$sql->bindValue(':qty', $qty);
	}

	//If there's something wrong with the query, display the error.
	if($sql->execute())
	{

		$sql->setFetchMode(PDO::FETCH_ASSOC);

		while($row = $sql->fetch())
		{
			$result_id = $row['wine_id'];
			$result_name = $row['wine_name'];
			$result_winery = $row['winery_name'];
			$result_region = $row['region_name'];
			$result_year = $row['year'];

			$t->setVariable("wine_name", $result_name);
			$t->setVariable("winery", $result_winery);
			$t->setVariable("region", $result_region);
			$t->setVariable("year", $result_year);


			//Print the grape varieties (some wines are blends)
			$query_v = 'SELECT grape_variety.variety
				FROM wine, wine_variety, grape_variety
				WHERE wine.wine_id = :result_id
				AND wine.wine_id = wine_variety.wine_id
				AND wine_variety.variety_id = grape_variety.variety_id;';
			
			$sql_v = $db->prepare($query_v);
			$sql_v->bindValue(':result_id', $result_id);
			$sql_v->execute();

			while($row_v = $sql_v->fetch())
			{
				$result_variety = $row_v['variety'];
				
				$t->setVariable("variety", $result_variety);
				$t->addBlock("variety_block");
			}
			

			
			//Fetches quantities and prices, needs the wine_id, which we now have
			$query_s = 'SELECT SUM(qty) AS qty, SUM(price) AS price
				FROM items
				WHERE wine_id = :result_id;';

			$sql_s = $db->prepare($query_s);
			$sql_s->bindValue(':result_id', $result_id);
			$sql_s->execute();

			while($row_s = $sql_s->fetch())
			{
				$result_qty = $row_s['qty'];
				$result_price = $row_s['price'];

				$t->setVariable("qty", $result_qty);
				$t->setVariable("price", $result_price);
				$t->addBlock("inventory_block");
			}
			
			$t->addBlock("wine_block");
		}
	}	

	$t->generateOutput();

	function set_result_metadata($t, $message)
	{
		$t->setVariable("result_metadata", $message);
		$t->addBlock("result_metadata_block");
	}
?>
