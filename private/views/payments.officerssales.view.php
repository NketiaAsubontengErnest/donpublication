<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row m-2">
                        <h4 class="card-title"><?= Auth::getRank() == 'marketer' ? 'My Summary' : 'Officers Sales' ?></h4>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <form method="get">
                                <div class="input-group ">
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
                        </div>
                        <div class="col-lg-2">
                            <form action="">
                                <div class="input-group">
                                    <input name="startDate" class="form-control" type="date" required>
                                </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="input-group">
                                <input name="endDate" class="form-control" type="date" required>
                                <button class="btn btn-primary">Load</button>
                            </div>
                        </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Officer Name
                                    </th>
                                    <th>
                                        Gross Sales (GHC)
                                    </th>
                                    <th>
                                        Return Amt (GHC)
                                    </th>
                                    <th>
                                        Net Return Amt (GHC)
                                    </th>
                                    <th>
                                        Discount Amt (GHC)
                                    </th>
                                    <th>
                                        Net Sales (GHC)
                                    </th>
                                    <th>
                                        Total Payments (GHC)
                                    </th>
                                    <th>
                                        Balance (GHC)
                                    </th>
                                    <th>
                                        Payments
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows) : ?>
                                    <?php foreach ($rows as $row) : ?>
                                        <?php
                                        $recovery = 0;
                                        $returns = 0;
                                        $balance = 0;
                                        $netamt = 0;
                    
                                        try {
                                            $netamt = ($row->OfficTotalDept->totaldept - ($row->OfficTotalDept->totaldisc));
                                        } catch (\Throwable $th) {
                                        }
                                        try {
                                            $recovery = ($row->OfficTotal->totalpayed / $netamt) * 100;
                                        } catch (\Throwable $th) {
                                        }
                                        try {
                                            $returns = ($row->OfficTotalDept->total_net_returns / ($row->OfficTotalDept->total_net_returns + $netamt)) * 100;
                                        } catch (\Throwable $th) {
                                            //throw $th;
                                        }
                                        try {
                                            $balance = ((($netamt - $row->OfficTotal->totalpayed)) / $netamt) * 100;
                                        } catch (\Throwable $th) {
                                            //throw $th;
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <a href="<?= HOME ?>/orders/officersalelist/<?= $row->id ?>">
                                                    <?= esc($row->firstname) ?> <?= esc($row->lastname) ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?= esc(number_format($row->OfficTotalDept->totaldept, 2)) ?>
                                            </td>
                                            <td>
                                                <?= esc(number_format($row->OfficTotalDept->totalReturns, 2)) ?> 
                                            </td>
                                            <td>
                                                <?=esc(number_format($row->OfficTotalDept->total_net_returns, 2))?> (<?= esc(number_format($returns)) ?>%)
                                            </td>
                                            <td>
                                                <?= esc(number_format($row->OfficTotalDept->totaldisc, 2)) ?>
                                            </td>
                                            <td>
                                                <?= esc(number_format($netamt, 2)) ?>
                                            </td>
                                            <td>
                                                <?= esc(number_format($row->OfficTotal->totalpayed, 2)) ?> (<?= esc(number_format($recovery)) ?>%)
                                            </td>
                                            <td>
                                                <?= esc(number_format(($netamt - ($row->OfficTotal->totalpayed)), 2)) ?>
                                                (<?= esc(number_format($balance)) ?>%)
                                            </td>
                                            <td>
                                                <a href="<?= HOME ?>/payments/officerpayments/<?= $row->id ?>">
                                                    <i class="m-2 mdi mdi-cash-multiple"> View</i>
                                                </a>
                                            </td>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="7" class="align-middle text-center text-sm">
                                                No Record Found
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                            </tbody>
                        </table>
                        <form method="Post">
                            <button name="exportexl" class="btn btn-success">Export to Excel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>