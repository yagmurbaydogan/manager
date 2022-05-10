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
    <title>CPSC 304: Staff</title>
</head>

<body>

<!-- Include for other files -->
<?php include 'common/database.php' ?>
<?php include 'common/render.php' ?>

<!-- Include for the navbar -->
<?php include 'navbar.php' ?>

<!-- Reused data within page -->
<?php
$staff_df = Database::executePlainSQL("select * from Staff");
?>

<!-- Description block -->
<br>
<div class="container">
    <h1 class="text-lg-center">Staff</h1>
    <p class="text-sm-center">Show all staff present in system</p>
</div>

<!-- Tabs for modifying Staff table -->
<br>
<div class="container">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#insert" type="button" role="tab" aria-controls="insert" aria-selected="true">Insert</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#update" type="button" role="tab" aria-controls="update" aria-selected="false">Update</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#delete" type="button" role="tab" aria-controls="delete" aria-selected="false">Delete</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <!-- Insert tab -->
        <div class="tab-pane fade show active" id="insert" role="tabpanel" aria-labelledby="insert-tab">
            <br>
            <div class="container">
                <form method="POST" action="staff.php">
                    <div class="row">
                        <div class="col">
                            <input type="number" class="form-control" id="employeeId" name="employeeId" placeholder="Employee ID">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" id="staffAlias" name="staffAlias" placeholder="Staff Alias">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name">
                        </div>
                        <div class="col">
                            <select class="form-select" id="position" name="position">
                                <option selected value="SDE">Developer</option>
                                <option value="Technician">Technician</option>
                            </select>
                        </div>
                        <div class="col-sm-auto">
                            <button type="submit" class="btn btn-primary" name="insertRequestSubmit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <?php
            function handleInsertRequest($id, $alias, $fn, $ln, $pos)
            {
                //Getting the values from user and insert data into the table
                $alias_binding = array(
                    ":bind1" => $alias,
                    ":bind2" => $alias . "@awesome.com"
                );

                $staff_binding = array(
                    ":bind1" => $id,
                    ":bind2" => $alias,
                    ":bind3" => $fn,
                    ":bind4" => $ln,
                    ":bind5" => $pos,
                );
                Database::executeBoundSQL("insert into Alias values (:bind1, :bind2)", $alias_binding);
                Database::executeBoundSQL("insert into Staff values (:bind1, :bind2, :bind3, :bind4, :bind5)", $staff_binding);
            }

            if (isset($_POST['insertRequestSubmit'])) {
                handleInsertRequest($_POST['employeeId'], $_POST['staffAlias'], $_POST['firstName'], $_POST['lastName'], $_POST['position']);
                // refresh our staff df after handling the request
                $staff_df = Database::executePlainSQL("select * from Staff");
            }
            ?>
        </div>
        <div class="tab-pane fade" id="update" role="tabpanel" aria-labelledby="update-tab">
            <!-- Update tab -->
            <br>
            <div class="container">
                <form method="POST" action="staff.php">
                    <div class="row">
                        <label for="employeeId" class="col-sm-auto col-form-label">Employee ID</label>
                        <div class="col-sm-1">
                            <select class="form-select" id="employeeId" name="employeeId">
                                <?php
                                foreach ($staff_df->rows as $row) {
                                    echo "<option>$row[0]</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <label for="firstName" class="col-sm-auto col-form-label">First Name</label>
                        <div class="col">
                            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name">
                        </div>
                        <label for="lastName" class="col-sm-auto col-form-label">Last Name</label>
                        <div class="col">
                            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name">
                        </div>
                        <label for="position" class="col-sm-auto col-form-label">Position</label>
                        <div class="col">
                            <select class="form-select" id="position" name="position">
                                <option value="SDE">Developer</option>
                                <option value="Technician">Technician</option>
                            </select>
                        </div>
                        <div class="col-sm-auto">
                            <button type="submit" class="btn btn-primary" name="updateRequestSubmit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <?php
            function updateField($id, $field, $val) {
                $binding = array(
                    ":id" => $id,
                    ":val" => $val
                );

                Database::executeBoundSQL("update Staff set $field = :val where employee_id = :id", $binding);
            }

            function handleUpdateRequest($id, $firstName, $lastName, $pos) {
                if ($firstName) updateField($id, "first_name", $firstName);
                if ($lastName) updateField($id, "last_name", $lastName);
                if ($pos) updateField($id, "position", $pos);
            }

            if (isset($_POST['updateRequestSubmit'])) {
                handleUpdateRequest($_POST['employeeId'], $_POST['firstName'], $_POST['lastName'], $_POST['position']);
                // refresh our staff df after handling the request
                $staff_df = Database::executePlainSQL("select * from Staff");
            }
            ?>
        </div>
        <div class="tab-pane fade" id="delete" role="tabpanel" aria-labelledby="delete-tab">
            <!-- Delete tab -->
            <br>
            <div class="container">
                <form method="POST" action="staff.php">
                    <div class="row">
                        <label for="employeeId" class="col-sm-auto col-form-label">Employee ID</label>
                        <div class="col">
                            <select class="form-select" id="employeeId" name="employeeId">
                                <?php
                                foreach ($staff_df->rows as $row) {
                                    echo "<option>$row[0]</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-auto">
                            <button type="submit" class="btn btn-primary" name="deleteRequestSubmit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <?php
            function deleteRequestSubmit($id)
            {
                $binding = array(":bind1" => $id);
                Database::executeBoundSQL("delete from staff where employee_id = :bind1", $binding);
            }

            if (isset($_POST['deleteRequestSubmit'])) {
                deleteRequestSubmit($_POST['employeeId']);
                // refresh our staff df after handling the request
                $staff_df = Database::executePlainSQL("select * from Staff");
            }
            ?>
        </div>
    </div>
</div>

<!-- Result display block -->
<br>
<div class="container">
    <h1 class="text-lg-center">Staff Data</h1>
    <br>
    <?php
        Renderer::renderTable($staff_df);
    ?>
</div>

<!-- Javascript includes -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

</body>
</html>
