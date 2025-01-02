<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">List of Orders</h4>
                    </div>
                    <div class="row">
                            
                            <div class="col-md-6">
                                <form method="get">
                                    <div class="input-group col-lg-8">
                                        <?php if ($hiddenSearch == ''): ?>
                                            <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                                                <span class="input-group-text" id="search">
                                                    <i class="icon-search"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="navbar-search-input" name="search_box"
                                                placeholder="Search now" aria-label="search" aria-describedby="search">
                                        <?php endif; ?>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <?php if (isset($_SESSION['seasondata'])): ?>
                                    <?php if (Auth::getRank() == 'marketer'): ?>
                                        <a href="<?= HOME ?>/orders/placeorder" class="btn btn-primary float-right"><i class="mdi mdi-cart-plus"></i> Place Order</a>
                                    <?php endif ?>
                                    <?php if (Auth::getRank() == 'marketer'): ?>
                                        <a href="<?= HOME ?>/orders/groupsample" class="btn btn-primary float-right mx-1"><i class="mdi mdi-cart-plus"></i>Group </a>
                                    <?php endif ?>
                                <?php else: ?>
                                    <label for="" class="float-right"><code>No Season Set</code></label>
                                <?php endif ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Order Number
                                    </th>
                                    <th>
                                        Accepted
                                    </th>
                                    <th>
                                        Customer
                                    </th>
                                    <th>
                                        Makerter
                                    </th>
                                    <th>
                                        Order Type
                                    </th>
                                    <th>
                                        Date
                                    </th>
                                    <th>
                                        Stores Officer
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
                                <?php if ($rows): ?>
                                    <?php foreach ($rows as $row): ?>
                                        <tr>
                                            <td>
                                                <?= esc($row->ordernumber) ?>
                                            </td>
                                            <td>
                                                <?php if (isset($row->maketeraccept) && $row->maketeraccept == 'YES') : ?>
                                                    <label class="text-success">YES</label>
                                                <?php else : ?>
                                                    Not Accepted
                                                <?php endif; ?>
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
                                                <?php if (isset($row->storesOff->firstname)): ?>
                                                    <?= esc($row->storesOff->firstname) ?> <?= esc($row->storesOff->lastname) ?>
                                                <?php else: ?>
                                                    Not Issued
                                                <?php endif; ?>

                                            </td>
                                            <td>
                                                <?php if (isset($row->verificOff->firstname)): ?>
                                                    <?= esc($row->verificOff->firstname) ?> <?= esc($row->verificOff->lastname) ?>
                                                <?php else: ?>
                                                    Not Verified
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= HOME ?>/orders/list/<?= $row->ordernumber ?>">
                                                    <i class="m-2 mdi mdi-arrow-expand-all"> View</i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="align-middle text-center text-sm">
                                            No Order Added
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