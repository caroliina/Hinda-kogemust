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
?>

<div class = "header">
	<h1>

<?php

echo 'Hinda kohta > ' . $business_name;

?>

</h1></div>

<?php

        $user = $_REQUEST['user'];
		$comment = $_REQUEST['comment'];
		$rating = $_REQUEST['rating'];

		if(!empty($_POST) && (!$user || !$comment || !$rating) && !isset($_POST["delete"])) {
        	echo "<h3>Kõik väljad on kohustuslikud!</h3>";
        }
 
        if (!$user || !$comment || !$rating){
        ?>
 		<div class="rate_form">
        <form method="POST">
            Nimi: <input class="name" type="text" name="user" placeholder="Nimi"/><p>
            Kommentaar: <textarea rows="4" cols="50" name="comment" placeholder="Kommentaar..."/></textarea><p>
            Hinnang:<br>
            <div class="radiobtns">
            <input type="radio" name="rating" value="1">1
			<input type="radio" name="rating" value="2">2
			<input type="radio" name="rating" value="3">3
			<input type="radio" name="rating" value="4">4
			<input type="radio" name="rating" value="5">5
			<input type="radio" name="rating" value="6">6
			<input type="radio" name="rating" value="7">7
			<input type="radio" name="rating" value="8">8
			<input type="radio" name="rating" value="9">9
			<input type="radio" name="rating" value="10">10</div> <br><br></div>
            <div class="buttons"><a class = "go_back" href="index.php"> Tagasi </a><input class="send_button" type="submit" value="Saada"/></div>
        </form>

        <br><br>
        <h2>Teiste hinnangud:</h2>
 
        <?php
        } else {
            if($user && $comment && $rating) {
            $dbq = mysql_query("INSERT INTO t123661_ratings (name, user, comment, rating) VALUES ('$business_name', '$user', '$comment', '$rating')");
             
            echo "<p>";

            //echo '<a href="logout.php">Log Out</a>';
            Header('Location: index.php');
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
		echo '</div>';
	}

mysql_close($con);
?>