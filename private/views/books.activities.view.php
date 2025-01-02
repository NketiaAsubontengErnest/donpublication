<?php $this->view('includes/header', ['crumbs'=>$crumbs, 'actives'=>$actives, 'hiddenSearch'=>$hiddenSearch,])?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title mb-0">Books Activities</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <thead>
                                <tr>
                                    <th>Book</th>
                                    <th>Quantity Added</th>
                                    <th>Stock Officer</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($rows):?>
                                    <?php foreach($rows as $row):?>
                                        <tr>
                                            <td class="font-weight-bold"><?=esc($row->books->level->class.' '.$row->books->subject->subject.' '.$row->books->booktype->booktype)?></td>
                                            <td class="font-weight-bold"><?=esc(number_format($row->quantity))?></td>
                                            <td class="font-weight-bold"><?=esc($row->officer->firstname)?> <?=esc($row->officer->lastname)?></td>
                                            <td class="font-weight-medium">
                                                <?=esc(get_date($row->dateadded))?>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                <?php else:?>
                                    <tr>
                                        <td colspan="4">
                                            No book added
                                        </td>
                                    </tr>
                                    
                                <?php endif?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php $pager->display($rows ? count($rows) : 0);?>
            </div>
        </div>
    </div>



    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/)?>