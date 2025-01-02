<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <form method="get">
                    <div class="input-group col-lg-4 mt-4">
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

                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li><a class="nav-link active" data-toggle="tab" href="#home">GARRISONS</a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#profile">BOOKSHOPS</a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#contact">AGENTS</a></li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row m-2">
                                <h4 class="card-title">List of Customers (GARRISONS)</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>
                                                Customer Name
                                            </th>
                                            <th>
                                                Officer
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
                                                Net Sales
                                            </th>
                                            <th>
                                                Total Payments
                                            </th>
                                            <th>
                                                Ballance
                                            </th>
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($rows['garris']) : ?>
                                            <?php foreach ($rows['garris'] as $row) : ?>
                                                <?php                                                
                                                $recovery = 0;
                                                $returns = 0;
                                                $balance = 0;
                                                $netamt  = 0;

                                                try {
                                                    //code...
                                                    $netamt = $row->amout_disco->totaldept - ($row->amout_disco->totaldisc );
                                                } catch (\Throwable $th) {
                                                    //throw $th;
                                                }

                                                try {
                                                    $recovery = ($row->totalpayment->totalpayed / $netamt) * 100;
                                                } catch (\Throwable $th) {
                                                }
                                                try {
                                                    $returns = ($row->amout_disco->totalReturns / $netamt) * 100;
                                                } catch (\Throwable $th) {
                                                    //throw $th;
                                                }
                                                try {
                                                    $balance = (($netamt - ($row->totalpayment->totalpayed)) / $netamt) * 100;
                                                } catch (\Throwable $th) {
                                                    //throw $th;
                                                }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <a href="<?= HOME ?>/orders/salelist/<?= $row->cid ?>">
                                                            <?= ucfirst(esc($row->customername)) ?> - <?= esc($row->region) ?>
                                                            (<?= ucfirst(esc($row->custphone)) ?>)
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <?= esc($row->firstname) ?> <?= esc($row->lastname) ?>
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($row->amout_disco->totaldept, 2)) ?>
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($row->amout_disco->totalReturns, 2)) ?> (<?= esc(number_format($returns)) ?>%)
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($row->amout_disco->total_net_returns, 2)) ?>
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($row->amout_disco->totaldisc, 2)) ?>
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($netamt, 2)) ?>
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($row->totalpayment->totalpayed, 2)) ?> (<?= esc(number_format($recovery)) ?>%)
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($netamt - ($row->totalpayment->totalpayed), 2)) ?> (<?= esc(number_format($balance)) ?>%)
                                                    </td>

                                                    <td>
                                                        <a href="<?= HOME ?>/payments/viewpayments/<?= $row->cid ?>">
                                                            <i class="m-2 mdi mdi-cards">Pay</i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="8" class="align-middle text-center text-sm">
                                                    No Record Found
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <form method="Post">
                                <button name="exportexl" value="garris" class="btn btn-success">Export to Excel</button>
                            </form>
                            <a href="<?= HOME ?>/customers/specialssupply?type=garris" class="float-end">Details</a>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row m-2">
                                <h4 class="card-title">List of Customers (BOOKSHOPS)</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>
                                                Customer Name
                                            </th>
                                            <th>
                                                Officer
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
                                                Net Sales
                                            </th>
                                            <th>
                                                Total Payments
                                            </th>
                                            <th>
                                                Ballance
                                            </th>
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($rows['booksh']) : ?>
                                            <?php foreach ($rows['booksh'] as $row) : ?>
                                                <?php                                                
                                                $recovery = 0;
                                                $returns = 0;
                                                $balance = 0;
                                                $netamt  = 0;

                                                try {
                                                    //code...
                                                    $netamt = $row->amout_disco->totaldept - ($row->amout_disco->totaldisc);
                                                } catch (\Throwable $th) {
                                                    //throw $th;
                                                }

                                                try {
                                                    $recovery = ($row->totalpayment->totalpayed / $netamt) * 100;
                                                } catch (\Throwable $th) {
                                                }
                                                try {
                                                    $returns = ($row->amout_disco->totalReturns / $netamt) * 100;
                                                } catch (\Throwable $th) {
                                                    //throw $th;
                                                }
                                                try {
                                                    $balance = (($netamt - ($row->totalpayment->totalpayed)) / $netamt) * 100;
                                                } catch (\Throwable $th) {
                                                    //throw $th;
                                                }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <a href="<?= HOME ?>/orders/salelist/<?= $row->cid ?>">
                                                            <?= ucfirst(esc($row->customername)) ?> - <?= esc($row->region) ?>
                                                            (<?= ucfirst(esc($row->custphone)) ?>)
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <?= esc($row->firstname) ?> <?= esc($row->lastname) ?>
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($row->amout_disco->totaldept, 2)) ?>
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($row->amout_disco->totalReturns, 2)) ?> (<?= esc(number_format($returns)) ?>%)
                                                    </td>
                                                    
                                                    <td>
                                                        <?= esc(number_format($row->amout_disco->total_net_returns, 2)) ?>
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($row->amout_disco->totaldisc, 2)) ?>
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($netamt, 2)) ?>
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($row->totalpayment->totalpayed, 2)) ?> (<?= esc(number_format($recovery)) ?>%)
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($netamt - ($row->totalpayment->totalpayed), 2)) ?> (<?= esc(number_format($balance)) ?>%)
                                                    </td>

                                                    <td>
                                                        <a href="<?= HOME ?>/payments/viewpayments/<?= $row->cid ?>">
                                                            <i class="m-2 mdi mdi-cards">Pay</i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="8" class="align-middle text-center text-sm">
                                                    No Record Found
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <form method="Post">
                                <button name="exportexl" value="booksh" class="btn btn-success">Export to Excel</button>
                            </form>
                            <a href="<?= HOME ?>/customers/specialssupply?type=booksh" class="float-end">Details</a>
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="row m-2">
                                <h4 class="card-title">List of Customers (AGENTS)</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>
                                                Customer Name
                                            </th>
                                            <th>
                                                Officer
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
                                                Net Sales
                                            </th>
                                            <th>
                                                Total Payments
                                            </th>
                                            <th>
                                                Balance
                                            </th>
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($rows['agent']) : ?>
                                            <?php foreach ($rows['agent'] as $row) : ?>
                                                <?php                                                
                                                $recovery = 0;
                                                $returns = 0;
                                                $balance = 0;
                                                $netamt  = 0;

                                                try {
                                                    //code...
                                                    $netamt = $row->amout_disco->totaldept - ($row->amout_disco->totaldisc);
                                                } catch (\Throwable $th) {
                                                    //throw $th;
                                                }

                                                try {
                                                    $recovery = ($row->totalpayment->totalpayed / $netamt) * 100;
                                                } catch (\Throwable $th) {
                                                }
                                                try {
                                                    $returns = ($row->amout_disco->totalReturns / $netamt) * 100;
                                                } catch (\Throwable $th) {
                                                    //throw $th;
                                                }
                                                try {
                                                    $balance = (($netamt - ($row->totalpayment->totalpayed)) / $netamt) * 100;
                                                } catch (\Throwable $th) {
                                                    //throw $th;
                                                }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <a href="<?= HOME ?>/orders/salelist/<?= $row->cid ?>">
                                                            <?= ucfirst(esc($row->customername)) ?> - <?= esc($row->region) ?>
                                                            (<?= ucfirst(esc($row->custphone)) ?>)
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <?= esc($row->firstname) ?> <?= esc($row->lastname) ?>
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($row->amout_disco->totaldept, 2)) ?>
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($row->amout_disco->totalReturns, 2)) ?> (<?= esc(number_format($returns)) ?>%)
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($row->amout_disco->total_net_returns, 2)) ?>
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($row->amout_disco->totaldisc, 2)) ?>
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($netamt, 2)) ?>
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($row->totalpayment->totalpayed, 2)) ?> (<?= esc(number_format($recovery)) ?>%)
                                                    </td>
                                                    <td>
                                                        <?= esc(number_format($netamt - ($row->totalpayment->totalpayed), 2)) ?> (<?= esc(number_format($balance)) ?>%)
                                                    </td>

                                                    <td>
                                                        <a href="<?= HOME ?>/payments/viewpayments/<?= $row->cid ?>">
                                                            <i class="m-2 mdi mdi-cards">Pay</i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="8" class="align-middle text-center text-sm">
                                                    No Record Found
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <form method="Post">
                                <button name="exportexl" value="agent" class="btn btn-success">Export to Excel</button>
                            </form>
                            <a href="<?= HOME ?>/customers/specialssupply?type=agent" class="float-end">Details</a>
                        </div>
                    </div>
                </div>
                <?php $pager->display($rows ? count($rows) : 0); ?>
            </div>
        </div>
    </div>

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>