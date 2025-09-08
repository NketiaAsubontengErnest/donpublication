<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <?php
                    $subtotal = 0;
                    $discountpers = 0;
                    if ($rows) : ?>
                        <div class="row m-2">
                            <h4 class="card-title">Audit Price & Discount</h4>
                            <div class="row mb-2">
                                <div class="col-md-5">
                                    <h6 class="card-title"><b>School:</b> <?= esc($rows[0]->customers->customername) ?></h6>
                                </div>
                                <div class="col-md-5">
                                    <h6 class="card-title"> <b>Marketer:</b> <?= esc($rows[0]->makerter->firstname) ?> <?= esc($rows[0]->makerter->lastname) ?></h6>
                                </div>
                                <div class="col-md-2">
                                    <h6 class="card-title"> <b></b> <?= get_date(esc($rows[0]->orderdate)) ?></h6>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p><b>ORDER NO. : </b> <?= esc($disc) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><b>INVOICE NO. : </b> <?= esc($rows[0]->invoiceno) ?></p>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="row">
                            <h4 class="card-title">List of Orders</h4>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Book
                                    </th>
                                    <th>
                                        Quantity Supplied
                                    </th>
                                    <th>
                                        Quantity Returned
                                    </th>
                                    <th>
                                        Discount (%)
                                    </th>
                                    <th>
                                        Unit Price(GH¢)
                                    </th>
                                    <th>
                                        Sub Total(GH¢)
                                    </th>
                                    <?php if (Auth::access('verification')) : ?>
                                        <th>
                                            Action
                                        </th>
                                    <?php endif ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows) : ?>
                                    <?php foreach ($rows as $row) : ?>
                                        <tr>
                                            <td>
                                                <?= ucfirst(esc($row->books->level->class)) ?>
                                                <?= ucfirst(esc($row->books->subject->subject)) ?>
                                                <?= ucfirst(esc($row->books->booktype->booktype)) ?>
                                            </td>
                                            <td>
                                                <?php if (($row->quantsupp) > 0) : ?>
                                                    <?= esc($row->quantsupp) ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= esc($row->retverquant) ?>
                                            </td>
                                            <td>
                                                <?= esc($row->discount) ?> %
                                                <?php $discountpers = esc($row->discount) ?>
                                            </td>
                                            <td>
                                                <?= esc($row->unitprice) ?>
                                                <?php if ($row->updateprice != 0) : ?>
                                                    Price changed to <?= $row->updateprice ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $unittotal = ($row->quantsupp - $row->retverquant) * $row->unitprice;
                                                $subtotal += $unittotal;
                                                ?>
                                                <?= esc(number_format($unittotal, 2)) ?>
                                            </td>
                                            <?php if ($row->quantsupp != 0 || $row->quantsupp != null) : ?>
                                                <?php if (Auth::access('g-account') && $row->updateprice != 0) : ?>
                                                    <td>
                                                        <a href="<?= HOME ?>/orders/acceptprice/<?= $row->id ?>">
                                                            <b><i class="m-2 mdi">Amend Changes</i></b>
                                                        </a>
                                                    </td>
                                                <?php elseif (Auth::access('verification') && Auth::getRank() != 'director'  && Auth::getRank() != 'g-account') : ?>
                                                    <?php
                                                    $pricedate = $row->pricedate;

                                                    // Get today's date and the date of two days ago
                                                    $today = date("Y-m-d");
                                                    $two_days_ago = date("Y-m-d", strtotime("-2 days"));

                                                    if (($row->unitprice == 0) || ($pricedate >= $two_days_ago && $pricedate <= $today)) : ?>
                                                        <td>
                                                            <a href="<?= HOME ?>/orders/applyprice/<?= $row->id ?>">
                                                                <b><i class="m-2 mdi mdi-backup-restore <?= $row->unitprice > 0 ? 'text-danger' : 'text-success' ?>"> Add Price</i></b>
                                                            </a>
                                                        </td>
                                                    <?php else : ?>
                                                        <td>
                                                            <a href="<?= HOME ?>/orders/editprice/<?= $row->id ?>">
                                                                <i class="m-2 mdi mdi-backup-restore">Edit Price</i>
                                                            </a>
                                                        </td>
                                                    <?php endif ?>
                                                <?php else : ?>
                                                    <td></td>
                                                <?php endif ?>
                                            <?php else : ?>
                                                <td></td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="7" class="align-middle text-center text-sm">
                                            No Order Found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row m-2">
                        <div class="col-lg-6">

                        </div>
                        <div class="col-lg-4">
                            <h4 class="card-title"></h4>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th rowspan="4">

                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            SUB TOTAL
                                        </th>
                                        <th>
                                            : <?= esc(number_format($subtotal, 2)) ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            DISCOUNT (<?= $discountpers ?> %)
                                        </th>
                                        <th>
                                            <?php
                                            $discamount = esc(($discountpers / 100) * $subtotal);
                                            ?>
                                            : <?= esc(number_format($discamount, 2)) ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            GRAND TOTAL
                                        </th>
                                        <th>
                                            : <?= esc(number_format($subtotal - $discamount, 2)) ?>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <h4 class="card-title">Non Invoiced Returns</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <th>
                                    Book
                                </th>
                                <th>
                                    Ordered
                                </th>
                                <th>
                                    Return
                                </th>
                            </thead>
                            <tbody>
                                <?php if ($retrows): ?>
                                    <?php foreach ($retrows as $row): ?>
                                        <tr>
                                            <td>
                                                <?= ucfirst(esc($row->books->level->class)) ?>
                                                <?= ucfirst(esc($row->books->subject->subject)) ?>
                                                <?= ucfirst(esc($row->books->booktype->booktype)) ?>
                                            </td>
                                            <td>
                                                <?= $row->ordered ?>
                                            </td>
                                            <td>
                                                <?= $row->quant ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?php if ($rows[0]->audit != 1): ?>
                                <form action="" method="post">
                                    <button name="auditdone" value="1" class="btn btn-primary ">
                                        <i class="">Audit Done</i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?php if ($rows[0]->audit != 2 && $rows[0]->audit != 1): ?>
                                <form action="" method="post">
                                    <button name="findings" value="2" class="btn btn-warning float-end">
                                        <i class="">Audit with Findings</i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>