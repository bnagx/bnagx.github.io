<!--ID : 2020052 -->
<!--Name : WAN HAO XIN   -->
<!--Topic : Eshop Customer Read -->





<?php
include 'config/session.php';
include 'config/navbar.php';

?>


<head>
    <title>Customer List</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>



<!-- container -->
<div class="container">
    <div class="page-header">
        <h1>Customer List</h1>
    </div>

    <?php
    // include database connection
    include 'config/database.php';

    // delete message prompt will be here
    $action = isset($_GET['action']) ? $_GET['action'] : "";

    // if it was redirected from delete.php
    if ($action == 'deleted') {
        echo "<div class='alert alert-success'>Customer was deleted.</div>";
    } else if ($action == 'deny') {
        echo "<div class='alert alert-danger'>Customer is cannot delete when order is still exist</div>";
    }



    // select all data
    $query = "SELECT username,email,first_name,last_name,gender,dateofbirth,registration,accountstatus FROM customers ORDER BY first_name DESC";
    $stmt = $con->prepare($query);
    $stmt->execute();

    // this is how to get number of rows returned
    $num = $stmt->rowCount();

    // link to create record form
    echo "<a href='customer_create.php' class='btn btn-primary m-b-1em'>Create New Customer</a>";

    //check if more than 0 record found
    if ($num > 0) {

        echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

        //creating our table heading
        echo "<tr>";
        echo "<th>Username</th>";
        echo "<th>Email</th>";
        echo "<th>First Name</th>";
        echo "<th>Last Name</th>";
        echo "<th>Gender</th>";
        echo "<th>Action</th>";
        echo "</tr>";

        // retrieve our table contents
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            // this will make $row['firstname'] to just $firstname only
            extract($row);
            // creating new table row per record
            echo "<tr>";
            echo "<td>{$username}</td>";
            echo "<td>{$email}</td>";
            echo "<td>{$first_name}</td>";
            echo "<td>{$last_name}</td>";
            echo "<td>{$gender}</td>";
            echo "<td>";
            // read one record
            echo "<a href='customer_read_one.php?id={$username}' class='btn btn-info m-r-1em'>Read</a>";

            // we will use this links on next part of this post
            echo "<a href='customer_update.php?id={$username}' class='btn btn-primary m-r-1em'>Edit</a>";

            // we will use this links on next part of this post
            echo "<a href='customer_delete.php' onclick='delete_customer(\"{$username}\");'  class='btn btn-danger'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }


        // end table
        echo "</table>";
    } else {
        echo "<div class='alert alert-danger'>No records found.</div>";
    }
    ?>


</div> <!-- end .container -->


<!-- confirm delete record will be here -->
<script type='text/javascript'>
    function delete_customer(username) {
        if (confirm('Are you sure to delete?')) {
            window.location = 'customer_delete.php?id=' + username;
        }
    }
</script>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>





</body>

</html>