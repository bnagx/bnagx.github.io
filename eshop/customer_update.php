<!--ID : 2020052 -->
<!--Name : WAN HAO XIN   -->
<!--Topic : Eshop Customer Update(PDO Method)-->


<?php
include 'config/navbar.php';
include 'config/session.php';

?>



<div class="container">
    <div class="page-header">
        <h1>Update Customers</h1>
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
        $query = "SELECT * FROM customers WHERE username = ? LIMIT 0,1";
        $stmt = $con->prepare($query);

        // this is the first question mark
        $stmt->bindParam(1, $id);

        // execute our query
        $stmt->execute();

        // store retrieved row to a variable
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // values to fill up our form
        $username = $row['username'];
        $email = $row['email'];
        $password = $row['password'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $gender = $row['gender'];
        $dateofbirth = $row['dateofbirth'];
        $accountstatus = $row['accountstatus'];
        $customer_img = $row['customer_img'];
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
            $query = "UPDATE customers
                  SET username=:username, email=:email,password=:new_password,first_name=:first_name,last_name=:last_name, gender=:gender,dateofbirth=:dateofbirth, customer_img=:customer_img, accountstatus=:accountstatus  WHERE username = :username";
            // prepare query for excecution
            $stmt = $con->prepare($query);
            // posted values
            $username = ($_POST['username']);
            $email = ($_POST['email']);
            $first_name = ($_POST['first_name']);
            $last_name = ($_POST['last_name']);
            $gender = ($_POST['gender']);
            $old_password = ($_POST['old_password']);
            $new_password = ($_POST['new_password']);
            $confirm_password = ($_POST['confirm_password']);
            $dateofbirth = ($_POST['dateofbirth']);
            $accountstatus = ($_POST['accountstatus']);
            $customer_img = basename($_FILES["cimg"]["name"]);

            // bind the parameters
            $stmt->bindParam(':username', $id);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':new_password', $new_password);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':dateofbirth', $dateofbirth);
            $stmt->bindParam(':accountstatus', $accountstatus);
            $stmt->bindParam(':customer_img', $customer_img);
            // Execute the query

            $flag = 0;
            $message = ' ';

            if (!empty($_FILES['cimg']['name'])) {
                $target_dir = "uploads/";
                unlink($target_dir . $row['cimg']);
                $target_file = $target_dir . basename($_FILES["cimg"]["name"]);
                $isUploadOK = TRUE;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $check = getimagesize($_FILES["cimg"]["tmp_name"]);
                if (
                    $check !== false
                ) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $isUploadOK = TRUE;
                } else {
                    $flag = 1;
                    $message .= "File is not an image.<br>";
                    $isUploadOK = FALSE;
                }


                if ($_FILES["cimg"]["size"] > 5000000) {
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
                    if (move_uploaded_file($_FILES["cimg"]["tmp_name"], $target_file)) {
                        echo "The file " . basename($_FILES["cimg"]["name"]) . " has been uploaded.";
                    } else {
                        $flag = 1;
                        $message .= "Sorry, there was an error uploading your file.<br>";
                    }
                }
            } else {

                $customer_img = $row['customer_img'];
            }








            if (
                empty($old_password) && empty($new_password) && empty($confirm_password)
            ) {
                $flag = 0;
                $unchange_new_password = ($row['password']);
                $unchange_confirm_new_password = ($row['password']);
                $stmt->bindParam(':new_password', $unchange_new_password);
                $stmt->bindParam(':comfirm_password', $unchange_confirm_new_password);
            }

            if (empty($email)) {
                $flag = 1;
                $message = " Please fill in all the field";
                $emailErr = "Email is required";
            } else if (empty($first_name)) {
                $flag = 1;
                $message = " Please fill in all the field";
                $first_nameErr = "First Name is required";
            } else if (empty($last_name)) {
                $flag = 1;
                $message = " Please fill in all the field";
                $last_nameErr = "Last Name is required";
            }



            // if (!empty($password) || !empty($new_password) || !empty($confirm_password)) {
            //     if ($password != $row['password']) {
            //         $flag = 1;
            //         $message = " Your old password is not correct";
            //     } else if ($password == $new_password) {
            //         $flag = 1;
            //         $message = "Old password must not be the same with new password.";
            //     } else if ($new_password !== $confirm_password) {
            //         $flag = 1;
            //         $message = "Please Make sure the new password and confirm password are the same.";
            //     } else if (empty($password)) {
            //         $flag = 1;
            //         $message = "Please input original/old password.";
            //     } else if (empty($new_password)) {
            //         $flag = 1;
            //         $message = "Please input a new password.";
            //     } else if (empty($confirm_password)) {
            //         $flag = 1;
            //         $message = "Please make sure password is confirmed/ same as your new password.";
            //     }
            // }


            if (!empty($old_password) || !empty($new_password) || !empty($confirm_password)) {

                if (empty($old_password)) {
                    $flag = 1;
                    $message = "Please fill in Old password";
                } elseif (empty($new_password)) {
                    $flag = 1;
                    $message = "Please full in New password";
                } elseif (empty($confirm_password)) {
                    $flag = 1;
                    $message = "Please confirm your new password";
                } elseif ($old_password !== $password) {
                    $flag = 1;
                    $message = 'Your Old Password is Incorrect!';
                } elseif ($old_password == $new_password) {
                    $flag = 1;
                    $message = 'Old and new password cannot be the same';
                } elseif (!preg_match("/[a-zA-Z]/", $new_password) || !preg_match("/[0-9]/", $new_password) || !preg_match("/[a-zA-Z0-9]{8,}/", $new_password)) {
                    $flag = 1;
                    $message = "Password must at least 8 character and must contain number and alphabets.";
                } elseif ($new_password !== $confirm_password) {
                    $flag = 1;
                    $message = "New password and Confirm Password is NOT match.";
                }
            }




            if ($flag == 0) {
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";

                    //echo $password;
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
    <form action="<?php echo ($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post" enctype="multipart/form-data">
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Image</td>

                <?php
                if ($customer_img == '') {
                    echo '<td>No image<br>';
                } else {
                    echo '<td><img src="uploads/' . $customer_img . '" width="200px"><br>';
                }
                echo ' <input type="file" name="cimg" id="fileToUpload" /></td>';
                ?>

            </tr>
            <tr>
                <td>Username</td>
                <td><input type='text' name='username' value="<?php echo htmlspecialchars($username, ENT_QUOTES);  ?>" class='form-control' readonly /></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type='text' name='email' value="<?php echo htmlspecialchars($email, ENT_QUOTES);  ?>" class='form-control' />
                    <span>
                        <?php if (isset($emailErr)) echo "<div class='text-danger'>*$emailErr</div>  "; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>First Name</td>
                <td><input type='text' name='first_name' value="<?php echo htmlspecialchars($first_name, ENT_QUOTES);  ?>" class='form-control' />
                    <span>
                        <?php if (isset($first_nameErr)) echo "<div class='text-danger'>*$first_nameErr</div>  "; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td><input type='text' name='last_name' value="<?php echo htmlspecialchars($last_name, ENT_QUOTES);  ?>" class='form-control' />
                    <span>
                        <?php if (isset($last_nameErr)) echo "<div class='text-danger'>*$last_nameErr</div>  "; ?>
                    </span>
                </td>
            </tr>
            <td>Gender</td>

            <td>
                <div class="form-check form-check-inline">
                    <input type="radio" id="male" name='gender' value="Male" class="form-check-input unchecked" <?php if ($gender == "Male") echo 'checked' ?>> <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="female" name='gender' value="Female" class="form-check-input" <?php if ($gender == "Female") echo 'checked'  ?>>
                    <label class="form-check-label" for="female">Female</label>
                </div>
            </td>
            <tr>
                <td>Account Status</td>
                <td>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="Active" name='accountstatus' value="Active" class="form-check-input" <?php if ($accountstatus == "Active") echo 'checked' ?>> <label class="form-check-label" for="Active">Active</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="Inactive" name='accountstatus' value="Inactive" class="form-check-input" <?php if ($accountstatus == "Inactive") echo 'checked'  ?>>
                        <label class="form-check-label" for="Inavtive">Inavtive</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td><input type='date' name='dateofbirth' value="<?php echo htmlspecialchars($dateofbirth, ENT_QUOTES);  ?>" class='form-control' />
                </td>
            </tr>
            <tr>
                <td>Old Password</td>
                <td><input type='password' name='old_password' class='form-control' /></td>
            </tr>
            <tr>
                <td>New Password</td>
                <td><input type='password' name='new_password' class='form-control' /></td>
            </tr>
            <tr>
                <td>Confirm New Password</td>
                <td><input type='password' name='confirm_password' class='form-control' /></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type='submit' value='Save Changes' class='btn btn-primary' />
                    <a href='customer_read.php' class='btn btn-danger'>Back to read products</a>
                </td>
            </tr>
        </table>
    </form>

</div>
<!-- end .container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>



</body>

</html>