<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <?php if ($rows): ?>
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
                                        <tr>
                                            <td><?php

                                                if (($row->retquant != 0)):

                                                    $verifiedDate = new DateTime($row->verifiedDate);
                                                    $now = new DateTime();
                                                    $interval = $now->diff($verifiedDate);

                                                    if ($interval->days <= 10):
                                                        //if (true):
                                                ?>
                                                        <form method="POST">
                                                            <input type="text" name="orderid" value="<?= esc($row->id) ?>" hidden>
                                                            <input type="text" name="quantsupp" value="<?= esc($row->quantsupp) ?>" hidden>
                                                            <input type="text" name="bookid" value="<?= esc($row->bookid) ?>" hidden>
                                                            <input type="text" name="retquant" value="<?= esc($row->retquant) ?>" hidden>
                                                            <button class="btn btn-success btn-icon-text" name="verifyret">
                                                                <i class="ti-back-left btn-icon-prepend"></i>
                                                                Verify
                                                            </button>
                                                        </form>
                                                    <?php elseif (($row->retverofficer == '')): ?>
                                                        <form method="POST">
                                                            <input type="text" name="orderid" value="<?= esc($row->id) ?>" hidden>
                                                            <input type="text" name="quantsupp" value="<?= esc($row->quantsupp) ?>" hidden>
                                                            <input type="text" name="bookid" value="<?= esc($row->bookid) ?>" hidden>
                                                            <input type="text" name="retquant" value="<?= esc($row->retquant) ?>" hidden>
                                                            <button class="btn btn-primary btn-icon-text">
                                                                <i class="ti-back-right btn-icon-prepend"></i>
                                                                Verify
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <i class="m-2 mdi mdi-check-all">Done</i>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <i class="m-2 mdi">No Return</i>
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
                                                <?php if (($row->retquant) > 0 && $row->retverquantacc == 0): ?>
                                                    Not Verified
                                                <?php elseif (($row->retverquantacc) > 0): ?>
                                                    Verified
                                                <?php else: ?>
                                                    Done
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($row->retquant > $row->retverquantacc): ?>
                                                    <a href="<?= HOME ?>/returns/verify/<?= $row->id ?>">Edit
                                                        <i class="m-2 mdi mdi-forward"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <i class="m-2 mdi mdi-check-all">Verified</i>
                                                <?php endif; ?>
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
                    <form method="POST">
                        <input type="text" name="returnal" value="all" hidden>
                        <button class="btn btn-primary float-end btn-icon-text">
                            <i class="ti-file btn-icon-prepend"></i>
                            Return All
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>