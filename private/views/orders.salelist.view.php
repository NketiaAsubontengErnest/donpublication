<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title"><?= esc($customer->customername) ?> - <?= esc($customer->region) ?> / <?= ucfirst(esc($customer->custlocation)) ?> (<?= esc($customer->custphone) ?>)</h4>
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
                                        Order Number
                                    </th>
                                    <th>
                                        Invoice
                                    </th>

                                    <th>
                                        Gross Amt
                                    </th>
                                    <th>
                                        Discount
                                    </th>
                                    <th>
                                        Discount Amt
                                    </th>
                                    <th>
                                        Net Amt
                                    </th>
                                    <th>
                                        Verification Officer
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows) : ?>
                                    <?php foreach ($rows as $row) : ?>
                                        <tr>
                                            <td>
                                                <?= esc(get_date($row->orderdate)) ?>
                                            </td>
                                            <td>
                                                <a href="<?= HOME ?>/orders/listprice/<?= $row->ordernumber ?>">
                                                    <?= esc($row->ordernumber) ?>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="<?= HOME ?>/orders/listprice/<?= $row->ordernumber ?>">
                                                    <?= esc($row->invoiceno) ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?= esc(number_format($row->totalOrderSale->totalGrossSales, 2)) ?>
                                            </td>
                                            <td>
                                                <?= esc(number_format($row->discount), 1) ?> %
                                            </td>
                                            <td>
                                                <?= esc(number_format($row->totalOrderSale->totalDiscount, 2)) ?>
                                            </td>
                                            <td>
                                                <?= esc(number_format($row->totalOrderSale->totalNetSales, 2)) ?>
                                            </td>
                                            <td>
                                                <?php if (isset($row->verificOff->firstname)) : ?>
                                                    <?= esc($row->verificOff->firstname) ?> <?= esc($row->verificOff->lastname) ?>
                                                <?php else : ?>
                                                    Not Verified
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= HOME ?>/orders/listprice/<?= $row->ordernumber ?>">
                                                    <i class="m-2 mdi mdi-arrow-expand-all"></i>
                                                </a>
                                                <?php if (Auth::getRank() == 'verification' || Auth::getRank() == 'account'): ?>
                                                    <a href="<?= HOME ?>/orders/applyprices/<?= $row->ordernumber ?>">
                                                        <i class="m-2 mdi mdi-arrow-expand"> Entry</i>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="9" class="align-middle text-center text-sm">
                                            No Order Verified
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

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>