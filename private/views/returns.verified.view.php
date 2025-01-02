<?php $this->view('includes/header', ['crumbs'=>$crumbs, 'actives'=>$actives, 'hiddenSearch'=>$hiddenSearch,])?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row m-2">
                        <h4 class="card-title">List of Verified Returns</h4>
                    </div>

                    <?php $this->view('patials/searcherbar', ['hiddenSearch'=>$hiddenSearch]);?>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Order Number
                                    </th>
                                    <th>
                                        Customer
                                    </th>
                                    <th>
                                        Marketer
                                    </th>
                                    <th>
                                        Return Date
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows):?>
                                <?php foreach ($rows as $row):?>
                                <tr>
                                    <td>
                                        <?=esc($row->ordernumber)?>
                                    </td>
                                    <td>
                                        <?=ucfirst(esc($row->customers->customername))?>
                                    </td>
                                    <td>
                                        <?=ucfirst(esc($row->customers->customername))?>
                                    </td>
                                    <td>
                                        <?=esc(get_date($row->retdate))?>
                                    </td>
                                    <td>
                                        <a href="<?=HOME?>/orders/list/<?=$row->ordernumber?>">
                                            <i class="m-2 mdi mdi-arrow-expand-all"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="5" class="align-middle text-center text-sm">
                                        No Verified Returns
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