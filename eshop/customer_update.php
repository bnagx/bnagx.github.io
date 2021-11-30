<!DOCTYPE HTML>
<html>

<head>
    <title> Customer update </title>
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
            $query = "SELECT username, email, password, confirm_password, first_name, last_name FROM customers WHERE username = ? LIMIT 0,1";
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
            $confirm_password = $row['confirm_password'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
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
                  SET username=:username, email=:email, password=:password,confirm_password=:confirm_password,first_name=:first_name,last_name=:last_name WHERE username = :username";
                // prepare query for excecution
                $stmt = $con->prepare($query);
                // posted values
                $username = htmlspecialchars(strip_tags($_POST['username']));
                $email = htmlspecialchars(strip_tags($_POST['email']));
                $password = htmlspecialchars(strip_tags($_POST['password']));
                $confirm_password = htmlspecialchars(strip_tags($_POST['confirm_password']));
                $first_name = htmlspecialchars(strip_tags($_POST['first_name']));
                $last_name = htmlspecialchars(strip_tags($_POST['last_name']));
                // bind the parameters
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':confirm_password', $confirm_password);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
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
                    <td>username</td>
                    <td><input type='text' name='username' value="<?php echo htmlspecialchars($username, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>email</td>
                    <td><textarea name='email' class='form-control'><?php echo htmlspecialchars($email, ENT_QUOTES);  ?></textarea></td>
                </tr>
                <tr>
                    <td>password</td>
                    <td><input type='text' name='password' value="<?php echo htmlspecialchars($password, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>confirm_password</td>
                    <td><input type='text' name='confirm_password' value="<?php echo htmlspecialchars($confirm_password, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>first_name</td>
                    <td><input type='text' name='first_name' value="<?php echo htmlspecialchars($first_name, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>last_name</td>
                    <td><input type='text' name='last_name' value="<?php echo htmlspecialchars($last_name, ENT_QUOTES);  ?>" class='form-control' /></td>
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
</body>

</html>