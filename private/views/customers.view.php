<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row ">
                        <h4 class="card-title">List of Customers</h4>
                    </div>
                    <button id="show-add-book" class="btn btn-primary float-right mb-2">
                        <i class="mdi mdi mdi-gamepad"> Add Customer</i>
                    </button>

                    <form method="get">
                        <div class="input-group col-lg-4">
                            <?php if ($hiddenSearch == '') : ?>
                                <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                                    <span class="input-group-text" id="search">
                                        <i class="icon-search"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" id="navbar-search-input" name="search_box" placeholder="Search now" aria-label="search" aria-describedby="search">
                            <?php endif; ?>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Customer Name
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
                                    <th colspan="3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows) : ?>
                                    <?php foreach ($rows as $row) : ?>
                                        <tr>
                                            <td>
                                                <?= esc($row->customername) ?>
                                            </td>
                                            <td>
                                                <?= ucfirst(esc($row->custphone)) ?>
                                            </td>
                                            <td>
                                                <?= ucfirst(esc($row->custlocation) . " (" . esc($row->region) . ")") ?>
                                            </td>
                                            <td>
                                                <?= esc(get_Cust_type($row->custtype)) ?>
                                            </td>
                                            <td>
                                                <a href="<?= HOME ?>/customers/edit/<?= $row->id ?>">
                                                    <i class="m-2 mdi mdi-table-edit"> Edit</i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="<?= HOME ?>/customers/addvisited/<?= $row->id ?>">
                                                    <i class="m-2 mdi mdi-chili-hot"> Sample</i>
                                                </a>
                                            </td>
                                            <td>
                                                <form method="POST">
                                                    <!-- <button name="delcust" value="<?= $row->id ?>" class="btn-sm btn-danger">
                                                        <i class="m-2 mdi mdi-delete-forever"></i>
                                                    </button> -->
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
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
                <?php $pager->display($rows ? count($rows) : 0); ?>
            </div>
        </div>
    </div>



    <div class="popup">
        <div class="row mx-0">
            <div class="col-lg-12 mx-auto">
                <button class="close-btn"><b>&times;</b></button>
                <div class="auth-form-light">
                    <h4>Add New Customer</h4>
                    <form method="post">
                        <div class="form-group">
                            <label for="exampleInputName1">Customer Name</label>
                            <input type="text" class="form-control" value="<?= get_var('customername') ?>" id="exampleInputName1" name="customername" required placeholder="5 Garrison">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail3">Phone Number</label>
                            <input type="text" value="<?= get_var('custphone') ?>" class="form-control" id="exampleInputEmail3" name="custphone" required placeholder="0554013980">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputCity1">Location</label>
                            <input type="text" name="custlocation" value="<?= get_var('custlocation') ?>" class="form-control" id="exampleInputCity1" required placeholder="Madina">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputCity1">Region</label>
                            <select class="form-control" name="region" required id="exampleSelectGender">
                                <option <?= get_select('region', '') ?> value="">-- Select Region --</option>
                                <option <?= get_select('region', 'Greater Accra') ?> value="Greater Accra">Greater Accra</option>
                                <option <?= get_select('region', 'Ashanti') ?> value="Ashanti">Ashanti</option>
                                <option <?= get_select('region', 'Central') ?> value="Central">Central</option>
                                <option <?= get_select('region', 'Eastern') ?> value="Eastern">Eastern</option>
                                <option <?= get_select('region', 'Western') ?> value="Western">Western</option>
                                <option <?= get_select('region', 'Bono') ?> value="Bono">Bono</option>
                                <option <?= get_select('region', 'Bono East') ?> value="Bono East">Bono East</option>
                                <option <?= get_select('region', 'Ahafo') ?> value="Ahafo">Ahafo</option>
                                <option <?= get_select('region', 'Northern') ?> value="Northern">Northern</option>
                                <option <?= get_select('region', 'Savannah') ?> value="Savannah">Savannah</option>
                                <option <?= get_select('region', 'North East') ?> value="North East">North East</option>
                                <option <?= get_select('region', 'Upper East') ?> value="Upper East">Upper East</option>
                                <option <?= get_select('region', 'Upper West') ?> value="Upper West">Upper West</option>
                                <option <?= get_select('region', 'Volta') ?> value="Volta">Volta</option>
                                <option <?= get_select('region', 'Oti') ?> value="Oti">Oti</option>
                                <option <?= get_select('region', 'Western') ?> value="Western">Western</option>
                                <option <?= get_select('region', 'Western North') ?> value="Western North">Western North</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleSelectGender">Customer Type</label>
                            <select class="form-control" name="custtype" required id="exampleSelectGender">
                                <option <?= get_select('custtype', '') ?> value="">-- Select a type --</option>
                                <option <?= get_select('custtype', 'school') ?> value="school">School</option>
                                <option <?= get_select('custtype', 'agent') ?> value="agent">Agent</option>
                                <option <?= get_select('custtype', 'garris') ?> value="garris">Garrison</option>
                                <option <?= get_select('custtype', 'booksh') ?> value="booksh">Bookshop</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>