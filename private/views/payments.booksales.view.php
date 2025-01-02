<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title mb-0">Individual Books Sales</p>
                    <form method="get">
                        <div class="input-group col-lg-4">
                            <?php if ($hiddenSearch == '') : ?>
                                <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                                    <span class="input-group-text" id="search">
                                        <i class="icon-search"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" id="navbar-search-input" name="search_box" placeholder="Search now" aria-label="search" aria-describedby="search">
                            <?php endif; ?>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <thead>
                                <tr>
                                    <th>Book</th>
                                    <th>Total Quantity Supply</th>
                                    <th>Gross Sales</th>
                                    <th>Return Amount</th>
                                    <th>Net Return Amount</th>
                                    <th>Discount Amount</th>
                                    <th>Net Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows): ?>
                                    <?php foreach ($rows as $row): ?>
                                        <tr>
                                            <td><?= esc($row->level->class . ' ' . $row->subject->subject . ' ' . $row->booktype->booktype) ?></td>
                                            <td class="font-weight-bold"><?= esc(number_format($row->bookSales->totalQuantSuppAccountOfficerNotEmpty)) ?></td>
                                            <td class="font-weight-medium"><?= esc(number_format($row->bookSales->totalBookGross, 2)) ?></td>
                                            <td class="font-weight-medium"><?= esc(number_format($row->bookSales->totalBookReturns, 2)) ?></td>
                                            <td class="font-weight-medium"><?= esc(number_format($row->bookSales->total_net_returns, 2)) ?></td>
                                            <td class="font-weight-medium"><?= esc(number_format($row->bookSales->totalBookNet, 2)) ?></td>
                                            <td class="font-weight-medium"><?= esc(number_format($row->bookSales->totalBookGross - ($row->bookSales->totalBookNet), 2)) ?></td>
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