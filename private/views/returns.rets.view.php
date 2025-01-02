<?php $this->view('includes/header', ['crumbs'=>$crumbs, 'actives'=>$actives, 'hiddenSearch'=>$hiddenSearch,])?>
<div class="content-wrapper">
    <!-- partial -->
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Return Book</h4>
                    <p class="card-description">
                        Change the return quantity if needed
                    </p>
                    <form method="POST" class="forms-sample">
                        <input type="text" name="orderid" class="form-control" value="<?=$rows[0]->ordernumber?>"
                            id="exampleInputUsername1" placeholder="Username" hidden>
                        <input type="text" name="ret" class="form-control" value="ret" id="exampleInputUsername1"
                            placeholder="Username" hidden>
                        <div class="form-group">
                            <label for="exampleInputUsername1">Book</label>
                            <input type="text" class="form-control"
                                value="<?=$rows[0]->books->subject->subject ." ". $rows[0]->books->level->class ." ". $rows[0]->books->booktype->booktype?>"
                                id="exampleInputUsername1" placeholder="Username" readonly>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="exampleInputPassword1">Quantity Received</label>
                                <input type="text" name="quantsupp" value="<?=$rows[0]->quantsupp?>"
                                    class="form-control" id="exampleInputPassword1" placeholder="Password" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputPassword1">Quantity Returning</label>
                                <input type="text" name="retquant" value="" class="form-control"
                                    id="exampleInputPassword1" placeholder="10">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Return</button>
                        <a class="btn btn-light" href="<?=HOME?>/orders/list/<?=$rows[0]->ordernumber?>">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->


    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/)?>