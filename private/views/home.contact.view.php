<?php $this->view('includes/homeheader', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>


<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 offset-lg-2 text-center">
				<div class="breadcrumb-text">
					<p>Get 24/7 Support</p>
					<h1>Contact us</h1>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end breadcrumb section -->

<!-- contact form -->
<div class="contact-from-section mt-150 mb-150">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 mb-5 mb-lg-0">
				<div class="form-title">
					<h2>Have a Question?</h2>
					<p>If you need more information about Don Series textbooks, workbooks, or bulk ordering for your school or bookshop, we’re here to help. Reach out to us — our team is ready to assist you.</p>
				</div>

				<div id="form_status"></div>
				<div class="contact-form">
					<form type="POST" method="post" id="fruitkha-contact" onSubmit="return valid_datas( this );">
						<p>
							<input type="text" placeholder="Name" name="name" id="name">
							<input type="email" placeholder="Email" name="email" id="email">
						</p>
						<p>
							<input type="tel" placeholder="Phone" name="phone" id="phone">
							<input type="text" placeholder="Location" name="location" id="location">
						</p>
						<p><textarea name="message" id="message" cols="30" rows="10" placeholder="Message"></textarea></p>
						<input type="hidden" name="token" value="FsWga4&@f6aw" />
						<p><input type="submit" value="Submit"></p>
					</form>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="contact-form-wrap">
					<div class="contact-form-box">
						<h4><i class="fas fa-map"></i>Address</h4>
						<p>Accra, Ghana</p>
					</div>
					<div class="contact-form-box">
						<h4><i class="far fa-clock"></i> Hours</h4>
						<p> 24/7 </p>
					</div>
					<div class="contact-form-box">
						<h4><i class="fas fa-address-book"></i> Contact</h4>
						<p><b>Email:</b> <a href="mailto:donseriesgh1.gmail.com">donseriesgh1.gmail.com</a></p>
						<p><b>Phone:</b>
							<br>
							<a href="tel:+233244402913">+233 244 402 913</a>
							<br>
							<a href="tel:+233550007711">+233 550 007 711</a>
							<br>
							<a href="tel:+233550007722">+233 550 007 722</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end contact form -->

<?php $this->view('includes/homefooter'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>