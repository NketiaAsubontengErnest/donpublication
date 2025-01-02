
<?php $this->view('includes/homeheader', ['crumbs'=>$crumbs, 'actives'=>$actives, 'hiddenSearch'=>$hiddenSearch,])?>
  <!-- ***** Main Banner Area Start ***** -->
  <section class="section main-banner" id="top" data-section="section1">
      <video autoplay muted loop id="bg-video">
          <source src="<?=HOMEASSET?>/assets/images/course-video.mp4" type="video/mp4" />
      </video>
      <div class="video-overlay header-text">
          <div class="container">
            <div class="row">
              <div class="col-lg-12">
                <div class="caption">
              <h2>Welcome to <?=COMPANY?></h2>
              <p>Our services: Educational consultation, in-service training, printing & sales of Educational textbooks.</p>
              <div class="main-button-red">
                  <div class="scroll-to-section"><a href="#contact">Order Now!</a></div>
              </div>
          </div>
              </div>
            </div>
          </div>
      </div>
  </section>
  
  <!-- ***** Main Banner Area End ***** -->

  <section class="services">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="owl-service-item owl-carousel">
          
            <div class="item">
              <div class="icon">
                <img src="<?=HOMEASSET?>/assets/images/service-icon-01.png" alt="">
              </div>
              <div class="down-content">
                <h4>Best Educational Books</h4>
                <p>We print and sale Basic 1 - Basic 9 Textbooks and Workbooks for almost all subjects.</p>
              </div>
            </div>
            
            <div class="item">
              <div class="icon">
                <img src="<?=HOMEASSET?>/assets/images/service-icon-02.png" alt="">
              </div>
              <div class="down-content">
                <h4>Best Educational Consultation</h4>
                <p>We give tips and stractagies on how to manage and stractagise your educational system.</p>
              </div>
            </div>
            
            <div class="item">
              <div class="icon">
                <img src="<?=HOMEASSET?>/assets/images/service-icon-03.png" alt="">
              </div>
              <div class="down-content">
                <h4>In-Service Training</h4>
                <p>We provide trainnig for teachers on new carriculom and new subjects.</p>
              </div>
            </div> 
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="our-courses" id="courses">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="section-heading">
            <h2>Our Popular Books</h2>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="owl-courses-item owl-carousel">
            <div class="item">
              <img src="<?=HOMEASSET?>/books/IMG-20240208-WA0104.jpg" alt="Course One">              
            </div>
            <div class="item">
              <img src="<?=HOMEASSET?>/books/IMG-20240208-WA0094.jpg" alt="Course One">              
            </div>

            <div class="item">
              <img src="<?=HOMEASSET?>/books/IMG-20240208-WA0095.jpg" alt="Course One">              
            </div>

            <div class="item">
              <img src="<?=HOMEASSET?>/books/IMG-20240208-WA0100.jpg" alt="Course One">              
            </div>

            <div class="item">
              <img src="<?=HOMEASSET?>/books/IMG-20240208-WA0126.jpg" alt="Course One">              
            </div>

            <div class="item">
              <img src="<?=HOMEASSET?>/books/IMG-20240208-WA0110.jpg" alt="Course One">
            </div>

            <div class="item">
              <img src="<?=HOMEASSET?>/books/IMG-20240208-WA0119.jpg" alt="Course One">              
            </div>

            <div class="item">
              <img src="<?=HOMEASSET?>/books/IMG-20240208-WA0111.jpg" alt="Course One">              
            </div>

            <div class="item">
              <img src="<?=HOMEASSET?>/books/IMG-20240208-WA0100.jpg" alt="Course One">              
            </div>

            <div class="item">
              <img src="<?=HOMEASSET?>/books/IMG-20240208-WA0123.jpg" alt="Course One">              
            </div>

            <div class="item">
              <img src="<?=HOMEASSET?>/books/IMG-20240208-WA0124.jpg" alt="Course One">              
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="contact-us" id="contact">
    <div class="container">
      <div class="row">
        <div class="col-lg-9 align-self-center">
          <div class="row">
            <div class="col-lg-12">
              <form id="contact" action="" method="post">
                <div class="row">
                  <div class="col-lg-12">
                    <h2>Let's get in touch</h2>
                  </div>
                  <div class="col-lg-4">
                    <fieldset>
                        <label for="">Name</label>
                      <input name="name" type="text" id="name" placeholder="YOURNAME...*" required="">
                    </fieldset>
                  </div>
                  <div class="col-lg-4">
                    <fieldset>
                    <label for="">Email</label>
                    <input name="email" type="text" id="email" pattern="[^ @]*@[^ @]*" placeholder="YOUR EMAIL..." required="">
                  </fieldset>
                  </div>
                  <div class="col-lg-4">
                    <fieldset>
                    <label for="">Phone Number</label>
                      <input name="phone" type="text" id="subject" placeholder="0554..." required="">
                    </fieldset>
                  </div>
                  <div class="col-lg-4">
                    <fieldset>
                    <label for="">Location</label>
                      <input name="location" type="text" id="subject" placeholder="Oyibi" required="">
                    </fieldset>
                  </div>
                  <div class="col-lg-12">
                    <fieldset>
                    <label for="">Message</label>
                      <textarea name="message" type="text" class="form-control" id="message" placeholder="YOUR MESSAGE..." required=""></textarea>
                    </fieldset>
                  </div>
                  <div class="col-lg-12">
                    <fieldset>
                      <button type="submit" id="form-submit" class="button">SEND MESSAGE NOW</button>
                    </fieldset>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="right-info">
            <ul>
              <li>
                <h6>Phone Number</h6>
                <span>0244 402 913</span>
              </li>
              <li>
                <h6>Email Address</h6>
                <span>donseriesgh1@gmail.com</span>
              </li>
              <li>
                <h6>Street Address</h6>
                <span>Accra Newtown - Greater Accra, Ghana</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    
  <?php $this->view('includes/homefooter'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/)?>