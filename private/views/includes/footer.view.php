  </div>
  
  <!-- content-wrapper ends -->
  <!-- partial:partials/_footer.html -->
  <footer class="footer">
      <script src="<?= ASSETS ?>/js/flush_arlert.js"></script>
      <?php if (isset($_SESSION['messsage'])) : ?>
          <script>
              swal({
                  title: "<?= $_SESSION['status_headen'] ?>",
                  text: "<?= $_SESSION['messsage'] ?>",
                  icon: "<?= $_SESSION['status_code'] ?>",
                  button: "OK, THANKS!",
              });
              <?php
                unset($_SESSION['messsage']);
                unset($_SESSION['status_code']);
                unset($_SESSION['status_headen']);
                ?>
          </script>
      <?php endif; ?>
      <div class="d-sm-flex justify-content-center justify-content-sm-between">
          <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
              Copyright ©
              <script>
                  document.write(new Date().getFullYear())
              </script>. ANEK TECH, All rights reserved.
          </span>
          <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">
              <script>
                  let currentdate = new Date();
                  document.write(new Date().getDate() + "/" + (currentdate.getMonth() + 1) + "/" + currentdate
                      .getFullYear())
              </script>
          </span>
      </div>
  </footer>
  <!-- partial -->
  </div>
  <!-- main-panel ends -->
  </div>
  <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="<?= ROOT ?>/assets/js/essential.js"></script>

  <link rel="stylesheet" href="<?= ROOT ?>/assets/js/jquerys/jquery-1.12.4.js">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/js/jquerys/jquery-ui.js">



  <script src="<?= ROOT ?>/assets/vendors/js/vendor.bundle.base.js"></script>


  <!-- end inject -->
  <!-- Plugin js for this page -->
  <script src="<?= ROOT ?>/assets/vendors/chart.js/Chart.min.js"></script>
  <!-- 
    <script src="vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script> 
    -->
  <script src="<?= ROOT ?>/assets/js/dataTables.select.min.js"></script>

  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="<?= ROOT ?>/assets/js/off-canvas.js"></script>
  <script src="<?= ROOT ?>/assets/js/hoverable-collapse.js"></script>
  <script src="<?= ROOT ?>/assets/js/template.js"></script>
  <script src="<?= ROOT ?>/assets/js/settings.js"></script>
  <script src="<?= ROOT ?>/assets/js/todolist.js"></script>
  <!-- end inject -->

  <!-- Custom js for this page-->
  <script src="<?= ROOT ?>/assets/js/dashboard.js"></script>
  <script src="<?= ROOT ?>/assets/js/Chart.roundedBarCharts.js"></script>

  <!-- End custom js for this page-->

  </body>

  </html>

  <script>
      var select_box_element2 = document.querySelector('#book');

      dselect(select_box_element2, {
          search: true
      });

      var select_box_elementd = document.querySelector('#select_box');

      dselect(select_box_elementd, {
          search: true
      });
      var select_box_element1 = document.querySelector('#select_box1');

      dselect(select_box_element1, {
          search: true
      });
  </script>


  

  <script>
      function printTringger(elementId) {
          var getMyFrame = document.getElementById(elementId);
          getMyFrame.focus();
          getMyFrame.contentWindow.print();
      }
  </script>

  <script>
      var count = 0;
      let allcustomer = [];

      document.querySelector("#add").addEventListener("click", function() {
          $('#customer').val('');
          $('#error_customer').text('');
          $('#customer').css('border-color', '');
          $('#savecustomer').text('Save Customer');
          allcustomer = [];
          document.querySelector(".popup").classList.add("activepop");
      });

      document.querySelector(".popup .close-btn").addEventListener("click", function() {
          document.querySelector(".popup").classList.remove("activepop");
      });

      document.querySelector("#savecustomer").addEventListener("click", function() {
          var error_customer = '';
          var customer = '';
          if ($('#customer').val() == '') {
              error_customer = 'Customer is required';
              $('#error_customer').text(error_customer);
              $('#customer').css('border-color', '#cc0000');
              customer = '';
          } else {
              error_customer = '';
              $('#error_customer').text(error_customer);
              $('#customer').css('border-color', '');
              customer = $('#customer').val();
          }

          if (error_customer != '') {
              return false;
          } else {
              if ($('#savecustomer').text() == 'Save Customer') {
                  if (!allcustomer.includes(customer)) {
                      count = count + 1;
                      output = '<tr id="row_' + count + '">';
                      output += '<td>' + customer + ' <input type="hidden" name="hidden_customer[]" id="customer' + count +
                          '"  value="' + customer + '" /></td>';
                      output += '<td>' + $("#customer option:selected").text() +
                          ' <input type="hidden" name="hidden_customer_name[]" id="customer' + count + '"  value="' + $(
                              "#customer option:selected").text() + '" /></td>';
                      output +=
                          '<td><button type="button" name="remove_details" class="btn btn-danger btn-xs remove_details" id="' +
                          count + '">Remove</button></td>';
                      output += '</tr>';
                      allcustomer.push(customer);
                      $('#item_data').append(output);
                      $('#customer').val('');
                  } else {
                      error_customer = 'Customer alredy exists!!';
                      $('#error_customer').text(error_customer);
                      $('#customer').css('border-color', '#cc0000');
                      customer = '';
                  }
              } else {
                  if (allcustomer.includes(customer)) {
                      var row_id = $('#hidden_row_id').val();
                      output = '<td>' + customer + ' <input type="hidden" name="hidden_book[]" id="book' + row_id +
                          '" class="first_name" value="' + customer + '" /></td>';
                      output +=
                          '<td><button type="button" name="view_details" class="btn btn-warning btn-xs view_details" id="' +
                          row_id + '">View</button></td>';
                      output +=
                          '<td><button type="button" name="remove_details" class="btn btn-danger btn-xs remove_details" id="' +
                          row_id + '">Remove</button></td>';
                      allcustomer.pop(customer);
                      $('#row_' + row_id + '').html(output);
                      $('#customer').val('');
                  }
              }
          }
      });

      $(document).on('click', '.remove_details', function() {
          var row_id = $(this).attr("id");
          if (confirm("Are you sure you want to remove this row data?")) {
              $('#row_' + row_id + '').remove();
          } else {
              return false;
          }
      });
  </script>

  <script>
      var select_box_element = document.querySelector('#customerid');

      dselect(select_box_element, {
          search: true
      });

      var select_box_element = document.querySelector('#book');

      dselect(select_box_element, {
          search: true
      });

      var select_box_element = document.querySelector('#select_box');

      dselect(select_box_element, {
          search: true
      });

      var select_box_element = document.querySelector('#select_box1');

      dselect(select_box_element, {
          search: true
      });

      var select_box_element = document.querySelector('#select_box_order');

      dselect(select_box_element, {
          search: true
      });
  </script>