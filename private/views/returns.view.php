<?php $this->view('includes/header', ['crumbs'=>$crumbs, 'actives'=>$actives, 'hiddenSearch'=>$hiddenSearch,])?>
<div class="content-wrapper">

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">List of Returns</h4>
                    </div>

                    <form method="get">
                        <div class="input-group col-lg-4">
                            <?php if ($hiddenSearch == ''):?>
                            <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                                <span class="input-group-text" id="search">
                                    <i class="icon-search"></i>
                                </span>
                            </div>                                
                            <input type="text" class="form-control" id="navbar-search-input" name="search_box"
                                placeholder="Search now" aria-label="search" aria-describedby="search">
                            <?php endif;?>
                        </div>
                    </form>
                    
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
                                        Makerter
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
                                        <?=ucfirst(esc($row->makerter->firstname))?> <?=ucfirst(esc($row->makerter->lastname))?>
                                    </td>
                                    <td>
                                        <?=esc(get_date($row->retdate))?>
                                    </td>
                                    <td>
                                        <a href="<?=HOME?>/orders/list/<?=$row->ordernumber?>">
                                            <i class="m-2 mdi mdi-arrow-expand-all">View</i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="5" class="align-middle text-center text-sm">
                                        No Order Added
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