# Menambahkan halaman indeks untuk menampilkan semua data
1. Buatlah file `index.php` pada _root directory_.
2. Tambahkan header dan footer halaman, file `database`, `product`, dan `category`.
    ```php
    <?php
    $page_title = "Read Products";
    
    include_once 'config/database.php';
    include_once 'objects/product.php';
    include_once 'objects/category.php';
    
    include_once "header.php";
    
    ?>
    
    <!--Button Create Product-->
    
    <!--Content area-->
    
    <!--Paging area-->
    
    <!-- Script untuk delete product -->
    <?
    include_once "footer.php";
    ?>
    ```
3. Tambahkan button **Create Product** di antara bagian header dan footer.
    ```php
    ...
    <!--Button Create Product-->
    
    <div class="right-button-margin">
        <a href="create_product.php" class="btn btn-default pull-right">Create Product</a>
    </div>
    
    ...
    ```
4. Tambahkan pengaturan _pagination_ dan konten tabel data `products` yang akan ditampilkan di bawah button **Create Product**
    ```php
    <!--Content area-->
    
    <?php
    // Tambahkan pagination
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    
    // Set jumlah record dalam 1 halaman
    $records_per_page = 3;
    
    // Hitung limit data dalam query
    $from_record_num = ($records_per_page * $page) - $records_per_page;
    
    
    $database = new Database();
    $db = $database->getConnection();
    
    $product = new Product($db);
    
    // Query product
    $stmt = $product->readAll($page, $from_record_num, $records_per_page);
    $num = $stmt->rowCount();
    
    if ($num >0)
    {
        $category = new Category($db);
    
        echo '<table class="table table-hover table-responsive table-bordered">';
        echo '  <tr>';
        echo '      <th>Product</th>';
        echo '      <th>Price</th>';
        echo '      <th>Description</th>';
        echo '      <th>Category</th>';
        echo '      <th>Actions</th>';
        echo '  </tr>';
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
            echo '<tr>';
            echo '  <td>'.$name.'</td>';
            echo '  <td>'.$price.'</td>';
            echo '  <td>'.$description.'</td>';
            echo '  <td>';
                $category->id = $category_id;
                $category->readName();
                echo $category->name;
            echo '</td>';
            echo '  <td>';
                <!--Edit dan Delete button-->
            echo '</td>';
            echo '</tr>';
        }
    
        echo '</table>';
    
        // paging button will be here
    }
    else
    {
        echo '<div>No products found.</div>';
    }
    
    ?>
    ```
5. Tambahkan fungsi `readAll()` pada file `objects/product.php`
    ```php
    ...
    function readAll($page, $from_record_num, $records_per_page){
       
        $query = "SELECT
        id, name, description, price, category_id
        FROM
        " . $this->table_name . "
        ORDER BY
        name ASC
        LIMIT
        {$from_record_num}, {$records_per_page}";
        
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        
        return $stmt;
    }
    ...
    ```
6. Tambahkan button **Edit** dan **Delete** di bagian _comment_ pada kode program sebelumnya.
    ```php
    ...
        <!--Edit dan Delete button-->
        echo '<a href="update_product.php?id='.$id.'" class="btn btn-default left-margin">Edit</a>';
        echo '<a delete-id="'.$id.'" class="btn btn-danger delete-object">Delete</a>';
    
    ...
    ```

# Menambahkan Paging Button
1. Buatlah sebuah file baru dengan nama `paging_product.php` pada _root directory_.
2. Tambahkan kode berikut di dalam file `paging_product.php`
    ```php
    <?php
    // the page where this paging is used
    $page_dom = "index.php";
    
    echo "<ul class=\"pagination\">";
    
    // button for first page
    if($page>1){
        echo "<li><a href='{$page_dom}' title='Go to the first page.'>";
        echo "<<";
        echo "</a></li>";
    }
    
    // count all products in the database to calculate total pages
    $total_rows = $product->countAll();
    $total_pages = ceil($total_rows / $records_per_page);
    
    // range of links to show
    $range = 2;
    
    // display links to 'range of pages' around 'current page'
    $initial_num = $page - $range;
    $condition_limit_num = ($page + $range)  + 1;
    
    for ($x=$initial_num; $x<$condition_limit_num; $x++) {
    
        // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
        if (($x > 0) && ($x <= $total_pages)) {
    
            // current page
            if ($x == $page) {
                echo "<li class='active'><a href=\"#\">$x <span class=\"sr-only\">(current)</span></a></li>";
            } 
    
            // not current page
            else {
                echo "<li><a href='{$page_dom}?page=$x'>$x</a></li>";
            }
        }
    }
    
    // button for last page
    if($page<$total_pages){
        echo "<li><a href='" .$page_dom . "?page={$total_pages}' title='Last page is {$total_pages}.'>";
        echo ">>";
        echo "</a></li>";
    }
    
    echo "</ul>";
    ?>
    ```
3. Tambahkan fungsi `countAll` pada `objects/product.php`
    ```php
    ...
        // used for paging products
        public function countAll(){
         
            $query = "SELECT id FROM " . $this->table_name . "";
         
            $stmt = $this->conn->prepare( $query );
            $stmt->execute();
         
            $num = $stmt->rowCount();
         
            return $num;
        }
    ...
    ```
4. _Include_-kan `paging_product.php` di dalam file `index.php` pada bagian comment `<!--Paging area-->`.

    ```php
    ...
        <!--Paging area-->
        include_once 'paging_product.php';
    ...
    ```

5. Jalankan aplikasi untuk melihat hasilnya.
