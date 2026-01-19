<?php
    $timesnap=date('Y-m-d : H:i:s');
    
    if (!isset($pgnm)) {
        $pgnm="Rent-a-Kaya Software";
    }
    // ensure admin-only pages are protected
    require_once __DIR__ . '/functions/auth.php';
    // only enforce on pages that include this header (not the login page itself)
    require_admin();
?>
<!-- this page has the header and the top nav bar -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../plugins/images/icon.png">
    <title><?php echo $pgnm?></title>
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
    <link href="../plugins/bower_components/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <!--w3css-->
    <link href="../css/w3.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- toast CSS -->
    <link href="../plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- morris CSS -->
    <link href="../plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- Kayarent shared theme -->
    <!-- Brand variables (must load before theme and color overrides) -->
    <link href="../css/brand.css" rel="stylesheet">
    <!-- Admin color overrides mapped to the site brand -->
    <link href="css/colors/kaya.css" id="theme" rel="stylesheet">
    <!-- Kayarent shared theme -->
    <link href="../css/kayarent-theme.css" rel="stylesheet">
    <style type="text/css">
        #dlt{
            /*error message div*/
            margin-left:auto;
            margin-right: auto;
            text-align: center;
            max-width: 500px;

        }
    </style>
        <style>
        /* Override admin header to use white background and dark icons/text */
        .navbar.navbar-default.navbar-static-top {
            background-color: #ffffff !important;
            border-bottom: 1px solid #e5e7eb;
            color: #111;
        }
        .navbar .navbar-top-links > li > a, .navbar .navbar-header .logo, .navbar .navbar-header .logo b, .navbar .navbar-top-links i {
            color: #111 !important;
        }
        .navbar .navbar-header .logo b img { filter: none; }
        </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header"> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="ti-menu"></i></a>
                <div class="top-left-part"><a class="logo" href="index.php"><b><img src="../plugins/images/mainlogo.png" style="width: 30px; height: 30px;" alt="home" /></b><span class="hidden-xs"><b>RentaKaya</b></span></a></div>
                                <!-- Sidebar toggle (modern) -->
                                <button id="kaya-sidebar-toggle" class="btn btn-link visible-xs visible-sm" aria-label="Toggle sidebar" title="Toggle sidebar" style="border:0;background:transparent;color:inherit;margin-left:8px">
                                    <i class="ti-menu"></i>
                                </button>
                <ul class="nav navbar-top-links navbar-left hidden-xs">
                    <li><a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i class="icon-arrow-left-circle ti-menu"></i></a></li>
                    <li>
                        <form role="search" class="app-search hidden-xs">
                            <input type="text" placeholder="Search..." class="form-control"> <a href=""><i class="fa fa-search"></i></a> </form>
                    </li>
                </ul>
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li>
                        <a href="functions/logout.php" class="waves-effect waves-light" title="Logout">
                            <i class="fa fa-sign-out"></i>
                            <span class="hidden-xs" style="margin-left:6px">Logout</span>
                        </a>
                    </li>
                    <!-- /.dropdown -->
                    <li class="right-side-toggle"> <a class="waves-effect waves-light" href="javascript:void(0)"><i class="ti-settings"></i></a></li>
                    <!-- /.dropdown -->
                </ul>
            </div>
            <!-- /.navbar-header -->
            <!-- /.navbar-top-links -->
            <!-- /.navbar-static-side -->
        </nav>
                <script>
                (function(){
                    function toggleSidebar(){
                        document.body.classList.toggle('sidebar-collapsed');
                    }

                    function openMobileSidebar(){
                        document.body.classList.add('sidebar-open');
                        // create backdrop
                        var bd = document.getElementById('kaya-sidebar-backdrop');
                        if(!bd){ bd = document.createElement('div'); bd.id = 'kaya-sidebar-backdrop'; bd.setAttribute('aria-hidden','true'); document.body.appendChild(bd); }
                        bd.addEventListener('click', closeMobileSidebar);
                    }

                    function closeMobileSidebar(){
                        document.body.classList.remove('sidebar-open');
                        var bd = document.getElementById('kaya-sidebar-backdrop');
                        if(bd){ bd.parentNode.removeChild(bd); }
                    }

                    function toggleMobile(){
                        if(document.body.classList.contains('sidebar-open')) closeMobileSidebar(); else openMobileSidebar();
                    }

                    var btn = document.getElementById('kaya-sidebar-toggle');
                    if(btn){
                        btn.addEventListener('click', function(e){
                            e.preventDefault();
                            // on small screens open drawer, on larger screens collapse
                            if(window.matchMedia('(max-width: 992px)').matches){
                                toggleMobile();
                            } else {
                                toggleSidebar();
                            }
                        }, {passive:false});
                    }

                    // existing open-close anchor support (keeps backward compatibility)
                    var legacy = document.querySelector('.open-close');
                    if(legacy){
                        legacy.addEventListener('click', function(e){
                            e.preventDefault();
                            toggleSidebar();
                        });
                    }

                    // close mobile sidebar on outside click
                    document.addEventListener('click', function(e){
                        if(document.body.classList.contains('sidebar-open')){
                            var sidebar = document.querySelector('.sidebar');
                            if(sidebar && !sidebar.contains(e.target) && !e.target.closest('#kaya-sidebar-toggle')){
                                closeMobileSidebar();
                            }
                        }
                    }, true);

                })();
                </script>