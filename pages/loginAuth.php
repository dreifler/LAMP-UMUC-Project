<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


   <title>Form Login</title>
</head>

<body OnLoad="document.main.username.focus();">

<?php
  header('X-Content-Type-Options: nosniff');
  header('X-Frame-Options: SAMEORIGIN');
  header('Content-Security-Policy: script-src \'self\'');
?>
	
<table >
	<tr>
		<td colspan="2">	
<h4>Enter your Username and Email Address to continue</h4> 
</td>
</tr>
<!-- create the main form with an input text box named uid and a password text box named mypassword -->
<form name="main" method="post" action="authcheck.php"> 
<tr> 
<td>username (alphanumeric):</td> 
<td><input name="username" type="text" size="50" pattern="[A-Za-z0-9_]{1,15}" required></td> 
</tr> 
<tr> 
<td>Email Address:</td> 
<td><input name="emailadd" type="email" size="50"  required></td> 
</tr> 
<tr> 
<td colspan="2" align="center"><input name="btnsubmit" type="submit" value="Submit"></td> 
</tr>
</table>
</form>


</body>
</html> 

