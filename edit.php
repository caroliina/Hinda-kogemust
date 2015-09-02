<!doctype html>
<html>
    <head>
        <meta charset= "utf-8">
        <link rel="stylesheet" type="text/css" href="main.css">
        <link rel="stylesheet" media="screen" href="http://openfontlibrary.org/face/fantasque-sans-mono" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" media="screen" href="http://openfontlibrary.org/face/gen-light" rel="stylesheet" type="text/css"/>
    </head>
</html>

<?php

session_start();

$con = mysql_connect("localhost","rstudent","student");
if (!$con) {
  die('Could not connect: ' . mysql_error());
}
 
mysql_select_db("reddit", $con);

$business_name = $_SESSION['business_name'];
$location = $_SESSION['location'];
?>

<div class = "header">
	<h1>

<?php

echo 'Muuda > ' . $business_name;

?>

</h1></div>

<?php

        $name = $_REQUEST['name'];
		$adress = $_REQUEST['adress'];

		if(!empty($_POST) && (!$name || !$adress) && !isset($_POST["delete"])) {
        	echo "<h3>Kõik väljad on kohustuslikud!</h3>";
        }
 
        if (!$name || !$adress){
         echo '
 		<div class="rate_form">
        <form method="POST">
            Nimi: <input class="name" type="text" name="name" value="' . $business_name . '"/><p>
            Aadress: <textarea rows="2" cols="30" style="margin-left: 57px;" class="name" name="adress"/>' . $location . '</textarea><p>
            <br><br></div>
            <div class="buttons"><a class = "go_back" href="admin.php"> Tagasi </a><input class="send_button" type="submit" value="Salvesta"/></div>
        </form>

        <br><br>
        <h2>Hinnangud:</h2>';
 
        
        } else {
            if($name && $adress) {
            #$dbq = mysql_query("INSERT INTO t123661_ratings (name, user, comment, rating) VALUES ('$business_name', '$user', '$comment', '$rating')");
                $dbq = mysql_query("DROP TABLE t123661_temp");
                $dbq2 = mysql_query("UPDATE t123661_details SET name='$name', location='$adress' WHERE name='$business_name'");
                $dbq3 = mysql_query("UPDATE t123661_ratings SET name='$name' WHERE name='$business_name'");
            
            echo "<p>";

            //echo '<a href="logout.php">Log Out</a>';
            Header('Location: admin.php');
            exit();
            }
            
        }

        if (isset($_POST["delete"])) {
        	$timestamp = $_POST['delete'];
        	$query_del = mysql_query("DELETE FROM t123661_ratings WHERE added = '$timestamp'");
		}

$query = mysql_query("SELECT * FROM t123661_ratings WHERE name = '$business_name' ORDER BY added DESC");

	while($row = mysql_fetch_array($query)) {
		echo '<div class="box">';
		echo '<p>';
		echo $row['user'] . " (" . $row['rating'] . "/10)";
		echo '<br>';
		echo 'Kommentaar: ' . $row['comment'] . ' <br><br>(' . $row['added'] . ')';
		echo '</p>';
		echo '<form action="" method="POST"><button name="delete" type="submit" class="button" value="' . $row['added'] . '">Kustuta kommentaar</button></form>';
		echo '</div>';
	}

mysql_close($con);
?>