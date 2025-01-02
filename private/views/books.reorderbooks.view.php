<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row m-2">
                        <h4 class="card-title">List of Reorder Level Books</h4>
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
                    <?php if (isset($errors['bookexist'])) : ?>
                        <div class="alert alert-warning alert-dismissible" role="alert">
                            <?= $errors['bookexist'] ?>
                        </div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Level</th>
                                    <th>Subject</th>
                                    <th>Book Type</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows) : ?>
                                    <?php foreach ($rows as $row) : ?>
                                        <tr>
                                            <td><?= esc($row->level->class) ?></td>
                                            <td><?= esc($row->subject->subject) ?></td>
                                            <td><?= esc($row->booktype->booktype) ?></td>
                                            <td><label class="<?= $row->quantity <= $row->treshhold ? 'badge badge-danger' : '' ?>"><?= esc(number_format($row->quantity)) ?></label></td>

                                            <td colspan="3">
                                                <?php if (isset($_SESSION['seasondata'])) : ?>
                                                    <a href="<?= HOME ?>/books/add/<?= $row->id ?>">
                                                        <i class="m-2 mdi mdi-plus"> Add</i>
                                                    </a>
                                                <?php else : ?>
                                                    <code>No Season set</code>
                                                <?php endif ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="8">
                                            No Reorder Level Books Yet
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php $pager->display($rows ? count($rows) : 0); ?>
            </div>
        </div>
    </div>
    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>