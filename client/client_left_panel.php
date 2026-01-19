<!-- Client left panel (read-only) -->
<aside class="navbar-default sidebar kaya-card" role="navigation" aria-label="Client sidebar">
    <div class="sidebar-nav navbar-collapse slimscrollsidebar">
        <div class="p-4 user-pro" style="border-bottom:1px solid rgba(2,24,54,0.04);">
            <div style="display:flex;align-items:center;gap:10px">
                <img src="../plugins/images/user.jpg" alt="user-img" class="img-circle" style="width:44px;height:44px;border-radius:8px;object-fit:cover">
                <div>
                    <div class="client-username"><?php echo htmlspecialchars($username ?? 'Client'); ?></div>
                    <a href="profile.php" class="profile-link">Profile</a>
                </div>
            </div>
        </div>

        <nav class="p-3" role="navigation">
            <ul class="nav flex-column" id="client-side-menu">
                <li class="nav-item mb-2">
                    <a class="nav-link kaya-card" href="dashboard.php" style="display:flex;align-items:center;gap:12px;padding:12px;position:relative">
                        <i class="fa fa-tachometer kaya-icon" aria-hidden="true" style="width:24px;text-align:center"></i>
                        <span class="menu-label">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link kaya-card" href="invoices.php" style="display:flex;align-items:center;gap:12px;padding:12px;position:relative">
                        <i class="fa fa-credit-card kaya-icon" aria-hidden="true" style="width:24px;text-align:center"></i>
                        <span>Invoices</span>
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link kaya-card" href="payment.php" style="display:flex;align-items:center;gap:12px;padding:12px;position:relative">
                        <i class="fa fa-money kaya-icon" aria-hidden="true" style="width:24px;text-align:center"></i>
                        <span>Payments</span>
                    </a>
                </li>

                <li class="nav-item mb-2"><a class="nav-link kaya-card" href="inbox.php" style="display:flex;align-items:center;gap:12px;padding:12px;position:relative"><i class="fa fa-envelope kaya-icon" style="width:24px;text-align:center"></i><span>Messages</span></a></li>

                <li class="nav-item mb-2"><a class="nav-link kaya-card" href="notices.php" style="display:flex;align-items:center;gap:12px;padding:12px;position:relative"><i class="fa fa-newspaper-o kaya-icon" style="width:24px;text-align:center"></i><span>Notices</span></a></li>

                <li class="nav-item mt-3"><a class="nav-link kaya-card" href="logout.php" style="display:flex;align-items:center;gap:12px;padding:12px;position:relative"><i class="fa fa-sign-out kaya-icon" style="width:24px;text-align:center"></i><span>Log out</span></a></li>
            </ul>
        </nav>
    </div>
</aside>
