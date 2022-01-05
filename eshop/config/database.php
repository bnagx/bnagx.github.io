<!--ID : 2020052 -->
<!--Name : WAN HAO XIN   -->
<!--Topic : Eshop Include database -->


<?php
// used to connect to the database
$host = "localhost";
$db_name = "wanhaoxin";
$username = "wanhaoxin";
$password = "QubJpzTCdbfNQ0p8";

try {
    $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // show error
    // echo "Connected successfully";
}
// show error
catch (PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
}
?>