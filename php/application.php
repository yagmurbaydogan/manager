<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Extra Styling -->
    <style></style>

    <!-- Others -->
    <title>CPSC 304: Application</title>
</head>

<body>

<!-- Include for other files -->
<?php include 'common/database.php' ?>
<?php include 'common/render.php' ?>

<!-- Include for the navbar -->
<?php include 'navbar.php' ?>

<!-- Reused data within page -->
<?php
$application_df = Database::executePlainSQL("select * from APPLICATION");
?>

<!-- Display how many machines are running each application -->
<br>
<div class="container">
    <h1 class="text-lg-center">Application Info</h1>
    <p class="text-sm-center">Shows how many machines are running the application currently</p>
</div>
<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-10">
            <h3 class='text-md-center'>Hosting Info</h3>
            <br>
            <?php
            $machine_df = Database::executePlainSQL("
                select app_name as \"App Name\", 
                       app_ver as \"App Version\", 
                       Count(machine_ip) as \"Number of Host Machines\"
                FROM Hosting
                GROUP BY app_name, app_ver
                ORDER BY app_name, app_ver"
            );
            Renderer::renderTable($machine_df);
            ?>
        </div>
    </div>
</div>

<!-- Form for getting information about who works on which application -->
<br>
<div class="container">
    <h1 class="text-lg-center">Contact Info</h1>
    <p class="text-sm-center">Find contact of application developer/maintainer</p>
</div>
<br>
<div class="container">
    <form method="GET" action="application.php">
        <div class="row">
            <label for="app" class="col-sm-2 col-form-label">Application</label>
            <div class="col">
                <select class="form-select" id="app" name="app">
                    <?php
                    foreach ($application_df->rows as $app) {
                        echo "<option value='$app[0],$app[1]'>$app[1] (Version $app[0])</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-auto">
                <button type="submit" class="btn btn-primary" name="queryContactSubmit">Submit</button>
            </div>
        </div>
    </form>
    <br>
    <div class="row justify-content-center">
        <div class="col-sm-8">
            <?php
            function handleQueryContact($ver, $name) {
                $bindings = array(
                    ":bind1" => $ver,
                    ":bind2" => $name
                );

                $contact_df = Database::executeBoundSQL("
                    select first_name, last_name, alias, email 
                    from workon w, staff s, alias a
                    where w.app_ver = :bind1
                      and w.app_name = :bind2
                      and w.employee_id = s.employee_id
                      and s.staff_alias = a.alias"
                    , $bindings
                );

                echo "<div class='container'>";
                echo "<h3 class='text-md-center'>Contacts for $name (Version $ver)</h3>";
                echo "<br>";
                Renderer::renderTable($contact_df);
                echo "</div>";
            }

            if (isset($_GET['queryContactSubmit'])) {
                $args = explode(',', $_GET['app']);
                handleQueryContact($args[0], $args[1]);
            }
            ?>
        </div>
    </div>
</div>

<!-- Javascript includes -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

</body>
</html>
