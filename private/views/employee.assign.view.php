<?php $this->view('includes/header', ['crumbs'=>$crumbs, 'actives'=>$actives, 'hiddenSearch'=>$hiddenSearch,])?>
<div class="content-wrapper"> <!-- partial -->
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Assign</h4>
                    <p class="card-description">
                        Assign Marketer to Officer
                    </p>
                    <form method="POST" class="forms-sample">
                        <div class="form-group">
                            <label for="exampleInputUsername1">Book</label>
                            <input type="text" class="form-control"
                                value="<?=$rows[0]->username?> - <?=$rows[0]->firstname ." ". $rows[0]->lastname?> (<?=$rows[0]->phone?>)"
                                id="exampleInputUsername1" placeholder="Username" readonly>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Quantity Supplied</label>
                            <select name="officer" id="officer" class="form-control form-control-lg"
                                id="exampleFormControlSelect2">
                                <option value="">-- choose officer --</option>
                                <?php if ($rows['officers']):?>
                                <?php foreach ($rows['officers'] as $row):?>
                                <option value="<?=esc($row->id)?>"><?=esc($row->firstname)?>
                                    <?=esc($row->lastname)?> (<?=esc($row->phone)?>)</option>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <option>Officer</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mr-2">Assign</button>
                        <a class="btn btn-light" href="<?=ROOT?>/employee">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->


    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/)?>