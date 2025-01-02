<?php $this->view('includes/header', ['crumbs'=>$crumbs, 'actives'=>$actives, 'hiddenSearch'=>$hiddenSearch,])?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row m-2">
                        <h4 class="card-title">List of Customers Payments</h4>
                    </div>
                    
                    <form method="get">
                        <div class="input-group col-lg-4">
                            <?php if ($hiddenSearch == ''):?>
                            <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                                <span class="input-group-text" id="search">
                                    <i class="icon-search"></i>
                                </span>
                            </div>                                
                            <input type="text" class="form-control" id="navbar-search-input" name="search_box"
                                placeholder="Search now" aria-label="search" aria-describedby="search">
                            <?php endif;?>
                        </div>
                    </form>

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
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows):?>
                                <?php foreach ($rows as $row):?>
                                    <?php
                                    try {
                                        //code...
                                        $netamt = $row->amout_disco->totaldept - $row->amout_disco->totaldisc;
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                    }
                                        $balance = 0;
                                        
                                        try {
                                            $balance = (($netamt - $row->totalpayment->totalpayed)/ $netamt) * 100;
                                        } catch (\Throwable $th) {
                                            //throw $th;
                                        }
                                    ?>
                                <tr>
                                    <td>
                                        <?=ucfirst(esc($row->customername))?> - <?=esc($row->region)?>
                                        (<?=ucfirst(esc($row->custphone))?>)
                                    </td>
                                    <td>
                                        <?=esc($row->firstname)?> <?=esc($row->lastname)?>
                                    </td>
                                    <td>
                                        <?=esc(number_format($row->amout_disco->totaldept,2))?>
                                    </td>
                                    <td>
                                        <?=esc(number_format($row->amout_disco->totalReturns,2))?>                              
                                    </td>
                                    <td>
                                        <?=esc(number_format($row->amout_disco->totaldisc,2))?>
                                    </td>
                                    <td>                                        
                                        <?=esc(number_format($netamt,2))?>
                                    </td>
                                    <td>
                                        <?=esc(number_format($row->totalpayment->totalpayed,2))?>
                                    </td>
                                    <td>
                                        <?=esc(number_format($netamt - $row->totalpayment->totalpayed,2))?> (<?=esc(number_format($balance))?>%) 
                                    </td>
                                    <td>
                                        <a href="<?=HOME?>/payments/viewpayments/<?=$row->cid?>">
                                            <i class="m-2 mdi mdi-cash-multiple"> Pay</i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="10" class="align-middle text-center text-sm">
                                        No Record Found
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php $pager->display($rows ? count($rows) : 0);?>
            </div>
        </div>
    </div>

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/)?>