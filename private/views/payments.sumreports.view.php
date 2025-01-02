<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row m-2">
                        <h3 class="card-title">Total Sales & Payments</h3>
                    </div> 
                    
                    <div class="table-responsive">
                        <h3>Marketers</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Gross Amt
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
                                        Balance
                                    </th>
                                    <th>
                                        Recovery %
                                    </th>

                                    <?php
                                    $schoolTotalPa = 0;
                                    try {
                                        //code...
                                        $schoolTotalPa = number_format(esc($rows['schoolTtSalses']['SchoolTotalPayments']->totalPayment), 2);
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                        $schoolTotalPa = 0;
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows['schoolTtSalses']['SchoolTotalSales']->totalGrossSales) : ?>
                                    <tr>
                                        <td>
                                            <?= number_format(esc($rows['schoolTtSalses']['SchoolTotalSales']->totalGrossSales), 2) ?? 0 ?>
                                        </td>
                                        <td>
                                            <?= number_format(esc($rows['schoolTtSalses']['SchoolTotalSales']->totalDiscount), 2) ?? 0 ?>
                                        </td>
                                        <td>
                                            <?= number_format(esc($rows['schoolTtSalses']['SchoolTotalSales']->totalNetSales), 2) ?? 0 ?>
                                        </td>
                                        <td>
                                            <?= $schoolTotalPa ?>
                                        </td>
                                        <td>
                                            <?= number_format(esc($rows['schoolTtSalses']['balance']), 2) ?? 0 ?>
                                        </td>
                                        <td>
                                            <?php
                                            $recovery = 0;
                                            $returns = 0;
                                            try {
                                                $recovery = ($rows['schoolTtSalses']['SchoolTotalPayments']->totalPayment / $rows['schoolTtSalses']['SchoolTotalSales']->totalNetSales) * 100;
                                            } catch (\Throwable $th) {
                                            }
                                            echo number_format($recovery, 2) . '%';
                                            ?>
                                        </td>
                                    </tr>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="align-middle text-center text-sm">
                                            No Record Found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <h3>Garrisons</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Gross Amt
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
                                        Balance
                                    </th>
                                    <th>
                                        Recovery %
                                    </th>
                                    <?php
                                    try {
                                        //code...
                                        $totalPay = number_format(esc($rows['garisTtSalses']['GarisTotalPayments']->totalPayment), 2);
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                        $totalPay = 0;
                                    }

                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows['garisTtSalses']['GarisTotalSales']->totalGrossSales) : ?>
                                    <tr>
                                        <td>
                                            <?= number_format(esc($rows['garisTtSalses']['GarisTotalSales']->totalGrossSales), 2) ?? 0 ?>
                                        </td>
                                        <td>
                                            <?= number_format(esc($rows['garisTtSalses']['GarisTotalSales']->totalDiscount), 2) ?? 0 ?>
                                        </td>
                                        <td>
                                            <?= number_format(esc($rows['garisTtSalses']['GarisTotalSales']->totalNetSales), 2) ?? 0 ?>
                                        </td>
                                        <td>
                                            <?= $totalPay; ?>
                                        </td>
                                        <td>
                                            <?= number_format(esc($rows['garisTtSalses']['balance']), 2) ?? 0 ?>
                                        </td>
                                        <td>
                                            <?php
                                            $recovery = 0;
                                            $returns = 0;
                                            try {
                                                $recovery = ($rows['garisTtSalses']['GarisTotalPayments']->totalPayment / $rows['garisTtSalses']['GarisTotalSales']->totalNetSales) * 100;
                                            } catch (\Throwable $th) {
                                            }
                                            echo number_format($recovery, 2) . '%';
                                            ?>
                                        </td>

                                    </tr>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="align-middle text-center text-sm">
                                            No Record Found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <h3>Agents</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Gross Amt
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
                                        Balance
                                    </th>
                                    <th>
                                        Recovery %
                                    </th>
                                    <?php
                                    $agentTotal;
                                    try {
                                        //code...
                                        $agentTotal = number_format($rows['agentTtSalses']['agentTotalPayments']->totalPayment, 2);
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                        $agentTotal = 0;
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows['agentTtSalses']['agentTotalSales']->totalGrossSales) : ?>
                                    <?php
                                    $recovery = 0;
                                    $returns = 0;
                                    try {
                                        $recovery = ($rows['agentTtSalses']['agentTotalPayments']->totalPayment / $rows['agentTtSalses']['agentTotalSales']->totalNetSales) * 100;
                                    } catch (\Throwable $th) {
                                        $recovery = 0;
                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <?= number_format(esc($rows['agentTtSalses']['agentTotalSales']->totalGrossSales), 2) ?? 0 ?>
                                        </td>
                                        <td>
                                            <?= number_format(esc($rows['agentTtSalses']['agentTotalSales']->totalDiscount), 2) ?? 0 ?>
                                        </td>
                                        <td>
                                            <?= number_format(esc($rows['agentTtSalses']['agentTotalSales']->totalNetSales), 2) ?? 0 ?>
                                        </td>
                                        <td>
                                            <?= $agentTotal ?>
                                        </td>
                                        <td>
                                            <?= number_format(esc($rows['agentTtSalses']['balance']), 2) ?? 0 ?>
                                        </td>
                                        <td>
                                            <?= number_format($recovery, 2) . '%' ?>
                                        </td>
                                    </tr>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="align-middle text-center text-sm">
                                            No Record Found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <h3>Bookshops</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Gross Amt
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
                                        Balance
                                    </th>
                                    <th>
                                        Recovery %
                                    </th>

                                   
                                </tr>
                            </thead>

                            <tbody>
                                <?php if ($rows['booksTtSalses']['BoshTotalSales']->totalGrossSales) : ?>
                                    <?php
                                    $recovery = 0;
                                    $returns = 0;
                                    $totalPay = 0;
                                    try {
                                        $recovery = ($rows['booksTtSalses']['BoshTotalPayments']->totalPayment / $rows['booksTtSalses']['BoshTotalSales']->totalNetSales) * 100;
                                    } catch (\Throwable $th) {
                                        $recovery = 0;
                                    }

                                    try {
                                        $totalPay = number_format(esc($rows['booksTtSalses']['BoshTotalPayments']->totalPayment), 2);
                                    } catch (\Throwable $th) {
                                        $totalPay = 0;
                                    }

                                    try {
                                        $recovery = ($rows['booksTtSalses']['BoshTotalPayments']->totalPayment / $rows['booksTtSalses']['BoshTotalSales']->totalNetSales) * 100;
                                    } catch (\Throwable $th) {
                                        $recovery = 0;
                                    }
                                    ?>
                                    <td>
                                        <?= number_format(esc($rows['booksTtSalses']['BoshTotalSales']->totalGrossSales), 2) ?? 0 ?>
                                    </td>
                                    <td>
                                        <?= number_format(esc($rows['booksTtSalses']['BoshTotalSales']->totalDiscount), 2) ?? 0 ?>
                                    </td>
                                    <td>
                                        <?= number_format(esc($rows['booksTtSalses']['BoshTotalSales']->totalNetSales), 2) ?? 0 ?>
                                    </td>
                                    <td>
                                        <?= $totalPay ?>
                                    </td>
                                    <td>
                                        <?= number_format(esc($rows['booksTtSalses']['balance']), 2) ?? 0 ?>
                                    </td>
                                    <td>
                                        <?= number_format($recovery, 2) . '%' ?>
                                    </td>
                                    </tr>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="align-middle text-center text-sm">
                                            No Record Found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <h3>Grand Totals</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Gross Amt
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
                                        Balance
                                    </th>
                                    <th>
                                        Recovery %
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows['ttSalses']['totalSales']->totalGrossSales) : ?>

                                    <?php
                                    $recovery = 0;
                                    $returns = 0;
                                    try {
                                        $recovery = ($rows['ttSalses']['totalPayments']->totalPayment / $rows['ttSalses']['totalSales']->totalNetSales) * 100;
                                    } catch (\Throwable $th) {
                                        $recovery = 0;
                                    }
                                    try {
                                        $totalPays = number_format(esc($rows['ttSalses']['totalPayments']->totalPayment), 2);
                                    } catch (\Throwable $th) {
                                        $totalPays = 0;
                                    }

                                    ?>
                                    <tr>
                                        <td>
                                            <?= number_format(esc($rows['ttSalses']['totalSales']->totalGrossSales), 2) ?? 0 ?>
                                        </td>
                                        <td>
                                            <?= number_format(esc($rows['ttSalses']['totalSales']->totalDiscount), 2) ?? 0 ?>
                                        </td>
                                        <td>
                                            <?= number_format(esc($rows['ttSalses']['totalSales']->totalNetSales), 2) ?? 0 ?>
                                        </td>
                                        <td>
                                            <?= $totalPays ?>
                                        </td>
                                        <td>
                                            <?= number_format(esc($rows['ttSalses']['balance']), 2) ?? 0 ?>
                                        </td>
                                        <td>
                                            <?= number_format(esc($recovery), 2) . '%' ?>
                                        </td>

                                    </tr>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="align-middle text-center text-sm">
                                            No Record Found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>