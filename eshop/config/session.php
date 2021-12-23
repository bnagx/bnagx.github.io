<!--ID : 2020052 -->
<!--Name : WAN HAO XIN   -->
<!--Topic : Eshop Session for Login -->




<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location:index.php?msg=loginerr');
}
