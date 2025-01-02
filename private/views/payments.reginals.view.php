<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">                    
                    <div class="row m-2">
                        <h4 class="card-title">Reginal Report</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Region
                                    </th>
                                    <th>
                                        Gross Amt
                                    </th>
                                    <th>
                                        Return Amt
                                    </th>
                                    <th>
                                        Net Return Amt
                                    </th>
                                    <th>
                                        Discount Amt
                                    </th>
                                    <th>
                                        Net Amt
                                    </th>
                                    <th>
                                        Total Payments
                                    </th>
                                    <th>
                                        Ballance
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows) : ?>
                                    <?php foreach ($rows as $row) : ?>
                                        <?php
                                        try {
                                            //code...
                                            $netamt = $row->gross_amount - ($row->total_disc);
                                        } catch (\Throwable $th) {
                                            //throw $th;
                                        }
                                        $recoveryPers = 0;
                                        $returnsPers = 0;
                                        $balancePrs = 0;
                                        try {
                                            $recoveryPers = ($row->total_payed / $netamt) * 100;
                                        } catch (\Throwable $th) {
                                        }
                                        try {
                                            $returnsPers = ($row->total_returns / $netamt) * 100;
                                        } catch (\Throwable $th) {
                                            //throw $th;
                                        }
                                        try {
                                            $balancePrs = (($netamt - $row->total_payed) / $netamt) * 100;
                                        } catch (\Throwable $th) {
                                            //throw $th;
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <?= ucfirst(esc($row->region)) ?>
                                            </td>
                                            <td>
                                                <?= esc(number_format($row->gross_amount, 2)) ?>
                                            </td>
                                            <td>
                                                <?= esc(number_format($row->total_returns, 2)) ?> (<?= esc(number_format($returnsPers)) ?>%)
                                            </td>
                                            <td>
                                                <?= esc(number_format($row->total_net_returns, 2)) ?>
                                            </td>
                                            <td>
                                                <?= esc(number_format($row->total_disc, 2)) ?>
                                            </td>
                                            <td>
                                                <?= esc(number_format($netamt, 2)) ?>
                                            </td>
                                            <td>
                                                <?= esc(number_format($row->total_payed, 2)) ?> (<?= esc(number_format($recoveryPers)) ?>%)
                                            </td>
                                            <td>
                                                <?= esc(number_format($netamt - ($row->total_payed), 2)) ?> (<?= esc(number_format($balancePrs)) ?>%)
                                            </td>
                                        </tr>
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