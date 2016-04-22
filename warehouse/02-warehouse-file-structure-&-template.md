# Membuat struktur file
1. Buatlah struktur aplikasi web dengan folder seperti berikut
```text
+-- warehouse
|   +-- config
|   +-- objects
|   +-- css
|   +-- img
|   +-- js
```


# Membuat file template
1. Buat file `header.php` pada _root directory_.
    ```php
    <html>
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo $page_title; ?></title>
      <style>
        .left-margin {
          margin:0 .5em 0 0;
        }
    
        .right-button-margin {
          margin: 0 0 1em 0;
          overflow: hidden;
        }
      </style>
    
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <script src="js/jquery.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
    </head>
    
    <body>
    
      <div class="container">
        <?php
          echo "<div class='page-header'>";
          echo "<h1>{$page_title}</h1>";
          echo "</div>";
        ?>
    ```

2. Buat file `footer.php` pada _root directory_.
    ```php
      </div>
      
    </body>
    </html>
    ```
3. Copy-kan file `css/*` dan `js/*` dari repo ini ke dalam folder kalian.
