<?php $this->view('includes/header', ['crumbs'=>$crumbs, 'actives'=>$actives, 'hiddenSearch'=>$hiddenSearch,])?>
          
        <div class="content-wrapper">
          
            <div class="row"> 
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit Customer</h4>
                  <form class="forms-sample" method="post">
                    <div class="form-group">
                      <label for="exampleInputName1">Customer Name</label>
                      <input type="text" value="<?=esc($rows[0]->customername)?>"  class="form-control" id="exampleInputName1" name="customername" required placeholder="Name">
                    </div>                  

                    <div class="form-group">
                      <label for="exampleInputCity1">Phone Number</label>
                      <input type="text" value="<?=esc($rows[0]->custphone)?>" name="custphone" class="form-control" id="exampleInputCity1" required placeholder="0554013980">
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail3">Location</label>
                      <input type="text" value="<?=esc($rows[0]->custlocation)?>" class="form-control" id="exampleInputEmail3" name="custlocation" required placeholder="Last Name">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputCity1">Region</label>
                        <select class="form-control" name="region" required id="exampleSelectGender">
                            <option value="">-- Select Region --</option>
                            <option <?=get_select('custtype','Greater Accra',$rows[0]->region);?> value="Greater Accra">Greater Accra</option>
                            <option <?=get_select('custtype','Ashanti',$rows[0]->region);?> value="Ashanti">Ashanti</option>
                            <option <?=get_select('custtype','Central',$rows[0]->region);?> value="Central">Central</option>
                            <option <?=get_select('custtype','Eastern',$rows[0]->region);?> value="Eastern">Eastern</option>
                            <option <?=get_select('custtype','Western',$rows[0]->region);?> value="Western">Western</option>
                            <option <?=get_select('custtype','Bono',$rows[0]->region);?> value="Bono">Bono</option>
                            <option <?=get_select('custtype','Bono East',$rows[0]->region);?> value="Bono East">Bono East</option>
                            <option <?=get_select('custtype','Ahafo',$rows[0]->region);?> value="Ahafo">Ahafo</option>
                            <option <?=get_select('custtype','Northern',$rows[0]->region);?> value="Northern">Northern</option>
                            <option <?=get_select('custtype','Savannah',$rows[0]->region);?> value="Savannah">Savannah</option>
                            <option <?=get_select('custtype','North East',$rows[0]->region);?> value="North East">North East</option>
                            <option <?=get_select('custtype','Upper East',$rows[0]->region);?> value="Upper East">Upper East</option>
                            <option <?=get_select('custtype','Upper West',$rows[0]->region);?> value="Upper West">Upper West</option>
                            <option <?=get_select('custtype','Volta',$rows[0]->region);?> value="Volta">Volta</option>
                            <option <?=get_select('custtype','Oti',$rows[0]->region);?> value="Oti">Oti</option>
                            <option <?=get_select('custtype','Western',$rows[0]->region);?> value="Western">Western</option>
                            <option <?=get_select('custtype','Western North',$rows[0]->region);?> value="Western North">Western North</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                      <label for="exampleSelectGender">Customer Type</label>
                        <select class="form-control" name="custtype" required id="exampleSelectGender">
                          <option value="" >-- Select a type --</option>
                            <option <?=get_select('custtype','school',$rows[0]->custtype);?> value="school">School</option>
                            <option <?=get_select('custtype','agent',$rows[0]->custtype);?> value="agent">Agent</option>
                            <option <?=get_select('custtype','garris',$rows[0]->custtype);?> value="garris">Garrison</option>
                            <option <?=get_select('custtype','booksh',$rows[0]->custtype);?> value="booksh">Bookshop</option>
                        </select>
                      </div>                    
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="mdi mdi-content-save-settings" ></i>
                        Save Edit
                    </button>
                    <a class="btn btn-light float-right" href="<?=HOME?>/customers">Cancel</a>
                  </form>
                </div>
              </div>
            </div>
          </div>
        
<?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/)?>    