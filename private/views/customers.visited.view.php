<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row m-2">
                        <h4 class="card-title">List of Visited Customers</h4>
                    </div>
                    <?php if (Auth::getRank() == 'marketer') : ?>
                        <a href="<?= HOME ?>/customers/addvisited" class="btn btn-primary float-right mb-2">
                            <i class="mdi mdi mdi-gamepad"> Add Visitor</i>
                        </a>
                        <a href="<?= HOME ?>/customers" class="float-right">
                            <i class="m-2 mdi mdi-chili-hot"> Old Customers</i>
                        </a>
                    <?php endif; ?>

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
                                        Contact Person
                                    </th>
                                    <th>
                                        Phone Number
                                    </th>
                                    <th>
                                        Location
                                    </th>
                                    <th>
                                        Marketer
                                    </th>
                                    <th>
                                        Work Book
                                    </th>
                                    <th>
                                        Text Book
                                    </th>
                                    <th colspan="2" align="centre">
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
                                                <?= ucfirst(esc($row->contactperson)) ?>
                                            </td>
                                            <td>
                                                <?= ucfirst(esc($row->custphone)) ?>
                                            </td>
                                            <td>
                                                <?= ucfirst(esc($row->custlocation) . " (" . esc($row->region) . ")") ?>
                                            </td>
                                            <td>
                                                <?php
                                                if (isset($row->marketer->firstname)) {
                                                    echo esc($row->marketer->firstname);
                                                }

                                                if (isset($row->marketer->lastname)) {
                                                    echo esc($row->marketer->lastname);
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?= esc($row->workbook) ?>
                                            </td>
                                            <td>
                                                <?= esc($row->textbook) ?>
                                            </td>
                                            <td>
                                                <?php // if (($row->officerid == Auth::getId()) && ($row->dateadded == date("Y-m-d", strtotime(date("Y-m-d")."-1 day")))) : 
                                                ?>
                                                <?php if (($row->officerid == Auth::getId()) || ($row->withother == Auth::getId())) : ?>
                                                    <?php
                                                    $today = date('Y-m-d');
                                                    $dateAdded = date('Y-m-d', strtotime($row->dateadded));
                                                    ?>

                                                    <?php if ($dateAdded === $today): ?>
                                                        <a href="<?= HOME ?>/customers/visitedit/<?= $row->id ?>">
                                                            <i class="m-2 mdi mdi-table-edit"> </i>Edit
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="<?= HOME ?>/customers/visitededit/<?= $row->id ?>">
                                                            <i class="m-2 mdi mdi-table-edit"> </i>Top up
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($row->donedata != 1 && Auth::getRank() == 'marketer'): ?>
                                                    <form action="" method="post">
                                                        <button name="movetocustomer" value="<?= $row->id ?>" class="btn-ssm btn-success">
                                                            Move
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="8" class="align-middle text-center text-sm">
                                            No Visitor Added
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (Auth::access('stores')): ?>
                        <form method="Post">
                            <button name="exportexl" class="btn btn-success">Export to Excel</button>
                        </form>
                    <?php endif; ?>
                    <?php if (Auth::access('director')): ?>
                        <a href="<?= HOME ?>/customers/smssending" class="float-end">Send SMS</a>
                    <?php endif; ?>
                </div>
                <?php $pager->display($rows ? count($rows) : 0); ?>
            </div>
        </div>
        <?php if ($alldata) : ?>
            <div class="card">
                <div class="card-body">
                    <div class="row m-2">
                        <h4 class="card-title"> General Search (+ Original Customers)</h4>
                    </div>
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
                                        Marketer
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($alldata as $row) : ?>
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
                                            <?php
                                            if (isset($row->marketer->firstname)) {
                                                echo esc($row->marketer->firstname);
                                            }

                                            if (isset($row->marketer->lastname)) {
                                                echo esc($row->marketer->lastname);
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?= ucfirst(esc($row->status)) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>


    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>