<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <?php
                    $count = 0;
                    if ($rows): ?>
                        <div class="row">
                            <div class="col-md-5">
                                <h4 class="card-title"><b>School:</b> <?= esc($rows[0]->customers->customername) ?> (<?= esc($rows[0]->customers->custlocation) ?>)</h4>
                            </div>
                            <div class="col-md-5">
                                <h4 class="card-title"> <b>Marketer:</b> <?= esc($rows[0]->makerter->firstname) ?> <?= esc($rows[0]->makerter->lastname) ?></h4>
                            </div>
                            <div class="col-md-2">
                                <h4 class="card-title"> <b></b> <?= get_date(esc($rows[0]->orderdate)) ?></h4>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <h4 class="card-title">List of Returns</h4>
                        </div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>
                                            Verify
                                        </th>
                                        <th>
                                            Book
                                        </th>
                                        <th>
                                            Quantity Supplied
                                        </th>
                                        <th>
                                            Quantity Returning
                                        </th>
                                        <th>
                                            Acc. Veriry Qty
                                        </th>
                                        <th>
                                            Stores Accepted
                                        </th>
                                        <th>
                                            Status
                                        </th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($rows): ?>
                                        <?php foreach ($rows as $row): ?>
                                            <?php if (($row->retverquantacc > $row->retverquant)): ?>
                                                <tr>
                                                    <td>
                                                        <?php
                                                        $count++;
                                                        if (($row->retverquantacc > $row->retverquant)): ?>
                                                            <form method="POST">
                                                                <input type="text" name="orderid" value="<?= esc($row->id) ?>" hidden>
                                                                <input type="text" name="quantsupp" value="<?= esc($row->quantsupp) ?>" hidden>
                                                                <input type="text" name="bookid" value="<?= esc($row->bookid) ?>" hidden>
                                                                <input type="text" name="retquant" value="<?= esc($row->retverquantacc) ?>" hidden>
                                                                <button class="btn btn-primary btn-icon-text">
                                                                    <i class="m-2 mdi mdi-check-all"></i>
                                                                    Accept
                                                                </button>
                                                            </form>
                                                        <?php else: ?>
                                                            <i class="m-2 mdi mdi-check-all">Done</i>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?= ucfirst(esc($row->books->level->class)) ?>
                                                        <?= ucfirst(esc($row->books->subject->subject)) ?>
                                                        <?= ucfirst(esc($row->books->booktype->booktype)) ?>
                                                    </td>
                                                    <td>
                                                        <?= esc($row->quantsupp) ?>
                                                    </td>
                                                    <td>
                                                        <?= esc($row->retquant) ?>
                                                    </td>
                                                    <td>
                                                        <?= esc($row->retverquantacc) ?>
                                                    </td>
                                                    <td>
                                                        <?= esc($row->retverquant) ?>
                                                    </td>
                                                    <td>
                                                        <?php if (($row->retverquantacc) > 0 && $row->retverquant == 0): ?>
                                                            Not Accepted
                                                        <?php elseif (($row->retverquantacc) > 0): ?>
                                                            Accepted
                                                        <?php else: ?>
                                                            Done
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if (($row->retverquantacc > $row->retverquant)): ?>
                                                            <form method="POST">
                                                                <input type="text" name="orderid" value="<?= esc($row->id) ?>" hidden>
                                                                <input type="text" name="quantsupp" value="<?= esc($row->quantsupp) ?>" hidden>
                                                                <input type="text" name="bookid" value="<?= esc($row->bookid) ?>" hidden>
                                                                <input type="text" name="retquant" value="<?= esc($row->retverquantacc) ?>" hidden>
                                                                <button class="btn btn-primary btn-icon-text">
                                                                    <i class="m-2 mdi mdi-check-all"></i>
                                                                    Accept
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endif ?>
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
                    <?php if ($count > 1): ?>
                        <form method="POST">
                            <input type="text" name="returnal" value="all" hidden>
                            <button class="btn btn-primary float-end btn-icon-text">
                                <i class="m-2 mdi mdi-check-all"></i>
                                Accept All
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>