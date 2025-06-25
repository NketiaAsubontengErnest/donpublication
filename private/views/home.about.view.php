<?php $this->view('includes/homeheader', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>

<!-- breadcrumb-section -->
<div class='breadcrumb-section breadcrumb-bg'>
    <div class='container'>
        <div class='row'>
            <div class='col-lg-8 offset-lg-2 text-center'>
                <div class='breadcrumb-text'>
                    <p>We sale Textbooks and Workbooks</p>
                    <h1>About Us</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->

<!-- featured section -->
<div class='feature-bg'>
    <div class='container'>
        <div class='row'>
            <div class='col-lg-7'>
                <div class='featured-text'>
                    <h2 class='pb-3'>Why Choose <span class='orange-text'>Don Series Books</span></h2>
                    <div class='row'>
                        <div class='col-lg-6 col-md-6 mb-4 mb-md-5'>
                            <div class='list-box d-flex'>
                                <div class='list-icon'>
                                    <i class='fas fa-shipping-fast'></i>
                                </div>
                                <div class='content'>
                                    <h3>Nationwide Delivery</h3>
                                    <p>We offer prompt and secure delivery of Don Series textbooks and workbooks directly to schools and bookshops across the country.</p>
                                </div>
                            </div>
                        </div>
                        <div class='col-lg-6 col-md-6 mb-5 mb-md-5'>
                            <div class='list-box d-flex'>
                                <div class='list-icon'>
                                    <i class='fas fa-money-bill-alt'></i>
                                </div>
                                <div class='content'>
                                    <h3>Competitive Pricing</h3>
                                    <p>Our books are priced with bulk buyers in mind, ensuring great value for schools and resellers without sacrificing quality.</p>
                                </div>
                            </div>
                        </div>
                        <div class='col-lg-6 col-md-6 mb-5 mb-md-5'>
                            <div class='list-box d-flex'>
                                <div class='list-icon'>
                                    <i class='fas fa-briefcase'></i>
                                </div>
                                <div class='content'>
                                    <h3>Complete Book Sets</h3>
                                    <p>We supply both textbooks and workbooks for a full learning experience — perfect for structured classroom use.</p>
                                </div>
                            </div>
                        </div>
                        <div class='col-lg-6 col-md-6'>
                            <div class='list-box d-flex'>
                                <div class='list-icon'>
                                    <i class='fas fa-sync-alt'></i>
                                </div>
                                <div class='content'>
                                    <h3>Return Support</h3>
                                    <p>Wrong order or damaged stock? We make replacements easy with our efficient returns process for schools and distributors.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end featured section -->

<!-- shop banner -->
<section class='shop-banner'>
    <div class='container'>
        <h3>Sale is on! <br> with big <span class='orange-text'>Discount...</span></h3>
        <div class='sale-percent'><span>Sale! <br> Upto</span>20% <span>off</span></div>
        <a href='shop.html' class='cart-btn btn-lg'>Shop Now</a>
    </div>
</section>
<!-- end shop banner -->

<!-- team section -->

<!-- end team section -->

<?php $this->view('includes/homefooter'/*, [ 'crumbs'=>$crumbs, 'actives'=>$actives ]*/) ?>