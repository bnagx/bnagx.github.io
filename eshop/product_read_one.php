<!--ID : 2020052-BSE -->
<!--Name : WAN HAO XIN   -->
<!--Topic : Eshop Customer Read One-->




<?php
include 'config/session.php';
include 'config/navbar.php';
?>


<head>
    <title>Create Product</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>


<!-- container -->
<div class="container">
    <div class="page-header">
        <h1>Read Product</h1>
    </div>

    <?php
    // get passed parameter value, in this case, the record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

    //include database connection
    include 'config/database.php';

    // read current record's data


    //         $query2 = "SELECT order_summary.order_id, customers.first_name, customers.last_name, customers.username
    // FROM order_summary  
    // INNER JOIN customers 
    // ON order_summary.username= customers.username 
    // ORDER BY order_id = $id ";

    try {
        // prepare select query
        $query = "SELECT products.product_id, products.name, products.description, products.product_img, products.price, products.promotion_price, products.manufacture_date, products.expired_date, categories.category_name FROM products INNER JOIN categories ON products.category_id=categories.category_id WHERE product_id = :product_id ";
        $stmt = $con->prepare($query);

        // Bind the parameter
        $stmt->bindParam(":product_id", $id);

        // execute our query
        $stmt->execute();

        // store retrieved row to a variable
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // values to fill up our form
        $name = $row['name'];
        $description = $row['description'];
        $product_img = $row['product_img'];
        $price = $row['price'];
        $promo_price = $row['promotion_price'];
        $manu_date = $row['manufacture_date'];
        $exp_date = $row['expired_date'];
        $product_category = $row['category_name'];
        // shorter way to do that is extract($row)
    }

    // show error
    catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }
    ?>


    <!--we have our html table here where the record will be displayed-->
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Product Image</td>

            <?php
            if ($product_img == '') {
                echo '<td>No image</td>';
            } else {
                echo '<td><img src="productimg/' . $product_img . '"width="200px"></td>';
            }

            ?>
        </tr>
        <tr>
            <td>Name</td>
            <td><?php echo htmlspecialchars($name, ENT_QUOTES);  ?></td>
        </tr>
        <tr>
            <td>Category</td>
            <td><?php echo htmlspecialchars($product_category, ENT_QUOTES);  ?></td>
        </tr>
        <tr>
            <td>Description</td>
            <td><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></td>
        </tr>
        <tr>
            <td>Price</td>
            <td><?php echo htmlspecialchars(number_format($price, 2), ENT_QUOTES);  ?></td>
        </tr>
        <tr>
            <td>Promotion Price</td>
            <td><?php echo htmlspecialchars(number_format($promo_price, 2), ENT_QUOTES);  ?></td>
        </tr>
        <tr>
            <td>Manufacture Date</td>
            <td><?php echo htmlspecialchars($manu_date, ENT_QUOTES);  ?></td>
        </tr>
        <tr>
            <td>Expired Date</td>
            <td><?php echo htmlspecialchars($exp_date, ENT_QUOTES);  ?></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <a href='product_read.php' class='btn btn-danger'>Back to Product List</a>
            </td>
        </tr>
    </table>


</div> <!-- end .container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

</body>

</html>