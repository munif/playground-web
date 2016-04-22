# Membuat file koneksi basis data

1. Buat file `database.php` di dalam folder `config`. Sesuaikan `host`, `db_name`, `username`, dan `password` database kalian.
  ```php
  <?php
  class Database{
   
      // specify your own database credentials
      private $host = "localhost";
      private $db_name = "warehouse";
      private $username = "root";
      private $password = "root";
      public $conn;
   
      // get the database connection
      public function getConnection(){
   
          $this->conn = null;
   
          try{
              $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
          }catch(PDOException $exception){
              echo "Connection error: " . $exception->getMessage();
          }
   
          return $this->conn;
      }
  }
  ?>
  ```
