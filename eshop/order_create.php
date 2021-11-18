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
                <li><a href="order_read.php">Read Orders</a></li>
            </ul>
        </div>
    </nav>


    <!-- container -->
    <div class="container">

        <div class="page-header">
            <h1>Create New Order</h1>
        </div>


        <?php
        include 'config/database.php';


        $q = "SELECT product_id, name, price FROM products";
        $stmt = $con->prepare($q);
        $stmt->execute();

        $product_arrID = array();
        $product_arrName = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($product_arrID, $row['product_id']);
            array_push($product_arrName, $row['name']);
        }

        $cus_username = "SELECT username FROM customers";

        $cu = $con->prepare($cus_username);
        $cu->execute();


        if ($_POST) {
            // include database connection
            include 'config/database.php';

            // echo count($_POST['product']); This is to display how many product(Array) count 

            $flag = 0;
            $message = '';
            $pflag = 0;
            $pflagfail = 0;

            for ($count1 = 0; $count1 < count($_POST['product']); $count1++) {
                if (!empty($_POST['quantity'][$count1]) && !empty($_POST['product'][$count1])) {
                    $pflag++;
                }
                if (empty($_POST['quantity'][$count1]) || empty($_POST['product'][$count1])) {
                    $pflagfail++;
                }
            }
            if (empty($_POST['cus_username'])) {
                $flag = 1;
                $message = 'Please select a Username.';
            } else if ($pflag == 0 || $pflagfail > 0) {
                $flag = 1;
                $message = "Please Select a Product together with Quantity.";
            } else if (count($_POST['product']) !== count(array_unique($_POST['product']))) {
                $flag = 1;
                $message = "You can't select the same product multiple times";
            }





            try {
                // insert query
                $query = "INSERT INTO order_summary SET username=:cus_username, order_date=:order_date";
                // prepare query for execution
                $stmt = $con->prepare($query);
                $cus_username = $_POST['cus_username'];
                // bind the parameters
                $stmt->bindParam(':cus_username', $cus_username);
                $order_date = date('Y-m-d H:i:s'); // get the current date and time
                $stmt->bindParam(':order_date', $order_date);


                if ($flag == 0) {
                    if ($stmt->execute()) {
                        $last_id = $con->lastInsertId();
                        for ($count = 0; $count < count($_POST['product']); $count++) {
                            $query2 = "INSERT INTO order_details SET order_id=:order_id, product_id=:product_id, quantity=:quantity";
                            $stmt = $con->prepare($query2);
                            $stmt->bindParam(':order_id', $last_id);
                            $stmt->bindParam(':product_id', $_POST['product'][$count]);
                            $stmt->bindParam(':quantity', $_POST['quantity'][$count]);
                            if (!empty($_POST['product'][$count]) && !empty($_POST['quantity'][$count])) {
                                $stmt->execute();
                            }
                        }
                        echo "<div class='alert alert-success'>Record was saved.Last inserted ID is: $last_id</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>";
                    echo $message;
                    echo "</div>";
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }

        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">

            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Customer Name:</td>
                    <?php
                    echo "<td>";
                    echo '<select class="w-100 fs-4 rounded" id="" name="cus_username">';
                    echo  '<option class="bg-white" disable selected value>Select Your Username</option>';
                    while ($row = $cu->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $selected = $username == $_POST['cus_username'] ? 'selected' : ''; // if user input a username then it will store in selected($username == to $_POST['cus_username']), then output after save, if no then it will not store any thing which is ' '.
                        echo "<option class='bg-white' value='" . $username . "' $selected>" . $username . "</option>";
                    }


                    echo "</td>";
                    ?>
                </tr>
                <tr>
                    <th>Products</th>
                    <th>Quantity</th>
                </tr>
                <?php

                $quantity = 1;

                echo "<tr class='productrow'>";
                echo '<td>
                       <select class="fs-4 rounded" name="product[]">';
                echo  '<option disable selected value>Select Product</option>';
                for ($product_count = 0; $product_count < count($product_arrName); $product_count++) {
                    echo  "<option value='" . $product_arrID[$product_count] . "'>" . $product_arrName[$product_count] . "</option>";
                }
                echo "</select>";
                echo '</td>';


                echo "<td>";
                echo '<select class="w-100 fs-4 rounded" name="quantity[]" class="form-control">';
                echo "<option class='bg-white' disable selected value> Select Quantity</option>";
                for ($quantity = 1; $quantity <= 10; $quantity++) {
                    echo "<option value='$quantity'>$quantity</option>";
                }
                echo '</td>';
                echo "</tr>";

                ?>

                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='order_read.php' class='btn btn-danger'>Back to read orders</a>
                    </td>
                </tr>
            </table>
        </form>
        <div class="d-flex justify-content-center flex-column flex-lg-row">
            <div class="d-flex justify-content-center">
                <button type="button" class="add_one btn mb-3 mx-2">Add More Product</button>
                <button type="button" class="delete_one btn mb-3 mx-2">Delete Last Product</button>
            </div>
        </div>
    </div>


    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('click', function(event) {
            if (event.target.matches('.add_one')) {
                var element = document.querySelector('.productrow');
                var clone = element.cloneNode(true);
                element.after(clone);
            }
            if (event.target.matches('.delete_one')) {
                var total = document.querySelectorAll('.productrow').length;
                if (total > 1) {
                    var element = document.querySelector('.productrow');
                    element.remove(element);
                }
            }
        }, false);
    </script>
</body>

</html>