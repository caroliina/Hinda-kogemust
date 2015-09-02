
<!doctype html>
<html>
    <head>
        <meta charset= "utf-8">
        <link rel="stylesheet" type="text/css" href="main.css">
        <link rel="stylesheet" media="screen" href="http://openfontlibrary.org/face/fantasque-sans-mono" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" media="screen" href="http://openfontlibrary.org/face/gen-light" rel="stylesheet" type="text/css"/>
    </head>
</html>

<div class = "header">
	<h1>Hinda kogemust</h1>

	<div class="logout"><form action="logout.php" method="POST"><input name="logout" type="submit" class="button" value="Logi välja"/></form></div>

	<div id="searchbox">
	    <form action="?">
	    <input type="text" name="search_place" placeholder="Otsi nime järgi...">
	    <input type="submit" class="button" value="Otsi">
	    </form>
	</div>

	

</div>
 
<?php session_start();
$con = mysql_connect("localhost","rstudent","student");
if (!$con) {
  die('Could not connect: ' . mysql_error());
}
 
mysql_select_db("reddit", $con);
 
$result = mysql_query("SELECT * FROM t123661_places");

?>

<div class="menu">

<?php
while($row = mysql_fetch_array($result)) {
  #echo '<button class="' . $row['place_id'] . '" name="submit_place">' . $row['place_id'] . " " . $row['name'] . '</button>';
  echo '<form action="index.php" id="' . $row['place_id'] . '" method="POST"><input name="choose_place" type="submit" class="button" value="' . $row['name'] . '"></form>';
}
?>

</div>

<?php

function createTableMain() {
	echo '<table style="width:1000px"><tr class="main"><td>Nimi</td><td><form action="index.php" method="POST"><input name="sort_name_desc" type="submit" class="button" value="↑" '. $sort_dis .'></form>';
	echo '<form action="index.php" method="POST"><input name="sort_name_asc" type="submit" class="button" value="↓" '. $sort_dis .'></form></td>';

	echo '<td>Asukoht</td><td><form action="index.php" method="POST"><input name="sort_location_desc" type="submit" class="button" value="↑" '. $sort_dis .'></form>';
	echo '<form action="index.php" method="POST"><input name="sort_location_asc" type="submit" class="button" value="↓" '. $sort_dis .'></form></td>';

	echo '<td>Keskmine hinne</td><td><form action="index.php" method="POST"><input name="sort_average_desc" type="submit" class="button" value="↑" '. $sort_dis .'></form>';
	echo '<form action="index.php" method="POST"><input name="sort_average_asc" type="submit" class="button" value="↓" '. $sort_dis .'></form></td>';

	echo '<td>Hinnatud (korda)</td><td><form action="index.php" method="POST"><input name="sort_ratings_desc" type="submit" class="button" value="↑" '. $sort_dis .'></form>';
	echo '<form action="index.php" method="POST"><input name="sort_ratings_asc" type="submit" class="button" value="↓" '. $sort_dis .'></form></td>';

	echo '<td>Hinda</td></tr>';
}

$search_place = $_REQUEST['search_place'];

function createTable() {

	$query = mysql_query("DROP TABLE t123661_temp;");

  	$place = $_POST["choose_place"];
	        $dbq = mysql_query("SELECT * FROM t123661_places WHERE name LIKE '%$place%'");

	        while($row = mysql_fetch_array($dbq)) {
	        	#echo $row['place_id'];
	        	$place_id = $row['place_id'];

	        	$dbq1 = mysql_query("SELECT * FROM t123661_details WHERE place_id = '$place_id'");
	        	#$dbq2 = mysql_query("SELECT * FROM t123661_ratings WHERE name LIKE '%$place_name%'");

	        	while($row1 = mysql_fetch_array($dbq1)) {

	        		$place_name = $row1['name'];

	        		$check = mysql_query("SELECT name FROM t123661_ratings WHERE name = '$place_name'");
	        		$count_check = mysql_num_rows($check);

	        		if ($count_check > 0) {
	        			$dbq2 = mysql_query("SELECT name, AVG(rating), COUNT(rating) FROM t123661_ratings WHERE name = '$place_name' GROUP BY name");

		        		while($row = mysql_fetch_array($dbq2)){
							$average = $row['AVG(rating)'];
							$rows = $row['COUNT(rating)'];
						}

		        		if (!$average) {
		        			$average = 0;
		        		}

		        		
	        		} else {
	        			$average = 0;
						$rows = 0;
	        		}

	        		$query = mysql_query("CREATE TABLE IF NOT EXISTS t123661_temp (id int NOT NULL AUTO_INCREMENT, name varchar(200) NOT NULL, location varchar(200) NOT NULL, average FLOAT(10,2) NOT NULL, rows int NOT NULL, PRIMARY KEY (id));");

		        		$name = $row1['name'];
		        		$location = $row1['location'];

		        		$query = mysql_query("INSERT INTO t123661_temp (name, location, average, rows) VALUES ('$name', '$location', '$average', '$rows')");

		        		#echo $name . " | " . $location . " | " . $average . " | " . $rows . '<br>';
	        		
	        	}
	        }

	        showPlaces();
}

