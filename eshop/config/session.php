<!--ID : 2020052_BSE -->
<!--Name : WAN HAO XIN   -->
<!--Topic : Eshop Session for Login -->




<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location:index.php?msg=loginerr');
}
