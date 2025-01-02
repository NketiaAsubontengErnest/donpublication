<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row m-2">
                            <h4 class="card-title">List of Verified Orders</h4>
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
                                            Accepted
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
                                                        <label class="text-success">DONE</label>
                                                    <?php else : ?>
                                                        Not Verified
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (isset($row->maketeraccept) && $row->maketeraccept == 'YES') : ?>
                                                        <label class="text-success">YES</label>
                                                    <?php else : ?>
                                                        Not Accepted
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?= HOME ?>/orders/list/<?= $row->ordernumber ?>">
                                                        <i class="mdi mdi-arrow-expand-all"> View</i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="8" class="align-middle text-center text-sm">
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