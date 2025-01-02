<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Don Publication</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="<?=ASSETS?>/vendors/feather/feather.css">
    <link rel="stylesheet" href="<?=ASSETS?>/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="<?=ASSETS?>/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="<?=ASSETS?>/css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="<?=ASSETS?>/images/donLOGO.png" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>

</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <img src="<?=ASSETS?>/images/dondesign.png" alt="logo">
                            </div>
                            <h4>Use your Staff Number and password</h4>
                            <h6 class="font-weight-light">Sign in to continue.</h6>
                            <?php if(isset($errors['authlogin'])):?>
                            <div class="alert alert-warning alert-dismissible" role="alert">
                                <?=$errors['authlogin']?>
                            </div>
                            <?php endif;?>
                            <?php if(isset($errors['blocked'])):?>
                            <div class="alert alert-warning alert-dismissible" role="alert">
                                <?=$errors['blocked']?>
                            </div>
                            <?php endif;?>
                            <form class="pt-3" method="post">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" id="exampleInputEmail1"
                                        name="username" placeholder="Username" required>
                                </div>
                                <div class="input-group mb-3">
                                    <input class="form-control form-control-lg password " id="password" 
                                        type="password" name="password" value="" placeholder="Password" required />
                                    <span class="input-group-text togglePassword" style="height:50px" id="">
                                        <i data-feather="eye" style="cursor: pointer; "></i>
                                    </span>
                                    
                                </div>
                                <script>
                                    feather.replace({
                                        'aria-hidden': 'true'
                                    });

                                    $(".togglePassword").click(function(e) {
                                        e.preventDefault();
                                        var type = $(this).parent().parent().find(".password").attr("type");
                                        console.log(type);
                                        if (type == "password") {
                                            $("svg.feather.feather-eye").replaceWith(feather.icons["eye-off"]
                                                .toSvg());
                                            $(this).parent().parent().find(".password").attr("type", "text");
                                        } else if (type == "text") {
                                            $("svg.feather.feather-eye-off").replaceWith(feather.icons["eye"]
                                                .toSvg());
                                            $(this).parent().parent().find(".password").attr("type",
                                                "password");
                                        }
                                    });
                                    </script>
                                <button class="btn btn-primary col-md-12">LOGIN</button>
                                <center>
                                    <div class="mt-3">
                                        <a href="<?=HOME?>/home">Go to home page</a>
                                    </div>
                                </center>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="<?=ASSETS?>/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="<?=ASSETS?>/js/off-canvas.js"></script>
    <script src="<?=ASSETS?>/js/hoverable-collapse.js"></script>
    <script src="<?=ASSETS?>/js/template.js"></script>
    <script src="<?=ASSETS?>/js/settings.js"></script>
    <script src="<?=ASSETS?>/js/todolist.js"></script>
    <!-- endinject -->
</body>

</html>