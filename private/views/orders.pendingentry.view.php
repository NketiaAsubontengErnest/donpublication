<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row m-2">
                        <h4 class="card-title">List of Pending Entry</h4>
                    </div>

                    <?php $this->view('patials/searcherbar', ['hiddenSearch' => $hiddenSearch]); ?>

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
                                        Customer
                                    </th>
                                    <th>
                                        Order Type
                                    </th>
                                    <th>
                                        Markerter
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows): ?>
                                    <?php foreach ($rows as $row): ?>
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
                                                <?= ucfirst(esc($row->customers->customername)) ?>
                                            </td>

                                            <td>
                                                <?= isset($row->ordertypes->typename) ? esc($row->ordertypes->typename) : "" ?>
                                            </td>
                                            <td>
                                                <?= ucfirst(esc($row->makerter->firstname)) ?> <?= ucfirst(esc($row->makerter->lastname)) ?>
                                            </td>
                                            <td>
                                                <?php if (Auth::access('verification') && $row->makerter->officer->id == Auth::getId()): ?>
                                                    <a href="<?= HOME ?>/orders/applyprices/<?= $row->ordernumber ?>">
                                                        <i class="m-2 mdi mdi-arrow-expand"> Entry</i>
                                                    </a>
                                                <?php endif ?>
                                                <?php if (Auth::access('g-account')): ?>
                                                    <a href="<?= HOME ?>/orders/listprice/<?= $row->ordernumber ?>">
                                                        <i class="m-2 mdi mdi-arrow-expand"> View</i>
                                                    </a>
                                                <?php endif ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="align-middle text-center text-sm">
                                            No Pending Order
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <form method="Post">
                        <button name="exportexl" class="btn btn-success">Export to Excel</button>
                    </form>
                </div>
                <?php $pager->display($rows ? count($rows) : 0); ?>
            </div>
        </div>
    </div>

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>