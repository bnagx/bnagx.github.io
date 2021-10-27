<!--ID: 2020052-BSE-->
<!--Name: WAN HAO XIN-->
<!--Topic:Week2 Homework2(generate select menu using php with Array) -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous" />
    <title>Week2 Homework2(generate select menu using php with Array)</title>
</head>

<body>


    <div class="text-center">
        <h1>What is your birthday?</h1>

        <!-- DAY start here -->
        <?php
        echo "<select class='bg-primary fs-2 rounded' id='day' name='day'>";
        echo "<option class='bg-white' selected>Day</option>";

        for ($day = 1; $day <= 31; $day++) {
            echo "<option class='bg-white' value='$day'> $day </option>";
        }
        echo '</select>';
        ?>

        <!-- MONTH start here -->
        <?php
        $month = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

        echo "<select class='bg-warning fs-2 rounded text-center' id='month' name='month'>";
        echo "<option class='bg-white' selected>Month</option>";

        // for ($montharray = 0; $montharray <= 11; $montharray++) {
        //     echo "<option class='bg-white' value='" . ($montharray + 1) . "'> $month[$montharray] </option>";
        // }

        foreach ($month as $index => $monthvalue) {
            echo "<option class='bg-white' value='" . ($index + 1) . "'> $monthvalue </option>";
        }
        echo '</select>';
        ?>

        <!-- YEAR start here -->
        <?php
        $staring_year  = 1900;
        $current_year = date('Y');
        echo "<select class='bg-danger fs-2 rounded' id='year' name='year'>";
        echo "<option class='bg-white' selected>Year</option>";

        for ($year = $staring_year; $year <= $current_year; $year++) {
            echo "<option class='bg-white' value='$year'> $year </option>";
        }
        echo '</select>';
        ?>





    </div>






    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>


</body>








</html>