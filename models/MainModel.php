<?php
// include('config.php');

// require  'vendor/autoload.php';

class DatabaseClass{	
	
    

    private $connection = null;

    private $dbhost = ""; // Ip Address of database if external connection.
    private $dbuser = ""; // Username for DB
    private $dbpass = ""; // Password for DB
    private $dbname = ""; // DB Name

    // this function is called everytime this class is instantiated		
    public function __construct(){

        try{
            
            $this->dbhost = $_ENV['DBHOST']; // Ip Address of database if external connection.
            $this->dbuser = $_ENV['DBUSER']; // Username for DB
            $this->dbpass = $_ENV['DBPWD']; // Password for DB
            $this->dbname = $_ENV['DBNAME']; // DB Name
            
            $this->connection = new PDO("mysql:host={$this->dbhost};dbname={$this->dbname};", $this->dbuser, $this->dbpass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->connection->exec("SET time_zone = '+08:00'");
            
        }catch(Exception $e){
            throw new Exception($e->getMessage());   
        }			
        
    }

    
    // Insert a row/s in a Database Table
    public function Insert( $statement = "" , $parameters = [] ){
        try{
            
            $this->executeStatement( $statement , $parameters );
            return $this->connection->lastInsertId();
            
        }catch(Exception $e){
            throw new Exception($e->getMessage());   
        }		
    }

    // Select a row/s in a Database Table
    public function Select( $statement = "" , $parameters = [] ){
        try{
            
            $stmt = $this->executeStatement( $statement , $parameters );
            return $stmt->fetchAll();
            
        }catch(Exception $e){
            throw new Exception($e->getMessage());   
        }		
    }
    
    // Update a row/s in a Database Table
    public function Update( $statement = "" , $parameters = [] ){
        try{
            
            $this->executeStatement( $statement , $parameters );
            
        }catch(Exception $e){
            throw new Exception($e->getMessage());   
        }		
    }		
    
    // Remove a row/s in a Database Table
    public function Remove( $statement = "" , $parameters = [] ){
        try{
            
            $this->executeStatement( $statement , $parameters );
            
        }catch(Exception $e){
            throw new Exception($e->getMessage());   
        }		
    }		
    
    // execute statement
    private function executeStatement( $statement = "" , $parameters = [] ){
        try{
        
            $stmt = $this->connection->prepare($statement);
            $stmt->execute($parameters);
            return $stmt;
            
        }catch(Exception $e){
            throw new Exception($e->getMessage());   
        }		
    }

    public function insertRequestBatchRquest($request, $table, $folder =false) {
          // Initialize field and value arrays
          $fields = [];
          $placeholders = [];
          $values = [];
      
          // Loop through the request array
          foreach ($request as $key => $value) {
              if (is_array($value)) {
                  // Handle multiple file uploads
                  if (isset($value['name']) && is_array($value['name'])) {
                      $uploadedPaths = $this->handleMultipleFileUpload($value, $folder);
                      if ($uploadedPaths) {
                          $fields[] = $key; // Add field for the file paths
                          $placeholders[] = '?';
                          $values[] = implode('|', $uploadedPaths); // Concatenate paths with "|"
                      }
                  }
              } else {
                  // Handle normal fields
                  if (!empty($value)) {
                      $fields[] = $key;
                      $placeholders[] = '?';
                      $values[] = $value;
                  }
              }
          }
      
          // Construct the SQL query dynamically
          $fieldList = implode(', ', $fields);
          $placeholderList = implode(', ', $placeholders);
          $sql = "INSERT INTO ".$table." ($fieldList) VALUES ($placeholderList)";
      
          // Execute the query
          $this->Insert($sql, $values);
    }

     // Helper function to handle multiple file uploads
     public function handleMultipleFileUpload($fileArray, $folder = false) {
        if(!$folder) {
            return false;
        }

            // Check if the directory exists; if not, create it
        if (!is_dir($folder)) {
            if (!mkdir($folder, 0777, true)) {
                die("Failed to create directory: $folder");
            }
        }
        
        $uploadDir =  $folder; // Ensure this directory exists and is writable
        $uploadedPaths = [];
    
        foreach ($fileArray['name'] as $index => $fileName) {
            $tmpName = $fileArray['tmp_name'][$index];
            $error = $fileArray['error'][$index];
    
            if ($error === 0) {
                $uniqueFileName = uniqid() . '_' . $fileName;
                $destinationPath = $uploadDir . $uniqueFileName;
    
                if (move_uploaded_file($tmpName, $destinationPath)) {
                    $uploadedPaths[] = $destinationPath; // Collect the uploaded file path
                }
            }
        }
    
        return $uploadedPaths;
    }

 
    
}



?>