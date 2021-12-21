<!--ID : 2020052 -->
<!--Name : WAN HAO XIN   -->
<!--Topic : Eshop Home page-->






<?php
include 'config/navbar.php';
?>



<div>
    <h1>Welcome to E shop</h1>
</div>

<?php

$action = isset($_GET['action']) ? $_GET['action'] : "";

// if it was redirected from delete.php
if ($action == 'deleted') {
    echo "<div class='alert alert-success'>Record was deleted.</div>";
}






?>





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>