<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Banks</h4>
                    <form action="" method="post">
                        <div class="row">
                            <div class="col-lg-4">
                                <input type="text" name="bankname" class="form-control m-2" id="typename" required
                                    placeholder="Ghana Commercial Bank">
                            </div>
                            <div class="col-lg-4">
                                <input type="text" name="abrv" class="form-control m-2" id="abrv" required
                                    placeholder="GCB">
                            </div>
                            <div class="col-lg-4">
                                <button class="btn btn-primary mt-2">Add Bank</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Bank Name</th>
                                    <th>Abbreviation</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows): ?>
                                    <?php foreach ($rows as $row): ?>
                                        <tr>
                                            <td><?= esc($row->bankname) ?></td>
                                            <td><?= esc($row->abrv) ?></td>
                                            <td><?= $row->status == 1 ? "ACTIVE" : "BLOCKED" ?></td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <?php if ($row->status == 0): ?>
                                                        <form method="post">
                                                            <button class="btn btn-sm btn-success btn-icon-text" name="activs" value="<?= $row->id ?>">
                                                                <i class="mdi mdi-auto-fix"></i> ACTIVE
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <form method="post">
                                                            <button class="btn btn-sm btn-dark btn-icon-text" name="block" value="<?= $row->id ?>">
                                                                <i class="mdi mdi-block-helper"></i> BLOCK
                                                            </button>
                                                        </form>
                                                    <?php endif ?>
                                                    <form method="post">
                                                        <button class="btn btn-sm btn-danger btn-icon-text" name="dels" value="<?= $row->id ?>">
                                                            <i class="mdi mdi-delete"></i> DELETE
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="align-middle text-center text-sm">
                                            No Banks Added
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>