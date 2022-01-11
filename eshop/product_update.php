<!--ID : 2020052 -->
<!--Name : WAN HAO XIN   -->
<!--Topic : Eshop Customer Update-->



<?php
include 'config/session.php';
include 'config/navbar.php';
?>


<head>
    <title>Product Update</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>



<div class="container">
    <div class="page-header">
        <h1>Update Product</h1>
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
        $query = "SELECT products.product_id, products.name, products.description, products.product_img, products.price, products.promotion_price, products.manufacture_date, products.expired_date, categories.category_id FROM products INNER JOIN categories ON products.category_id=categories.category_id WHERE product_id = ? LIMIT 0,1";
        $stmt = $con->prepare($query);

        // this is the first question mark
        $stmt->bindParam(1, $id);

        // execute our query
        $stmt->execute();

        // store retrieved row to a variable
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // values to fill up our form
        $name = $row['name'];
        $product_category_id = $row['category_id'];
        $description = $row['description'];
        $product_img = $row['product_img'];
        $price = $row['price'];
        $promo_price = $row['promotion_price'];
        $manu_date = $row['manufacture_date'];
        $exp_date = $row['expired_date'];


        $catQ = 'SELECT category_id, category_name FROM categories';
        $stmt2 = $con->prepare($catQ);
        $stmt2->execute();
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
            $query = "UPDATE products
                  SET name=:name, description=:description, category_id=:category_id, product_img=:product_img, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date, modified=:modified WHERE product_id = :id";
            // prepare query for excecution
            $stmt = $con->prepare($query);
            // posted values
            $name = htmlspecialchars(strip_tags($_POST['name']));
            $description = htmlspecialchars(strip_tags($_POST['description']));
            $category = $_POST['category'];
            $product_img = basename($_FILES["product_img"]["name"]);
            $price = htmlspecialchars(strip_tags($_POST['price']));
            $promo_price = $_POST['promo_price'];
            $manu_date = $_POST['manu_date'];
            $exp_date = $_POST['exp_date'];


            // bind the parameters
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':category_id', $category);
            $stmt->bindParam(':product_img', $product_img);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':promotion_price', $promo_price);
            $stmt->bindParam(':manufacture_date', $manu_date);
            $stmt->bindParam(':expired_date', $exp_date);
            $modified = date('Y-m-d H:i:s'); // get the current date and time
            $stmt->bindParam(':modified', $modified);
            $stmt->bindParam(':id', $id);
            // Execute the query

            $flag = 0;
            $message = '';

            if (!empty($_FILES['product_img']['name'])) {
                $target_dir = "productimg/";
                unlink($target_dir . $row['product_img']);
                $target_file = $target_dir . basename($_FILES["product_img"]["name"]);
                $isUploadOK = TRUE;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $check = getimagesize($_FILES["product_img"]["tmp_name"]);
                if ($check !== false) {

                    $isUploadOK = TRUE;
                } else {
                    $flag = 1;
                    $message .= "File is not an image.<br>";
                    $isUploadOK = FALSE;
                }


                if ($_FILES["product_img"]["size"] > 5000000) {
                    $flag = 1;
                    $message .= "Sorry, your file is too large.<br>";
                    $isUploadOK = FALSE;
                }
                // Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $flag = 1;
                    $message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
                    $isUploadOK = FALSE;
                }
                // Check if $uploadOk is set to 0 by an error
                if ($isUploadOK == FALSE) {
                    $flag = 1;
                    $message .= "Sorry, your file was not uploaded."; // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["product_img"]["tmp_name"], $target_file)) {
                    } else {
                        $flag = 1;
                        $message .= "Sorry, there was an error uploading your file.<br>";
                    }
                }
            } else {

                $product_img = $row['product_img'];
            }

            if (empty($name)) {
                $flag = 1;
                $message = "Please fill in all the fields.";
                $nameErr = "Name is required";
            } elseif (empty($description)) {
                $flag = 1;
                $message = "Please fill in all the fields.";
                $descriptionErr = "First Name is required";
            } elseif (empty($category)) {
                $flag = 1;
                $message = "Please fill in all the fields.";
                $categoryErr = "Last Name is required";
            } elseif (empty($price)) {
                $flag = 1;
                $message = "Please fill in all the fields.";
                $priceErr = "Last Name is required";
            } elseif (empty($promo_price)) {
                $flag = 1;
                $message = "Please fill in all the fields.";
                $promo_priceErr = "Last Name is required";
            } elseif (empty($manu_date)) {
                $flag = 1;
                $message = "Please fill in all the fields.";
                $manu_dateErr = "Last Name is required";
            }

            if ($promo_price > $price) {
                $flag = 1;
                $message = "Error: Promotion Price cannot bigger than Original Price";
            } elseif ($manu_date > $exp_date) {
                $flag = 1;
                $message = "Error: Expired date must be after Manufacture date";
            }

            if ($flag == 0) {
                if ($stmt->execute()) {
                    echo "<script>location.replace('product_read_one.php?id=" . $id . "')</script>";
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
        // show errors
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
    } ?>



    <!--we have our html form here where new record information can be updated-->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post" enctype="multipart/form-data">
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Image</td>

                <?php
                if ($product_img == '') {
                    echo '<td>No image<br>';
                } else {
                    echo '<td><img src="productimg/' . $product_img . '"width="200px"><br>';
                }
                echo ' <input type="file" name="product_img" id="fileToUpload" /></td>';
                ?>

            </tr>
            <tr>
                <td>Name</td>
                <td><input type='text' name='name' value="<?php echo htmlspecialchars($name, ENT_QUOTES);  ?>" class='form-control' />
                    <span>
                        <?php if (isset($nameErr)) echo "<div class='text-danger'>*$nameErr</div>  "; ?>
                    </span>
                </td>

            </tr>
            <tr>
                <td>Category</td>
                <td>
                    <?php

                    $selected = '';
                    echo '<select class="fs-4 rounded" id="" name="category">';
                    echo  '<option selected></option>';

                    while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {


                        if ($_POST) {
                            $selected = $row['category_id'] == $_POST['category'] ? 'selected' : '';
                        } else {

                            $selected = $row['category_id'] == $product_category_id ? 'selected' : '';
                        }

                        echo "<option value='" . $row['category_id'] . "' " . $selected . ">" . $row['category_name'] . "</option>";
                    }

                    echo "</select>";

                    ?>
                </td>
            </tr>
            <tr>
                <td>Description</td>
                <td><textarea name='description' class='form-control'><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></textarea>
                    <span>
                        <?php if (isset($descriptionErr)) echo "<div class='text-danger'>*$descriptionErr</div>  "; ?>
                    </span>
                </td>

            </tr>

            <tr>
                <td>Price</td>
                <td><input type='text' name='price' value="<?php echo htmlspecialchars($price, ENT_QUOTES);  ?>" class='form-control' />
                    <span>
                        <?php if (isset($priceErr)) echo "<div class='text-danger'>*$priceErr</div>  "; ?>
                    </span>
                </td>

            </tr>
            <tr>
                <td>Promotion Price</td>
                <td><input type='text' name='promo_price' value="<?php echo htmlspecialchars($promo_price, ENT_QUOTES);  ?>" class='form-control' />
                    <span>
                        <?php if (isset($priceErr)) echo "<div class='text-danger'>*$promo_priceErr</div>  "; ?>
                    </span>
                </td>

            </tr>
            <tr>
                <td>Manufacture Date</td>
                <td><input type='date' name='manu_date' value="<?php echo htmlspecialchars($manu_date, ENT_QUOTES);  ?>" class='form-control' />
                    <span>
                        <?php if (isset($priceErr)) echo "<div class='text-danger'>*$manu_dateErr</div>  "; ?>
                    </span>
                </td>

            </tr>
            <tr>
                <td>Expired Date</td>
                <td><input type='date' name='exp_date' value="<?php echo htmlspecialchars($exp_date, ENT_QUOTES);  ?>" class='form-control' /></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type='submit' value='Save Changes' class='btn btn-primary' />
                    <a href='product_read.php' class='btn btn-danger'>Back to Product List</a>
                </td>
            </tr>
        </table>
    </form>

</div>
<!-- end .container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>



</body>

</html>