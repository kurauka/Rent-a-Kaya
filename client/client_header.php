<?php
    // Lightweight client header: reuse admin assets but do not enforce admin auth
    if (!isset($pgnm)) { $pgnm = 'Rent-a-Kaya: Client'; }
    // prevent caching of client pages so logged-out users cannot see stale pages
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
    header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
?>
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
    <link href="../admin/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
    <link href="../plugins/bower_components/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <!--w3css-->
    <link href="../css/w3.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- toast CSS -->
    <link href="../plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="../admin/css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../admin/css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="../admin/css/colors/blue.css" id="theme" rel="stylesheet">
    <!-- Kayarent shared theme -->
    <link href="../css/kayarent-theme.css" rel="stylesheet">
    <!-- Client-specific overrides -->
    <link href="../client/css/client-style.css" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-pap2X1k6Yf3k1mQj3fV0Yxk1KQG1qZ1Qk1Q1Z1Q1Z1Q1Z1Q1Z1Q1Z1Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div id="wrapper">
        <!-- Navigation (shared look) -->
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="bg-white border-b border-gray-100">
                <div class="top-left-part">
                    <a class="logo" href="../client/dashboard.php">
                        <img src="../plugins/images/mainlogo.png" class="w-28 h-auto pt-2 bg-white object-contain" alt="RentaKaya" />
                    </a>
                </div>
                <button id="kaya-sidebar-toggle" class="btn btn-link visible-xs visible-sm" aria-label="Toggle sidebar" title="Toggle sidebar" style="border:0;background:transparent;color:inherit;margin-left:8px"><i class="ti-menu"></i></button>
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li class="nav-item dropdown right-side-toggle">
                        <a class="nav-link dropdown-toggle rounded-xl bg-[#A7634E] shadow d-flex align-items-center justify-content-center" href="#" id="clientSettingsDropdown" role="button" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Settings">
                            <i class="ti-settings"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="clientSettingsDropdown">
                            <li><a class="dropdown-item" href="settings.php"><i class="fa-regular fa-user mr-2"></i> Account settings</a></li>
                            <li><a class="dropdown-item" href="inbox.php"><i class="fa-regular fa-envelope mr-2"></i> Inbox</a></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fa-solid fa-sign-out-alt mr-2"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <script>
        (function(){
            var btn = document.getElementById('kaya-sidebar-toggle');
            if(btn){ btn.addEventListener('click', function(e){ e.preventDefault(); if(window.matchMedia('(max-width: 992px)').matches){ document.body.classList.toggle('sidebar-open'); } else { document.body.classList.toggle('sidebar-collapsed'); } }, {passive:false}); }
        })();
        </script>

                <!-- client pages should include a sidebar separately (client_left_panel.php) -->


<script>
// Client-side dynamic loader: sidebar links load pages into the main wrapper
(function(){
    function ajaxLoad(url, push){
        fetch(url, {headers:{'X-Requested-With':'XMLHttpRequest'}}).then(function(res){
            if(!res.ok) throw new Error('Network error');
            var ct = res.headers.get('content-type') || '';
            if(ct.indexOf('application/json') !== -1) return res.json();
            return res.text();
        }).then(function(data){
            // JSON response (e.g., logout)
            if(typeof data === 'object'){
                try {
                    if(data.suspended) document.body.classList.add('kaya-suspended');
                    if(data.redirect) window.location.href = data.redirect;
                } catch(e){ console.error(e); }
                return;
            }
            var html = data;
            var wrapper = document.getElementById('page-wrapper');
            if(wrapper){ wrapper.outerHTML = html; }
            else { document.body.insertAdjacentHTML('beforeend', html); }
            if(push!==false) history.pushState({url:url}, '', url);
            window.scrollTo(0,0);
            if(window.initClientPage) try{ window.initClientPage();}catch(e){}
        }).catch(function(err){ console.error(err); window.location.href = url; });
    }

    document.addEventListener('click', function(e){
        var a = e.target.closest && e.target.closest('#client-side-menu a');
        if(!a) return;
        var href = a.getAttribute('href');
        if(!href) return;
        if(href.indexOf('http')===0 && href.indexOf(location.origin)!==0) return;
        e.preventDefault();
        ajaxLoad(href, true);
    }, true);

    window.addEventListener('popstate', function(e){ if(e.state && e.state.url) ajaxLoad(e.state.url, false); });

})();
</script>
<script>
if(!window.initClientPage) window.initClientPage = function(){
    try {
        if(window.jQuery && jQuery().DataTable) {
            jQuery('table.table').each(function(){ if(!jQuery(this).hasClass('dataTable')) jQuery(this).DataTable({responsive:true}); });
        }
    } catch(e){ /* ignore */ }
};
</script>

<?php
// end of client header
