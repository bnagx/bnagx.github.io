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
                <li class="active"><a href="product_create.php">Create Products</a></li>
                <li><a href="order_create.php">Create Order</a></li>
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
            <h1>Create Product</h1>
        </div>

        <!-- html form to create product will be here -->
        <!-- PHP insert code will be here -->
        <?php
        if ($_POST) {
            // include database connection
            include 'config/database.php';
            try {
                // insert query
                $query = "INSERT INTO products SET name=:name, description=:description, price=:price, created=:created, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date";
                // prepare query for execution
                $stmt = $con->prepare($query);
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $promotion_price = $_POST['promotion_price'];
                $manufacture_date = $_POST['manufacture_date'];
                $expired_date = $_POST['expired_date'];
                // bind the parameters
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $created = date('Y-m-d H:i:s'); // get the current date and time
                $stmt->bindParam(':created', $created);
                $stmt->bindParam(':promotion_price', $promotion_price);
                $stmt->bindParam(':manufacture_date', $manufacture_date);
                $stmt->bindParam(':expired_date', $expired_date);

                $flag = 0;
                $message = "";

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (empty($_POST["name"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $nameErr = "Name is required";
                    } else {
                        $name = ($_POST["name"]);
                    }

                    if (empty($_POST["description"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $descriptionErr = "Description is required";
                    } else {
                        $description = trim(htmlspecialchars($_POST["description"]));
                    }

                    if (empty($_POST["price"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $priceErr = "Price is required";
                    } else {
                        $price = ($_POST["price"]);
                    }

                    if (empty($_POST["promotion_price"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $promo_priceErr = "Promotion Price is required";
                    } else {
                        $promo_price = ($_POST["promotion_price"]);
                    }

                    if (empty($_POST["manufacture_date"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $manu_dateErr = "manufacture_date is required";
                    } else {
                        $manu_date = ($_POST["manufacture_date"]);
                    }

                    if (empty($_POST["expired_date"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $exp_dateErr = "Expired date is required";
                    } else {
                        $exp_date = ($_POST["expired_date"]);
                    }

                    if (!is_numeric($price) || !is_numeric($promotion_price)) {
                        $flag = 1;
                        $message = "Price / Promotion Price must be numerical.";
                    } elseif ($price < 0 || $promotion_price < 0) {
                        $flag = 1;
                        $message = "Price cannot be negative.";
                    } elseif ($promotion_price > $price) {
                        $flag = 1;
                        $message = "Error: Promo Price must be cheaper than Normal Price";
                    } elseif ($manufacture_date > $expired_date) {
                        $flag = 1;
                        $message = "Error: Expired date must be after Manufacture date";
                    }
                }



                if ($flag == 0) {
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    } else {
                        echo "Unable to save record.";
                    }
                } else {
                    echo "<div class='alert alert-danger'>";
                    echo $message;
                    echo "</div>";
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
                    <td>Name</td>
                    <td><input type='text' name='name' class='form-control' />
                        <span>
                            <?php if (isset($nameErr)) echo "<div class='text-danger'>*$nameErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control'></textarea>
                        <span>
                            <?php if (isset($descriptionErr)) echo "<div class='text-danger'>*$descriptionErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' class='form-control' />
                        <span>
                            <?php if (isset($priceErr)) echo "<div class='text-danger'>*$priceErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promotion_price' class='form-control' />
                        <span>
                            <?php if (isset($promo_priceErr)) echo "<div class='text-danger'>*$promo_priceErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manufacture_date' class='form-control' />
                        <span>
                            <?php if (isset($manu_dateErr)) echo "<div class='text-danger'>*$manu_dateErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type="date" name='expired_date' class='form-control' />
                        <span>
                            <?php if (isset($exp_dateErr)) echo "<div class='text-danger'>*$exp_dateErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href="product_read.php" class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>