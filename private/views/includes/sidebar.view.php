 <!-- partial:partials/_sidebar.html -->
 <nav class="sidebar sidebar-offcanvas" id="sidebar">
     <ul class="nav">
         <li class="nav-item <?= $actives == 'dashboard' ? 'active' : '' ?>">
             <a class="nav-link" href="<?= HOME ?>/dashboard">
                 <i class="icon-grid menu-icon"></i>
                 <span class="menu-title">Dashboard</span>
             </a>
         </li>
         <?php if (Auth::getRank() == 'marketer' || Auth::getRank() == 'stores') : ?>
             <li class="nav-item <?= $actives == 'order' ? 'active' : '' ?>">
                 <a class="nav-link" data-toggle="collapse" href="#ui-orders">
                     <i class="mdi mdi-cart menu-icon"></i>
                     <span class="menu-title"> Orders</span>
                     <i class="menu-arrow"></i>
                 </a>
                 <div class="collapse" id="ui-orders">
                     <ul class="nav flex-column sub-menu">
                         <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/orders">Order List</a></li>
                         <?php if (Auth::access('stores')) : ?>
                             <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/orders/issued">Issued Orders</a></li>
                         <?php endif ?>
                         <li class="nav-item"><a class="nav-link" href="<?= HOME ?>/returns/pending">Pending Returns</a></li>
                         <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/returns">Returned Orders</a></li>
                     </ul>
                 </div>
             </li>
         <?php endif; ?>
         <?php if (Auth::getRank() == 'marketer') : ?>
             <li class="nav-item <?= $actives == 'customers' ? 'active' : '' ?>">
                 <a class="nav-link" href="<?= HOME ?>/customers">
                     <i class="mdi mdi-account-multiple menu-icon"></i>
                     <span class="menu-title">Customers</span>
                 </a>
             </li>
             <li class="nav-item <?= $actives == 'smpcustomers' ? 'active' : '' ?>">
                 <a class="nav-link" href="<?= HOME ?>/customers/visited">
                     <i class="mdi mdi-human-greeting menu-icon"></i>
                     <span class="menu-title">Sample Customers</span>
                 </a>
             </li>
         <?php endif; ?>
         <?php if (Auth::getRank() == 'verification' || Auth::getRank() == 'account') : ?>
             <li class="nav-item <?= $actives == 'order' ? 'active' : '' ?>">
                 <a class="nav-link" data-toggle="collapse" href="#verification" aria-expanded="false" aria-controls="verification">
                     <i class="mdi mdi-book-open menu-icon"></i>
                     <span class="menu-title">Verification</span>
                     <i class="menu-arrow"></i>
                 </a>
                 <div class="collapse" id="verification">
                     <ul class="nav flex-column sub-menu">
                         <li class="nav-item"><a class="nav-link" href="<?= HOME ?>/orders/pending">Pending</a></li>
                         <li class="nav-item"><a class="nav-link" href="<?= HOME ?>/orders/verified">Verified Orders</a></li>
                         <li class="nav-item"><a class="nav-link" href="<?= HOME ?>/returns/pending">Pending Returns</a></li>
                         <li class="nav-item"><a class="nav-link" href="<?= HOME ?>/returns/verified">Verified Returns</a>
                         </li>
                     </ul>
                 </div>
             </li>
         <?php endif; ?>
         <?php if (Auth::access('marketer') || Auth::access('verification')) : ?>
             <li class="nav-item">
                 <a class="nav-link" data-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                     <i class="mdi mdi-briefcase menu-icon"></i>
                     <span class="menu-title">Accounts</span>
                     <i class="menu-arrow"></i>
                 </a>
                 <div class="collapse" id="tables">
                     <ul class="nav flex-column sub-menu">
                         <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/orders/salesent">Sales Entry</a></li>
                         <?php if (Auth::getRank() == 'verification' || Auth::getRank() == 'account'): ?>
                             <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/orders/special">Special Entry</a></li>
                         <?php endif ?>
                         <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/payments">Payment Entry</a></li>
                         <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/orders/pendingentry">Pending Entry</a></li>

                     </ul>
                 </div>
             </li>
         <?php endif; ?>
         <?php if (Auth::access('marketer') || Auth::access('verification')) : ?>
             <li class="nav-item">
                 <a class="nav-link" data-toggle="collapse" href="#tabla" aria-expanded="false" aria-controls="tabla">
                     <i class="mdi mdi-briefcase menu-icon"></i>
                     <span class="menu-title">Income & Expends</span>
                     <i class="menu-arrow"></i>
                 </a>
                 <div class="collapse" id="tabla">
                     <ul class="nav flex-column sub-menu">
                         <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/accountings/expenditures">Expenditure Entry</a></li>
                         <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/accountings/incomes">Incomes</a></li>
                         <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/accountings/expendituretype">Expenditure Type</a></li>

                     </ul>
                 </div>
             </li>
         <?php endif; ?>
         <?php if (Auth::access('g-account')) : ?>
             <li class="nav-item <?= $actives == 'audits' ? 'active' : '' ?>">
                 <a class="nav-link" data-toggle="collapse" href="#ui-markcon">
                     <i class="mdi mdi-book menu-icon"></i>
                     <span class="menu-title">Auditing</span>
                     <i class="menu-arrow"></i>
                 </a>
                 <div class="collapse <?= $actives == 'audits' ? 'show' : '' ?>" id="ui-markcon">
                     <ul class="nav flex-column sub-menu">
                         <li class="nav-item">
                             <a class="nav-link" href="<?= HOME ?>/audits/unaudited">UnAudited</a>
                         </li>
                         <li class="nav-item">
                             <a class="nav-link" href="<?= HOME ?>/audits/findings">Findings</a>
                         </li>
                         <li class="nav-item">
                             <a class="nav-link" href="<?= HOME ?>/audits/audited">Audited</a>
                         </li>
                     </ul>
                 </div>
             </li>

         <?php endif; ?>
         <?php if (Auth::access('stores')) : ?>
             <li class="nav-item">
                 <a class="nav-link" data-toggle="collapse" href="#icons">
                     <i class="mdi mdi-book-open-page-variant menu-icon"></i>
                     <span class="menu-title">Books</span>
                     <i class="menu-arrow"></i>
                 </a>
                 <div class="collapse" id="icons">
                     <ul class="nav flex-column sub-menu">
                         <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/books">List of Books</a></li>
                         <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/subjects">Subject / Class</a></li>
                         <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/subjects/summary">Books Summary</a></li>
                         <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/books/reorderbooks">Reorder Books</a></li>
                     </ul>
                 </div>
             </li>
         <?php endif; ?>


         <?php if (Auth::access('marketer') || Auth::access('verification')) : ?>
             <li class="nav-item <?= $actives == 'payments' ? 'active' : '' ?>">
                 <a class="nav-link" data-toggle="collapse" href="#report-element-" aria-expanded="false" aria-controls="report-element">
                     <i class="mdi mdi-chart-line menu-icon"></i>
                     <span class="menu-title">Report</span>
                     <i class="menu-arrow"></i>
                 </a>
                 <div class="collapse <?= $actives == 'payments' ? 'show' : '' ?>" id="report-element-">
                     <ul class="nav flex-column sub-menu">
                         <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/payments/officerssales"><?= Auth::getRank() == 'marketer' ? 'My Summary' : 'Officers Sales' ?></a></li>
                         <?php if (Auth::access('account')) : ?>
                             <li class="nav-item"><a class="nav-link" href="<?= HOME ?>/payments/customerreport">Customers Report</a></li>
                             <li class="nav-item"><a class="nav-link" href="<?= HOME ?>/payments/genreports">General Report</a></li>
                             <li class="nav-item"><a class="nav-link" href="<?= HOME ?>/payments/sumreports">Summary Report</a></li>
                             <li class="nav-item"><a class="nav-link" href="<?= HOME ?>/payments/reginals">Reginal Report</a></li>
                             <li class="nav-item"><a class="nav-link" href="<?= HOME ?>/payments/booksales">Book Sales Report</a></li>
                             <li class="nav-item"><a class="nav-link" href="<?= HOME ?>/payments/tithe">Tithe Report</a></li>
                             <li class="nav-item"><a class="nav-link" href="<?= HOME ?>/payments/allpayments">All Payments</a></li>
                         <?php endif ?>
                     </ul>
                 </div>
             </li>
         <?php endif; ?>

         <?php if (Auth::access('director')) : ?>
             <li class="nav-item">
                 <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                     <i class="mdi mdi-account-switch menu-icon"></i>
                     <span class="menu-title">Employees</span>
                     <i class="menu-arrow"></i>
                 </a>
                 <div class="collapse" id="auth">
                     <ul class="nav flex-column sub-menu">
                         <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/employee"> List of Users </a></li>
                     </ul>
                 </div>
             </li>

             <li class="nav-item">
                 <a class="nav-link" data-toggle="collapse" href="#error" aria-expanded="false" aria-controls="error">
                     <i class="mdi mdi-brightness-7 menu-icon"></i>
                     <span class="menu-title">Setup</span>
                     <i class="menu-arrow"></i>
                 </a>
                 <div class="collapse" id="error">
                     <ul class="nav flex-column sub-menu">
                         <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/setups/season"> Seasons </a></li>
                         <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/setups/ordertype"> Order Type </a></li>
                     </ul>
                 </div>
             </li>
         <?php endif; ?>

         <?php if (Auth::access('stores')) : ?>
             <li class="nav-item <?= $actives == 'logs' ? 'active' : '' ?>">
                 <a class="nav-link" data-toggle="collapse" href="#logs">
                     <i class="mdi mdi-book-open-variant menu-icon"></i>
                     <span class="menu-title">Activity Logs</span>
                     <i class="menu-arrow"></i>
                 </a>
                 <div class="collapse <?= $actives == 'logs' ? 'show' : '' ?>" id="logs">
                     <ul class="nav flex-column sub-menu">
                         <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/books/activities">Books Logs</a></li>
                         <?php if (Auth::getRank() == 'director' || Auth::getRank() == 'g-account') : ?>
                             <li class="nav-item"><a class="nav-link" href="<?= HOME ?>/setups/activitie">Approvals & Logs</a></li>
                         <?php endif; ?>
                     </ul>
                 </div>
             </li>
         <?php endif; ?>
         <?php if (Auth::access('stores')) : ?>
             <li class="nav-item <?= $actives == 'marketers' ? 'active' : '' ?>">
                 <a class="nav-link" data-toggle="collapse" href="#ui-mark">
                     <i class="mdi mdi-cart menu-icon"></i>
                     <span class="menu-title"> Marketing</span>
                     <i class="menu-arrow"></i>
                 </a>
                 <div class="collapse <?= $actives == 'marketers' ? 'show' : '' ?>" id="ui-mark">
                     <ul class="nav flex-column sub-menu">
                         <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/customers/market">Officer Market</a></li>
                         <li class="nav-item"> <a class="nav-link" href="<?= HOME ?>/customers/visited">Visited Customers</a></li>
                     </ul>
                 </div>
             </li>
         <?php endif; ?>
     </ul>
 </nav>