<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">System Activities</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Activity</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows) : ?>
                                    <?php foreach ($rows as $row) : ?>
                                        <tr>
                                            <td><?= esc($row->users->firstname) ?> <?= esc($row->users->lastname) ?></td>
                                            <td><?= esc($row->activity) ?></td>
                                            <td><?= esc(get_date($row->activitiedate)) ?></td>
                                            <?php if ($row->loclink != '') : ?>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-6 ">
                                                            <a href="<?= HOME ?>/<?= $row->loclink ?>" class="btn btn-success btn-sm" target="_blank"><i class="mdi mdi-arrange-send-to-back"> Approve</i></a>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <form method="POST">
                                                                <button name="actdone" value="<?= esc($row->id) ?>" class="btn btn-primary btn-sm">
                                                                    <i class="mdi mdi-content-duplicate"> Done</i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            <?php else : ?>
                                                <td></td>
                                            <?php endif ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="align-middle text-center text-sm">
                                            No Season Added
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