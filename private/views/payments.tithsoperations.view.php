<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row m-2">
                        <h4 class="card-title">Tithe Operations</h4>
                    </div>

                    <form method="get">
                        <div class="input-group col-lg-4">
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

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        SN
                                    </th>
                                    <th>
                                        Customer Name
                                    </th>
                                    <th>
                                        Location
                                    </th>
                                    <th>
                                        Payment Amt
                                    </th>
                                    <th>
                                        Profit Amt
                                    </th>
                                    <th>
                                        Tithe Amt
                                    </th>
                                    <th>
                                        Date
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
                                                <?= esc($row->id) ?>
                                            </td>
                                            <td>
                                                <?= ucfirst(esc($row->customername)) ?> (<?= esc($row->custphone) ?>)
                                            </td>
                                            <td>
                                                <?= esc($row->custlocation) ?>
                                            </td>
                                            <td>
                                                <?= esc(number_format($row->amountPayed, 2)) ?>
                                            </td>
                                            <td>
                                                <?= esc(number_format($row->profit, 2)) ?>
                                            </td>
                                            <td>
                                                <?= esc(number_format($row->tithe, 2)) ?>
                                            </td>
                                            <td>
                                                <?= esc(get_date_time($row->datepaid)) ?>
                                            </td>
                                            <td>
                                                <form action="" method="post">
                                                    <button name="delete_payment" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this payment record? This action cannot be undone.');" value="<?= $row->id ?>">
                                                        <i class="icon-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="align-middle text-center text-sm">
                                            No Record Found
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