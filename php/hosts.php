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
    <title>CPSC 304: Hosts</title>
</head>

<body>

<!-- Include for other files -->
<?php include 'common/database.php' ?>
<?php include 'common/render.php' ?>

<!-- Include for the navbar -->
<?php include 'navbar.php' ?>

<!-- Reused data within page -->
<?php
?>

<!-- Description block at the top of page -->
<br>
<div class="container">
    <h1 class="text-lg-center">Host Info</h1>
    <p class="text-sm-center">Displays metrics and statistics about host machines in location and datacenters</p>
</div>

<!-- Display number of available machines within each datacenter -->
<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-auto">
            <h3 class='text-md-center'>Available Hosts</h3>
            <p class="text-sm-center">Shows machines within each datacenter that have available storage and network capacity</p>
            <?php
            $dc_df = Database::executePlainSQL("
                select M.datacenter_loc as \"Location\", 
                       COUNT(M.ip) as \"Machines With Available Network and Disk Capacity\"
                from Machine M, MachineModel MM
                where MM.model_number = M.model_number 
                  and M.disk_used < MM.disk_capacity 
                  and M.network_used < MM.network_capacity
                group by M.datacenter_loc
                having COUNT(*) > 1
            ");
            Renderer::renderTable($dc_df);
            ?>
        </div>
    </div>
</div>

<!-- Display machines in each cluster with the most free disk or bandwidth -->
<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-auto">
            <h3 class='text-md-center'>Host Resources</h3>
            <p class="text-sm-center">Shows machine in each cluster with the most resources still available</p>
            <?php
            // query to run contains many lines, stored in file instead
            $resource_df = Database::executeFile('sql/nested_aggregation.sql')[0];
            Renderer::renderTable($resource_df);
            ?>
        </div>
    </div>
</div>

<!-- Display Machines that host all applications  -->
<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-auto">
            <h3 class='text-md-center'>Monoliths</h3>
            <p class="text-sm-center">Shows machines that are hosting all applications</p>
            <?php
            $mh_df = Database::executePlainSQL("
                select ip as \"Machines Hosting All Applications\",
                       model_number as \"Model Number\",
                       cluster_id as \"Cluster ID\", 
                       datacenter_loc as \"Datacenter Location\", 
                       disk_used as \"Disk Usage (GB)\",
                       network_used as \"Network Usage (MB/s)\"
                from Machine M
                where not EXISTS(
                    (
                        select A.app_name, A.app_ver
                        from Application A
                    )
                    minus 
                    (
                        select H.app_name, H.app_ver
                        from Hosting H
                        where H.machine_ip = M.ip
                    )
                )
            ");
            Renderer::renderTable($mh_df);
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
