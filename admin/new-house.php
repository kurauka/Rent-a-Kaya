<?php 

    //a page name
    $pgnm='Rent-a-Kaya: Add a new house';
    $error=' ';

    //start sessions 
    ob_start();

    //require a connector
    require_once "functions/db.php";

    //require the global file for errors
    require_once "functions/errors.php";

    // Initialize the session
    session_start();

    // If user is not logged in, redirect to index otherwise, allow access
     if (is_logged_in_temporary()) {
        //allow access

        //take requests & actions

     /*****************************************************
                       action add a house
     ***************************************************/
                       if (isset($_POST['submit'])) {
                           #admin requests to add a house

                            //gather the data
                        $hname=is_username($_POST['hname']);
                        $numOfRooms=uncrack($_POST['numOfRooms']);
                        $numOfbRooms=uncrack($_POST['numOfbRooms']);
                        $rent=uncrack($_POST['rent']);
                        $location=uncrack($_POST['location']);
                        $status=uncrack($_POST['status']);

                        $timesnap=date('Y-m-d : H:i:s');

                                        //insert the data
                                        $sq="INSERT into `houses` 
                            (`house_name`,`number_of_rooms`,`rent_amount`,`location`,`num_of_bedrooms`,`house_status`) values('$hname','$numOfRooms','$rent','$location','$numOfbRooms','$status');";

                             $sql_transactions="INSERT into `transactions` (`actor`,`time`,`description`)
                            VALUES ('Admin ($username)', '$timesnap','$username added a new house ($hname) with $numOfRooms rentable units, and $numOfbRooms bedrooms per unit located in $location')";

                            $mysqli->autocommit(FALSE);
                            $state=true;

                            $mysqli->query($sq)?null: $state=false;
                            $mysqli->query($sql_transactions)?null: $state=false;

                            if ($state) {
                                $mysqli->commit();
                                #head to index with error state 1
                                header('location:houses.php?state=1');
                            }else{
                                //rollback changes
                                $mysqli -> rollback();
                                //return to page with error state 2
                                header('location:new-house.php?state=2');
                            }

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
                        <h4 class="page-title"><?php echo 'Hey there, '.$username;?></h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
                        <ol class="breadcrumb">
                            <li><a href="index.php">Dashboard</a></li>
                            <li><a href="houses.php">Houses</a></li>
                            <li class="active">New</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!--.row-->
                <div class="row">
                    <div class="col-md-12">
                        <div style="">
                            <?php 
                            echo $error;
                            ?>
                        </div>
                        <div class="white-box">
                            <h3 class="box-title m-b-0"><i class="fa fa-institution fa-3x"></i> Add A New House</h3>
                            <p class="text-muted m-b-30 font-13"> Fill in the form below: </p>
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <form action="new-house.php" method="post" enctype="multipart/form-data">
                                        <!-- <div class="form-group">
                                            <label for="exampleInputuname">User Name</label>
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="ti-user"></i></div>
                                                <input type="text" class="form-control" id="exampleInputuname" placeholder="Username"> </div>
                                        </div> -->
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label for="hname">House Name *</label>
                                                    <input type="text" name="hname" id="hname" class="form-control form-control-lg" placeholder="E.g. Sunrise Apartments, Block B" required>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="numOfRooms">Rentable Units</label>
                                                        <input id="numOfRooms" type="number" min="1" name="numOfRooms" class="form-control" placeholder="e.g. 6" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="numOfbRooms">Bedrooms per Unit</label>
                                                        <input id="numOfbRooms" type="number" min="0" name="numOfbRooms" class="form-control" placeholder="e.g. 2" required>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="rent">Rent Amount (KES / month)</label>
                                                        <input id="rent" type="number" min="0" name="rent" class="form-control" placeholder="e.g. 3000" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="status">House Status</label>
                                                        <select id="status" name="status" class="form-control">
                                                            <option value="Vacant">Vacant</option>
                                                            <option value="Occupied">Occupied</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="location">Location</label>
                                                    <select id="location" name="location" class="form-control">
                                                        <option value="">Select location...</option>
                                                        <?php
                                                            $sq0="SELECT * FROM `locations` order by `id` asc";
                                                            $rec=mysqli_query($conn,$sq0);
                                                            while ($row=mysqli_fetch_array($rec,MYSQLI_BOTH)) {
                                                                $place=htmlspecialchars($row['location_name']);
                                                                echo "<option value=\"$place\">$place</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label>House Image (optional)</label>
                                                    <div class="card p-2 text-center">
                                                        <img id="house-preview" src="../images/placeholder-house.png" alt="preview" style="width:100%;height:auto;object-fit:cover;border-radius:8px;margin-bottom:8px">
                                                        <input type="file" name="house_image" accept="image/*" class="form-control">
                                                        <small class="text-muted">Upload an image to show in the houses list (optional).</small>
                                                    </div>
                                                </div>

                                                <div class="mt-4">
                                                    <label>&nbsp;</label>
                                                    <div class="d-grid">
                                                        <button type="submit" name="submit" class="btn btn-primary btn-lg"><i class="fa fa-plus-circle"></i> Add House</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
           

                </div>
                <!--./row-->
               
           
               
                <!-- .right-sidebar -->
                <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                        <div class="r-panel-body">
                            <ul>
                                <li><b>Layout Options</b></li>
                                <li>
                                    <div class="checkbox checkbox-info">
                                        <input id="checkbox1" type="checkbox" class="fxhdr">
                                        <label for="checkbox1"> Fix Header </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="checkbox checkbox-warning">
                                        <input id="checkbox2" type="checkbox" checked="" class="fxsdr">
                                        <label for="checkbox2"> Fix Sidebar </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="checkbox checkbox-success">
                                        <input id="checkbox4" type="checkbox" class="open-close">
                                        <label for="checkbox4"> Toggle Sidebar </label>
                                    </div>
                                </li>
                            </ul>
                            <ul id="themecolors" class="m-t-20">
                                <li><b>With Light sidebar</b></li>
                                <li><a href="javascript:void(0)" theme="default" class="default-theme">1</a></li>
                                <li><a href="javascript:void(0)" theme="green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" theme="gray" class="yellow-theme">3</a></li>
                                <li><a href="javascript:void(0)" theme="blue" class="blue-theme working">4</a></li>
                                <li><a href="javascript:void(0)" theme="purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" theme="megna" class="megna-theme">6</a></li>
                                <li><b>With Dark sidebar</b></li>
                                <br/>
                                <li><a href="javascript:void(0)" theme="default-dark" class="default-dark-theme">7</a></li>
                                <li><a href="javascript:void(0)" theme="green-dark" class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" theme="gray-dark" class="yellow-dark-theme">9</a></li>
                                <li><a href="javascript:void(0)" theme="blue-dark" class="blue-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" theme="purple-dark" class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" theme="megna-dark" class="megna-dark-theme">12</a></li>
                            </ul>
                            </div>
                    </div>
                </div>
                <!-- /.right-sidebar -->
            </div>
            <!-- /.container-fluid -->
            <footer class="footer text-center"> 2018 &copy; Company Admin </footer>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/dist/js/tether.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/custom.min.js"></script>
    <script src="js/jasny-bootstrap.js"></script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>

    <!-- Local Javascript -->
        <script type="text/javascript">
            
        </script>
    <!--END of local JS -->

</body>

</html>
<?php
}
else{
    header('location:../index.php');
}
?>
