<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row m-2">
                        <h4 class="card-title">List of Profit & Tithe</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Date of Payment
                                    </th>
                                    <th>
                                        Amount Paid
                                    </th>
                                    <th>
                                        Profit
                                    </th>
                                    <th>
                                        Tithe
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
                                                <?= esc(get_date($row->datepaid, 2)) ?>
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
                                                <form method="POST">
                                                    <input type="text" name="payid" value="<?= esc($row->id) ?>" hidden>
                                                    <button class="btn btn-primary btn-icon-text">
                                                        <i class="mdi mdi-cash-multiple"></i>
                                                        Pay
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="align-middle text-center text-sm">
                                            No Record Found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <form method="POST" align="center">
                        <input type="text" name="payall" value="all" hidden>
                        <?php if ($totalTithes->total_unpaid_tithe > 0): ?>
                            <button class="btn btn-primary btn-icon-text float-end">
                                <i class="mdi mdi-cash-multiple"></i>
                                Pay All Tithe's
                            </button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
        <?php $pager->display($rows ? count($rows) : 0); ?>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td colspan="3" class="align-middle">
                                            <h5><b>Total Amount Paid: </b> GHC <?= number_format($totalTithes->total_unpaid_amountPayed, 2) ?></h5>
                                        </td>
                                        <td colspan="3" class="align-middle">
                                            <h5><b>Total Tithe Paid: </b> GHC <?= number_format($totalTithes->total_paid_tithe, 2) ?></h5>
                                        </td>
                                        <td colspan="3" class="align-middle ">
                                            <h5> <b>Total Tithe Unpayed: </b> GHC <?= number_format($totalTithes->total_unpaid_tithe, 2) ?></h1>
                                        </td>
                                        <td colspan="3" class="align-middle ">
                                            <h5> <b>Total Tithe: </b> GHC <?= number_format($totalTithes->totalTithes, 2) ?></h1>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- <form action="" method="post">
                        <button name="generate" value="generator" class="btn btn-primary btn-icon-text">
                            <i class="mdi mdi-cash-multiple"></i>
                            Generate
                        </button>
                    </form> -->

                    <form method="Post">
                        <button name="exportexl" class="btn btn-success">Export to Excel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>