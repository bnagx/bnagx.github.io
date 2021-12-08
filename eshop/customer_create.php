<!DOCTYPE HTML>
<html>

<head>
    <title>Create Customers</title>
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
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Products
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item text-primary" href="product_create.php">Create Product</a></li>
                        <li><a class="dropdown-item text-primary" href="product_read.php">Read Product</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Customers
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item text-primary" href="customer_create.php">Create Customers</a></li>
                        <li><a class="dropdown-item text-primary" href="customer_read.php">Read Customers</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Orders
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item text-primary" href="order_create.php">Create Order</a></li>
                        <li><a class="dropdown-item text-primary" href="order_read.php">Read Orders</a></li>
                    </ul>
                </li>
                <li><a href="contactus.php">Contact Us</a></li>

            </ul>
        </div>
    </nav>



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
                $query = "INSERT INTO customers SET username=:username,email=:email, password=:password, confirm_password=:confirm_password, first_name=:first_name, last_name=:last_name, gender=:gender, dateofbirth=:dateofbirth";
                // prepare query for execution
                $stmt = $con->prepare($query);
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = ($_POST['password']);
                $confirm_password = $_POST['confirm_password'];
                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $gender = $_POST['gender'];
                $dateofbirth = $_POST['dateofbirth'];
                // bind the parameters
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':confirm_password', $confirm_password);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':dateofbirth', $dateofbirth);
                // Execute the query

                $flag = 0;
                $message = "";
                $cur_date = date('Y');
                $cust_age = ((int)$cur_date - (int)$dateofbirth);

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
                } elseif (!preg_match("/[a-zA-Z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/[a-zA-Z0-9]{8,}/", $password)) {
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
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username' class='form-control' />
                        <span>
                            <?php if (isset($usernameErr)) echo "<div class='text-danger'>*$usernameErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type='email' name='email' class='form-control' />
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
                    <td><input type="text" name='first_name' class='form-control' />
                        <span>
                            <?php if (isset($first_nameErr)) echo "<div class='text-danger'>*$first_nameErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type="text" name='last_name' class='form-control' />
                        <span>
                            <?php if (isset($last_nameErr)) echo "<div class='text-danger'>*$last_nameErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="male" name='gender' value="Male" class="form-check-input">
                            <label class="form-check-label" for="male">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="female" name='gender' value="Female" class="form-check-input">
                            <label class="form-check-label" for="female">Female</label>
                        </div>
                        <span>
                            <?php if (isset($genderErr)) echo "<div class='text-danger'>*$genderErr</div>  "; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Date of birth</td>
                    <td><input type='date' name='dateofbirth' class='form-control' />
                        <span>
                            <?php if (isset($date_of_birthErr)) echo "<div class='text-danger'>*$date_of_birthErr</div>  "; ?>
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