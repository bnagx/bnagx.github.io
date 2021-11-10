<!DOCTYPE HTML>
<html>

<head>
    <title>Create Product</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">

            <ul class="nav navbar-nav">
                <li><a href="home.php">Home</a></li>
                <li><a href="customer_create.php">Create Customer</a></li>
                <li><a href="product_create.php">Create Products</a></li>
                <li class="active"><a href="order_create.php">Create Order</a></li>
                <li><a href="contactus.php">Contact Us</a></li>
                <li><a href="customer_read.php">Read Customers</a></li>
                <li><a href="product_read.php">Read Products</a></li>
                <li><a href="product_read.php">Read Orders</a></li>
            </ul>
        </div>
    </nav>


    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Order</h1>
        </div>

        <!-- html form to create Order will be here -->
        <!-- PHP insert code will be here -->
        <?php

        // include database connection
        include 'config/database.php';

        $q = "SELECT id, name, price FROM products";

        $stmt = $con->prepare($q);
        $stmt->execute();

        $stmt2 = $con->prepare($q);
        $stmt2->execute();

        $stmt3 = $con->prepare($q);
        $stmt3->execute();




        if ($_POST) {

            $q1 = "SELECT id, name, price FROM products WHERE name='" . $_POST['product1'] . "'";
            $stmt = $con->prepare($q1);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $p1 = $row['price'];
            }

            $q2 = "SELECT id, name, price FROM products WHERE name='" . $_POST['product2'] . "'";
            $stmt = $con->prepare($q2);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $p2 = $row['price'];
            }

            $q3 = "SELECT id, name, price FROM products WHERE name='" . $_POST['product3'] . "'";
            $stmt = $con->prepare($q3);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $p3 = $row['price'];
            }


            try {
                // insert query
                $query  = "INSERT INTO order_details SET product1=:product1, quantity1=:quantity1, price1=:price1, product2=:product2, quantity2=:quantity2, price2=:price2,product3=:product3, quantity3=:quantity3, price3=:price3";
                $product1 = $_POST['product1'];
                $quantity1 = $_POST['quantity1'];
                $price1 = $p1 * $_POST['quantity1'];
                $product2 = $_POST['product2'];
                $quantity2 = $_POST['quantity2'];
                $price2 = $p2 * $_POST['quantity2'];
                $product3 = $_POST['product3'];
                $quantity3 = $_POST['quantity3'];
                $price3 = $p3 * $_POST['quantity3'];
                // prepare query for execution
                $stmt = $con->prepare($query);

                // bind the parameters
                $stmt->bindParam(':product1', $product1);
                $stmt->bindParam(':quantity1', $quantity1);
                $stmt->bindParam(':price1', $price1);
                $stmt->bindParam(':product2', $product2);
                $stmt->bindParam(':quantity2', $quantity2);
                $stmt->bindParam(':price2', $price2);
                $stmt->bindParam(':product3', $product3);
                $stmt->bindParam(':quantity3', $quantity3);
                $stmt->bindParam(':price3', $price3);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to save record.</div>";
                }
            }
            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }

            try {
                $customer_username = $_POST['customer_username'];
                $total_price = $price1 + $price2 + $price3;
                $order_date = date('Y-m-d');

                // insert query
                $query  = "INSERT INTO order_summary SET customer_username=:customer_username, total_price=:total_price, order_date=:order_date";
                // prepare query for execution
                $stmt = $con->prepare($query);

                // bind the parameters
                $stmt->bindParam(':customer_username', $customer_username);
                $stmt->bindParam(':total_price', $total_price);
                $stmt->bindParam(':order_date', $order_date);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to save record.</div>";
                }
            }
            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">




            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Customer Name:</td>
                    <td><input type='text' name='customer_username' class='form-control' /></td>
                </tr>
                <tr>
                    <th>Products</th>
                    <th>Quantity</th>
                </tr>

                <?php


                echo "<tr>";
                echo '<td>
                       <select name="product1" class="form-control">';
                echo  '<option class="bg-white" selected> Select Product </option>';
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    echo "<option class='bg-white' value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                }
                echo "</select>";
                echo '</td>';
                echo "<td>";
                echo '<select name="quantity1" class="form-control">';
                echo "<option class='bg-white' selected> Select Quantity</option>";
                for ($quantity = 0; $quantity <= 10; $quantity++) {
                    echo "<option value='$quantity'>$quantity</option>";
                }
                echo '</td>';
                echo "</tr>";


                echo '<td>
                <select name="product2" class="form-control">';
                echo  '<option class="bg-white" selected> Select Product </option>';
                while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                    extract($row2);
                    echo "<option class='bg-white' value='" . $row2['name'] . "'>" . $row2['name'] . "</option>";
                }
                echo "</select>";
                echo '</td>';
                echo "<td>";
                echo '<select name="quantity2" class="form-control">';
                echo "<option class='bg-white' selected> Select Quantity</option>";
                for ($quantity = 0; $quantity <= 10; $quantity++) {
                    echo "<option value='$quantity'>$quantity</option>";
                }
                echo '</td>';



                echo "<tr>";
                echo '<td>
                 <select name="product3" class="form-control">';
                echo  '<option class="bg-white" selected> Select Product </option>';

                while ($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {
                    extract($row3);
                    echo "<option class='bg-white' value='" . $row3['name'] . "'>" . $row3['name'] . "</option>";
                }
                echo "</select>";
                echo '</td>';
                echo "<td>";
                echo '<select name="quantity3" class="form-control">';
                echo "<option class='bg-white' selected> Select Quantity</option>";
                for ($quantity = 0; $quantity <= 10; $quantity++) {
                    echo "<option value='$quantity'>$quantity</option>";
                }
                echo '</td>';
                echo "</tr>";
                ?>

                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='index.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>





    </div>
    ?>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>