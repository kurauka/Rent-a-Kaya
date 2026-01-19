<!-- Left navbar-header (modernized) -->
<style>
/* Responsive sidebar styles: collapsed desktop and off-canvas mobile */
:root{--kaya-sidebar-width:240px;--kaya-sidebar-collapsed:72px}
.sidebar{width:var(--kaya-sidebar-width);transition:width .22s ease,transform .22s ease;padding-top:8px}
.sidebar .nav-link{white-space:nowrap}
body.sidebar-collapsed .sidebar{width:var(--kaya-sidebar-collapsed)}
/* hide text labels when collapsed */
body.sidebar-collapsed .sidebar .nav-link span, body.sidebar-collapsed .sidebar .user-pro div{display:none}
body.sidebar-collapsed .sidebar .user-pro img{margin:0 auto}
/* mobile off-canvas */
@media (max-width: 991px){
    .sidebar{position:fixed;left:0;top:0;bottom:0;z-index:1040;height:100vh;transform:translateX(-100%);box-shadow:2px 0 10px rgba(0,0,0,0.08)}
    body.sidebar-open .sidebar{transform:translateX(0)}
    /* ensure main content is scrollable behind overlay */
    #kaya-sidebar-backdrop{display:none}
    body.sidebar-open #kaya-sidebar-backdrop{display:block;position:fixed;inset:0;background:rgba(0,0,0,0.35);z-index:1030}
}
/* small visual tweaks */
.sidebar .kaya-card{background:transparent;border:none}
.sidebar .nav-link{color:var(--kaya-text,#0f172a)}
</style>
<aside class="navbar-default sidebar kaya-card" role="navigation" aria-label="Main sidebar">
    <div class="sidebar-nav navbar-collapse slimscrollsidebar">
     

        <div class="p-4 user-pro" style="border-bottom:1px solid rgba(2,24,54,0.04);">
            <div style="display:flex;align-items:center;gap:10px">
                <img src="../plugins/images/user.jpg" alt="user-img" class="img-circle" style="width:44px;height:44px;border-radius:8px;object-fit:cover">
                <div>
                    <div style="font-weight:600;color:#0f172a"><?php echo htmlspecialchars($username); ?></div>
                    <a href="settings.php" style="font-size:12px;color:var(--kaya-muted)">Account settings</a>
                </div>
            </div>
        </div>

        <nav class="p-3" role="navigation">
            <ul class="nav flex-column" id="side-menu">
                <li class="nav-item mb-2">
                    <a class="nav-link kaya-card" href="index.php" data-label="Dashboard" style="display:flex;align-items:center;gap:12px;padding:12px;position:relative">
                        <i class="fa fa-tachometer kaya-icon" aria-hidden="true" style="width:24px;text-align:center"></i>
                        <span class="menu-label">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <details>
                        <summary class="nav-link kaya-card" data-label="Houses" style="display:flex;align-items:center;gap:12px;padding:12px;cursor:pointer;position:relative">
                            <i class="fa fa-building" aria-hidden="true" style="width:24px;text-align:center"></i>
                            <span>Houses</span>
                        </summary>
                        <ul class="nav flex-column" style="margin-left:18px;margin-top:8px">
                            <li class="nav-item"><a class="nav-link" href="new-house.php">Add a house</a></li>
                            <li class="nav-item"><a class="nav-link" href="houses.php">View houses</a></li>
                        </ul>
                    </details>
                </li>

                <li class="nav-item">
                    <details>
                        <summary class="nav-link kaya-card" data-label="Tenants" style="display:flex;align-items:center;gap:12px;padding:12px;cursor:pointer;position:relative">
                            <i class="fa fa-users" aria-hidden="true" style="width:24px;text-align:center"></i>
                            <span>Tenants</span>
                        </summary>
                        <ul class="nav flex-column" style="margin-left:18px;margin-top:8px">
                            <li class="nav-item"><a class="nav-link" href="new-tenant.php">Add tenant</a></li>
                            <li class="nav-item"><a class="nav-link" href="tenants.php">View tenants</a></li>
                        </ul>
                    </details>
                </li>

                <li class="nav-item">
                    <details>
                        <summary class="nav-link kaya-card" data-label="Invoices" style="display:flex;align-items:center;gap:12px;padding:12px;cursor:pointer;position:relative">
                            <i class="fa fa-credit-card" aria-hidden="true" style="width:24px;text-align:center"></i>
                            <span>Invoices</span>
                        </summary>
                        <ul class="nav flex-column" style="margin-left:18px;margin-top:8px">
                            <li class="nav-item"><a class="nav-link" href="new-invoice.php">Add invoice</a></li>
                            <li class="nav-item"><a class="nav-link" href="invoices.php">View invoices</a></li>
                        </ul>
                    </details>
                </li>

                <li class="nav-item">
                    <details>
                        <summary class="nav-link kaya-card" data-label="Payments" style="display:flex;align-items:center;gap:12px;padding:12px;cursor:pointer;position:relative">
                            <i class="fa fa-money" aria-hidden="true" style="width:24px;text-align:center"></i>
                            <span>Payments</span>
                        </summary>
                        <ul class="nav flex-column" style="margin-left:18px;margin-top:8px">
                            <li class="nav-item"><a class="nav-link" href="new-payment.php">New payment</a></li>
                            <li class="nav-item"><a class="nav-link" href="payments.php">View payments</a></li>
                        </ul>
                    </details>
                </li>

                <li class="nav-item mb-2"><a class="nav-link kaya-card" href="inbox.php" data-label="Messages" style="display:flex;align-items:center;gap:12px;padding:12px;position:relative"><i class="fa fa-envelope" style="width:24px;text-align:center"></i><span>Messages</span></a></li>

                <li class="nav-item">
                    <details>
                        <summary class="nav-link kaya-card" data-label="Blog" style="display:flex;align-items:center;gap:12px;padding:12px;cursor:pointer;position:relative">
                            <i class="fa fa-newspaper-o" aria-hidden="true" style="width:24px;text-align:center"></i>
                            <span>Blog</span>
                        </summary>
                        <ul class="nav flex-column" style="margin-left:18px;margin-top:8px">
                            <li class="nav-item"><a class="nav-link" href="posts.php">All posts</a></li>
                            <li class="nav-item"><a class="nav-link" href="new-post.php">Create post</a></li>
                            <li class="nav-item"><a class="nav-link" href="comments.php">Comments</a></li>
                        </ul>
                    </details>
                </li>

                <li class="nav-item mb-2"><a class="nav-link kaya-card" href="new-location.php" data-label="Locations" style="display:flex;align-items:center;gap:12px;padding:12px;position:relative"><i class="fa fa-map-marker" style="width:24px;text-align:center"></i><span>Locations</span></a></li>

                <li class="nav-item nav-small-cap mt-3" style="margin-top:10px;color:var(--kaya-muted);font-size:12px">Other</li>

                <li class="nav-item">
                    <details>
                        <summary class="nav-link kaya-card" data-label="Accounts" style="display:flex;align-items:center;gap:12px;padding:12px;cursor:pointer;position:relative">
                            <i class="fa fa-cogs" aria-hidden="true" style="width:24px;text-align:center"></i>
                            <span>Accounts</span>
                        </summary>
                        <ul class="nav flex-column" style="margin-left:18px;margin-top:8px">
                            <li class="nav-item"><a class="nav-link" href="users.php">Administrators</a></li>
                            <li class="nav-item"><a class="nav-link" href="new-user.php">Create admin</a></li>
                        </ul>
                    </details>
                </li>

                <li class="nav-item mt-3"><a class="nav-link kaya-card" href="functions/logout.php" data-label="Log out" style="display:flex;align-items:center;gap:12px;padding:12px;position:relative"><i class="fa fa-sign-out" style="width:24px;text-align:center"></i><span>Log out</span></a></li>
            </ul>
        </nav>
    </div>
</aside>
<!-- Left navbar-header end -->

