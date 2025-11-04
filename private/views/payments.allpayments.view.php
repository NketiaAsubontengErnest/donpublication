<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row m-2">
                        <h4 class="card-title">All Payments </h4>
                    </div>
                    <?php
                    $payments = 0;
                    ?>

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
                                        Date
                                    </th>
                                    <th>
                                        Customer
                                    </th>
                                    <th>
                                        Officer
                                    </th>
                                    <th>
                                        Receipt No.
                                    </th>
                                    <th>
                                        Transaction Id
                                    </th>
                                    <th>
                                        Mode
                                    </th>
                                    <th>
                                        Amount
                                    </th>
                                    <th>
                                        Date Recorded
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows) : ?>
                                    <?php foreach ($rows as $row) : ?>
                                        <tr>
                                            <td>
                                                <?= esc(get_date($row->paymentdate)) ?>
                                            </td>
                                            <td>
                                                <?= esc($row->customers->customername) ?>
                                            </td>
                                            <td>
                                                <?= esc($row->marketer->firstname) ?> <?= esc($row->marketer->lastname) ?>
                                            </td>
                                            <td>
                                                <?= esc($row->reciept) ?>
                                            </td>
                                            <td>
                                                <?= ucfirst(esc($row->transid)) ?>
                                            </td>

                                            <td>
                                                <?= ucfirst(esc($row->modeofpayment)) ?>
                                            </td>

                                            <td>
                                                GHS <?= esc(number_format($row->amount, 2)) ?>
                                                <?php $payments += $row->amount ?>
                                                <?php if ($row->updateamount != NULL) : ?>
                                                    Edited to <?= esc(number_format($row->updateamount, 2)) ?>
                                                <?php endif; ?>
                                            </td>

                                            <td>
                                                <?= esc(get_date($row->datepaid)) ?>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="align-middle text-center text-sm">
                                            No Payments Received
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (Auth::access('account')) : ?>
                        <form method="post">
                            <button name="exportexl" class="btn btn-success">Export to Excel</button>
                        </form>
                    <?php endif; ?>
                </div>
                <?php $pager->display($rows ? count($rows) : 0); ?>
            </div>

        </div>
        <div class="row">
            <div class="col-lg-12 ">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="row m-2">
                                <h1 class="card-title">Total</h1>
                            </div>
                        </div>
                        <div class="row  m-2">
                            <div class="col-md-4">
                                <h4>Payment:</h4>
                            </div>
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                                <h4>GHS <?= number_format($payments, 2) ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>