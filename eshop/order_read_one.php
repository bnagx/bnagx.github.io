<!DOCTYPE HTML>
<html>

<head>
    <title>Read one order</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</head>

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
            // prepare select query
            $query = "SELECT orderdetail_id, order_id, product_id, quantity FROM order_details WHERE orderdetail_id = :orderdetail_id ";
            $stmt = $con->prepare($query);

            // Bind the parameter
            $stmt->bindParam(":orderdetail_id", $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $orderdetail_id = $row['orderdetail_id'];
            $order_id = $row['order_id'];
            $product_id = $row['product_id'];
            $quantity = $row['quantity'];

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
                <td>orderdetail_id</td>
                <td><?php echo htmlspecialchars($orderdetail_id, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>order_id</td>
                <td><?php echo htmlspecialchars($order_id, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>product_id</td>
                <td><?php echo htmlspecialchars($product_id, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>quantity</td>
                <td><?php echo htmlspecialchars($quantity, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a href='order_read.php' class='btn btn-danger'>Back to read orders</a>
                </td>
            </tr>
        </table>


    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

</body>

</html>