if (isset($_POST["next"])) {

	$_SESSION['disabled'] = 'enabled="true"';

    $_SESSION['start'] = $_SESSION['start'] + 10;

    $query_rows = mysql_query("SELECT * FROM t123661_temp");
	$_SESSION['end'] = mysql_num_rows($query_rows);

    if($_SESSION['end'] <= $_SESSION['start']+10) { 
		$_SESSION['disabled_next'] = 'disabled="true"';

		}

    showPlaces();

} else if (isset($_POST["previous"])) {
    $_SESSION['start'] = $_SESSION['start'] - 10;
    $_SESSION['disabled_next'] = 'enabled="true"';

    showPlaces();
}

if($_SESSION['start'] == 0) { 
		 ?>
		<script>
		document.getElementById("previous").disabled = true;
		</script>
		 <?php

}

function showPlaces() {

	createTableMain();

	$start = $_SESSION['start'];
	$disabled = $_SESSION['disabled'];
	
	$order_by = $_SESSION['order_by'];
	$dir = $_SESSION['dir'];

	if($order_by){
		$query = mysql_query("SELECT * FROM t123661_temp ORDER BY $order_by $dir LIMIT $start, 10");
	} else {
		$query = mysql_query("SELECT * FROM t123661_temp LIMIT $start, 10");
	}

	if (mysql_num_rows($query) < 10) {
		$_SESSION['disabled_next'] = 'disabled="true"';
	}

	$disabled_next = $_SESSION['disabled_next'];

	while($q_row = mysql_fetch_array($query)) {
		$value =  '<tr class="row"><td>' . $q_row['name'] . "</td><td></td><td>" . $q_row['location'] . "</td><td></td><td>" . $q_row['average'] . "</td><td></td><td>" . $q_row['rows'] . '</td>';
		$rate_btn = '<td></td><td><form action="" method="POST"><button name="business_name" type="submit" class="button" value="' . $q_row['name'] . '">Hinda</button></td></tr>';
		echo $value . $rate_btn;
		
	}
	echo '</table><div class="navigate">';
	echo '<form action="index.php" method="POST"><input name="previous" type="submit" class="button" value="Eelmine" id="previous" '. $disabled .'>';
	echo '<form action="index.php" method="POST"><input name="next" type="submit" class="button" value="Järgmine" '. $disabled_next .'></div> ';
}

if ($_POST['business_name']) {
	$_SESSION['business_name'] = $_POST['business_name'];
	header('Location: rating.php');
    exit();
}

