<?php $this->view('includes/header', ['crumbs'=>$crumbs, 'actives'=>$actives, 'hiddenSearch'=>$hiddenSearch,])?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Order Type</h4>
                    <form action="" method="post">
                        <div class="row">
                            <div class="col-lg-4">
                                 <input type="text" name="typename" class="form-control m-2" id="typename" required
                                placeholder="CASH SALES ORDER">
                            </div>
                            <div class="col-lg-4">
                                <button class="btn btn-primary float-right">Add Order Type</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows):?>
                                <?php foreach ($rows as $row):?>
                                <tr>
                                    <td><?=esc($row->typename)?></td>
                                    <td><?=$row->typestatus == 1 ? "ACTIVE" : "BLOCKED"?></td>
                                    <td>
                                        <div class="row">
                                            <?php if ($row->typestatus == 0):?>
                                            <form method="post">
                                                <button class="btn btn-success btn-icon-text" name="activs" value="<?=$row->id?>">
                                                    <i class="mdi mdi-auto-fix"> ACTIVE</i>      
                                                </button>
                                            </form>
                                            <?php else:?>
                                            <form method="post">
                                                <button class="btn btn-dark btn-icon-text" name="block" value="<?=$row->id?>">
                                                    <i class="mdi mdi-block-helper"> BLOCK</i>                                                    
                                                </button>
                                            </form>
                                            <?php endif?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="3" class="align-middle text-center text-sm">
                                        No Order Type Added
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

<?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/)?>