<!--ID: 2020052-BSE-->
<!--Name: WAN HAO XIN-->
<!--Topic:Week2 Homework1(generate select menu using php) -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous" />
    <title>Week2 Homework1(generate select menu using php)</title>
</head>

<body>

    <div class="text-center">
        <h1>What is your birthday?</h1>

        <!-- DAY start here -->
        <?php
        echo "<select class='bg-primary fs-2 rounded text-center' id='day' name='day'>";
        echo "<option class='bg-white' selected>Day</option>";
        for ($day = 1; $day <= 31; $day++) {
            echo "<option class='bg-white' value='$day'> $day </option>";
        }
        echo '</select>';
        ?>

        <!-- MONTH start here -->
        <?php
        echo "<select class='bg-warning fs-2 rounded text-center' id='month' name='month'>";
        echo "<option class='bg-white' selected>Month</option>";

        for ($month = 1; $month <= 12; $month++) {
            echo "<option class='bg-white text-center' value='$month'>  $month  </option>";
        }
        echo '</select>';
        ?>

        <!-- YEAR start here -->
        <?php
        $starting_year  = 1900;
        $current_year = date('Y');
        echo "<select class='bg-danger fs-2 rounded' id='year' name='year'>";
        echo "<option class='bg-white' selected>Year</option>";

        for ($year = $starting_year; $year <= $current_year; $year++) {
            echo "<option class='bg-white' value='$year'>  $year  </option>";
        }
        echo '</select>';
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>



</body>

</html>