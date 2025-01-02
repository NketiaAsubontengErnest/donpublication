<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <?php
                    $count = 0;
                    $countVerif = 0;
                    $countIssue = 0;
                    if ($rows) : ?>
                        <div class="row mb-2">
                            <div class="col-md-5">
                                <h4 class="card-title"><b>School:</b> <?= esc($rows[0]->customers->customername) ?></h4>
                            </div>
                            <div class="col-md-5">
                                <h4 class="card-title"> <b>Marketer:</b> <?= esc($rows[0]->makerter->firstname) ?> <?= esc($rows[0]->makerter->lastname) ?></h4>
                            </div>
                            <div class="col-md-2">
                                <h4 class="card-title"> <b></b> <?= get_date(esc($rows[0]->orderdate)) ?></h4>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="row m-2">
                            <h4 class="card-title">List of Order Empty</h4>
                        </div>
                    <?php endif; ?>
                    <?php if (Auth::access('stores')) : ?>
                        <a href="<?= HOME ?>/orders/print/<?= esc($id) ?>" target="_blank" class="btn btn-primary float-right">
                            <i class="ti-printer btn-icon-append"></i>
                            Print Order
                        </a>
                    <?php else : ?>
                        <?php if ($rows[0]->issureid == '') : ?>
                            <a href="<?= HOME ?>/orders/placeorder/<?= esc($id) ?>" class="btn btn-primary float-right">
                                <i class="ti-plus btn-icon-append"> Add Books</i>
                            </a>
                        <?php endif ?>
                    <?php endif ?>

                    <?php $this->view('patials/searcherbar', ['hiddenSearch' => $hiddenSearch]); ?>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Book
                                    </th>
                                    <th>
                                        Quantity Ordered
                                    </th>
                                    <th>
                                        Quantity Supplied
                                    </th>
                                    <th>
                                        Quantity Returned
                                    </th>
                                    <th>
                                        Verif. Officer
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th colspan="2">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows) : ?>
                                    <?php foreach ($rows as $row) :
                                        $count++;
                                        $bookname = ucfirst(esc($row->books->level->class)) . " " . ucfirst(esc($row->books->subject->subject)) . " " . ucfirst(esc($row->books->booktype->booktype));
                                    ?>
                                        <tr>
                                            <td>
                                                <?= $bookname ?>
                                            </td>
                                            <td>
                                                <?= esc($row->quantord) ?>
                                            </td>
                                            <td>
                                                <?php if (($row->quantsupp) > 0) : ?>
                                                    <?= esc($row->quantsupp) ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= esc($row->retquant) ?>
                                            </td>
                                            <td>
                                                <?php if (isset($row->verificOff->firstname)) : ?>
                                                    <?= esc($row->verificOff->firstname) ?>                                                    
                                                <?php else : ?>
                                                    <a href="<?= HOME ?>/orders/verifylist/<?= esc($id) ?>"></a>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (($row->retquant) > 0 && (($row->retverquant) > 0)) : ?>
                                                    Verified, Returned Verified (<?= esc($row->retverofficer->firstname) ?>)
                                                    <?php if($row->retquant > $row->retverquantacc):?>
                                                        <a href="<?= HOME ?>/returns/retsedit/<?= $row->id ?>"> Edit</a>
                                                    <?php endif;?>
                                                <?php elseif (($row->quantsupp) > 0 && (($row->retquant) > 0)) : ?>
                                                    Verified & Returned <a href="<?= HOME ?>/returns/retsedit/<?= $row->id ?>"> Edit</a>
                                                <?php elseif (($row->quantsupp) > 0 || $row->verificid != '') : ?>
                                                    Verified
                                                <?php else : ?>
                                                    Not Verified
                                                <?php endif; ?>
                                                <?php
                                                if ($row->verificid != '') {
                                                    $countVerif++;
                                                }
                                                if ($rows[0]->issureid != '') {
                                                    $countIssue++;
                                                }
                                                ?>
                                            </td>

                                            <?php if ((Auth::getRank() == ('stores') || Auth::getRank() == ('marketer')) && $rows[0]->issureid == '') : ?>
                                                <?php if (($row->quantsupp) == 0 || ($row->quantsupp) == '') : ?>
                                                    <td>
                                                        <a href="<?= HOME ?>/orders/edit/<?= $row->id ?>">
                                                            <i class="m-2 mdi mdi-table-edit"> </i>Edit
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <form id="deleteForm-<?= $row->id ?>" action="" method="POST">
                                                            <button type="button" class="btn-sm btn-danger btn-rounded btn-icon" onclick="confirmDelete('<?= $row->id?>', '<?=$bookname ?>')">
                                                                <i class="mdi mdi-delete-forever"></i>
                                                            </button>
                                                            <input type="hidden" name="removeorder" value="<?= $row->id ?>">
                                                        </form>
                                                    </td>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <td>
                                                    <?php if (Auth::getRank() == ('marketer')) : ?>
                                                        <?php if (($row->retquant) >= ($row->quantsupp)) : ?>
                                                            Can't Return
                                                        <?php else : ?>
                                                            <a href="<?= HOME ?>/returns/rets/<?= $row->id ?>">
                                                                <i class="m-2 mdi mdi-backup-restore">Return</i>
                                                            </a>
                                                        <?php endif; ?>
                                                    <?php else : ?>
                                                        <i class="m-2">Done</i>
                                                    <?php endif; ?>
                                                </td>
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
                </div>
                <?php if ($rows) : ?>
                    <?php if (Auth::getRank() == 'stores' && $rows[0]->issureid == '') : ?>
                        <form action="" method="POST">
                            <input type="text" name="use" valuel="use" hidden>
                            <button class="m-2  btn btn-primary float-right">Done</button>
                        </form>
                    <?php endif ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?php if ((Auth::getRank() == 'marketer' && $rows[0]->verificid != '') && $rows[0]->maketeraccept == '') : ?>
                                <?php if ($countVerif == $count): ?>
                                    <h4 class="m-2 text-danger">Please we wait for your acceptance</h4>
                                    <form action="" method="POST">
                                        <input type="text" name="accept" valuel="<?= $rows[0]->ordernumber ?>" hidden>
                                        <button class="m-2 btn btn-success float-right"><i class="mdi mdi-cart"> Accept Verification</i></button>
                                    </form>
                                <?php endif ?>
                            <?php else: ?>
                                <?php if (Auth::getRank() == 'marketer'): ?>
                                    <?php if ($countVerif == $count  && $rows[0]->maketeraccept != ''): ?>
                                        <h4 class="m-2 text-danger">Thank you for Accepting. Do well to bring our invoice</h4>
                                    <?php endif ?>
                                <?php endif ?>
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?php if ((Auth::getRank() == 'stores' && $countVerif == 0)) : ?>
                                <form action="" method="POST">
                                    <input type="text" name="canc" valuel="<?= $rows[0]->ordernumber ?>" hidden>
                                    <button class="m-2  btn btn-danger"><i class="mdi mdi-cart-off"> Cancel Order</i></button>
                                </form>
                            <?php endif ?>
                            <?php if ((Auth::getRank() == 'marketer' && $countIssue == 0)) : ?>
                                <form action="" method="POST">
                                    <input type="text" name="canc" valuel="<?= $rows[0]->ordernumber ?>" hidden>
                                    <button class="m-2  btn btn-danger float-right"><i class="mdi mdi-cart-off"> Cancel Order</i></button>
                                </form>
                            <?php endif ?>
                        </div>
                    </div>

                <?php endif ?>
            </div>
        </div>
    </div>

    <script src="<?= ASSETS ?>/js/flush_arlert1.js"></script>
    <script>
        // Function to show SweetAlert confirmation for delete
        function confirmDelete(orderId, bookname) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to delete '+ bookname +'? This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, submit the corresponding form
                    document.getElementById('deleteForm-' + orderId).submit();
                }
            });
        }
    </script>

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>