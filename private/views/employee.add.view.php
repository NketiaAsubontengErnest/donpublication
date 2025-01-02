<?php $this->view('includes/header', ['crumbs'=>$crumbs, 'actives'=>$actives, 'hiddenSearch'=>$hiddenSearch,])?>
          
        <div class="content-wrapper">
          
            <div class="row"> 
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Add New Employee</h4>
                  <form class="forms-sample" method="post">
                    <div class="form-group">
                      <label for="exampleInputName1">First Name</label>
                      <input type="text" class="form-control" id="exampleInputName1" name="firstname" required placeholder="First Name">
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail3">Last Name</label>
                      <input type="text" class="form-control" id="exampleInputEmail3" name="lastname" required placeholder="Last Name">
                    </div>

                    <div class="form-group">
                      <label for="exampleInputCity1">Phone Number</label>
                      <input type="text" name="phone" class="form-control" id="exampleInputCity1" required placeholder="0554013980">
                    </div>

                    <div class="form-group">
                      <label for="exampleSelectGender">Role</label>
                        <select class="form-control" name="rank" required id="exampleSelectGender">
                          <option value="" >-- Select a role --</option>
                          <option value="marketer" >Marketer</option>
                          <option value="stores" >Stores Manager</option>
                          <option value="driver" >Driver</option>
                          <option value="security" >Security</option>
                          <option value="verification" >Verification Officer</option>
                          <option value="account" >Account Officer</option>
                          <option value="g-account" >General Account Officer</option>
                          <option value="director" >Director</option>
                        </select>
                      </div>
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <button class="btn btn-light float-right">Cancel</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        
<?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/)?>    