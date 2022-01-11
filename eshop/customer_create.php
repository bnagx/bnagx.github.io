<!--ID : 2020052 -->
<!--Name : WAN HAO XIN   -->
<!--Topic : Eshop Customer Create to insert the data in database(PDO Method)-->

<?php

session_start();
if (isset($_SESSION["username"])) {
    include 'config/navbar.php';
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Create Customer </title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>



    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Customer</h1>
        </div>



        <?php
        if ($_POST) {
            // include database connection
            include 'config/database.php';
            try {
                // insert query
                $query = "INSERT INTO customers SET username=:username,email=:email, password=:password, confirm_password=:confirm_password, first_name=:first_name, last_name=:last_name, gender=:gender, dateofbirth=:dateofbirth,customer_img=:customer_img";
                // prepare query for execution
                $stmt = $con->prepare($query);
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = md5($_POST['password']);
                $confirm_password = md5($_POST['confirm_password']);
                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                //$gender = $_POST['gender'];
                $dateofbirth = $_POST['dateofbirth'];
                $customer_img = basename($_FILES["customer_img"]["name"]);
                // bind the parameters
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':confirm_password', $confirm_password);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':gender', $_POST['gender']);
                $stmt->bindParam(':dateofbirth', $dateofbirth);
                $stmt->bindParam(':customer_img', $customer_img);
                // Execute the query

                $flag = 0;
                $message = "";
                $cur_date = date('Y');
                $cust_age = ((int)$cur_date - (int)$dateofbirth);

                if (!empty($_FILES['customer_img']['name'])) {
                    $target_dir = "cus_img/";
                    $target_file = $target_dir . basename($_FILES["customer_img"]["name"]);
                    $isUploadOK = TRUE;
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                    $check = getimagesize($_FILES["customer_img"]["tmp_name"]);
                    if ($check !== false) {
                        echo "File is an image - " . $check["mime"] . ".";
                        $isUploadOK = TRUE;
                    } else {
                        $flag = 1;
                        $message .= "File is not an image.<br>";
                        $isUploadOK = FALSE;
                    }


                    if ($_FILES["customer_img"]["size"] > 5000000) {
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
                        if (move_uploaded_file($_FILES["customer_img"]["tmp_name"], $target_file)) {
                            echo "The file " . basename($_FILES["customer_img"]["name"]) . " has been uploaded.";
                        } else {
                            $flag = 1;
                            $message .= "Sorry, there was an error uploading your file.<br>";
                        }
                    }
                } else {

                    $customer_img = 'noimg.png';
                }









                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    if (empty($_POST["username"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $usernameErr = "Name is required";
                    }

                    if (empty($_POST["email"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $emailErr = "Email is required";
                    }

                    if (empty($_POST["password"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $passwordErr = "Password is required";
                    }

                    if (empty($_POST["confirm_password"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $confirm_passwordErr = "Confirm Password is required";
                    }

                    if (empty($_POST["first_name"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $first_nameErr = "First Name is required";
                    }

                    if (empty($_POST["last_name"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $last_nameErr = "Last Name is required";
                    }

                    if (empty($_POST["gender"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $genderErr = "Gender is required";
                    }

                    if (empty($_POST["dateofbirth"])) {
                        $flag = 1;
                        $message = "Please fill in every field.";
                        $date_of_birthErr = "Date of Birth is required";
                    }
                }

                if (!preg_match("/[a-zA-Z]/", $_POST['password']) || !preg_match("/[0-9]/", $_POST['password']) || !preg_match("/[a-zA-Z0-9]{8,}/", $_POST['password'])) {
                    $flag = 1;
                    $message = "Password must at least 8 character and must contain number and alphabets.";
                } elseif ($password !== $confirm_password) {
                    $flag = 1;
                    $message = "Please make sure Password and Confirm Password are same.";
                } elseif ($cust_age < 18) {
                    $flag = 1;
                    $message = "Customer must be age of 18 or above.";
                } elseif (!preg_match("/[a-zA-Z0-9]{6,}/", $username)) {
                    $flag = 1;
                    $message = "Username must be at least 6 characters";
                }

                if ($flag == 0) {
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                        //header('Location:index.php?msg=createsuccess');
                    } else {
                        echo "Unable to save record.";
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

        <!-- html form here where the customer information will be entered -->
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered'>

                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username' class='form-control' value="<?php echo $_POST ? $_POST['username'] : ''; ?>" />
                        <span>
                            <?php if (isset($usernameErr)) echo "<div class='text-danger'>*$usernameErr</div>  "; ?>
                        </span>
                    </td>
                </tr>


                <tr>
                    <td>Email</td>
                    <td><input type='email' name='email' class='form-control' value=" <?php echo $_POST ? $_POST['email'] : ''; ?>" />
                        <span>
                            <?php if (isset($emailErr)) echo "<div class='text-danger'>*$emailErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" name='password' class='form-control' />
                        <span>
                            <?php if (isset($passwordErr)) echo "<div class='text-danger'>*$passwordErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td><input type="password" name='confirm_password' class='form-control' />
                        <span>
                            <?php if (isset($confirm_passwordErr)) echo "<div class='text-danger'>*$confirm_passwordErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type="text" name='first_name' class='form-control' value="<?php echo $_POST ? $_POST['first_name'] : ''; ?>" />
                        <span>
                            <?php if (isset($first_nameErr)) echo "<div class='text-danger'>*$first_nameErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type=" text" name='last_name' class='form-control' value="<?php echo $_POST ? $_POST['last_name'] : ''; ?>" />
                        <span>
                            <?php if (isset($last_nameErr)) echo "<div class='text-danger'>*$last_nameErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="male" name='gender' value="Male" class="form-check-input" <?php if ($_POST) {
                                                                                                                    echo $_POST['gender'] == 'Male' ? 'checked' : '';
                                                                                                                } ?>>
                            <label class="form-check-label" for="male">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="female" name='gender' value="Female" class="form-check-input" <?php if ($_POST) {
                                                                                                                        echo $_POST['gender'] == 'Female' ? 'checked' : '';
                                                                                                                    } ?>>
                            <label class="form-check-label" for="female">Female</label>
                        </div>
                        <span>
                            <?php if (isset($genderErr)) echo "<div class='text-danger'>*$genderErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Date of birth</td>
                    <td><input type='date' name='dateofbirth' class='form-control' value="<?php echo $_POST ? $_POST['dateofbirth'] : ' '; ?>" />
                        <span>
                            <?php if (isset($date_of_birthErr)) echo "<div class='text-danger'>*$date_of_birthErr</div>  "; ?>
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>Select image (optional):</td>
                    <td> <input type="file" name="cus_img" id="fileToUpload">
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <?php
                        if (isset($_SESSION['username'])) {
                            echo "<a href='product_read.php' class='btn btn-danger'>Back to read customers</a>";
                        } else {
                            echo "<a href='index.php' class='btn btn-danger'>Back to  login page</a>";
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </form>


    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>













</html>