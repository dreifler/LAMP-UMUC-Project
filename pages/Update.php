<html>
	<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">   
   <title>Update Student </title>
</head>
<body > 

<?php   			
// Check to see if Delete name is provided
if (isset($_GET["id"])) {
  $toUpdate = $_GET["id"];
  // A bit dangerous without checks and use of getMethod
  updateIt($toUpdate);
  
   echo "<p></p>";
	    echo "<a href=Insert.php> Insert Another Product </a>";	   
	     echo "<p></p>"; 
	    echo "<a href=Select.php> Select Products </a>";	
	     echo "<p></p>"; 
	    echo "<a href=Delete.php> Delete Products </a>";  
}
else if (isset($_POST["UpdateMe"])) {
	// Assign values
  $id = $_POST["id"];
  $name = $_POST["name"];
  $cost = $_POST["cost"];
  
  $product = new ProductClass($id,$name,$cost);
  // Update the database
  FinalUpdate($product);
 echo "<p></p>";
	    echo "<a href=Insert.php> Insert Another Product </a>";	   
	     echo "<p></p>";
	    echo "<a href=Select.php> Select Products </a>";	
	     echo "<p></p>"; 
	    echo "<a href=Delete.php> Delete Products </a>";	
	     echo "<p></p>"; 
	    echo "<a href=Update.php> Update Products </a>";	 
}
 else {
	    show_form();  
	    
	    // Provide option for inserting another product
	    echo "<p></p>";
	    echo "<a href=Insert.php> Insert Another Product </a>";	   
	     echo "<p></p>"; 
	    echo "<a href=Select.php> Select Students </a>";	   }
  	
	?>
		
<?php
function show_form() { 			
	
	echo "<p></p>";
	echo "<h2> Select the Product to Update</h2>";
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
		echo "<td> <a href=Update.php?id=" . $data->getId() . ">" . "Update" . "</a></td>";
		echo "<td>" . $data->getId() . "</td>";
	 	echo "<td>" . $data->getName() . "</td>";
	 	echo "<td>" . $data->getCost() . "</td>";
	  echo"</tr>";
}
	echo "</table>";

} // End Show form
?>

<?php
  	
  function getProduct ($productD) {
  	// Connect to the database
   $mysqli = connectdb();
 
   // Add Prepared Statement
		$Query = "Select id,name,cost from products 
		         where id = ?";	         
	           
		$stmt = $mysqli->prepare($Query);
				
// Bind and Execute
$stmt->bind_param("i", $productD);
$result = $stmt->execute();

 $stmt->bind_result($id,$name,$cost);
 
  /* fetch values */
    $stmt->fetch();
  $productData = new Productclass($id,$name,$cost);

// Clean-up				
	$stmt->close();   
   $mysqli->close();
   return $productData;
  }

function updateIt($productD) {
  	
  	
	$product = getProduct($productD);
	// Extract data
	$id = $product->getId();
	$name = $product->getName();
	$cost = $product->getCost();

	// Show the data in the Form for update
	?>
	<p></p>
	
	<form name="updateStudent" method="POST" action="Update.php">	
	<table border="1" width="75%" cellpadding="0">			
			<tr>
				<td width="157">ID:</td>
				<td><input type="text" name="id" value='<?php echo $id ?>' size="30"></td>
			</tr>
			<tr>
				<td width="157">Name:</td>
				<td><input type="text" name="name" value='<?php echo $name ?>' size="30"></td>
			</tr>
			<tr>
				<td width="157">Cost:</td>
				<td><input type="text" name="cost" value='<?php echo $cost ?>' size="30"></td>
			</tr>
			<tr>
				<td width="157"><input type="submit" value="Update" name="UpdateMe"></td>
				<td>&nbsp;</td>
			</tr>
	</table>			
	</form>
		  	
  <?php	
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
// Final Update
function FinalUpdate($product) {
	$mysqli = connectdb();
	
   $id = $product->getId();	
   $name = $product->getName();
   $cost = $product->getCost();
		
		// Add Prepared Statement
		$Query = "UPDATE products SET id=?,name=?,cost=? 
	           WHERE id=?";
	           
		
		$stmt = $mysqli->prepare($Query);
				
$stmt->bind_param("isi",$id, $name, $cost);
$stmt->execute();
		
	$stmt->close();
	$mysqli->close();
		
		return true;
	}
?>
</body>
</html>

	  
