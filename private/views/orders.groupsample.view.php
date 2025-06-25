<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">Order List</h4>
                    </div>

                    <form action="" method="post" id="items_form">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <select name="customerid" class="form-control form-control-lg" id="customerid" required>
                                    <option value="">-- Customer --</option>
                                    <?php if ($rowc) : ?>
                                        <?php foreach ($rowc as $row) : ?>
                                            <option value="<?= esc($row->id) ?>"><?= esc($row->customername) ?> (<?= esc($row->custlocation) ?>)</option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="">No Customer Found</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <select name="ordertype" id="ordertype" class="form-control form-control-lg" required>
                                    <option value="">-- Order Type --</option>
                                    <?php if ($typedata) : ?>
                                        <?php foreach ($typedata as $row) : ?>
                                            <option value="<?= esc($row->id) ?>"><?= esc($row->typename) ?></option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="">No Order Found</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Textbook Quantity</label>
                                    <input type="number" name="ord_quant_text" class="form-control form-control-lg" />
                                    <span id="error_ord_quant" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Workbook Quantity</label>
                                    <input type="number" name="ord_quant_work" class="form-control form-control-lg" />
                                    <span id="error_ord_quant" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="insert" id="insert" class="btn btn-primary mr-2 mt-2">
                            <i class="mdi mdi-sd"> Submit</i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>