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
  <?php

	// Retrieve Post Data
	$username = $_POST["username"];
	$email = $_POST["email"];
	
 
		 // Set the session information
		 session_start();  
		 $_SESSION['appusername'] = $username; 
		 $_SESSION['appemail'] = $email;
		 
 // Display the Session information
echo "<h3> User information:  </h3>";
echo "Welcome, " . $_SESSION['appusername'] . "!<br> Please browse our selection of axes.<br>";
echo  "<br>email: " . $_SESSION['appemail']. "</td>";     
		           
// Provide a button to logout
echo "<br><form name='logout' method='post' action='logout.php'> 
<input name='btnsubmit' type='submit' value='Logout'> 
</form><br>";	  	
?>

<p>Currently available battle axes:</p";

  </div>
  <!-- Add images linked to axes -->
   <div class="jumbotron">
    <div class="container">
       <?php	 
	// Retrieve the products
	$products = selectProducts();
	// Loop through table and display
	echo "<table border='1'>";
		echo "<td>ID</td>";
	 	echo "<td>Name</td>";
	 	echo "<td>Cost(GP)</td>";
	foreach ($products as $data) {
	  echo "<tr>";	
		echo "<td>" . $data->getId() . "</td>";
	 	echo "<td>" . $data->getName() . "</td>";
	 	echo "<td>" . $data->getCost() . "</td>";
	  echo"</tr>";
}
?>
	<tr>
		<td width="157">Item ID:</td>
	<form name="createorder" method="POST" action="orderSubmit2.php">
	<td width="157"><input type="submit" value="Order Axes" name="CreateSubmit"></td>
				<td>&nbsp;</td>
	</table>
      </div>
    </div> 
 </div>
</p>
<?php
  	
  function selectProducts ()
  {
		
		// Connect to the database
   $mysqli = connectdb();
		
	 
		// Add Prepared Statement
		$Query = "Select id,name,cost from products";	         
	          
		$result = $mysqli->query($Query);
		$myAccounts = array();
if ($result->num_rows > 0) {    
    while($row = $result->fetch_assoc()) {
    	// Assign values
    	$id = $row["id"];
    	$name = $row["name"];
    	$cost = $row["cost"];    	
      
       // Create a Student instance     
       $productData = new Productclass($id,$name,$cost);
       $myProducts[] = $productData;         
      }    
 } 

	$mysqli->close();
	
	return $myProducts;		
		
	}
	  	
  function getDbparms()
	 {
	 	$trimmed = file('parms/dbparms.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$key = array();
	$vals = array();
	foreach($trimmed as $line)
	{
		  $pairs = explode("=",$line);    
	    $key[] = $pairs[0];
	    $vals[] = $pairs[1]; 
	}
	// Combine Key and values into an array
	$mypairs = array_combine($key,$vals);
	
	// Assign values to ParametersClass
	$myDbparms = new DbparmsClass($mypairs['username'],$mypairs['password'],
	                $mypairs['host'],$mypairs['db']);
	
	// Display the Paramters values
	return $myDbparms;
	 }
	 
  function connectdb() {      		
		// Get the DBParameters
	  $mydbparms = getDbparms();
	  
	  // Try to connect
	  $mysqli = new mysqli($mydbparms->getHost(), $mydbparms->getUsername(), 
	                        $mydbparms->getPassword(),$mydbparms->getDb());
	
	   if ($mysqli->connect_error) {
	      die('Connect Error (' . $mysqli->connect_errno . ') '
	            . $mysqli->connect_error);      
	   }
	  return $mysqli;
	}
 
 class DBparmsClass
	{
	    // property declaration  
	    private $username="";
	    private $password="";
	    private $host="";
	    private $db="";
	   
	    // Constructor
	    public function __construct($myusername,$mypassword,$myhost,$mydb)
	    {
	      $this->username = $myusername;
	      $this->password = $mypassword;
			  $this->host = $myhost;
				$this->db = $mydb;
	    }
	    
	    // Get methods 
		  public function getUsername ()
	    {
	    	return $this->username;
	    } 
		  public function getPassword ()
	    {
	    	return $this->password;
	    } 
		  public function getHost ()
	    {
	    	return $this->host;
	    } 
		  public function getDb ()
	    {
	    	return $this->db;
	    } 	 
	
	    // Set methods 
	    public function setUsername ($myusername)
	    {
	    	$this->username = $myusername;    	
	    }
	    public function setPassword ($mypassword)
	    {
	    	$this->password = $mypassword;    	
	    }
	    public function setHost ($myhost)
	    {
	    	$this->host = $myhost;    	
	    }
	    public function setDb ($mydb)
	    {
	    	$this->db = $mydb;    	
	    }    
	    
	} // End DBparms class
	
 // Class to construct Accounts with getters/setter
class ProductClass
{
    // property declaration
    private $id="";
    private $name="";
    private $cost="";
   
    // Constructor
    public function __construct($id,$name,$cost)
    {
      $this->id = $id;
      $this->name = $name;
      $this->cost = $cost;     
    }
    
    // Get methods 
	  public function getId ()
    {
    	return $this->id;
    } 
	  public function getName ()
    {
    	return $this->name;
    } 
	  public function getCost ()
    {
    	return $this->cost;
    } 
	  

    // Set methods 
    public function setId ($value)
    {
    	$this->Id = $value;    	
    }
    public function setName ($value)
    {
    	$this->name = $value;    	
    }
    public function setCost ($value)
    {
    	$this->cost = $value;    	
    }
       
    
} // End Productclass

?>
</body>
</html>

