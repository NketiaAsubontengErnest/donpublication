<?php $this->view('includes/header', ['crumbs'=>$crumbs, 'actives'=>$actives, 'hiddenSearch'=>$hiddenSearch,])?>
<div class="content-wrapper">
    <!-- partial -->
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Confirm Update Payment for <?=$rows->customers->customername?></h4>
        
                    <form method="POST" class="forms-sample">
                        <label for="exampleInputUsername1">Transaction ID</label>
                        <input type="text" name="transid" class="form-control" value="<?=$rows->transid?>"
                            id="exampleInputUsername1" placeholder="transid" readonly>
                            <br>
                        <label for="exampleInputUsername1">Reciept Number</label>
                        <input type="text" name="reciept" class="form-control" value="<?=$rows->reciept?>"
                            id="exampleInputUsername1" placeholder="reciept" readonly>
                            <br>
                        <div class="form-group">
                            <label for="exampleInputUsername1">Amount</label>
                            <input type="text" name="" class="form-control"
                                value="<?=$rows->amount?>" id="exampleInputUsername1" placeholder="00.00" readonly>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputUsername1">New Amount</label>
                            <input type="text" name="updateamount" class="form-control"
                                value="<?=$rows->updateamount?>" id="exampleInputUsername1" placeholder="00.00" readonly>
                        </div>
                       
                        <button type="submit" class="btn btn-primary mr-2">Confirm Changes</button>
                        <a class="btn btn-light" href="<?=HOME?>/orders/list/<?=$rows[0]->ordernumber?>">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->


    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/)?>