<!--ID : 2020052 -->
<!--Name : WAN HAO XIN   -->
<!--Topic : Eshop Order Read One-->






<?php
include 'config/navbar.php';
include 'config/session.php.php';
?>


<body>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Orders</h1>
        </div>

        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';


        // read current record's data
        try {
            $query = "SELECT order_details.orderdetail_id, order_details.order_id, order_details.product_id, order_details.quantity, products.name, products.price 
            FROM order_details 
            INNER JOIN products 
            ON order_details.product_id = products.product_id 
            WHERE order_id = :order_id ";

            $stmt = $con->prepare($query);

            $stmt->bindParam(":order_id", $id);

            $stmt->execute();

            $num = $stmt->rowCount();



            //  $query2 = "SELECT customers.username FROM customers INNER JOIN order_summary ON order_summary.username WHERE username =:username";
            $query2 = "SELECT order_summary.order_id, customers.first_name, customers.last_name, customers.username
            FROM order_summary  
            INNER JOIN customers 
            ON order_summary.username= customers.username 
            ORDER BY order_id = $id ";


            $stmt2 = $con->prepare($query2);
            $stmt2->execute();

            $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            $username = $row2['username'];
            $first_name = $row2['first_name'];
            $last_name = $row2['last_name'];




            if ($num > 0) {

                echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

                //creating our table heading
                echo "<tr>";

                echo "<th>Order ID</th> <td> $id</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<th> Customer Name</th><td> $username </td>";
                //echo "<th> Customer Name</th><td>" . $first_name . "" . $last_name . "</td>";
                echo "</tr>";


                echo "</table>";


                echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

                echo "<th>Product Name </th>";
                echo "<th>Quantity</th>";
                echo "<th>Price</th>";
                echo "<th>Total</th>";
                // echo "<th>Username</th>";
                echo "</tr>";

                $grand_total = 0;
                // retrieve our table contents
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    // creating new table row per record
                    echo "<tr>";
                    echo "<td>{$name}</td>";
                    echo "<td>{$quantity}</td>";
                    echo "<td class='col text-end'>{$price}</td>";
                    $total = $price * $quantity;
                    echo "<td class='col text-end'>{$total}</td>";
                    echo "</tr>";
                    $grand_total = $grand_total + $total;
                }

                echo "<tr class='fw-bold fs-5'>";
                echo "<td colspan='3'>Grand Total:</td>";
                echo "<td class='col text-end'>$grand_total</td>";
                echo "</tr>";



                // end table
                echo "</table>";
            }
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <tr>
            <td></td>
            <td>
                <a href='order_read.php' class='btn btn-danger'>Back to read order</a>
            </td>
        </tr>

    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

</body>

</html>