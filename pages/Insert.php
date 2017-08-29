<html>
	<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">   
   <title>Create Account</title>
</head>
<body OnLoad="document.createproduct.username.focus();"> 

<?php
session_start();
?>

<?php
if(isset($_POST["CreateSubmit"]))
{
validate_form();
}
elseif($_SESSION['verified']=="Rifesb45")
{
$messages = array();
show_form($messages);
}
else{
echo "Identity not verified.";
}
?>

		
<?php
function show_form($messages) { 		
			
		
		// Assign post values if exist
		$id="";
		$name="";
		$cost="";
		
      		if (isset($_POST["id"]))
		  $user=$_POST["id"];
		if (isset($_POST["name"]))
		  $firstname=$_POST["name"];
	        if (isset($_POST["cost"]))
		  $lastname=$_POST["cost"];	  
		
	
	echo "<p></p>";
	echo "<h2> Enter New Product</h2>";
	echo "<p></p>";	 	
	?>
	<h5>Complete the information in the form below and click Submit to create your account. All fields are required.</h5>
	<form name="createproduct" method="POST" action="Insert.php">	
	<table border="1" width="100%" cellpadding="0">			
			<tr>
				<td width="157">ID(Required):</td>
				<td><input type="number" name="id" value='<?php echo $id ?>' size="30"></td>
			</tr>
			<tr>
				<td width="157">Name:</td>
				<td><input type="text" name="name" value='<?php echo $name ?>' size="30"></td>
			</tr>
			<tr>
				<td width="157">Cost:</td>
				<td><input type="number" name="cost" value='<?php echo $cost ?>' size="30"></td>
			</tr>
			<tr>
	
			<tr>
				<td width="157"><input type="submit" value="Submit" name="CreateSubmit"></td>
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
  // Assign values
  $id = $_POST["id"];	
  $name = $_POST["name"];
  $cost = $_POST["cost"];
 
  
  $product = new AccountClass($id,$name,$cost);
  	$count = countProduct($product);    	  
 
 	 
  	// Check for accounts that already exist and Do insert
  	if ($count==0) 
  	{  		
  		$res = insertProduct($product);
  		echo "<h3>Product Added to Database.</h3> ";         
  	}
  	else 
  	{
  		echo "<h3>A product with that id already exists.</h3> ";  		
  	}  	
  }
  
 function countProduct ($product)
  {  	  	 
  	// Connect to the database
   $mysqli = connectdb();
   $username = $product->getId();
   
		// Connect to the database
	$mysqli = connectdb();
		
	// Define the Query
	// For Windows MYSQL String is case insensitive
	 $Myquery = "SELECT count(*) as count from products
		   where id='$id'";	 
		
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
  	
  function insertProduct ($product)
  {	
		// Connect to the database
   $mysqli = connectdb();
	
   $id = $product->getId();	
   $name = $product->getName();
   $cost = $product->getCost();
 
   
		
		// Add Prepared Statement
		$Query = "INSERT INTO products (id,name,cost) 
	           VALUES (?,?,?)";
	           
		
		$stmt = $mysqli->prepare($Query);
				
$stmt->bind_param("isi",$id, $name, $cost);
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
    	$this->id= $value;    	
    }
    public function setName ($value)
    {
    	$this->name = $value;    	
    }
    public function setCost ($value)
    {
    	$this->cost = $value;    	
    }
   
         
    
} // End Accountclass

?>
</body>
</html>
