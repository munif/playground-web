# Membuat form **Update Record**
1. Buatlah file `update_product.php` pada _root directory_.
2. Tambahkan _header_, button **Read Products**, dan _footer_.
    ```php
    <?php
    
    $page_title = "Update Product";
    include_once "header.php";
    include_once 'config/database.php';
    include_once 'objects/product.php';
    
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
    
    $product = new Product($db);
    
    ?>
    
    <!-- Kode apabila form disubmit -->
    
    <div class="right-button-margin">
        <a href="index.php" class="btn btn-default pull-right">Read Products</a>
    </div>
    
    <!-- Dapatkan satu `product` berdasarkan request `id` -->
    
    <!-- Form Edit Product -->
    
    <?php
    
    include_once "footer.php";
    ?>
    ```
3. Dapatkan satu `product` berdasarkan request `id`.
    ```php
    <!-- Dapatkan satu `product` berdasarkan request `id` -->
    <?
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
    
        // get database connection
        $database = new Database();
        $db = $database->getConnection();
    
        // prepare product object
        $product = new Product($db);
    
        // set ID property of product to be edited
        $product->id = $id;
    
        // read the details of product to be edited
        $product->readOne();
    ?>
    ```
4. Tambahkan fungsi `readOne()` pada file `objects/product.php`.
    ```php
    ...
        public function readOne(){
     
            $query = "SELECT
                        name, price, description, category_id
                    FROM
                        " . $this->table_name . "
                    WHERE
                        id = ?
                    LIMIT
                        0,1";
         
            $stmt = $this->conn->prepare( $query );
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
         
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
         
            $this->name = $row['name'];
            $this->price = $row['price'];
            $this->description = $row['description'];
            $this->category_id = $row['category_id'];
        }
    ```
5. Tambahkan form edit product dan tampilkan data dari hasil query sebelumnya.
    ```php
    <!-- Form Edit Product -->
    <form action="update_product.php?id=<?php echo $id;?>" method="post">
        <table class="table table-hover table-responsive table-bordered">
            <input type="hidden" name="id" value="<?php echo $product->id;?>">
            <tr>
                <td>Name</td>
                <td><input type="text" name="name" value="<?php echo $product->name; ?>"></td>
            </tr>
            <tr>
                <td>Price</td>
                <td><input type="text" name="name" value="<?php echo $product->price; ?>"></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><textarea name="description" class="form-control"><?php echo $product->description; ?></textarea></td>
            </tr>
            <tr>
                <td>Category</td>
                <td>
                    <?php
                    // read the product categories from the database
                    include_once 'objects/category.php';
     
                    $category = new Category($db);
                    $stmt = $category->read();
             
                    // put them in a select drop-down
                    echo "<select class='form-control' name='category_id'>";
             
                        echo "<option>Please select...</option>";
                        while ($row_category = $stmt->fetch(PDO::FETCH_ASSOC)){
                            extract($row_category);
             
                            // current category of the product must be selected
                            if($product->category_id==$id){
                                echo "<option value='$id' selected>";
                            }else{
                                echo "<option value='$id'>";
                            }
             
                            echo "$name</option>";
                        }
                    echo "</select>";
                    ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button class="btn btn-primary" type="submit">Update</button>
                </td>
            </tr>
        </table>
    </form>
    ```
6. Tambahkan kode program untuk menangani request `POST` (form disubmit).
    ```php
    ...
    <!-- Kode apabila form disubmit -->
    <?
    if($_POST){
     
        // set product property values
        $product = new Product($db);
        $product->name = $_POST['name'];
        $product->price = $_POST['price'];
        $product->description = $_POST['description'];
        $product->category_id = $_POST['category_id'];
     
        // update the product
        if($product->update()){
            echo "<div class=\"alert alert-success alert-dismissable\">";
                echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                echo "Product was updated.";
            echo "</div>";
        }
     
        // if unable to update the product, tell the user
        else{
            echo "<div class=\"alert alert-danger alert-dismissable\">";
                echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                echo "Unable to update product.";
            echo "</div>";
        }
    }
    ?>
    ...
    ```
7. Tambahkan fungsi `update` pada file `objects/product.php`.
    ```php
        public function update(){
         
            $query = "UPDATE
                        " . $this->table_name . "
                    SET
                        name = :name,
                        price = :price,
                        description = :description,
                        category_id  = :category_id
                    WHERE
                        id = :id";
         
            $stmt = $this->conn->prepare($query);
         
            // posted values
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->price=htmlspecialchars(strip_tags($this->price));
            $this->description=htmlspecialchars(strip_tags($this->description));
            $this->category_id=htmlspecialchars(strip_tags($this->category_id));
            $this->id=htmlspecialchars(strip_tags($this->id));
         
            // bind parameters
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':price', $this->price, PDO::PARAM_INT);
            $stmt->bindValue(':description', $this->description, PDO::PARAM_STR);
            $stmt->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);    
    
            // execute the query
            if($stmt->execute() && ($stmt->rowCount()>0)){
                return true;
            }else{
                $stmt->errorInfo();
                return false;
            }
        }
    ```
