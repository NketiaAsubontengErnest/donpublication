<?php $this->view('includes/header', ['crumbs'=>$crumbs, 'actives'=>$actives, 'hiddenSearch'=>$hiddenSearch,])?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Expend Type</h4>
                    <form action="" method="post">
                        <div class="col-lg-4">
                            <input type="text" name="expendtype" class="form-control m-2" id="expendtype" required
                                placeholder="FUEL">
                            <button class="btn btn-primary float-right">Add Expend Type</button>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Expend Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows):?>
                                <?php foreach ($rows as $row):?>
                                <tr>
                                    <td><?=esc($row->expendtype)?></td>
                                    <td>
                                        <form method="post">
                                            <button class="btn btn-link btn-sm" name="dels" value="<?=$row->id?>">DEL</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="2" class="align-middle text-center text-sm">
                                        No Expend Type Added
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