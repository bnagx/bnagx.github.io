<!--ID : 2020052_BSE -->
<!--Name : WAN HAO XIN   -->
<!--Topic : Eshop Home page-->






<?php
include 'config/session.php';
include 'config/navbar.php';
include 'config/database.php';
?>

<head>
    <title>Home</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>



<div class="container">
    <?php

    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $query = 'SELECT first_name, last_name, gender from customers WHERE email= ?';
    } else {
        $query = 'SELECT first_name, last_name, gender FROM customers WHERE username=?';
    }

    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $_SESSION['username']);
    $stmt->execute();
    $numCustomer = $stmt->rowCount();
    if ($numCustomer > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $gender = $row['gender'];

        $loginperson = '';

        if ($gender == 'Male') {
            $loginperson = "Mr. $first_name $last_name";
        } else {
            $loginperson = "Ms. $first_name $last_name";
        }
    }

    $qOrder = 'SELECT * FROM order_summary';
    $stmtOrder = $con->prepare($qOrder);
    $stmtOrder->execute();
    $totalOrder = $stmtOrder->rowCount();

    $qCustomer = 'SELECT * FROM customers';
    $stmtOrder = $con->prepare($qCustomer);
    $stmtOrder->execute();
    $totalCustomer = $stmtOrder->rowCount();

    $qLastOrder = 'SELECT * FROM order_summary ORDER BY order_id DESC LIMIT 1';
    $stmtLastOrder = $con->prepare($qLastOrder);
    $stmtLastOrder->execute();
    $lastOrder = $stmtLastOrder->rowCount();

    ?>

    <div class="page-header">
        <h1>Home</h1>
        <h2>Welcome! <?php echo $loginperson; ?></h2>
    </div>

    <div>

        <table class="table table-dark table-striped table-responsive">
            <tr>
                <td>Total Order:</td>
                <td><?php echo $totalOrder ?></td>
            </tr>
            <tr>
                <td>Total Customer:</td>
                <td><?php echo $totalCustomer ?></td>
            </tr>
        </table>


    </div>

    <h3>Last Order</h3>
    <?php
    if ($lastOrder > 0) {
        while ($rowLast = $stmtLastOrder->fetch(PDO::FETCH_ASSOC)) {
            extract($rowLast);
            $order_id = $rowLast['order_id'];
            $ordercreate = $rowLast['order_date'];


            echo "<table class='table table-dark table-striped '>";
            echo "<tr>";
            echo "<th>Order ID</th><td>" . $order_id . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>Order Create Date</th><td>" . $ordercreate . "</td>";
            echo "</tr>";
            echo "</table>";
        }
    }
    ?>


</div>








<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>