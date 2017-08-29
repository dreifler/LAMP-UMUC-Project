<!DOCTYPE html>
<html>
<head>
  <title> AxesRUs </title>
  <link rel="stylesheet" type="text/css" href="../main.css">
</head>
<body>
  <div class = "header">
  <h1>AxesRUs<h1>
  <h2>Before the Romans are on your doorstep, you need an Axe.</h2>
  </div>
  <div class="main">
  <p>
  <?php

	// Retrieve Post Data
	$username = $_POST["username"];
	$password = $_POST["password"];
	$usernameTrue = "dreifler";
        $passwordTrue = "Rifesb45";
 
        if(($username == $usernameTrue) && ($password == $passwordTrue)){
		 // Set the session information
		 session_start();  
		 $_SESSION['appusername'] = "dreifler";
                 $_SESSION['verified']= $passwordTrue;

		 
 // Display the Session information
echo "<h3> User information:  </h3>";
echo "Welcome, " . $_SESSION['appusername'] . "!<br> Administration privilages verified.";    

echo "<br><a href=\"Insert.php\">Insert to Database</a><br>";
echo "<br><a href=\"Delete.php\">Delete from Database</a><br>";
echo "<br><a href=\"Select.php\">Display Database</a><br>";
echo "<br><a href=\"Update.php\">Update Database</a><br>";

}
else echo "<h2>Identity not verified.</h2>";		           
?>
</body>
</html>