if (isset($_POST["sort_name_desc"])) {

	$_SESSION['start'] = 0;
			$_SESSION['disabled'] = 'disabled="true"';
			$_SESSION['disabled_next'] = 'enabled="true"';
	$_SESSION['order_by'] = 'name';
	$_SESSION['dir'] = 'DESC';
	showPlaces();
	#$query = mysql_query("SELECT * FROM t123661_temp ORDER BY name DESC $start, 2;");

} else if (isset($_POST["sort_location_desc"])) {
	$_SESSION['start'] = 0;
			$_SESSION['disabled'] = 'disabled="true"';
			$_SESSION['disabled_next'] = 'enabled="true"';
	$_SESSION['order_by'] = 'location';
	$_SESSION['dir'] = 'DESC';
	showPlaces();
	#$query = mysql_query("SELECT * FROM t123661_temp ORDER BY location DESC $start, 2;");

} else if (isset($_POST["sort_average_desc"])) {
	$_SESSION['start'] = 0;
			$_SESSION['disabled'] = 'disabled="true"';
			$_SESSION['disabled_next'] = 'enabled="true"';
	$_SESSION['order_by'] = 'average';
	$_SESSION['dir'] = 'DESC';
	showPlaces();
	#$query = mysql_query("SELECT * FROM t123661_temp ORDER BY average DESC $start, 2;");
	
} else if (isset($_POST["sort_ratings_desc"])) {
	$_SESSION['start'] = 0;
			$_SESSION['disabled'] = 'disabled="true"';
			$_SESSION['disabled_next'] = 'enabled="true"';
	$_SESSION['order_by'] = 'rows';
	$_SESSION['dir'] = 'DESC';
	showPlaces();
	#$query = mysql_query("SELECT * FROM t123661_temp ORDER BY rows DESC $start, 2;");
	
} else if (isset($_POST["sort_location_asc"])) {
	$_SESSION['start'] = 0;
			$_SESSION['disabled'] = 'disabled="true"';
			$_SESSION['disabled_next'] = 'enabled="true"';
	$_SESSION['order_by'] = 'location';
	$_SESSION['dir'] = 'ASC';
	showPlaces();
	#$query = mysql_query("SELECT * FROM t123661_temp ORDER BY location ASC $start, 2;");

} else if (isset($_POST["sort_average_asc"])) {
	$_SESSION['start'] = 0;
			$_SESSION['disabled'] = 'disabled="true"';
			$_SESSION['disabled_next'] = 'enabled="true"';
	$_SESSION['order_by'] = 'average';
	$_SESSION['dir'] = 'ASC';
	showPlaces();
	#$query = mysql_query("SELECT * FROM t123661_temp ORDER BY average ASC $start, 2;");
	
} else if (isset($_POST["sort_ratings_asc"])) {
	$_SESSION['start'] = 0;
			$_SESSION['disabled'] = 'disabled="true"';
			$_SESSION['disabled_next'] = 'enabled="true"';
	$_SESSION['order_by'] = 'rows';
	$_SESSION['dir'] = 'ASC';
	showPlaces();
	#$query = mysql_query("SELECT * FROM t123661_temp ORDER BY rows ASC $start, 2;");
	
} else if (isset($_POST["sort_name_asc"])) {
	$_SESSION['start'] = 0;
			$_SESSION['disabled'] = 'disabled="true"';
			$_SESSION['disabled_next'] = 'enabled="true"';
	$_SESSION['order_by'] = 'name';
	$_SESSION['dir'] = 'ASC';
	showPlaces();
	#$query = mysql_query("SELECT * FROM t123661_temp ORDER BY name ASC $start, 2;");
	
}

#while($q_row = mysql_fetch_array($query)) {
#		$value = $q_row['name'] . " | " . $q_row['location'] . " | " . $q_row['average'] . " | " . $q_row['rows'] . '<br>';
#		echo '<form action="" method="POST"><button name="business_name" type="submit" class="button" value="' . $q_row['name'] . '">' . $value . '</button><br>';
#	}

if ($search_place) {

			$query = mysql_query("DROP TABLE t123661_temp;");

        	$dbq1 = mysql_query("SELECT * FROM t123661_details WHERE name LIKE '%$search_place%'");

        	while($row1 = mysql_fetch_array($dbq1)) {
        		
        		$searched_place = $row1['name'];
        		$dbq2 = mysql_query("SELECT name, AVG(rating), COUNT(rating) FROM t123661_ratings WHERE name = '$searched_place' GROUP BY name");

	        		while($row = mysql_fetch_array($dbq2)){
						$average = $row['AVG(rating)'];
						$rows = $row['COUNT(rating)'];
					}

        		if (!$average) {
        			$average = 0;
        		}

				$query = mysql_query("CREATE TABLE IF NOT EXISTS t123661_temp (id int NOT NULL AUTO_INCREMENT, name varchar(200) NOT NULL, location varchar(200) NOT NULL, average FLOAT(10,2) NOT NULL, rows int NOT NULL, PRIMARY KEY (id));");

        		#echo $row1['name'] . " | " . $row1['location'] . " | " . $average . " | " . $rows . '<br>';
        		$name = $row1['name'];
        		$location = $row1['location'];

        		$query_s = mysql_query("INSERT INTO t123661_temp (name, location, average, rows) VALUES ('$name', '$location', '$average', '$rows')");

        	}

        	$_SESSION['start'] = 0;
			$_SESSION['disabled'] = 'disabled="true"';
			$_SESSION['disabled_next'] = 'enabled="true"';

        	showPlaces();

		} else if (isset($_POST["choose_place"])) {
			session_destroy();
			$_SESSION['start'] = 0;
			$_SESSION['disabled'] = 'disabled="true"';
			createTable();

	    } 

echo '<br>';

mysql_close($con);
 
?> 