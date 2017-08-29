<!DOCTYPE html>
<!-- Lab 5 -->
<!-- Jul 16, 2017 -->
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
    session_start();  
    unset($_SESSION['appusername']);
    unset($_SESSION['appemail']);
    unset($_SESSION['axes']);
  ?>
  <h4>User Logout Successful.</h4>
 </div>
</body>
</html>
