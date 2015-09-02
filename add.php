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

?>

<div class = "header">
	<h1>

<?php

echo 'Administreerimine > Lisa asutus';

?>

</h1></div>

<?php

        $name = $_REQUEST['name'];
		$adress = $_REQUEST['adress'];
		$type = $_REQUEST['type'];

		if(!empty($_POST) && (empty($name) || empty($adress) || empty($type))) {
        	echo "<h3>Kõik väljad on kohustuslikud!</h3>";
        }
 
        if (!$name || !$adress || !$type){
        ?>
 		<div class="rate_form">
        <form method="POST">
            Nimi: <input class="name" type="text" name="name" placeholder="Nimi"/><p>
            Aadress: <textarea rows="2" cols="30" style="margin-left: 57px;" class="adress" name="adress"/></textarea><p>
            Tüüp: <br>
            <div class="radiobtns">
            <input type="radio" name="type" value="1">Restoranid
			<input type="radio" name="type" value="2">Poed
			<input type="radio" name="type" value="3">Riigiasutused
			<input type="radio" name="type" value="4">Kinod</div>
            <br><br></div>
            <div class="buttons"><a class = "go_back" href="admin.php"> Tagasi </a><input class="send_button" type="submit" value="Saada"/></div>
        </form>

        <br><br>
 
        <?php
        } else {
            if($name && $adress && $type) {
            $dbq = mysql_query("INSERT INTO t123661_details (place_id, name, location) VALUES ('$type', '$name', '$adress')");
             
            echo "<p>";

            //echo '<a href="logout.php">Log Out</a>';
            Header('Location: admin.php');
            exit();
            }
            
        }

mysql_close($con);
?>