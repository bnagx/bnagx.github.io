<!--ID : 2020052 -->
<!--Name : WAN HAO XIN   -->
<!--Topic : Eshop Product Create to insert the data in database(PDO Method)-->





<?php
include 'config/session.php';
include 'config/navbar.php';
?>


<!-- container -->
<div class="container">
    <div class="page-header">
        <h1>Create Product</h1>
    </div>

    <!-- html form to create product will be here -->
    <!-- PHP insert code will be here -->
    <?php

    include 'config/database.php';
    $query_category = "SELECT * FROM categories ORDER BY category_id ASC";
    $stmt_category = $con->prepare($query_category);
    $stmt_category->execute();


    if ($_POST) {

        try {
            // insert query
            $query = "INSERT INTO products SET name=:name, description=:description,category_id=:category_id, price=:price, created=:created, promotion_price=:promotion, manufacture_date=:manufacture, expired_date=:expired , product_img=:product_img";
            // prepare query for execution
            $stmt = $con->prepare($query);
            $name = $_POST['name'];
            $description = $_POST['description'];
            $category_id = $_POST['category_id'];
            $price = $_POST['price'];
            $promotion = $_POST['promotion'];
            $manufacture = $_POST['manufacture'];
            $expired = $_POST['expired'];
            $product_img = basename($_FILES["product_img"]["name"]);
            // bind the parameters
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':price', $price);
            $created = date('Y-m-d H:i:s'); // get the current date and time
            $stmt->bindParam(':created', $created);
            $stmt->bindParam(':promotion', $promotion);
            $stmt->bindParam(':manufacture', $manufacture);
            $stmt->bindParam(':expired', $expired);
            $stmt->bindParam(':product_img', $product_img);
            $flag = 0;
            $message = "";

            if (!empty($_FILES['product_img']['name'])) {
                $target_dir = "productimg/" . $row['product_id'];
                $target_file = $target_dir . basename($_FILES["product_img"]["name"]);
                $isUploadOK = TRUE;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $check = getimagesize($_FILES["product_img"]["tmp_name"]);
                if ($check !== false) {
                    // echo "File is an image - " . $check["mime"] . ".";
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
                if (
                    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"
                ) {
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
                        echo "The file " . basename($_FILES["product_img"]["name"]) . " has been uploaded.";
                    } else {
                        $flag = 1;
                        $message .= "Sorry, there was an error uploading your file.<br>";
                    }
                }
            } else {

                $product_img = '';
            }





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

                if (empty($_POST["category_id"])) {
                    $message = "Category cannot be empty";
                    $flag = 1;
                }

                if (empty($_POST["price"])) {
                    $flag = 1;
                    $message = "Please fill in every field.";
                    $priceErr = "price is required";
                } else {
                    $price = ($_POST["price"]);
                }

                if (empty($_POST["promotion"])) {
                    $flag = 1;
                    $message = "Please fill in every field.";
                    $promo_priceErr = "Promotion price is required";
                } else {
                    $promo_price = ($_POST["promotion"]);
                }

                if ($_POST["price"] == $_POST["promotion"]) {
                    $flag = 1;
                    $message = "Price and Promotion must not be the same";
                }

                if (empty($_POST["manufacture"])) {
                    $flag = 1;
                    $message = "Please fill in every field.";
                    $manu_dateErr = "Manufacture date is required";
                } else {
                    $manufacture = ($_POST["manufacture"]);
                }

                if (empty($_POST["expired"])) {
                    $flag = 1;
                    $message = "Please fill in every field.";
                    $exp_dateErr = "Expired date is required";
                } else {
                    $expired = ($_POST["expired"]);
                }
                if (!is_numeric($price) || !is_numeric($promotion)) {
                    $flag = 1;
                    $message = "Please Fill in every field & Price must be numerical.";
                } elseif ($price < 0 || $promotion < 0) {
                    $flag = 1;
                    $message = "Price cannot be negative.";
                }
                if ($promotion > $price) {
                    $flag = 1;
                    $message = "Error: Promotion Price cannot bigger than Original Price";
                } elseif ($manufacture > $expired) {
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
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Name</td>
                <td><input type='text' name='name' class='form-control' value="<?php echo $_POST ? $_POST['name'] : ''; ?>" />
                    <span>
                        <?php if (isset($nameErr)) echo "<div class='text-danger'>*$nameErr</div>  "; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Description</td>
                <td><textarea name='description' class='form-control' value="<?php echo $_POST ? $_POST['description'] : ' '; ?>"></textarea>
                    <span>
                        <?php if (isset($descriptionErr)) echo "<div class='text-danger'>*$descriptionErr</div>  "; ?>
                    </span>
                </td>
            </tr>

            <tr>
                <td>Category</td>
                <td>
                    <select class="form-control form-select fs-6 rounded" name="category_id">
                        <option value="">--Category--</option>
                        <?php
                        while ($row = $stmt_category->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            // $selected_category = $row['category_id'] == $_POST['category_id'] ? 'selected' : '';
                            echo "<option class='bg-white' value='{$category_id}'>$category_name</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Upload Image Here</td>
                <td> <input type="file" name="product_img" id="fileToUpload" />
                </td>
            </tr>


            <tr>
                <td>Price</td>
                <td><input type='text' name='price' class='form-control' value="<?php echo $_POST ? $_POST['price'] : ''; ?>" />
                    <span>
                        <?php if (isset($priceErr)) echo "<div class='text-danger'>*$priceErr</div>  "; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Promotion Price</td>
                <td><input type='text' name='promotion' class='form-control' value="<?php echo $_POST ? $_POST['promotion'] : ''; ?>" />
                    <span>
                        <?php if (isset($promo_priceErr)) echo "<div class='text-danger'>*$promo_priceErr</div>  "; ?>
                    </span>
                </td>
            </tr>




            <tr>
                <td>Manufacture Date</td>
                <td><input type='date' name='manufacture' class='form-control' value="<?php echo $_POST ? $_POST['manufacture'] : ' '; ?>" />
                    <span>
                        <?php if (isset($manu_dateErr)) echo "<div class='text-danger'>*$manu_dateErr</div>  "; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Expired Date</td>
                <td><input type="date" name='expired' class='form-control' value="<?php echo $_POST ? $_POST['expired'] : ' '; ?>" />
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