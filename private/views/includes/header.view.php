<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Don - Pub|Dashboard</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="<?=ASSETS?>/vendors/feather/feather.css">
    <link rel="stylesheet" href="<?=ASSETS?>/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="<?=ASSETS?>/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <link rel="stylesheet" href="<?=ASSETS?>/css/jquerys/jquery-ui.css">
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="<?=ASSETS?>/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="<?=ASSETS?>/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="<?=ASSETS?>/js/select.dataTables.min.css">
    <link rel="stylesheet" href="<?=ASSETS?>/vendors/mdi/css/materialdesignicons.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="<?=ASSETS?>/css/vertical-layout-light/style.css">

    <link rel="stylesheet" href="<?=ASSETS?>/css/essential.css">

    <script src="<?=ASSETS?>/js/jquerys/jquery-1.12.4.js"></script>
    <script src="<?=ASSETS?>/js/jquerys/jquery-ui.js"></script>

    <link href="<?=ASSETS?>/library/bootstrap-5/bootstrap.min.css" rel="stylesheet" />
    <script src="<?=ASSETS?>/library/bootstrap-5/bootstrap.bundle.min.js"></script>
    <script src="<?=ASSETS?>/library/dselect.js"></script>
    <!-- endinject -->
    <link rel="shortcut icon" href="<?=ASSETS?>/images/donLOGO.png" />

   

</head>

<body>

    <div class="container-scroller">      

        <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-5" href="<?=HOME?>/dashboard"><img src="<?=ASSETS?>/images/dondesign.png"
                class="mr-2" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="<?=HOME?>/dashboard"><img src="<?=ASSETS?>/images/donLOGO.png"
                alt="logo" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
                <a href="#" class="mx-5"  onclick="window.history.back(); return false;" > <i class="fa fa-arrow-left "> <b>Back</b></i> </a>
                              
                <ul class="navbar-nav navbar-nav-right">                    
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                            <?php
                            $image = ASSETS."/images/male_user.png";
                            
                            if (Auth::get_image()){
                                $image = ROOT ."/".Auth::get_image();
                            }
                            ?>
                            <img id="dp1" src="<?=$image?>" class="user-image" style="color: white" alt="User Image"/>
                            <i class="m-2"><?=esc(Auth::getFirstname())?></i>
                        </a>
                        
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                            aria-labelledby="profileDropdown">
                            <a href="<?=HOME?>/profile" class="dropdown-item">
                                <i class="ti-settings text-primary"></i>
                                Profile
                            </a>
                            <a href="<?=HOME?>/logout" class="dropdown-item">
                                <i class="ti-power-off text-primary"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="icon-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_settings-panel.html -->
            
            
            <!-- partial -->
            <?php $this->view('includes/sidebar',['actives'=>$actives])?>
            <!-- partial -->
            <div class="main-panel">