<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">

    <!-- partial -->
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Book Order Details</h4>
                    <p class="card-description">
                        Change the quantity if needed
                    </p>
                    <form method="POST" class="forms-sample">
                        <input type="text" name="orderid" class="form-control" value="<?= $rows[0]->ordernumber ?>"
                            id="exampleInputUsername1" placeholder="Username" hidden>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="exampleInputUsername1">Book</label>
                                    <input type="text" class="form-control"
                                        value="<?= $rows[0]->books->subject->subject . " " . $rows[0]->books->level->class . " " . $rows[0]->books->booktype->booktype ?>"
                                        id="exampleInputUsername1" placeholder="Username" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputUsername1">Order Date</label>
                                    <input type="text" class="form-control"
                                        value="<?= $rows[0]->orderdate ?>"
                                        id="exampleInputUsername1" placeholder="Username" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputUsername1">Order Type</label>
                                    <select name="ordertype" id="ordertype" class="form-control form-control-lg" required>
                                        <option value="">-- Order Type --</option>
                                        <?php if ($typedata) : ?>
                                            <?php foreach ($typedata as $row) : ?>
                                                <option <?= get_select('ordertype', esc($row->id), $rows[0]->ordertype) ?> value="<?= esc($row->id) ?>"><?= esc($row->typename) ?></option>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <option>No Order Found</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="exampleInputUsername1">Stock Man</label>
                                    <input type="text" class="form-control"
                                        value="<?= $rows[0]->storesOff->firstname . " " . $rows[0]->storesOff->lastname . " - " . $rows[0]->storesOff->username ?>"
                                        id="exampleInputUsername1" placeholder="Username" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputUsername1">Issued Date</label>
                                    <input type="text" class="form-control"
                                        value="<?= $rows[0]->orderdate ?>"
                                        id="exampleInputUsername1" placeholder="Username" readonly>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="exampleInputUsername1">Verification Officer</label>
                                    <input type="text" class="form-control"
                                        value="<?= $rows[0]->verificOff->firstname . " " . $rows[0]->verificOff->lastname . " - " . $rows[0]->verificOff->username ?>"
                                        id="exampleInputUsername1" placeholder="Username" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputUsername1">Verified Date</label>
                                    <input type="text" class="form-control"
                                        value="<?= $rows[0]->verifiedDate ?>"
                                        id="exampleInputUsername1" placeholder="Username" readonly>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="exampleInputPassword1">Quantity Ordered</label>
                                <input type="text" value="<?= $rows[0]->quantord ?>" class="form-control"
                                    id="exampleInputPassword1" placeholder="Password" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputConfirmPassword1">Supplied Quantity</label>
                                <input type="text" name="quantsupp" value="<?= $rows[0]->quantsupp ?>" class="form-control"
                                    id="exampleInputConfirmPassword1" placeholder="<?= $rows[0]->quantord ?>">
                            </div>
                        </div>

                        <?php if (!empty($rows[0]->verifiRetcOff)): ?>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="exampleInputPassword1">Return Verified By</label>
                                    <input type="text" value="<?= $rows[0]->verifiRetcOff->firstname . " " . $rows[0]->verifiRetcOff->lastname . " - " . $rows[0]->verifiRetcOff->username ?>" class="form-control"
                                        id="exampleInputPassword1" placeholder="Password" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="exampleInputConfirmPassword1">Return Date</label>
                                    <input type="date" name="" readonly value="<?= $rows[0]->retverdate ?>" class="form-control"
                                        id="exampleInputConfirmPassword1" placeholder="<?= $rows[0]->quantord ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="exampleInputConfirmPassword1">Returned Verified</label>
                                    <input type="text" name="retverquantacc" value="<?= $rows[0]->retverquantacc ?>" class="form-control"
                                        id="exampleInputConfirmPassword1">
                                </div>
                                <div class=" form-group col-md-3">
                                    <label for="exampleInputConfirmPassword1">Accepted Returned</label>
                                    <input type="text" name="retverquant" value="<?= $rows[0]->retverquant ?>" class="form-control"
                                        id="exampleInputConfirmPassword1">
                                </div>
                            </div>
                        <?php endif ?>

                        <div class=" row">
                            <div class="form-group col-md-3">
                                <label for="exampleInputPassword1">Invoice</label>
                                <input type="text" name="invoiceno" value="<?= $rows[0]->invoiceno ?>" class="form-control"
                                    id="exampleInputPassword1" placeholder="Password">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="exampleInputPassword1">Discount</label>
                                <input type="text" name="discount" value="<?= $rows[0]->discount ?>" class="form-control"
                                    id="exampleInputPassword1" placeholder="Password">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="exampleInputConfirmPassword1">Price GHC</label>
                                <input type="text" name="unitprice" value="<?= $rows[0]->unitprice ?>" class="form-control"
                                    id="exampleInputConfirmPassword1">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="exampleInputConfirmPassword1">Price Date</label>
                                <input type="text" value="<?= $rows[0]->pricedate ?>" class="form-control"
                                    id="exampleInputConfirmPassword1" readonly>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="mdi mdi-sd"> Edit</i>
                        </button>
                        <a class="btn btn-light" href="<?= HOME ?>/orders/listprice/<?= $rows[0]->ordernumber ?>">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->


    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>