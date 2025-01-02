<?php $this->view('includes/header', ['crumbs'=>$crumbs, 'actives'=>$actives, 'hiddenSearch'=>$hiddenSearch,])?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row m-2">
                        <h4 class="card-title">List of Customers / Marketers</h4>
                    </div>
                    <?php $this->view('patials/searcherbar', ['hiddenSearch'=>$hiddenSearch]);?>                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Customer Name
                                    </th>
                                    <th>
                                        Type
                                    </th>
                                    <th>
                                        Region
                                    </th>
                                    <th>
                                        Officer
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
                                        <?=ucfirst(esc($row->customername))?> (<?=ucfirst(esc($row->custphone))?>)
                                    </td>
                                    <td>
                                        <?=esc(get_Cust_type($row->custtype))?>
                                    </td>
                                    <td>
                                        <?=esc($row->region)?>
                                    </td>
                                    <td>
                                        <?=esc($row->firstname)?> <?=esc($row->lastname)?>
                                    </td>
                                    <td>
                                        <a href="<?=HOME?>/orders/salelist/<?=$row->cid?>">
                                            <i class="m-2 mdi mdi mdi-arrow-expand-all"> View</i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="7" class="align-middle text-center text-sm">
                                        No Order Found
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