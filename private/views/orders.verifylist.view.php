<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <?php
                    $count = 0;
                    $countVerif = 0;
                    if ($rows) : ?>
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
                                        Verify
                                    </th>
                                    <th>
                                        Book
                                    </th>
                                    <th>
                                        Quantity Ordered
                                    </th>
                                    <th>
                                        Quantity Supplied
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
                                                <?php if (($row->quantsupp == 0) || ($row->verificid == "")) : ?>
                                                    <?php if ($row->verificid == ''): ?>
                                                        <form method="POST">
                                                            <input type="text" name="orderid" value="<?= esc($row->id) ?>" hidden>
                                                            <input type="text" name="quantsupp" value="<?= esc($row->quantord) ?>" hidden>
                                                            <input type="text" name="bookid" value="<?= esc($row->bookid) ?>" hidden>
                                                            <button class="btn btn-primary btn-icon-text">
                                                                <i class="ti-file btn-icon-prepend"></i>
                                                                Verify
                                                            </button>
                                                        </form>
                                                    <?php else:
                                                        $countVerif++;
                                                    ?>
                                                        <i class="m-2 mdi mdi-check-all"> By <?= esc($row->verificOff->firstname) ?></i>
                                                    <?php endif; ?>
                                                <?php else :
                                                    $countVerif++;
                                                ?>
                                                    <i class="m-2 mdi mdi-check-all"> By <?= esc($row->verificOff->firstname) ?></i>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= $bookname ?>
                                            </td>
                                            <td>
                                                <?= esc($row->quantord) ?>
                                            </td>
                                            <td>
                                                <?php if (($row->quantsupp) != null || ($row->quantsupp) != '') : ?>
                                                    <?= esc($row->quantsupp) ?>
                                                <?php else : ?>
                                                    Not Verified
                                                <?php endif; ?>
                                            </td>
                                            <?php
                                            $pricedate = date("Y-m-d", strtotime($row->verifiedDate)); // Format verifiedDate to Y-m-d
                                            $today = date("Y-m-d");

                                            if ((($row->quantsupp) == 0 || ($row->quantsupp) == NULL) || ($pricedate == $today)) :
                                            ?>
                                                <td>
                                                    <a href="<?= HOME ?>/orders/verify/<?= $row->id ?>">
                                                        <i class="m-2 mdi mdi-auto-fix <?= $rows[0]->unitprice > 0 ? 'text-danger' : 'text-primary' ?>"> Edit</i>
                                                    </a>
                                                </td>
                                            <?php endif; ?>


                                            <?php
                                            $pricedate = date("Y-m-d", strtotime($row->verifiedDate));

                                            // Get today's date and the date of two days ago
                                            $today = date("Y-m-d");
                                            $two_days_ago = date("Y-m-d", strtotime("-7 days"));


                                            if ((($row->quantsupp) == 0 || ($row->quantsupp) == NULL) || (($pricedate >= $two_days_ago && $pricedate <= $today))) : ?>

                                                <td>
                                                    <?php if ((($row->quantsupp) == 0 || ($row->quantsupp) == NULL)) : ?>
                                                        <?php if ($row->verificid == ''): ?>
                                                            <form id="deleteForm-<?= $row->id ?>" action="" method="POST">
                                                                <button type="button" class="btn-sm btn-danger btn-rounded btn-icon" onclick="confirmDelete('<?= $row->id ?>', '<?= $bookname ?>')">
                                                                    <i class="mdi mdi-delete-forever"></i>
                                                                </button>
                                                                <input type="hidden" name="removeorder" value="<?= $row->id ?>">
                                                            </form>
                                                        <?php else: ?>
                                                            <i class="m-2 mdi mdi-close-box-off-outline text-danger"> Can't Delete Verified Item</i>
                                                        <?php endif ?>
                                                    <?php endif ?>
                                                </td>
                                            <?php
                                            else : ?>
                                                <td>
                                                    <i class="m-2 mdi mdi-check-all">Verified</i>
                                                </td>
                                            <?php endif; ?>

                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="align-middle text-center text-sm">
                                            No Order Found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (((Auth::getRank() == 'verification' || Auth::getRank() == 'account') && $rows[0]->verificid == '')): ?>
                        <?php if ($countVerif < $count): ?>
                            <form id="verifyAllForm" method="POST">
                                <input type="text" name="verific" value="all" hidden>
                                <button type="button" onclick="confirmVerifyAll()" class="btn btn-primary float-end btn-icon-text">
                                    <i class="ti-file btn-icon-prepend"></i>
                                    Verify All
                                </button>
                            </form>
                        <?php endif ?>
                    <?php elseif (($rows[0]->verificid != '') && ($rows[0]->maketeraccept == '' && $countVerif == $count)): ?>
                        <h4 class="text-danger">Wait for Acceptance from Maketer</h4>
                    <?php elseif ($countVerif != $count): ?>
                        <h4 class="text-success">Verification ongoing ...</h4>
                    <?php else: ?>
                        <h4 class="text-success">We are done with marketer</h4>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= ASSETS ?>/js/flush_arlert1.js"></script>
    <script>
        // Function to show SweetAlert confirmation for delete
        function confirmDelete(orderId, bookname) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to delete ' + bookname + '? This action cannot be undone!',
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

        function confirmVerifyAll() {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to verify all items?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, verify all!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form if the user confirms
                    document.getElementById('verifyAllForm').submit();
                }
            });
        }
    </script>

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>