<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Extra Styling -->
    <style>
    </style>

    <!-- Others -->
    <title>CPSC 304: Index</title>
</head>

<body>

<!-- Library includes -->
<?php include 'common/database.php' ?>
<?php include 'common/render.php' ?>

<!-- Include for the navbar -->
<?php include 'navbar.php' ?>

<br>

<!-- Description block -->
<div class="container">
    <h1 class="text-lg-center">Reset</h1>
    <p class="text-sm-center">Debug controls to help reset databases</p>
</div>

<br>

<!-- Buttons for controlling database -->
<div class="container">
    <div class="row">
        <div class="col-sm-6 text-center">
            <form method="POST" action="index.php">
                <p class="text-sm-center">Reset tables to default state</p>
                <button type="submit" class="btn btn-primary" name="resetTablesRequest">Submit</button>
            </form>
        </div>
        <div class="col-sm-6 text-center">
            <form method="POST" action="index.php">
                <p class="text-sm-center">Drop all tables used</p>
                <button type="submit" class="btn btn-primary" name="dropTablesRequest">Submit</button>
            </form>
        </div>
    </div>
</div>

<!-- Handlers for database modification -->
<?php
function handleDropTables() {
    Database::executeFile('sql/drop.sql', false);
}

function handleResetTables() {
    Database::executeFile('sql/define.sql');
    Database::executeFile('sql/populate.sql');
}

if (isset($_POST['resetTablesRequest']) || isset($_POST['dropTablesRequest'])) {
    handleDropTables();
}

if (isset($_POST['resetTablesRequest'])) {
    handleResetTables();
}
?>

<!-- Form for all tables within database -->
<br>
<div class="container">
    <h1 class="text-lg-center">Database Tables</h1>
    <p class="text-sm-center">Use form to query database tables</p>
</div>
<br>
<div class="container">
    <form method="GET" action="index.php">
        <div class="form-group row">
            <label for="tableName" class="col-sm-2 col-form-label">Table Name</label>
            <div class="col-sm-2">
                <select id="tableName" name="tableName" class="form-control">
                    <?php
                    $df = Database::executePlainSQL("select table_name from user_tables");
                    foreach ($df->rows as $tn) {
                        echo "<option>". $tn[0] . "</option>";
                    }
                    ?>
                </select>
                <small id="tableNameHelp" class="form-text text-muted">Select table to query</small>
            </div>
            <label for="tableFields" class="col-sm-auto col-form-label">Table Fields</label>
            <div class="col">
                <input type="text" class="form-control" id="tableFields" name="tableFields" value="*">
                <small id="tableFieldsHelp" class="form-text text-muted">Select fields of table to display (* for all)</small>
            </div>
        </div>
        <br>
        <div class="form-group row">
            <label for="queryConditions" class="col-sm-2 col-form-label">Query Conditions</label>
            <div class="col">
                <input type="text" class="form-control" id="queryConditions" name="queryConditions">
                <small id="queryConditionsHelp" class="form-text text-muted">Input filter conditions for entries</small>
            </div>
            <div class="col-sm-auto">
                <button type="submit" class="btn btn-primary" name="queryTableRequest">Submit</button>
            </div>
        </div>
    </form>
</div>
<br>

<!-- Display of query results -->
<?php
function handleQueryTable($table, $fields, $conditions) {
    $query = "select $fields from $table";
    if (trim($conditions) != '') {
        $query .= " where " . $conditions;
    }
    $df = Database::executePlainSQL($query);

    echo "<div class=\"container\">";
    echo "<h1 class=\"text-lg-center\">$table</h1>";
    echo "<br>";
    Renderer::renderTable($df);
    echo "</div>";
}

if (isset($_GET['queryTableRequest'])) {
    handleQueryTable($_GET['tableName'], $_GET['tableFields'], $_GET['queryConditions']);
}
?>

<!-- Javascript includes -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>
