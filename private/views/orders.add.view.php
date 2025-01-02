<?php $this->view('includes/header', ['crumbs'=>$crumbs, 'actives'=>$actives, 'hiddenSearch'=>$hiddenSearch,])?>
<div class="content-wrapper">
    <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
        <div class="card-body">
        <button id = "show-add-book" class="btn btn-primary float-right">Add Customer</button>
            <div class="row" >
                <h4 class="card-title">List of Customers</h4>               
            </div>

            <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>
                    Book
                    </th>
                    <th>
                    Phone Number
                    </th>
                    <th>
                    Location
                    </th>
                    <th>
                    Customer Type 
                    </th>
                    <th>
                    Action
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php if ($rows):?>
                    <?php foreach ($rows as $row):?>
                        <tr>
                            <td>
                            <?=esc($row->customername)?>
                            </td>
                            <td>
                            <?=ucfirst(esc($row->custphone))?>
                            </td>
                            <td>
                            <?=ucfirst(esc($row->custlocation). " (". esc($row->region).")")?>
                            </td>
                            <td>
                            <?=esc($row->custtype)?>
                            </td>
                            <td>
                                <a href="<?=ROOT?>/employee/edit/<?=$row->id?>">
                                    <i class="m-2 mdi mdi-table-edit"></i>
                                </a>
                                <a href="<?=ROOT?>/employee/del/<?=$row->id?>">
                                <code><i class="mdi mdi-delete-forever"></i></code>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="7" class="align-middle text-center text-sm">
                        No Customer Added
                    </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>
</div>


<div class="popup">   
    <div class="close-btn">&times;</div>          
    <div class="row mx-0">              
        <div class="col-lg-12 mx-auto">                
        <div class="auth-form-light">                                   
            <h4>Add New Customer</h4>
            <form method="post">                    
            <div class="form-group">
                <label for="exampleInputName1">Customer Name</label>
                <input type="text" class="form-control" id="exampleInputName1" name="customername" required placeholder="5 Garrison">
            </div>

            <div class="form-group">
                <label for="exampleInputEmail3">Phone Number</label>
                <input type="text" class="form-control" id="exampleInputEmail3" name="custphone" required placeholder="0554013980">
            </div>

            <div class="form-group">
                <label for="exampleInputCity1">Location</label>
                <input type="text" name="custlocation" class="form-control" id="exampleInputCity1" required placeholder="Madina">
            </div>

            <div class="form-group">
                <label for="exampleInputCity1">Region</label>
                <select class="form-control" name="region" required id="exampleSelectGender">
                    <option value="" >-- Select Region --</option>
                    <option value="Greater Accra" >Greater Accra</option>
                    <option value="Ashanti" >Ashanti</option>
                    <option value="Central" >Central</option>
                    <option value="Eastern" >Eastern</option>
                    <option value="Western" >Western</option>
                    <option value="Bono" >Bono</option>
                    <option value="Bono East" >Bono East</option>
                    <option value="Ahafo" >Ahafo</option>
                    <option value="Northern" >Northern</option>
                    <option value="Savannah" >Savannah</option>
                    <option value="North East" >North East</option>
                    <option value="Upper East" >Upper East</option>
                    <option value="Upper West" >Upper West</option>
                    <option value="Volta" >Volta</option>
                    <option value="Oti" >Oti</option>
                    <option value="Western" >Western</option>
                    <option value="Western North" >Western North</option>
                </select>
            </div>

            <div class="form-group">
                <label for="exampleSelectGender">Customer Type</label>
                <select class="form-control" name="custtype" required id="exampleSelectGender">
                    <option value="" >-- Select a type --</option>
                    <option value="school" >School</option>
                    <option value="agent" >Agent</option>
                </select>
                </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        </div>
    </div>
</div>
<?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/)?>       