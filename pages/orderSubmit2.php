<html>
	<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">   
   <title>Delivery Information</title>
</head>
<body OnLoad="document.submitorder.firstname.focus();"> 

<?php
$id = $_POST['id'];
session_start();
$_SESSION['itemId'] = $id;
?>

<?php
if(isset($_POST["CreateOrder"]))
{
validate_form();
}
else
{
$messages = array();
show_form($messages);
}
?>

		
<?php
function show_form($messages) { 		
			
		// Assign post values if exist
		$quantity="";
		$firstname="";
		$lastname="";
      		$address="";
  		$city="";	
	        $state="";
    		$zip="";
		if (isset($_POST["id"]))
		  $firstname=$_POST["quantity"];
		if (isset($_POST["quantity"]))
		  $firstname=$_POST["quantity"];
		if (isset($_POST["firstname"]))
		  $firstname=$_POST["firstname"];
	        if (isset($_POST["lastname"]))
		  $lastname=$_POST["lastname"];	  
		if (isset($_POST["address"]))
		  $address=$_POST["address"];
		if (isset($_POST["city"]))
		  $city=$_POST["city"];
  		if (isset($_POST["state"]))
		  $state=$_POST["state"];
	        if (isset($_POST["zip"]))
		  $zip=$_POST["zip"];
	
	echo "<p></p>";
	echo "<h2> Enter Delivery Information:</h2>";
	echo "<p></p>";	 	
	?>
	<h5>Complete the information in the form below and click Submit to create your account. All fields are required.</h5>
	<form name="submitorder" method="POST" action="orderSubmit2.php">	
	<table border="1" width="100%" cellpadding="0">	
		<tr>
				<td width="157">Item ID:</td>
				<td><input type="text" name="id" value='<?php echo $id ?>' size="30"></td>
			</tr>	
		<tr>		
		
		<tr>
				<td width="157">Quantity:</td>
				<td><input type="text" name="quantity" value='<?php echo $quantity ?>' size="30"></td>
			</tr>	
		<tr>
				<td width="157">Firstname:</td>
				<td><input type="text" name="firstname" value='<?php echo $firstname ?>' size="30"></td>
			</tr>
			<tr>
				<td width="157">Lastname:</td>
				<td><input type="text" name="lastname" value='<?php echo $lastname ?>' size="30"></td>
			</tr>
			<tr>
				<td width="157">Street Address:</td>
				<td><input type="text" name="address" value='<?php echo $address ?>' size="100"></td>
			</tr>
			<tr>
				<td width="157">City:</td>
				<td><input type="text" name="city" value='<?php echo $city ?>' size="30"></td>
			</tr>
			<tr>
				<td width="157">State:</td>
				<td><input type="text" name="state" value='<?php echo $state ?>' size="30"></td>
			</tr>
			<tr>
				<td width="157">Zip Code:</td>
				<td><input type="text" name="zip" value='<?php echo $zip ?>' size="30"></td>
			</tr>
<tr>
				<td width="157"><input type="submit" value="Submit" name="CreateOrder"></td>
				<td>&nbsp;</td>
			</tr>
	</table>			
	</form>
	
<?php
} // End Show form
?>

