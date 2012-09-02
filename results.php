
<?php 
	include('connect.php');
	include('db.php');

	$wineName = trim($_GET['nameTxt']);
	$wineryName = trim($_GET['wineryTxt']);
	$region = trim($_GET['regionDrpBx']);
	$grapeVariety = trim($_GET['grapeVarietyDrpBx']);
	$startYear = trim($_GET['startyearDrpBx']);
	$endYear = trim($_GET['endyearDrpBx']);
	$stock = trim($_GET['stockTxt']);
	$ordered = trim($_GET['orderedTxt']);
	$mincost = trim($_GET['mincostTxt']);
	$maxcost = trim($_GET['maxcostTxt']);

	if($startYear > $endYear)
	{
		echo "<hr><center><h1>Error : Please select a valid date Range</h1></center><HR>";
		exit;
	}
	else if(empty($stock)||empty($ordered))
	{
		echo "<hr><center><h1>Error: Please  provide both, <b>Minimum Stock</b> and <b>Maximum ordered number </b>limitation </h1></center><hr>";
		exit;
	}
	else if(empty($mincost)||empty($maxcost))
	{
		echo "<hr><center><h1>Error : Please procide both, the <b>Minimum</b> and <b>Maximum</b></h1></center><hr>";
		exit;
	}
	else if($mincost > $maxcost)
	{
		echo "<hr><center><h1>Error: <b>Minimum Cost</b> Cannot be greater than <b>Maximum Cost</b></h1></center><hr>";
		exit;
	}
	
	$searchquery = "SELECT w.wine_name, gv.variety, w.year, wry.winery_name, r.region_name, inv.cost, inv.on_hand, SUM(itm.qty), SUM(itm.price)
					FROM wine w, winery wry, region r, inventory inv, items itm, wine_variety wv, grape_variety gv
					WHERE w.winery_id = wry.winery_id 
						AND wry.region_id = r.region_id
						AND w.wine_id = wv.wine_id
						AND gv.variety_id = wv.variety_id
						AND inv.wine_id = w.wine_id
						AND itm.wine_id = w.wine_id
						AND gv.variety_id = {$grapeVariety}
						";
	if(!empty($wineName)){
		$searchquery .= " AND w.wine_name LIKE '%$wineName%'";
	}
	if(!empty($wineryName)){
		$searchquery .= " AND wry.winery_name LIKE '%$wineryName%'";
	}
	if($region != 1){
		$searchquery .= " AND r.region_id = {$region}";
	}
	if($startYear <= $endYear){
		$searchquery .= " AND w.year BETWEEN {$startYear} AND {$endYear}";
	}
	if(!empty($stock)){
		$searchquery .= " AND inv.on_hand >= {$stock}";
	}
	if($mincost <= $maxcost){
		$searchquery .= " AND inv.cost BETWEEN {$mincost} AND {$maxcost}";
	}
	if(!empty($ordered)){
		$searchquery .= " GROUP BY w.wine_id
						  HAVING SUM(itm.qty)>{$ordered}";
	}
	
	
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html401/loose.dtd">
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>WINE SEARCH RESULT</title>
	</head>
	<body>
	<center>
	<?php
		$result = mysql_query($searchquery, $dbconn);
		$rowFound = @ mysql_fetch_row($result);
		if($rowFound>0){
			print "The below Wines match your criteria<br>";
		  print "\n<table>\n<tr>" .
			  "\n\t<th>Wine Name</th>" .
			  "\n\t<th>Variety</th>" .
			  "\n\t<th>Year</th>" .
			  "\n\t<th>Winery</th>" .
			  "\n\t<th>Region Name</th>\n" .
			  "\n\t<th>Cost</th>\n" .
			  "\n\t<th>Sold</th>\n" .
			  "\n\t<th>Revenue</th>\n</tr>";
		  while ($row = @ mysql_fetch_array($result)) {
			
			print "\n<tr>\n\t<td>{$row['wine_name']}</td>" .
				"\n\t<td>{$row['variety']}</td>" .
				"\n\t<td>{$row['year']}</td>" .
				"\n\t<td>{$row['winery_name']}</td>" .
				"\n\t<td>{$row['region_name']}</td>" .
				"\n\t<td>{$row['cost']}</td>" .
				"\n\t<td>{$row['SUM(itm.qty)']}</td>" .
				"\n\t<td>{$row['SUM(itm.price)']}</td>\n</tr>";
		  } 
		  print "\n</table>";
		} 
		else
			print "<h1>No match found<h1>";
		//echo $searchquery;
		
		/*echo $wineName;
			echo $wineryName;
			echo $region;
			echo $startYear;
			echo $endYear;
			echo "<p>" .$grapeVariety. "</p>";
			echo $stock,$ordered,$mincost,$maxcost;
		*/
		mysql_close($dbconn);
	?>
	</center>
</body>
</html>