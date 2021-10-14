<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous" />
</head>


<body>
    <?php

    $num1 = rand();
    $num2 = rand();

    $bigger = ($num1 > $num2) ? $num1 : $num2;
    $smaller = ($num1 < $num2) ? $num1 : $num2;

    echo "<div class='fw-bold'>" . $bigger . "</div>";


    echo $smaller;


    ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>



</body>

</html>