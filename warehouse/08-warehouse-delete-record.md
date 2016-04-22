# Membuat _action_ delete data
## Menambahkan fungsi delete pada `objects/product.php`
1. Buka file `objects/product.php` dan tambahkan fungsi berikut.
    ```php
    ...
    // delete the product
    public function delete(){
       
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if($result = $stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    ...
    ```
## Menambahkan kode JavaScript untuk menangani event click button **Delete**
1. Buka file `index.php` dan tambahkan kode program berikut sebelum area `footer`.
    ```php
    ...
    <!-- Script untuk delete product -->
    <script>
    $(document).on('click', '.delete-object', function(){
             
        var id = $(this).attr('delete-id');
        var q = confirm("Are you sure?");
         
        if (q == true){
     
            $.post('delete_product.php', {
                object_id: id
            }, function(data){
                location.reload();
            }).fail(function() {
                alert('Unable to delete.');
            });
     
        }
             
        return false;
    });
    </script>
    ...
    ```

## Menambahkan halaman `delete_product.php`
1. Buatlah file baru dengan nama `delete_product.php` pada _root directory_.
2. Tambahkan kode program berikut.
    ```php
    <?php
    // check if value was posted
    if($_POST){
     
        // include database and object file
        include_once 'config/database.php';
        include_once 'objects/product.php';
     
        // get database connection
        $database = new Database();
        $db = $database->getConnection();
     
        // prepare product object
        $product = new Product($db);
         
        // set product id to be deleted
        $product->id = $_POST['object_id'];
         
        // delete the product
        if($product->delete()){
            echo "Object was deleted.";
        }
         
        // if unable to delete the product
        else{
            echo "Unable to delete object.";
             
        }
    }
    ?>
    ```
3. Tes dengan mengeklik button `Delete` yang ada pada tiap baris data. Apabila kode program benar, aplikasi seharusnya akan menampilkan notifikasi konfirmasi. Dan apabila diklik OK maka akan menghapus data yang dipilih.
