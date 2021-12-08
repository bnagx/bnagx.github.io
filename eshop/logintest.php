<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read One Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

</head>

<body>

    <!-- container -->
    <div class="container vh-100">

        <?php

        if ($_POST) {

            // include database connection
            include 'config/database.php';

            $q = 'SELECT username, password, accountstatus from customers';
            $stmt = $con->prepare($q);
            $stmt->execute();

            $flag = 0;
            $message = '';

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                if ($_POST['username'] !== $row['username']) {
                    $flag = 1;
                    $message = 'Your username not valid!';
                } elseif (md5($_POST['password']) == $row['password']) {

                    if ($row['account_status'] == 'Active') {
                        header("Location:home.php");
                    } else {
                        $flag = 1;
                        $message = 'Please tell admin to activate your account.';
                    }
                } else {
                    $flag = 1;
                    $message = 'Your username or password is incorrect';
                }

                /* if ($_POST['username'] !== $row['username']) {
          $flag = 1;
          $message = 'Username is not exists.';
        } */
            }


            if (empty($_POST['username'])) {
                $flag = 1;
                $message = "Please insert your username.";
            }

            if (empty($_POST['password'])) {
                $flag = 1;
                $message = "Please insert your password.";
            }

            if ($flag == 1) {
                echo "<div class='alert alert-danger'>$message</div>";
            }
        }
        ?>
        <div class="wrapper">

        </div>

        <div class="text-center  d-flex align-items-center h-100">

            <div class="container w-50 w-md-25">
                <h2>Login</h2>

                <form>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Check me out</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>





            </div>

        </div>


    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>