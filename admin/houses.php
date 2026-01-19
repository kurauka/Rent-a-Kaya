<?php
    
    $pgnm="Rent-a-Kaya : View Houses";
    $error=' ';

    //require the global file for errors
    require_once "functions/errors.php";
    
    ob_start();
    require_once "functions/db.php";

    // Initialize the session

    session_start();

    // If session variable is not set it will redirect to login page

    if(!isset($_SESSION['email']) || empty($_SESSION['email'])){

      header("location: login.php");

      exit;
    }
    if (is_logged_in_temporary()) {
        #allow access
    

    $email = $_SESSION['email'];

    $sql = "SELECT * FROM `houses`";
    $query = mysqli_query($connection, $sql);
    $houses = [];
    if ($query) {
        while ($r = mysqli_fetch_assoc($query)) $houses[] = $r;
    }
    
    /*******************************************************
                    introduce the admin header
    *******************************************************/
    require "admin_header0.php";

    /*******************************************************
                    Add the left panel
    *******************************************************/
    require "admin_left_panel.php";
?>

    

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title"><?php echo $username;?></h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
                        <ol class="breadcrumb">
                            <li><a href="index.php">Dashboard</a></li>
                            <li><a href="#" class="active">Houses</a></li>
                            <li><a href="new-house.php">New</a></li>
                            
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /row -->
                <div class="row">
                   
                    
                    <div class="col-sm-12">
                        <div class="white-box">

                        		<?php
                                    echo $error;
                                    
									if (isset($_GET["success"])) {
										echo 
										'<div class="alert alert-success" >
					                          <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
					                         <strong>DONE!! </strong><p> The new house has been added successfully.</p>
					                    </div>'
										;
									}
                                    elseif (isset($_GET["deleted"])) {
                                        echo 
                                        '<div class="alert alert-warning" >
                                              <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
                                             <strong>DELETED!! </strong><p> The house has been successfully deleted.</p>
                                        </div>'
                                        ;
                                    }
                                    elseif (isset($_GET["del_error"])) {
                                        echo 
                                        '<div class="alert alert-danger" >
                                              <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
                                             <strong>ERROR!! </strong><p> There was an error during deleting this record. Please try again.</p>
                                        </div>'
                                        ;
                                    }
                                    
								?>	

                            <h3 class="box-title m-b-0">Current house listings ( <x style="color: orange;"><?php echo mysqli_num_rows($query);?></x> )</h3>
                            <p class="text-muted m-b-30">Export data to Copy, CSV, Excel, PDF & Print</p>
                            <div class="table-responsive">
                                <?php if (count($houses) == 0): ?>
                                    <div class="p-4"><em style="color:brown">No houses to display :(</em></div>
                                <?php else: ?>

                                    <div class="d-flex align-items-center mb-3" style="gap:8px;flex-wrap:wrap">
                                        <a href="new-house.php" class="btn btn-success btn-sm">+ New House</a>
                                        <div class="input-group" style="max-width:320px">
                                            <input id="house-search" type="text" class="form-control input-sm" placeholder="Search houses...">
                                            <span class="input-group-btn">
                                                <button id="toggle-view" class="btn btn-default btn-sm" title="Toggle view">Cards</button>
                                            </span>
                                        </div>
                                        <select id="house-status-filter" class="form-control input-sm" style="max-width:180px">
                                            <option value="">All statuses</option>
                                            <option value="Vacant">Vacant</option>
                                            <option value="Occupied">Occupied</option>
                                        </select>
                                        <div class="ml-auto text-muted small">Total: <strong><?php echo count($houses); ?></strong></div>
                                    </div>

                                    <style>
                                    /* Page-specific card styles */
                                    .house-cards{display:none; gap:12px;}
                                    .house-card{border:1px solid #e6e6e6;border-radius:6px;padding:12px;background:#fff;display:flex;flex-direction:column;gap:8px;box-shadow:0 1px 3px rgba(0,0,0,0.04)}
                                    .house-card .meta{display:flex;justify-content:space-between;align-items:center;gap:8px}
                                    .house-card .title{font-weight:600;color:#03a9f3}
                                    @media(min-width:768px){.house-cards{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr))}}
                                    </style>

                                    <!-- Card grid (hidden by default; toggled) -->
                                    <div id="card-view" class="house-cards mb-3">
                                        <?php foreach ($houses as $row): $i = $row['houseID']; ?>
                                            <div class="house-card" data-status="<?php echo htmlspecialchars($row['house_status']); ?>">
                                                <div class="meta">
                                                    <div>
                                                        <div class="title"><?php echo htmlspecialchars($row['house_name']); ?></div>
                                                        <div class="small text-muted"><?php echo htmlspecialchars($row['location']); ?> • <?php echo htmlspecialchars($row['num_of_bedrooms']); ?> bed(s)</div>
                                                    </div>
                                                    <div class="text-right">
                                                        <div class="h4" style="margin:0;color:#333">Ksh <?php echo htmlspecialchars($row['rent_amount']); ?></div>
                                                        <div class="small text-muted"><?php echo htmlspecialchars($row['house_status']); ?></div>
                                                    </div>
                                                </div>
                                                <div class="small">Rooms: <?php echo htmlspecialchars($row['number_of_rooms']); ?></div>
                                                <div class="d-flex" style="gap:8px;margin-top:auto">
                                                    <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#responsive-modal_edit<?php echo $i; ?>">Edit</button>
                                                    <button class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#responsive-modal<?php echo $i; ?>">Delete</button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <!-- Table view -->
                                    <table id="example23" class="table table-striped table-hover" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>House ID</th>
                                                <th>House Name</th>
                                                <th>No. of rooms</th>
                                                <th>Rent amount</th>
                                                <th>Location</th>
                                                <th>Bedrooms</th>
                                                <th>House Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($houses as $row): $i = $row['houseID']; ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['houseID']); ?></td>
                                                <td><a href="#" data-toggle="modal" data-target="#responsive-modal_edit<?php echo $i; ?>" style="color:#03a9f3"><?php echo htmlspecialchars($row['house_name']); ?></a></td>
                                                <td><?php echo htmlspecialchars($row['number_of_rooms']); ?></td>
                                                <td><?php echo htmlspecialchars($row['rent_amount']); ?></td>
                                                <td><?php echo htmlspecialchars($row['location']); ?></td>
                                                <td><?php echo htmlspecialchars($row['num_of_bedrooms']); ?></td>
                                                <td><?php echo htmlspecialchars($row['house_status']); ?></td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#responsive-modal_edit<?php echo $i; ?>" title="Edit"><i class="fa fa-edit"></i></button>
                                                        <button class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#responsive-modal<?php echo $i; ?>" title="Delete"><i class="fa fa-trash"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                    <!-- Reuse modals for each house -->
                                    <?php foreach ($houses as $row): $i = $row['houseID']; ?>
                                        <div id="responsive-modal_edit<?php echo $i; ?>" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        <h4 class="modal-title">Edit <?php echo htmlspecialchars($row['house_name']); ?> details</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="functions/house_manage.php" method="post">
                                                            <div class="mb-3">
                                                                <label for="hname<?php echo $i; ?>">House Name *</label>
                                                                <input id="hname<?php echo $i; ?>" type="text" name="tname" class="form-control" value="<?php echo htmlspecialchars($row['house_name']); ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="tnum<?php echo $i; ?>">Number of Rooms</label>
                                                                <input id="tnum<?php echo $i; ?>" type="number" name="tnum" class="form-control" value="<?php echo htmlspecialchars($row['number_of_rooms']); ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="rent<?php echo $i; ?>">Rent Amount *</label>
                                                                <input id="rent<?php echo $i; ?>" type="number" min="1000" name="rent" class="form-control" value="<?php echo htmlspecialchars($row['rent_amount']); ?>" required>
                                                            </div>
                                                            <input type="hidden" name="hsid" value="<?php echo htmlspecialchars($row['houseID']); ?>">
                                                            <div class="mb-3">
                                                                <label for="oftype<?php echo $i; ?>">House Status</label>
                                                                <select id="oftype<?php echo $i; ?>" name="oftype" class="form-control">
                                                                    <option selected value="<?php echo htmlspecialchars($row['house_status']); ?>"><?php echo htmlspecialchars($row['house_status']); ?></option>
                                                                    <option value="Vacant">Vacant</option>
                                                                    <option value="Occupied">Occupied</option>
                                                                </select>
                                                            </div>
                                                            <div class="d-flex justify-content-end" style="gap:8px">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" name="editHouse" class="btn btn-primary">Update Record</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="responsive-modal<?php echo $row['houseID']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        <h4 class="modal-title">Confirm delete <?php echo htmlspecialchars($row['house_name']); ?>?</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>This action could detach all tenant records linked to this house.</p>
                                                        <form action="functions/del_house.php" method="post">
                                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['houseID']); ?>">
                                                            <div class="d-flex justify-content-end" style="gap:8px">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">Delete anyway</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>


             


                <!-- /.row -->
                <?php require "admin_righ_sidebar.php"; ?>
            </div>
            <?php require "admin_footer.php"; ?>
    <script>
    $(document).ready(function() {
        $('#myTable').DataTable();
        $(document).ready(function() {
            var table = $('#example').DataTable({
                "columnDefs": [{
                    "visible": false,
                    "targets": 2
                }],
                "order": [
                    [2, 'asc']
                ],
                "displayLength": 10,
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;
                    api.column(2, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                            last = group;
                        }
                    });
                }
            });
            // Order by the grouping
            $('#example tbody').on('click', 'tr.group', function() {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
        });
    });
    $(document).ready(function(){
        var dt = $('#example23').DataTable({
            dom: 'Bfrtip',
            buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print' ],
            responsive: true,
            order: [[1,'asc']]
        });

        // Wire search input to DataTable and to card view
        $('#house-search').on('input', function(){
            var q = $(this).val();
            dt.search(q).draw();
            // card view simple filter
            $('#card-view .house-card').each(function(){
                var txt = ($(this).text() || '').toLowerCase();
                $(this).toggle(txt.indexOf(q.toLowerCase()) !== -1);
            });
        });

        // Status filter (column 6)
        $('#house-status-filter').on('change', function(){
            var v = $(this).val();
            if(v) dt.column(6).search('^'+v+'$', true, false).draw(); else dt.column(6).search('').draw();
            // cards
            if($('#card-view').is(':visible')){
                if(!v) $('#card-view .house-card').show(); else $('#card-view .house-card').each(function(){ $(this).toggle($(this).data('status') == v); });
            }
        });

        // View toggle
        $('#toggle-view').on('click', function(){
            if($('#card-view').is(':visible')){
                $('#card-view').hide();
                $('#example23').closest('.dataTables_wrapper').show();
                $(this).text('Cards');
            } else {
                $('#card-view').show();
                $('#example23').closest('.dataTables_wrapper').hide();
                $(this).text('List');
            }
        });
        // ensure card-view hidden by default
        $('#card-view').hide();
    });
    </script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>
<?php
}
else{
    header('location:index.php');
}
?>