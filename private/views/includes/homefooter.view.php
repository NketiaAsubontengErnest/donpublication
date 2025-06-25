<!-- logo carousel -->
<div class="logo-carousel-section">
	<div class="container">

	</div>
</div>
<!-- end logo carousel -->

<!-- footer -->
<div class="footer-area">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-6">
				<div class="footer-box about-widget">
					<h2 class="widget-title">About us</h2>
					<p> Leading educational publisher in Ghana, providing textbooks and workbooks for Basic 1-9. Empowering young minds with quality resources.</p>
				</div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="footer-box get-in-touch">
					<h2 class="widget-title">Get in Touch</h2>
					<ul>
						<li>Accra Ghana.</li>
						<li><a href="mailto:donseriesgh1.gmail.com">donseriesgh1.gmail.com</a></li>
						<li><a href="tel:+233244402913">+233 244 402 913</a></li>
						<li><a href="tel:+233550007711">+233 550 007 711</a></li>
						<li><a href="tel:+233550007722">+233 550 007 722</a></li>
					</ul>
				</div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="footer-box pages">
					<h2 class="widget-title">Pages</h2>
					<ul>
						<li><a href="<?= HOME ?>/home">Home</a></li>
						<li><a href="<?= HOME ?>/home/about">About</a></li>
						<li><a href="<?= HOME ?>/home/shop">Shop</a></li>
						<li><a href="<?= HOME ?>/home/contact">Contact</a></li>
					</ul>
				</div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="footer-box subscribe">
					<h2 class="widget-title">Subscribe</h2>
					<p>Subscribe to our mailing list to get the latest updates.</p>
					<form action="index.html">
						<input type="email" placeholder="Email">
						<button type="submit"><i class="fas fa-paper-plane"></i></button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end footer -->

<!-- copyright -->
<div class="copyright">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 col-md-12">
				<p>Copyrights &copy; <script>
						document.write(new Date().getFullYear())
					</script> - ANEK TECH, All rights reserved.
					<br>Design by <a href="https://ernestnketiaasubonteng.netlify.app/" target="_blank" title="free css templates">ANEK TECH</a>
				</p>
			</div>
			<div class="col-lg-6 text-right col-md-12">
				<div class="social-icons">
					<ul>
						<li><a href="https://www.facebook.com/profile.php?id=61576605176507&mibextid=ZbWKwL" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
						<li><a href="https://x.com/don_publication?t=6N2P7YZg9fznAOSmq2x-mw&s=09" target="_blank"><i class="fab fa-twitter"></i></a></li>
						<li><a href="https://www.instagram.com/don_series0?igsh=MWt3eGNwZjBlajk2Zg==" target="_blank"><i class="fab fa-instagram"></i></a></li>
						<li><a href="www.linkedin.com/in/don-series-5b33a8302" target="_blank"><i class="fab fa-linkedin"></i></a></li>
						<li><a href="https://www.tiktok.com/@don_series0?_t=ZS-8wT77bye4Bj&_r=1" target="_blank"><i class="bi bi-tiktok"></i></a></li>
						<li><a href="https://youtube.com/@don_series0?si=3yp6Fh1do0Yk7mZZ" target="_blank"><i class="fa fa-youtube"></i></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end copyright -->

<!-- jquery -->
<script src="<?= HOMEASSET ?>/assets/js/jquery-1.11.3.min.js"></script>
<!-- bootstrap -->
<script src="<?= HOMEASSET ?>/assets/bootstrap/js/bootstrap.min.js"></script>
<!-- count down -->
<script src="<?= HOMEASSET ?>/assets/js/jquery.countdown.js"></script>
<!-- isotope -->
<script src="<?= HOMEASSET ?>/assets/js/jquery.isotope-3.0.6.min.js"></script>
<!-- waypoints -->
<script src="<?= HOMEASSET ?>/assets/js/waypoints.js"></script>
<!-- owl carousel -->
<script src="<?= HOMEASSET ?>/assets/js/owl.carousel.min.js"></script>
<!-- magnific popup -->
<script src="<?= HOMEASSET ?>/assets/js/jquery.magnific-popup.min.js"></script>
<!-- mean menu -->
<script src="<?= HOMEASSET ?>/assets/js/jquery.meanmenu.min.js"></script>
<!-- sticker js -->
<script src="<?= HOMEASSET ?>/assets/js/sticker.js"></script>
<!-- main js -->
<script src="<?= HOMEASSET ?>/assets/js/main.js"></script>

<script>
	//according to loftblog tut
	$('.nav li:first').addClass('active');

	var showSection = function showSection(section, isAnimate) {
		var
			direction = section.replace(/#/, ''),
			reqSection = $('.section').filter('[data-section="' + direction + '"]'),
			reqSectionPos = reqSection.offset().top - 0;

		if (isAnimate) {
			$('body, html').animate({
					scrollTop: reqSectionPos
				},
				800);
		} else {
			$('body, html').scrollTop(reqSectionPos);
		}

	};

	var checkSection = function checkSection() {
		$('.section').each(function() {
			var
				$this = $(this),
				topEdge = $this.offset().top - 80,
				bottomEdge = topEdge + $this.height(),
				wScroll = $(window).scrollTop();
			if (topEdge < wScroll && bottomEdge > wScroll) {
				var
					currentId = $this.data('section'),
					reqLink = $('a').filter('[href*=\\#' + currentId + ']');
				reqLink.closest('li').addClass('active').
				siblings().removeClass('active');
			}
		});
	};

	$('.responsive-menu, .scroll-to-section').on('click', 'a', function(e) {
		e.preventDefault();
		showSection($(this).attr('href'), true);
	});

	$(window).scroll(function() {
		checkSection();
	});
</script>

</body>

</html>