<?php include 'head.php'; ?>
<?php include 'navBar.php'; ?>


<section>
    <div class="container" style="border: 2px solid black; height: 80vh;">
        <div class="row">
            <div class="col">
                <div class="container-fluid my-2">
                    <h2 class="mb-2">Logs</h2>
                        <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center  ">
                            <thead class="table-light">
                            <tr>
                                <th>Timestamp</th>
                                <th>ID</th>
                                <th>Status</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                            </tr>
                            </thead>
                            <tbody id="logsTableBody">
                                <!-- Dashboard data rows will be rendered here -->
                                <td>0999-03-09 05:07:02</td>
                                <td>1</td>
                                <td>Active</td>
                                <td>14.18834</td>
                                <td>120.88149</td>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>