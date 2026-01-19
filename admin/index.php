<?php
    ob_start();
    require_once "functions/db.php";
    //page name
    $pgnm='Rent-a-Kaya: Dashboard';
    $error=' '; //error variable

    //require the global file for errors
    require_once "functions/errors.php";

    // Initialize the session

    session_start();

    //die($_COOKIE['themeselection']);

    // If session variable is not set it will redirect to login page

    if(!isset($_SESSION['email']) || empty($_SESSION['email'])){

      header("location: login.php");

      exit;
    }
    if (is_logged_in_temporary()) {
        //allow access


    $email = $_SESSION['email'];

    //main querries
    //houses
    $sql_houses = "SELECT * FROM houses";
    $query_houses = mysqli_query($connection, $sql_houses);

    //tenants
    $sql_tenants = "SELECT * FROM tenants";
    $query_tenants = mysqli_query($connection, $sql_tenants);

    //invoices
    $sql_invoices = "SELECT * FROM invoices";
    $query_invoices = mysqli_query($connection, $sql_invoices);

    //payments
    $sql_payments = "SELECT * FROM payments";
    $query_payments = mysqli_query($connection, $sql_payments);



    //other querries

    $sql_posts = "SELECT * FROM posts";
    $query_posts = mysqli_query($connection, $sql_posts);

    $sql_contacts = "SELECT * FROM contacts";
    $query_contacts = mysqli_query($connection, $sql_contacts);

    $sql_subscribers = "SELECT * FROM subscribers";
    $query_subscribers = mysqli_query($connection, $sql_subscribers);

    $sql_comments = "SELECT * FROM comments";
    $query_comments = mysqli_query($connection, $sql_comments);

    /*******************************************************
                    introduce the admin header
    *******************************************************/
    require "admin_header0.php";


    /*******************************************************
                    Add the left panel
    *******************************************************/
    require "admin_left_panel.php";

    

?>


<style>
		body { font-family: 'Inter', sans-serif; }
		::-webkit-scrollbar { width: 8px; }
		::-webkit-scrollbar-track { background: #A7634E; }
		::-webkit-scrollbar-thumb { background: #0D452C; border-radius: 4px; }
		::-webkit-scrollbar-thumb:hover { background: #0D452C; }
	</style>
       


        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <!-- salute the admin -->
                        <h4 class="page-title"><?php echo 'Hujambo '.$username.',';?></h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
                        <ol class="breadcrumb">
                            <li onclick=""><a href="#">Dashboard</a></li>
                            <li class="active">Home</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>

                <?php 

                 if (isset($_GET['set'])) {
                    echo'<div class="alert alert-success" >
                     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                   <strong>DONE!! </strong><p> Your password has been successfully updated.</p>
                     </div>';
                        }

                        echo $error;

                ?>

                <div style="width:20em; height:auto; max-height:25em; background-color: transparent;position:fixed; top:4.5em; right: 10px; z-index: 1; padding:1.2em; overflow-x:auto;">
                <?php

                $sqnotifications=mysqli_query($conn, "SELECT * FROM `transactions` WHERE `seen`='NO' order by `id` desc");

                while ($recc=mysqli_fetch_array($sqnotifications,MYSQLI_BOTH)) 
                {
                    $id=$recc['id'];
                    $actor=$recc['actor'];
                    $desc=$recc['description'];
                    $acttime=$recc['time'];

                    echo "<div id='dlt' class='w3-container alert alert-slim w3-card-8 w3-yellow fade in'> 
                         <h1 onclick=\"deleteNotifications('notifications','$id') \"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> 
                            <img src='../images/Close.png' title='close message'> </a> 
                         </h1>
                        <div style='text-align:center; align-self:center; font-size:14px'>
                            <strong>From $actor ... </strong><br>  
                            <p class='pcontent'>\"...$desc.\"</p>
                            <p class='badge badge-info'> $acttime </p>
                        </div>
                                                
                    </div>";
                }

                

                ?>

                </div>

                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        <div class="white-box">
                            <div class="row g-3 modern-stats">

                                <div class="col-lg-3 col-md-6">
                                    <div class="kaya-card p-3 d-flex align-items-center" style="gap:12px">
                                        <div style="font-size:32px;color:var(--kaya-primary)"><i class="fa fa-institution" aria-hidden="true"></i></div>
                                        <div style="flex:1">
                                            <div class="text-muted" style="font-size:12px">Houses</div>
                                            <div style="font-weight:700;font-size:20px"><?php echo mysqli_num_rows($query_houses);?></div>
                                            <div style="margin-top:8px;font-size:13px"><a href="houses.php">View</a> · <a href="new-house.php">Add</a></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="kaya-card p-3 d-flex align-items-center" style="gap:12px">
                                        <div style="font-size:32px;color:#0288D1"><i class="fa fa-users" aria-hidden="true"></i></div>
                                        <div style="flex:1">
                                            <div class="text-muted" style="font-size:12px">Tenants</div>
                                            <div style="font-weight:700;font-size:20px"><?php echo mysqli_num_rows($query_tenants);?></div>
                                            <div style="margin-top:8px;font-size:13px"><a href="tenants.php">View</a> · <a href="new-tenant.php">Add</a></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="kaya-card p-3 d-flex align-items-center" style="gap:12px">
                                        <div style="font-size:32px;color:#8B5CF6"><i class="fa fa-credit-card" aria-hidden="true"></i></div>
                                        <div style="flex:1">
                                            <div class="text-muted" style="font-size:12px">Invoices</div>
                                            <div style="font-weight:700;font-size:20px"><?php echo mysqli_num_rows($query_invoices);?></div>
                                            <div style="margin-top:8px;font-size:13px"><a href="invoices.php">View</a> · <a href="new-invoice.php">Add</a></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="kaya-card p-3 d-flex align-items-center" style="gap:12px">
                                        <div style="font-size:32px;color:#EF4444"><i class="fa fa-money" aria-hidden="true"></i></div>
                                        <div style="flex:1">
                                            <div class="text-muted" style="font-size:12px">Payments</div>
                                            <div style="font-weight:700;font-size:20px"><?php echo mysqli_num_rows($query_payments);?></div>
                                            <div style="margin-top:8px;font-size:13px"><a href="payments.php">View</a> · <a href="new-payment.php">Add</a></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!--row -->

                <!-- /.row to show snapshot of major statuses -->
                <div class="row">
                    <div class="col-12">
                        <div class="white-box">
                            <div class="row g-3">

                                <div class="col-lg-3 col-md-6">
                                    <div class="kaya-card p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="text-muted" style="font-size:12px">Month Collections (<?php echo date('Y-m')?>)</div>
                                                <div style="font-weight:700;font-size:20px">
                                                    <?php
                                                        $month=date('Y-m'); 
                                                        $total=0;
                                                        $sq_pay="SELECT `amountPaid`, `dateofPayment` from `payments` where `dateofPayment` like '%$month%'";
                                                        $rec=mysqli_query($conn,$sq_pay);
                                                        while ($row=mysqli_fetch_array($rec,MYSQLI_BOTH)) {
                                                            $total+=$row['amountPaid'];
                                                        }
                                                        echo "$total";
                                                    ?>
                                                    <div style="font-size:12px;color:var(--kaya-muted)">(KES)</div>
                                                </div>
                                            </div>
                                            <div style="font-size:32px;color:var(--kaya-primary)"><i class="fa fa-briefcase"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="kaya-card p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="text-muted" style="font-size:12px">Pending Invoices</div>
                                                <div style="font-weight:700;font-size:20px">
                                                    <?php
                                                        $total=0;
                                                        $sq_pay="SELECT `amountDue`, `status` from `invoices` where `status`='unpaid'";
                                                        $rec=mysqli_query($conn,$sq_pay);
                                                        while ($row=mysqli_fetch_array($rec,MYSQLI_BOTH)) {
                                                            $total+=$row['amountDue'];
                                                        }
                                                        echo "$total";
                                                    ?>
                                                    <div style="font-size:12px;color:var(--kaya-muted)">(KES)</div>
                                                </div>
                                            </div>
                                            <div style="font-size:32px;color:#EF4444"><i class="fa fa-usd"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="kaya-card p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="text-muted" style="font-size:12px">Tenant Balances</div>
                                                <div style="font-weight:700;font-size:20px">
                                                    <?php
                                                        $total=0;
                                                        $sq_pay="SELECT `amountDue`, `status` from `invoices` where `status`='paid'";
                                                        $rec=mysqli_query($conn,$sq_pay);
                                                        while ($row=mysqli_fetch_array($rec,MYSQLI_BOTH)) {
                                                            $total+=$row['amountDue'];
                                                        }
                                                        echo "$total";
                                                    ?>
                                                    <div style="font-size:12px;color:var(--kaya-muted)">(KES)</div>
                                                </div>
                                            </div>
                                            <div style="font-size:32px;color:#F59E0B"><i class="fa fa-usd"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="kaya-card p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="text-muted" style="font-size:12px">Rentable Units</div>
                                                <div style="font-weight:700;font-size:20px">
                                                    <?php
                                                        $total=0;
                                                        $sq_pay="SELECT `number_of_rooms`,`house_status` from `houses` where `house_status`='Vacant'";
                                                        $rec=mysqli_query($conn,$sq_pay);
                                                        while ($row=mysqli_fetch_array($rec,MYSQLI_BOTH)) {
                                                            $total+=$row['number_of_rooms'];
                                                        }
                                                        echo "$total";
                                                    ?>
                                                    <div style="font-size:12px;color:var(--kaya-muted)">(Units)</div>
                                                </div>
                                            </div>
                                            <div style="font-size:32px;color:#06A7FF"><i class="fa fa-home"></i></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transactions -->
                <div class="row">
                    <div class="col-12">
                        <div class="kaya-card p-3">
                            <h3 class="mb-3" style="text-align:center;margin:0">Latest Transactions</h3>
                            <div style="max-height:420px;overflow:auto;margin-top:12px">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Actor</th>
                                            <th>Action Description</th>
                                            <th style="width:160px">Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $sq_trans="SELECT * from `transactions` order by `id` desc limit 10";
                                        $rec=mysqli_query($conn,$sq_trans);
                                        while ($row=mysqli_fetch_array($rec,MYSQLI_BOTH)) {
                                            $actor=htmlspecialchars($row['actor']);
                                            $description=htmlspecialchars($row['description']);
                                            $time=htmlspecialchars($row['time']);
                                            echo "<tr>\n";
                                            echo "<td><strong>$actor</strong></td>\n";
                                            echo "<td style='max-width:720px;white-space:normal;word-break:break-word;'>$description</td>\n";
                                            echo "<td><small class='kaya-muted'>$time</small></td>\n";
                                            echo "</tr>\n";
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <div style="text-align:right;margin-top:8px"><a href="transactions.php" class="btn btn-outline-primary">View all</a></div>
                        </div>
                    </div>
                </div>

                <!-- /row for blog, comments etc. -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="kaya-card p-3 mb-3">
                            <h3 style="text-align:center;margin:0">Insights</h3>
                            <div class="row g-3" style="margin-top:12px">
                                <div class="col-lg-3 col-md-6">
                                    <div class="kaya-card p-3">
                                        <div class="text-muted" style="font-size:12px">Company Blog Posts</div>
                                        <div style="font-weight:700;font-size:20px"><?php echo mysqli_num_rows($query_posts);?></div>
                                        <div style="margin-top:8px">
                                            <div style="height:6px;background:rgba(2,24,54,0.04);border-radius:8px;overflow:hidden">
                                                <div style="width:40%;height:6px;background:var(--kaya-primary);"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="kaya-card p-3">
                                        <div class="text-muted" style="font-size:12px">Blog Comments</div>
                                        <div style="font-weight:700;font-size:20px"><?php echo mysqli_num_rows($query_comments);?></div>
                                        <div style="margin-top:8px">
                                            <div style="height:6px;background:rgba(2,24,54,0.04);border-radius:8px;overflow:hidden">
                                                <div style="width:40%;height:6px;background:#8B5CF6;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="kaya-card p-3">
                                        <div class="text-muted" style="font-size:12px">Contact Messages</div>
                                        <div style="font-weight:700;font-size:20px"><?php echo mysqli_num_rows($query_contacts);?></div>
                                        <div style="margin-top:8px">
                                            <div style="height:6px;background:rgba(2,24,54,0.04);border-radius:8px;overflow:hidden">
                                                <div style="width:40%;height:6px;background:#0288D1;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="kaya-card p-3">
                                        <div class="text-muted" style="font-size:12px">Company Subscribers</div>
                                        <div style="font-weight:700;font-size:20px"><?php echo mysqli_num_rows($query_subscribers);?></div>
                                        <div style="margin-top:8px">
                                            <div style="height:6px;background:rgba(2,24,54,0.04);border-radius:8px;overflow:hidden">
                                                <div style="width:40%;height:6px;background:var(--kaya-primary-600);"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--row -->
             
                <div class="row">
                    <div class="col-md-12 col-lg-6 col-sm-12">
                        <div class="kaya-card p-3" style="padding-top:24px">
                            <h3 class="box-title">Recent Comments</h3>
                            <div style="margin-top:12px">
                                <?php
                                if (mysqli_num_rows($query_comments) == 0) {
                                    echo '<div><em style="color:brown">There are no comments yet :(</em></div>';
                                } else {
                                    $counter = 0; $max = 5;
                                    mysqli_data_seek($query_comments, 0);
                                    while (($row2 = mysqli_fetch_array($query_comments)) && ($counter < $max)) {
                                        $blogid = $row2['blogid'];
                                        $sql2 = "SELECT title FROM posts WHERE id='$blogid' LIMIT 1";
                                        $query2 = mysqli_query($connection, $sql2);
                                        $post = mysqli_fetch_assoc($query2);
                                        $name = htmlspecialchars($row2['name']);
                                        $comment = htmlspecialchars($row2['comment']);
                                        $date = htmlspecialchars($row2['date']);
                                        $title = $post ? htmlspecialchars($post['title']) : 'Post';
                                        echo '<div style="display:flex;gap:12px;padding:10px 0;border-bottom:1px solid rgba(2,24,54,0.04)">';
                                        echo '<div style="width:48px;height:48px;border-radius:8px;background:linear-gradient(135deg,#e2e8f0,#c7d2fe);display:flex;align-items:center;justify-content:center;font-weight:700;color:#0f172a">'.strtoupper(substr($name,0,1)).'</div>';
                                        echo '<div style="flex:1">';
                                        echo '<div style="display:flex;justify-content:space-between;align-items:center"><div><strong>'.$name.'</strong> <div style="font-size:12px;color:var(--kaya-muted)">Blog: '.$title.'</div></div><div style="font-size:12px;color:var(--kaya-muted)">'.$date.'</div></div>';
                                        echo '<div style="margin-top:6px;color:#0f172a">'.(strlen($comment)>180?substr($comment,0,180).'...':$comment).'</div>';
                                        echo '</div></div>';
                                        $counter++;
                                    }
                                }
                                ?>
                                <div style="margin-top:12px;text-align:right"><a href="comments.php" class="btn btn-outline-primary">View All Comments</a></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-6 col-sm-12">
                        <div class="kaya-card p-3" style="padding-top:24px">
                            <h3 class="box-title">Recent Posts</h3>
                            <div style="margin-top:12px">
                                <?php
                                $counter = 0; $max = 5;
                                mysqli_data_seek($query_posts, 0);
                                if (mysqli_num_rows($query_posts) == 0) {
                                    echo '<div><em style="color:brown">No Posts Yet :( Upload Company\'s first blog post today!</em></div>';
                                } else {
                                    echo '<table class="table"><thead><tr><th>TITLE</th><th>DATE</th><th>COMMENTS</th></tr></thead><tbody>';
                                    mysqli_data_seek($query_posts, 0);
                                    while (($row = mysqli_fetch_array($query_posts)) && ($counter < $max)) {
                                        $postid = $row['id'];
                                        $sql2 = "SELECT * FROM comments WHERE blogid=$postid";
                                        $query2 = mysqli_query($connection, $sql2);
                                        echo '<tr><td>'.htmlspecialchars($row['title']).'</td><td>'.htmlspecialchars($row['date']).'</td><td><span class="text-success">'.mysqli_num_rows($query2).'</span></td></tr>';
                                        $counter++;
                                    }
                                    echo '</tbody></table>';
                                }
                                ?>
                                <div style="margin-top:12px;text-align:right"><a href="posts.php" class="btn btn-outline-primary">View All Posts</a></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /.row  right sidebar for colors-->
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
<?php require "admin_footer.php"; ?>
    <script type="text/javascript">

    //enable to use the toast popups
    /*
    $(document).ready(function() {
        $.toast({
            heading: 'Hey! Welcome onboard...',
            text: 'Here, every button means something',
            position: 'top-right',
            loaderBg: '#ff6849',
            icon: 'info',
            hideAfter: 3700,
            stack: 6
        })
    });*/

//delete notifications on realtime
    function deleteNotifications(opt,str) 
    {
        //declare server submitting variable
        var xmlhttp="";
        //initialize as per browser
         if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
        //submit to server for procesing
        xmlhttp.open("GET","liveActions.php?act="+opt+"&&q="+str,true);
        xmlhttp.send();
    }
    </script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>
<?php
}
else{
    header('location:../index.php');
}
?>