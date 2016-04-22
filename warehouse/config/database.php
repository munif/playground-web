<?php
/**
 * Created by PhpStorm.
 * User: Abdul Munif
 * Date: 4/15/2016
 * Time: 8:51 AM
 */
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