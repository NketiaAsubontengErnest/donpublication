<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row m-2">
                        <h4 class="card-title">List Payments: <?= $cust->customername ?> -
                            <?= $cust->custlocation . "-" . $cust->region ?>
                            (<?= $cust->custphone ?>)</h4>
                    </div>

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
                                                <?= esc($row->reciept) ?>
                                            </td>
                                            <td>
                                                <?= ucfirst(esc($row->transid)) ?>
                                            </td>

                                            <td>
                                                <?= ucfirst(esc($row->modeofpayment)) ?>
                                            </td>

                                            <td>
                                                GHC <?= esc(number_format($row->amount, 2)) ?>
                                                <?php if ($row->updateamount != 0) : ?>
                                                    Edited to <?= esc(number_format($row->updateamount, 2)) ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="align-middle text-center text-sm">
                                            No Payments Received
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

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>