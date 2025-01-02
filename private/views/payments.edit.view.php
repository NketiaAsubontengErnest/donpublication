<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <!-- partial -->
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Update Payment for <?= $rows->customers->customername ?></h4>

                    <form method="POST" class="forms-sample">
                        <div class="">
                            <label>Date</label>
                            <input value="<?=get_var('paymentdate',$rows->paymentdate)?>" name="paymentdate" id="paymentdate" type="date" required class="form-control form-control-lg">
                        </div>
                        <label for="exampleInputUsername1">Transaction ID</label>
                        <input type="text" name="transid" class="form-control" value="<?= get_var('transid',$rows->transid) ?>"
                            id="exampleInputUsername1" placeholder="transid" required>
                        <br>
                        <label for="exampleInputUsername1">Reciept Number</label>
                        <input type="text" name="reciept" class="form-control" value="<?= get_var('reciept',$rows->reciept) ?>"
                            id="exampleInputUsername1" placeholder="reciept" required>
                        <br>
                        <div class="form-group">
                            <label for="exampleInputUsername1">Amount</label>
                            <input type="text" name="" class="form-control"
                                value="<?= get_var('amount', $rows->amount) ?>" id="exampleInputUsername1" placeholder="00.00" readonly>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputUsername1">New Amount</label>
                            <input type="text" name="updateamount" class="form-control"
                                value="<?= get_var('amount',$rows->amount) ?>" id="exampleInputUsername1" placeholder="00.00" required>
                        </div>

                        <div class="">
                            <label>Mode</label>
                            <select name="modeofpayment" id="modeofpayment" class="form-control" required>
                                <option <?= get_select('modeofpayment', '', $rows->modeofpayment) ?> value=""> -- Payment Mode --</option>
                                <option <?= get_select('modeofpayment', 'UBA', $rows->modeofpayment) ?> value="UBA">UBA</option>
                                <option <?= get_select('modeofpayment', 'FIDELITY', $rows->modeofpayment) ?> value="FIDELITY">FIDELITY</option>
                                <option <?= get_select('modeofpayment', 'CBG', $rows->modeofpayment) ?> value="CBG">CBG</option>
                                <option <?= get_select('modeofpayment', 'NIB', $rows->modeofpayment) ?> value="NIB">NIB</option>
                                <option <?= get_select('modeofpayment', 'MOMO', $rows->modeofpayment) ?> value="MOMO">MOMO</option>
                                <option <?= get_select('modeofpayment', 'WHT', $rows->modeofpayment) ?> value="WHT">WHT</option>
                            </select>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="mdi mdi-content-save"> Save Changes</i>
                        </button>
                        <a class="btn btn-light" href="<?= HOME ?>/payments/viewpayments/<?= $rows->customers->id ?>">Cancel</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->


    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>