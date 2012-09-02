<?php
	include('db.php');
	include('connect.php');
?>
<html>
	<head>
		<title>Wine Search Page</title>
	</head>
	<body>
		<h1>Search Wine</h1><hr>
		<i> Please enter your search criteria below: <i><br><br>
		
		<form action = "results.php" method=GET>
			<table>
				<b>
				<tr>
					<td>Wine Name: </td>
					<td><input type = "text" name = "nameTxt"/></td>
				</tr>
				
				<tr>
					<td>Winery name: </td>
					<td><input type = "text" name = "wineryTxt"/></td>
				</tr>
				
				<tr>
					<td>Region: </td>
					<td>
					<select name = "regionDrpBx">
					<?php
						$regionQry = "SELECT * FROM region order by region_name ASC";
						$regionResult = mysql_query($regionQry, $dbconn);
							while ($row = mysql_fetch_array($regionResult)) 
								{
								  echo "<option value='$row[region_id]'>" .$row[region_name]. "</option>" ;
								}
						  ?>
					</select>
					</td>
				</tr>
				
				<tr>
					<td>Grape variety: </td>
					<td>
					<select name = "grapeVarietyDrpBx">
						<?php	
							$grapeQry = "SELECT * FROM grape_variety order by variety ASC";
							$grapeResult = mysql_query($grapeQry, $dbconn);
							while ($row = mysql_fetch_array($grapeResult)) 
								{
									echo "<option value='$row[variety_id]'>" .$row[variety]. "</option>" ;
								}
						?>
					</select>
					</td>
				</tr>
				
				<tr>
					<td>Range of year: </td>
					<td>
						<select name = "startyearDrpBx">
						<?php 	
							$startyearQry = "select DISTINCT year from wine order by year ASC";
							$startyearResult = mysql_query($startyearQry, $dbconn);
							while ($row = mysql_fetch_array($startyearResult)) 
								{
									echo "<option value='$row[year]'>" .$row[year]. "</option>" ;
								}
						?>
						</select>
						
						<select name = "endyearDrpBx">
						<?php	
							$endyearQry = "select DISTINCT year from wine order by year DESC";
							$endyearResult = mysql_query($endyearQry, $dbconn);
							while ($row = mysql_fetch_array($endyearResult)) {
								echo "<option value='$row[year]'>" .$row[year]. "</option>" ;
								}
						?>
						</select>
					</td>
				</tr>
				
				<tr>
					<td>Minimum Number of wines in stock: </td>
					<td><input type = "text" name = "stockTxt"/></td>
				</tr>
				
				<tr>
					<td>Minimum number of wines ordered: </td>
					<td><input type = "text" name = "orderedTxt"/></td>
				</tr>
			
				<tr>
					<td>Dollar cost range: </td>
					<td>
						Minimum:<input type = "text" name = "mincostTxt"/>
						Maximum:<input type = "text" name = "maxcostTxt"/>
					</td>
				</tr>
				
				<td colspan = "2" align = "center" border="1">
					<input type = "submit" value = "Search" name="submitBtn">
					<input type = "reset" value = "Reset" name="resetBtn">
				</td>
			</b>
			</table>
			<hr>
		</form>
	</body>
</html>