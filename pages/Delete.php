<html>
	<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">   
   <title>Delete Student </title>
</head>
<body > 

<?php   			

// Check to see if Delete name is provided
if (isset($_GET["id"])) {
  $toDelete = $_GET["id"];
  // A bit dangerous without checks and use of getMethod
  deleteIt($toDelete);
  echo "Thanks for the deletion of $toDelete";
   echo "<p></p>";
	    echo "<a href=Insert.php> Insert Another Product </a>";	   
	     echo "<p></p>"; 
	    echo "<a href=Select.php> Select Product </a>";	
	     echo "<p></p>"; 
	    echo "<a href=Delete.php> Delete Product </a>";	
  
}
 else {
	    show_form();  
	    
	    // Provide option for inserting another student
	    echo "<p></p>";
	    echo "<a href=Insert.php> Insert Another Product </a>";	   
	     echo "<p></p>"; 
	    echo "<a href=Select.php> Select Products </a>";	  
 }
  	
	?>
		
<?php
function show_form() { 			
	
	echo "<p></p>";
	echo "<h2> Select the Product to Delete</h2>";
	echo "<p></p>";	 	
	// Retrieve the products
	$products = selectProducts();
	
	echo "<h3> " . "Number of Products in Database is:  " . sizeof($products) . "</h3>";

	// Loop through table and display
	echo "<table border='1'>";
		echo "<td>ID</td>";
	 	echo "<td>Name</td>";
	 	echo "<td>Cost(GP)</td>";
	foreach ($products as $data) {
	  echo "<tr>";	
		echo "<td> <a href=Delete.php?id=" . $data->getId() . ">" . "Delete" . "</a></td>";
		echo "<td>" . $data->getId() . "</td>";
	 	echo "<td>" . $data->getName() . "</td>";
	 	echo "<td>" . $data->getCost() . "</td>";
	  echo"</tr>";
}
	echo "</table>";


} // End Show form
?>

<?php
  	
  function deleteIt($productD) {
  	echo "About to Delete " . $productD ;
  	// Connect to the database
   $mysqli = connectdb();
   
   // Add Prepared Statement
		$Query = "Delete from products 
		         where id = ?";	          
	           
		
		$stmt = $mysqli->prepare($Query);
				
// Bind and Execute
$stmt->bind_param("i", $productD);
$stmt->execute();

// Clean-up			
	
	$stmt->close();
   
   $mysqli->close();
  }

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

