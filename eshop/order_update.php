<!DOCTYPE HTML>
<html>

<head>
    <title> Product update </title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        .m-r-1em {
            margin-right: 1em;
        }

        .m-b-1em {
            margin-bottom: 1em;
        }

        .m-l-1em {
            margin-left: 1em;
        }

        .mt0 {
            margin-top: 0;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="page-header">
            <h1>Update Orders</h1>
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
            $query = "SELECT orderdetail_id, order_id, product_id, quantity FROM order_details WHERE orderdetail_id = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $orderdetail_id = $row['orderdetail_id'];
            $order_id = $row['order_id'];
            $product_id = $row['product_id'];
            $quantity = $row['quantity'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <?php
        // check if form was submitted
        if ($_POST) {
            try {
                // write update query
                // in this case, it seemed like we have so many fields to pass and
                // it is better to label them and not use question marks
                $query = "UPDATE order_details
                  SET orderdetail_id=:orderdetail_id, order_id=:order_id, product_id=:product_id, quantity=:quantity WHERE orderdetail_id = :orderdetail_id";
                // prepare query for excecution
                $stmt = $con->prepare($query);
                // posted values
                $orderdetail_id = ($_POST['orderdetail_id']);
                $order_id = ($_POST['order_id']);
                $product_id = ($_POST['product_id']);
                $quantity = ($_POST['quantity']);
                // bind the parameters
                $stmt->bindParam(':order_id', $order_id);
                $stmt->bindParam(':product_id', $product_id);
                $stmt->bindParam(':quantity', $quantity);
                // Execute the query
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was updated.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                }
            }
            // show errors
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        } ?>



        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>orderdetail_id</td>
                    <td><input type='text' name='orderdetail_id' value="<?php echo htmlspecialchars($orderdetail_id, ENT_QUOTES);  ?> " class='form-control' readonly /></td>
                </tr>
                <tr>
                    <td>order_id</td>
                    <td><textarea name='order_id' class='form-control'><?php echo htmlspecialchars($order_id, ENT_QUOTES);  ?></textarea></td>
                </tr>
                <tr>
                    <td>product_id</td>
                    <td><input type='text' name='product_id' value="<?php echo htmlspecialchars($product_id, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>quantity</td>
                    <td><input type='text' name='quantity' value="<?php echo htmlspecialchars($quantity, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='order_read.php' class='btn btn-danger'>Back to read orders</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
</body>

</html>