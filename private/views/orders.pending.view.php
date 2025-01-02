<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row m-2">
                        <h4 class="card-title">List of Pending Orders</h4>
                    </div>

                    <?php $this->view('patials/searcherbar', ['hiddenSearch'=>$hiddenSearch]);?>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Order Number
                                    </th>
                                    <th>
                                        Customer
                                    </th>
                                    <th>
                                        Markerter
                                    </th>
                                    <th>
                                        Order Type
                                    </th>
                                    <th>
                                        Date
                                    </th>
                                    <th>
                                        Verification
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
                                                <a href="<?= HOME ?>/orders/verifylist/<?= $row->ordernumber ?>">
                                                    <?= esc($row->ordernumber) ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?= ucfirst(esc($row->customers->customername)) ?>
                                            </td>
                                            <td>
                                                <?= ucfirst(esc($row->makerter->firstname)) ?> <?= ucfirst(esc($row->makerter->lastname)) ?>
                                            </td>
                                            <td>
                                                <?= isset($row->ordertypes->typename) ? esc($row->ordertypes->typename) : "" ?>
                                            </td>
                                            <td>
                                                <?= esc(get_date($row->orderdate)) ?>
                                            </td>
                                            <td>
                                                <?php if (isset($row->verificOff->firstname)) : ?>
                                                    <p class="text-success">DONE</p>
                                                <?php else : ?>
                                                    Not Verified
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (Auth::access('verification')) : ?>
                                                    <a href="<?= HOME ?>/orders/verifylist/<?= $row->ordernumber ?>">
                                                        <i class="m-2 mdi mdi-arrow-expand">Verify</i>
                                                    </a>
                                                <?php else : ?>
                                                    <a href="<?= HOME ?>/orders/list/<?= $row->ordernumber ?>">
                                                        <i class="m-2 mdi mdi-arrow-expand-all"></i>
                                                    </a>
                                                    <a href="<?= HOME ?>/orders/edit/<?= $row->ordernumber ?>">
                                                        <i class="m-2 mdi mdi-table-edit"></i>
                                                    </a>
                                                    <a href="<?= HOME ?>/orders/del/<?= $row->ordernumber ?>">
                                                        <i class="mdi mdi-delete-forever"></i>
                                                    </a>
                                                <?php endif ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="7" class="align-middle text-center text-sm">
                                            No Pending Order
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