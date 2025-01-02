<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row m-2">
                        <h4 class="card-title">List of Marketers</h4>
                    </div>

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
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        ID Number
                                    </th>
                                    <th>
                                        Full Name
                                    </th>
                                    <th>
                                        Total Book(s) Taken Out
                                    </th>
                                    <th>
                                        No. of School
                                    </th>
                                    <th>
                                        No. of Workbook(s)
                                    </th>
                                    <th>
                                        No. of Textbook(s)
                                    </th>
                                    <th>
                                        Total Book(s) Shared
                                    </th>

                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows) : ?>
                                    <?php foreach ($rows as $row) : ?>
                                        <tr>
                                            <td>
                                                <?= esc($row->username) ?>
                                            </td>
                                            <td>
                                                <?= ucfirst(esc($row->firstname)) ?> <?= ucfirst(esc($row->lastname)) ?>
                                            </td>
                                            <td>
                                                <?= isset($row->totalBooks->quantsupp) ? $row->totalBooks->quantsupp : 0 ?>
                                            </td>

                                            <td>
                                                <?= esc(isset($row->visitors->visitor) ? $row->visitors->visitor : 0) ?>
                                            </td>

                                            <td>
                                                <?= esc(isset($row->ttshered->ttworkbook) ? $row->ttshered->ttworkbook : 0) ?>
                                            </td>

                                            <td>
                                                <?= esc(isset($row->ttshered->tttextbook) ? $row->ttshered->tttextbook : 0) ?>
                                            </td>

                                            <td>
                                                <?= esc(isset($row->ttshered->ttshered) ? $row->ttshered->ttshered : 0) ?>
                                            </td>
                                            <td>
                                                <a href="<?= HOME ?>/customers/addmarkvisited/<?= $row->id ?>">
                                                    <h6>Details <i class="mdi mdi-chevron-double-right"></i></h6>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="7" class="align-middle text-center text-sm">
                                            No Employee Added
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (Auth::access('stores')) : ?>
                        <form method="Post">
                            <button name="exportexl" class="btn btn-success">Export to Excel</button>
                        </form>
                    <?php endif; ?>
                </div>
                <?php $pager->display($rows ? count($rows) : 0); ?>
            </div>
        </div>
    </div>

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>