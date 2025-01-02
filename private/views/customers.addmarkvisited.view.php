<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title mb-0">Breakdown of officer supply</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <thead>
                                <tr>
                                    <th>Book</th>
                                    <th>Qty Supplied</th>
                                    <th>Sample Qty</th>
                                    <th>Qty Returned</th>
                                    <th>Sample Returned</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows): ?>
                                    <?php foreach ($rows as $row): ?>
                                        <tr>
                                            <td><?= esc($row->level->class . ' ' . $row->subject->subject . ' ' . $row->booktype->booktype) ?></td>
                                            <td class="font-weight-medium"><?= esc(number_format($row->ttMarketSupply->ttMarketSupply)) ?></td>

                                            <td class="font-weight-medium"><?= esc(number_format($row->ttMarkSampleSupply->ttMarkSampleSupply)) ?></td>
                                            <td class="font-weight-medium">
                                                <?= esc(number_format($row->ttmarketreturns->ttmarketreturns)) ?>
                                            </td>
                                            <td class="font-weight-medium">
                                                <?= esc(number_format($row->ttmarketreturns->retsample)) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <form method="Post">
                        <button name="exportexl" class="btn btn-success">Export to Excel</button>
                    </form>
                </div>
                <?php $pager->display($rows ? count($rows) : 0); ?>
            </div>
        </div>

    </div>

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>