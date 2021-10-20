<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous" />
</head>

<body>


    <div class="text-center">
        <h1>What is your birthday?</h1>

        <!-- DAY start here -->
        <?php
        echo '<select class="bg-primary fs-2 rounded" id="day" name="day">' . "\n";
        echo "<option class='bg-white' selected>Day</option>" . "\n";

        for ($day = 1; $day <= 31; $day++) {
            echo "<option class='bg-white'>" . $day . "</option>" . "\n";
        }
        echo '</select>' . "\n";
        ?>

        <!-- MONTH start here -->
        <?php
        $month = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

        echo '<select class="bg-warning fs-2 rounded text-center" id="month" name="month">' . "\n";
        echo "<option class='bg-white' selected>Month</option>" . "\n";

        for ($montharray = 0; $montharray < 11; $montharray++) {
            echo "<option class='bg-white'>" . $month[$montharray] . "</option>" . "\n";
        }
        echo '</select>' . "\n";
        ?>

        <!-- YEAR start here -->
        <?php
        $staring_year  = 1900;
        $current_year = date('Y');
        echo '<select class="bg-danger fs-2 rounded" id="year" name="year">' . "\n";
        echo "<option class='bg-white' selected>Year</option>" . "\n";

        for ($year = $staring_year; $year <= $current_year; $year++) {
            echo "<option class='bg-white'>" . $year . "</option>" . "\n";
        }
        echo '</select>' . "\n";
        ?>
    </div>






    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>


</body>








</html>