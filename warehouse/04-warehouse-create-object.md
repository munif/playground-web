# Membuat kelas `Category` dan `Product`

## Membuat kelas `Category`
1. Tambahkan file `category.php` pada folder `objects/`.
  ```php
  <?php
  class Category {
   
      // database connection and table name
      private $conn;
      private $table_name = "categories";
   
      // object properties
      public $id;
      public $name;
   
      public function __construct($db){
          $this->conn = $db;
      }
   
      // used by select drop-down list
      function read(){
          //select all data
          $query = "SELECT
                      id, name
                  FROM
                      " . $this->table_name . "
                  ORDER BY
                      name";  
   
          $stmt = $this->conn->prepare( $query );
          $stmt->execute();
   
          return $stmt;
      }
  
      // used to read category name by its ID
      function readName(){
           
          $query = "SELECT name FROM " . $this->table_name . " WHERE id = ? limit 0,1";
       
          $stmt = $this->conn->prepare( $query );
          $stmt->bindParam(1, $this->id);
          $stmt->execute();
       
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
           
          $this->name = $row['name'];
      }
  }
  ?>
  ```

## Membuat kelas `Product`
1. Tambahkan file `product.php` pada folder `objects/`.
  ```php
  <?php
  class Product{
   
      // database connection and table name
      private $conn;
      private $table_name = "products";
   
      // object properties
      public $id;
      public $name;
      public $price;
      public $description;
      public $category_id;
      public $timestamp;
   
      public function __construct($db){
          $this->conn = $db;
      }
   
      // create product
      function create(){
   
          // to get time-stamp for 'created' field
          $this->getTimestamp();
   
          //write query
          $query = "INSERT INTO
                      " . $this->table_name . "
                  SET
                      name = ?, price = ?, description = ?, category_id = ?, created = ?";
   
          $stmt = $this->conn->prepare($query);
   
          // posted values
          $this->name=htmlspecialchars(strip_tags($this->name));
          $this->price=htmlspecialchars(strip_tags($this->price));
          $this->description=htmlspecialchars(strip_tags($this->description));
          $this->category_id=htmlspecialchars(strip_tags($this->category_id));
          $this->timestamp=htmlspecialchars(strip_tags($this->timestamp));
   
          // bind values
          $stmt->bindParam(1, $this->name);
          $stmt->bindParam(2, $this->price);
          $stmt->bindParam(3, $this->description);
          $stmt->bindParam(4, $this->category_id);
          $stmt->bindParam(5, $this->timestamp);
   
          if($stmt->execute()){
              return true;
          }else{
              return false;
          }
   
      }
  
      // used for the 'created' field when creating a product
      function getTimestamp(){
          date_default_timezone_set('Asia/Jakarta');
          $this->timestamp = date('Y-m-d H:i:s');
      }
  }
  ?>
  ```
