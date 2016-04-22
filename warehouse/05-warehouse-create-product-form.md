# Membuat halaman **Create Product**
1. Buatlah file `create_product.php` pada _root directory_.
2. Tambahkan page header dan page footer
   ```php
   <?php
   
   $page_title = "Create Product";
   
   include_once "header.php";
   include_once 'config/database.php';
   include_once 'objects/category.php';
    
   $database = new Database();
   $db = $database->getConnection();
   ?>
   
   <!--Area memproses POST form-->
   
   <!--Area button Read Products-->
   
   <!--Area Form-->
   
   
   <?php
   include_once "footer.php";
   ?>
   
   ```

3. Tambahkan button **Read Products** di antara _header_ dan _footer_.
   ```html
   ...
   <!--Area button Read Products-->
   
   <div class="right-button-margin">
       <a href="index.php" class="btn btn-default pull-right">Read Products</a>
   </div>
   
   ...
   ```

4. Tambahkan form untuk memasukkan informasi produk baru. Letakkan form ini di bawah button **Read Products**.
   ```html
   <!--Area Form-->
   <form action="create_product.php" method="post">
       <table class="table table-hover table-responsive table-bordered">
           <tr>
               <td>Name</td>
               <td><input type="text" name="name" class="form-control"></td>
           </tr>
           <tr>
               <td>Price</td>
               <td><input type="text" name="price" class="form-control"></td>
           </tr>
           <tr>
               <td>Description</td>
               <td><textarea name="description" class="form-control"></textarea></td>
           </tr>
           <tr>
               <td>Category</td>
               <td>
                   <!-- Categories from database will be here -->
               </td>
           </tr>
           <tr>
               <td></td>
               <td>
                   <button class="btn btn-primary" type="submit">Create</button>
               </td>
           </tr>
       </table>
   </form>
   ```
5. Tambahkan _looping_ untuk menampilkan dropdown list _category_.
   ```php
   ...
   
   <tr>
       <td>Category</td>
       <td>
          <!-- Categories from database will be here -->
          
           <?php
           $category = new Category($db);
           $stmt = $category->read();
   
           echo '<select name="category_id" class="form-control">';
           echo '<option>Select category ...</option>';
   
           while ($row_category = $stmt->fetch(PDO::FETCH_ASSOC))
           {
               extract($row_category);
               echo '<option value="'.$id.'">'.$name.'</option>';
           }
   
           echo '</select>';
           ?>
       </td>
   </tr>
   
   ...
   ```
6. Tambahkan kode berikut di atas `<form>` input untuk menangani form yang telah di-post.
   ```php
   <!--Area memproses POST form-->
   
   <?php
   // if the form was submitted
   if($_POST){
    
       // instantiate product object
       include_once 'objects/product.php';
       $product = new Product($db);
    
       // set product property values
       $product->name = $_POST['name'];
       $product->price = $_POST['price'];
       $product->description = $_POST['description'];
       $product->category_id = $_POST['category_id'];
    
       // create the product
       if($product->create()){
           echo '<div class="alert alert-success alert-dismissable">';
               echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
               echo "Product was created.";
           echo "</div>";
       }
    
       // if unable to create the product, tell the user
       else{
           echo '<div class="alert alert-danger alert-dismissable">';
               echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
               echo 'Unable to create product.';
           echo '</div>';
       }
   }
   ?>
   ```