<?php
function validate_form()
{
  $messages = array();
  $redisplay = false;
  // Assign
  $id = $_POST['id'];
  $username = $_SESSION["appusername"];	
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_SESSION["appemail"];
  $address = $_POST["address"];
  $city = $_POST["city"];
  $state = $_POST["state"];
  $zip = $_POST["zip"];
  $itemId = $_SESSION["itemId"];
  $quantity = $_POST['quantity'];

  
  $account = new AccountClass($username,$firstname,$lastname,$email,$address,$city,$state,$zip,$itemId,$quantity);
  	$count = countAccount($account);    	  
 	 
  	// Check for accounts that already exist and Do insert
  	if ($count==0) 
  	{  		
  		$res = insertAccount($account);
  		echo "<h3>Your order has been submitted.</h3> ";         
  	}
  	else 
  	{
  		echo "<h3>An account with that username already exists.</h3> ";  		
  	}  	
  }
  
 function countAccount ($account)
  {  	  	 
  	// Connect to the database
   $mysqli = connectdb();
   $username = $account->getUsername();
   
		// Connect to the database
	$mysqli = connectdb();
		
	// Define the Query
	// For Windows MYSQL String is case insensitive
	 $Myquery = "SELECT count(*) as count from accounts
		   where username='$username'";	 
		
	 if ($result = $mysqli->query($Myquery)) 
	 {
	    /* Fetch the results of the query */	     
	    while( $row = $result->fetch_assoc() )
	    {
	  	  $count=$row["count"];	    			   	     	  	     	  
	    }	 
	
 	    /* Destroy the result set and free the memory used for it */
	    $result->close();	      
   }
	
	$mysqli->close();   
	    
	return $count;
  	  	
  }
  	
  function insertAccount ($account)
  {	
		// Connect to the database
   $mysqli = connectdb();
	
   $username = $account->getUsername();	
   $firstname = $account->getFirstname();
   $lastname = $account->getLastname();
   $email = $account->getEmail();
   $address = $account->getAddress();
   $city = $account->getCity();
   $state = $account->getState();
   $zip = $account->getZip();
   $itemId = $account->getItemId();
   $quantity = $account->getQuantity();
		
		// Add Prepared Statement
		$Query = "INSERT INTO accounts (username,firstName,lastName,email,address,zipcode,city,state,
itemId,quantity) 
	           VALUES (?,?,?,?,?,?,?,?,?,?)";
	        
		$stmt = $mysqli->prepare($Query);
				
$stmt->bind_param("ssssssssii", $username, $lastname, $firstname,$email,$address,$zip,$city,$state,$itemId,$quantity);
$stmt->execute();
		
		
	
	$stmt->close();
	$mysqli->close();
		
		return true;
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
class AccountClass
{
    // property declaration
    private $username="";
    private $firstname="";
    private $lastname="";
    private $email="";
    private $address="";
    private $city="";
    private $state="";
    private $zip="";
    private $itemId="";
    private $quantity="";
  
   
    // Constructor
    public function __construct($username,$firstname,$lastname,$email,$address,$city,$state,$zip,$itemId,$quantity)
    { 
      $this->username = $username;  
      $this->firstname = $firstname;
      $this->lastname = $lastname;
      $this->email = $email;
      $this->address = $address;
      $this->city = $city; 
      $this->state = $state;
      $this->zip = $zip;   
      $this->itemId = $itemId;  
      $this->quantity = $quantity;  
     
    }
    
    // Get methods 
            public function getUsername ()
    {
    	return $this->username;
    } 
	  public function getFirstname ()
    {
    	return $this->firstname;
    } 
	  public function getLastname ()
    {
    	return $this->lastname;
    } 
	  public function getEmail ()
    {
    	return $this->email;
    } 
        public function getAddress ()
    {
    	return $this->address;
    } 
        public function getCity ()
    {
    	return $this->city;
    } 
        public function getState ()
    {
    	return $this->state;
    } 
        public function getZip ()
    {
    	return $this->zip;
    } 
       public function getItemId ()
    {
    	return $this->itemId;
    } 
       public function getQuantity ()
    {
    	return $this->quantity;
    }
   
	  

    // Set methods 
    public function setUsername ($value)
    {
    	$this->username = $value;    	
    }
    public function setFirstname ($value)
    {
    	$this->firstname = $value;    	
    }
    public function setLastname ($value)
    {
    	$this->lastname = $value;    	
    }
    public function setEmail ($value)
    {
    	$this->email = $value;    	
    }
    public function setAddress ($value)
    {
    	$this->address = $value;    	
    }
    public function setCity ($value)
    {
    	$this->city = $value;    	
    }
    public function setState ($value)
    {
    	$this->state = $value;    	
    }
    public function setZip ($value)
    {
    	$this->zip = $value;    	
    }
    public function setItemId ($value)
    {
    	$this->itemId = $value;
    }
    public function setQuantity($value)
    {
    	$this->quantity = $value;
    }

         
    
} // End Accountclass

?>
</body>
</html>
