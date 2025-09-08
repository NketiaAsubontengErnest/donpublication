<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Best Basic 1 to Basic 9 textbooks and workbooks">

	<!-- title -->
	<title><?= COMPANY ?> | <?= ucfirst($actives) ?> </title>

	<!-- favicon -->
	<link rel="shortcut icon" type="image/png" href="<?= ASSETS ?>/images/donLOGO.png">
	<!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
	<!-- fontawesome -->
	<link rel="stylesheet" href="<?= HOMEASSET ?>/assets/css/all.min.css">
	<!-- bootstrap -->
	<link rel="stylesheet" href="<?= HOMEASSET ?>/assets/bootstrap/css/bootstrap.min.css">
	<!-- owl carousel -->
	<link rel="stylesheet" href="<?= HOMEASSET ?>/assets/css/owl.carousel.css">
	<!-- magnific popup -->
	<link rel="stylesheet" href="<?= HOMEASSET ?>/assets/css/magnific-popup.css">
	<!-- animate css -->
	<link rel="stylesheet" href="<?= HOMEASSET ?>/assets/css/animate.css">
	<!-- mean menu css -->
	<link rel="stylesheet" href="<?= HOMEASSET ?>/assets/css/meanmenu.min.css">
	<!-- main style -->
	<link rel="stylesheet" href="<?= HOMEASSET ?>/assets/css/main.css">
	<!-- responsive -->
	<link rel="stylesheet" href="<?= HOMEASSET ?>/assets/css/responsive.css">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


	<style>
		.float {
			position: fixed;
			bottom: 20px;
			right: 20px;
			background-color: #25D366;
			color: #FFF;
			border-radius: 50px;
			padding: 10px;
			text-align: center;
			font-weight: bold;
			font-family: 'Poppins', sans-serif;
			font-weight: 600;
			display: flex;
			justify-content: center;
			align-items: center;
			text-decoration: none;
			border: 2px solid #25D366;
			border-radius: 50%;
			box-shadow: 2px 2px 3px #999;
			width: 50px;
			height: 50px;
			font-size: 24px;
			z-index: 100;
			transition: background-color 0.3s ease;

		}

		.float:hover {
			background-color: #1DA335;
		}
	</style>

</head>

<body>


	<a href="https://wa.me/+233551666224?text=Hello%20Don%20Series,%20I'd%20like%20to%20know%20more%20about%20your%20books." target="_blank" class="float">
		<i class="fa fa-whatsapp"></i>
	</a>


	<!--PreLoader-->
	<div class="loader">
		<div class="loader-inner">
			<div class="circle"></div>
		</div>
	</div>
	<!--PreLoader Ends-->

	<!-- header -->
	<div class="top-header-area" id="sticker">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-sm-12 text-center">
					<div class="main-menu-wrap">
						<!-- logo -->
						<div class="site-logo">
							<a href="<?= HOME ?>/">
								<img src="<?= HOMEASSET ?>/assets/img/_logo.png" alt="">
							</a>
						</div>
						<!-- logo -->

						<!-- menu start -->
						<nav class="main-menu">
							<ul>
								<li class="<?= $actives === 'home' ? 'current-list-item' : '' ?>"><a href="<?= HOME ?>/">Home</a></li>
								<li class="<?= $actives === 'about' ? 'current-list-item' : '' ?>"><a href="<?= HOME ?>/home/about">About</a></li>


								<li class="<?= $actives === 'contact' ? 'current-list-item' : '' ?>"><a href="https://donpublicationonline.com/contact-us/">Contact</a></li>
								<li class="<?= $actives === 'shop' ? 'current-list-item' : '' ?>"><a href="https://donpublicationonline.com/shop/">Shop</a></li>
								<li class="<?= $actives === 'shop' ? 'current-list-item' : '' ?>"><a href="https://donpublicationonline.com/gallery/">Gallery</a></li>
								<li class="<?= $actives === 'shop' ? 'current-list-item' : '' ?>"><a href="https://donpublicationonline.com/downloads/">Downloads</a></li>
								<li>
									<div class="header-icons">
										<a class="shopping-cart" href="cart.html"><i class="fas fa-shopping-cart"></i></a>
										<a class="shopping-cart" href="<?= HOME ?>/login"><i class="fas fa-lock"></i></a>
										<a class="mobile-hide search-bar-icon" href="#"><i class="fas fa-search"></i></a>
										<a class="shopping-cart" target="_blank" href="<?= HOME ?>/webmail"><i class="fas fa-envelope"></i></a>
									</div>
								</li>
							</ul>
						</nav>
						<a class="mobile-show search-bar-icon" href="#"><i class="fas fa-search"></i></a>
						<div class="mobile-menu"></div>
						<!-- menu end -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end header -->

	<!-- search area -->
	<div class="search-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<span class="close-btn"><i class="fas fa-window-close"></i></span>
					<div class="search-bar">
						<div class="search-bar-tablecell">
							<h3>Search For:</h3>
							<input type="text" placeholder="Keywords">
							<button type="submit">Search <i class="fas fa-search"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end search area -->