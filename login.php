<?php
ob_start(); ?>
 
<?php
/**  CONNECT **/
    //Create connection
$con = mysqli_connect('localhost', 'rstudent', 'student');
 
//Check connection
if (mysqli_connect_errno()) {
    die("Not connected: " . mysqli_connect_error());
}

$db_selected = mysqli_select_db($con, 'reddit');
if (!$db_selected) {
    die("Can't use db reddit" . mysqli_error());
}
/**  CONNECT **/

session_start();
if (!$_SESSION['username'] && $_POST['username'] && $_POST['password']) {
$login_user = $_POST['username'];
$login_pw = $_POST['password'];

 
$result = mysqli_query($con, "SELECT * FROM t123661_users WHERE login_user = '$login_user' AND login_pw = '$login_pw'");
 
if (mysqli_num_rows($result) == 1) {
    $_SESSION['username'] = $_POST['username'];
} else {
    $error_message = 'Login failed';
}
 
}
$username = $_SESSION['username'];
?>
 
<!doctype html>
<html>
    <head>
        <meta charset= "utf-8">
        <link rel="stylesheet" type="text/css" href="main.css">
        <link rel="stylesheet" media="screen" href="http://openfontlibrary.org/face/fantasque-sans-mono" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" media="screen" href="http://openfontlibrary.org/face/gen-light" rel="stylesheet" type="text/css"/>

    <div class = "header">
    <h1>Hinda kogemust</h1></div>
 
    </head>
    <body>
    <div id="body1">
        <?php
        if (!$username){
            echo "<p class='error'>$error_message</p>";
        ?>

        <div class="box" id="login">
        Logi sisse:<p>
        <form method="POST">
            <input type="text" name="username" placeholder="Kasutajanimi"/>
            <input type="password" name="password" placeholder="Parool"/>
            <input class="button" name="login" type="submit" value="Sisene"/>
        </form>
 
        <?php
        } else {
            if ($username == 'admin') {
                header('Location: admin.php');
            exit();
        } else {
            header('Location: index.php');
            exit();
        }
            
        }
        ?>
        <p>
    </div>
    </div>
    </body>
</html>