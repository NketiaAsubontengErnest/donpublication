<?php $this->view('includes/header', ['crumbs'=>isset($crumbs) ? $crumbs : array(), 'actives'=>isset($actives) ? $actives : "", 'hiddenSearch'=>isset($hiddenSearch) ? $hiddenSearch : "yep",])?>
<div class="content-wrapper d-flex align-items-center text-center error-page bg-primary">
    <div class="row flex-grow">
        <div class="col-lg-7 mx-auto text-white">
            <div class="row align-items-center d-flex flex-row">
                <div class="col-lg-12 pr-lg-4">
                    <h3 class="display-1 mb-0">Access - Denied</h1>
                </div>
                <div class="col-lg-6 error-page-divider text-lg-left pl-lg-4">
                    <h2>SORRY!</h2>
                    <h3 class="font-weight-light">The page you’re looking for is not accessible for you.</h3>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12 text-center mt-xl-2">
                    <?php $this->view('patials/back.patial')?>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/)?>