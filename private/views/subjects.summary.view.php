<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title mb-0">Summary of Products</p>
                    <form method="get" class="forms-sample">
                        <div class="row col-12">
                            <div class="col-md-4 m-1">
                                <input class="form-control" type="date" name="startdate" id="" required>
                            </div>
                            <div class="col-md-4 mb-1">
                                <input class="form-control" type="date" name="enddate" id="" required>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary col-md-2" type="submit">Search</button>
                            </div>

                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <thead>
                                <tr>
                                    <th>Book</th>
                                    <th>Opening stock</th>
                                    <th>Quantiry Added</th>
                                    <th>Total Quantiry</th>
                                    <th>Qty Supplied [Sample Qty]</th>
                                    <th>Qty Samp Returned</th>
                                    <th>Qty Returned</th>
                                    <th>Closing Instock</th>
                                    <th>Performance</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows): ?>
                                    <?php foreach ($rows as $row): ?>
                                        <tr>
                                            <td><?= esc($row->level->class . ' ' . $row->subject->subject . ' ' . $row->booktype->booktype) ?></td>
                                            <td class="font-weight-bold"><?= esc(number_format($row->Openstock)) ?></td>
                                            <td class="font-weight-medium"><?= esc(number_format($row->ttadded->ttAdded)) ?></td>
                                            <?php
                                            try {
                                                $totalQty = (($row->Openstock) + ($row->ttadded->ttAdded));
                                            } catch (\Throwable $th) {
                                                $totalQty = 0;
                                            }
                                            ?>
                                            <td class="font-weight-medium"><?= esc(number_format($totalQty)) ?></td>
                                            <?php if (isset($row->total_quantity_sold)): ?>
                                                <td><?= esc(number_format($row->total_quantity_sold)) ?> </td>
                                            <?php else: ?>
                                                <td><?= esc(number_format($row->ttSupply->ttSupply)) ?> [<?= esc(number_format($row->ttSampleSupply->ttSampleSupply)) ?>]</td>
                                            <?php endif ?>
                                            <td class="font-weight-medium">
                                                <?= esc(number_format($row->tt_samp_returns->tt_samp_returns)) ?>
                                            </td>
                                            <td class="font-weight-medium">
                                                <?= esc(number_format($row->ttreturns->ttreturns)) ?>
                                            </td>
                                            <td class="font-weight-bold"><?= esc(number_format($row->quantity)) ?></td>
                                            <?php
                                            try {
                                                $totalPer = (($row->ttSupply->ttSupply - $row->ttreturns->ttreturns) / ($row->quantity + $row->ttSupply->ttSupply)) * 100;
                                            } catch (\Throwable $th) {
                                                $totalPer = 0;
                                            }
                                            ?>
                                            <td class="<?= ($totalPer <= 49) ? "text-danger" : "text-success" ?>"> <?= esc(number_format($totalPer, 2)) ?>% <i class="<?= ($totalPer <= 49) ? "ti-arrow-down" : "ti-arrow-up" ?>"></i></td>
                                        </tr>
                                    <?php endforeach; ?>
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