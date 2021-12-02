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
                $message = "Please Select a Product together with Quantity OR Save again.";
            } else if (count($_POST['product']) !== count(array_unique($_POST['product']))) { // if the product[array] count is not equal to another, then run this (meaning that same items can't appear twice, prodcut [array] should be the same)
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
                    <option value="">
                        <td>Customer Name:</td>
                    </option>

                    <?php
                    echo "<td>";
                    echo '<select class="w-100 fs-4 rounded" id="" name="cus_username">';
                    echo  '<option class="bg-white" disable selected value>Select Your Username</option>';
                    $customer_list = $_POST ? $POST['cus_username'] : '';
                    while ($row = $cu->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $selected_username = $row['username'] == $customer_list ? 'selected' : ''; // if user input a username then it will store in selected($username == to $_POST['cus_username']), then output after save, if no then it will not store any thing which is ' '.
                        echo "<option class='bg-white' value='" . $username . "' $selected_username>" . $username . "</option>";
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

                /* if ($_POST) {
                    $post_product = count($_POST['product']);
                } else {
                    $post_product = 1;
                }*/
                $post_product = $_POST ? count($_POST['product']) : 1; // SAME MEANING last if else comment
                //if we add more than 1 row of ['product'], then post_product will increase, otherwise it will stay at 1
                $arrayP = array('');
                if ($_POST) {

                    for ($y = 0; $y <= count($_POST['product']); $y++) {
                        if (empty($_POST['product'][$y])  && empty($_POST['quantity'][$y])) {
                            unset($_POST['product'][$y]);
                            unset($_POST['quantity'][$y]);
                        }
                        $arrayP = $_POST['product'];
                    }

                    echo '<pre>';
                    //  var_dump($_POST);
                    echo '</pre>';
                }





                //for ($productrow = 0; $productrow < $post_product; $productrow++) {
                foreach ($arrayP as $productrow => $product_ID) {
                    //the for loop here will loop a total of how many product row we inserted (also meaning that how many product we try to order)
                    echo "<tr class='productrow'>";
                    echo '<td>
                       <select class="fs-4 rounded" name="product[]">';
                    echo  '<option disable selected value>Select Product</option>';
                    for ($product_count = 0; $product_count < count($product_arrName); $product_count++) {
                        //this for loop is to loop how many product count we have base on how much product name we have
                        $selected_product = $product_arrID[$product_count] == $_POST['product'][$productrow] ? 'selected' : '';
                        // each product in each product row will compare with the productID(which product we selected) then it will saved until the next page because we have 'selected' the product if not, it will stay empty
                        echo  "<option value='" . $product_arrID[$product_count] . "' $selected_product>" . $product_arrName[$product_count] . "</option>";
                        //we are choosing the product from the product name printing out by array([$product_count]), getting the value of each product which is their ID, exp: Basketball(Product_arrName) also means 1 in the product_arrID ARRAY
                    }
                    echo "</select>";
                    echo '</td>';


                    echo "<td>";
                    echo '<select class="w-100 fs-4 rounded" name="quantity[]" class="form-control">';
                    echo "<option class='bg-white' disable selected value> Select Quantity</option>";
                    for ($quantity = 1; $quantity <= 10; $quantity++) {
                        $selected_quantity = $quantity == $_POST['quantity'][$productrow] ? 'selected' : '';
                        echo "<option value='$quantity' $selected_quantity>$quantity</option>";
                    }
                    echo '</td>';
                    echo "</tr>";
                }
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