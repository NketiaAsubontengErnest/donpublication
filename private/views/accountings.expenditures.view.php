<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button id="show-add-book" class="btn btn-primary float-right">
                        <i class="mdi mdi-account-multiple-plus"></i> Add Expends
                    </button>
                    <div class="row m-2">
                        <h4 class="card-title">List of Expenditure</h4>
                    </div>

                    <?php 
                    $subtotal = 0;
                    ?>

                    <div class="row">
                        <div class="col-md-6">
                            <form method="get">
                                <div class="input-group col-lg-10 mb-2">
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
                        </div>
                        <div class="col-md-6">
                            <form method="get" id="expendsForm">
                                <div class="input-group col-lg-10">
                                    <?php if ($hiddenSearch == '') : ?>
                                        <select name="search_expendType" class="form-control form-control-lg" id="search_expendType" style="color: black;" onchange="this.form.submit()">
                                            <option value="">-- Select Expends Type --</option>
                                            <?php foreach ($expens as $exps): ?>
                                                <option value="<?= $exps->id ?>"> <?= $exps->expendtype ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>

                    </div>



                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Expends Type</th>
                                    <th>Amount</th>
                                    <th>Vocher No.</th>
                                    <th>Cheque No.</th>
                                    <th>Naration</th>
                                    <th>By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows) : ?>
                                    <?php foreach ($rows as $row) : 
                                    $subtotal += $row->expendAmount;
                                        ?>
                                        <tr>
                                            <td><?= esc($row->expendDate) ?></td>
                                            <td><?= esc($row->extype->expendtype) ?></td>
                                            <td><?= esc(number_format($row->expendAmount, 2)) ?></td>
                                            <td><?= esc($row->vocherNo) ?></td>
                                            <td><?= esc($row->chequeNo) ?></td>
                                            <td><?= esc($row->naration) ?></td>
                                            <td><?= esc($row->user->firstname) ?></td>
                                            <td>
                                                <a href="<?= HOME ?>/employee/edit/<?= $row->id ?>">
                                                    <i class="m-2 mdi mdi-table-edit"></i>
                                                </a>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="8" class="align-middle text-center text-sm">
                                            No Expenditure Records Found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <h6><b>Sub Total: GHC <?=number_format($subtotal, 2)?></b> </h4>
                    </div>
                </div>
                <?php $pager->display($rows ? count($rows) : 0); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td colspan="4" class="align-middle">
                                                <h5><b>Total Income: </b> GHC <?= esc(number_format($dataSum->totalIncomeAmount)) ?> </h5>
                                            </td>
                                            <td colspan="4" class="align-middle">
                                                <h5><b>Total Expeds: </b> GHC <?= esc(number_format($dataSum->totalExpendAmount)) ?> </h5>
                                            </td>
                                            <td colspan="4" class="align-middle ">
                                                <h5> <b>Total Balance: </b> GHC <?= esc(number_format($dataSum->totalIncomeAmount - $dataSum->totalExpendAmount)) ?> </h1>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- <form action="" method="post">
                    <button name="generate" value="generator" class="btn btn-primary btn-icon-text">
                        <i class="mdi mdi-cash-multiple"></i>
                        Generate
                    </button>
                </form> -->

                        <form method="Post">
                            <button name="exportexl" class="btn btn-success">Export to Excel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popup for Adding Income -->
    <div class="modal fade" id="addIncomeModal" tabindex="-1" role="dialog" aria-labelledby="addIncomeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addIncomeModalLabel">Add Expenditure</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="add_income_form">
                        <div class="form-group">
                            <label for="expendDate">Date</label>
                            <input type="date" class="form-control" id="expendDate" name="expendDate" required placeholder="dd/mm/yyyy">
                        </div>
                        <div class="form-group">
                            <label for="expendType">Expends Type</label>
                            <select name="expendType" class="form-control form-control-lg" id="expendType" style="color: black;">
                                <option value="">-- Select Expends Type --</option>
                                <?php foreach ($expens as $exps): ?>
                                    <option value="<?= $exps->id ?>"> <?= $exps->expendtype ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="expendAmount">Amount</label>
                            <input type="text" class="form-control" id="expendAmount" name="expendAmount" required placeholder="20000">
                        </div>
                        <div class="form-group">
                            <label for="vocherNo">Voture No.</label>
                            <input type="text" class="form-control" id="vocherNo" name="vocherNo" required placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="chequeNo">Check No.</label>
                            <input type="text" class="form-control" id="chequeNo" name="chequeNo" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="naration">Naration</label>
                            <textarea class="form-control" name="naration" id="naration" rows="3"></textarea>
                        </div>
                        <button type="submit" id="submit_income" class="btn btn-primary float-right">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Triggering Modal with Button -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show modal when 'Add Income' button is clicked
            document.getElementById('show-add-book').addEventListener('click', function() {
                $('#addIncomeModal').modal('show');
            });

            // Close button functionality in the modal
            document.querySelectorAll('.close').forEach(function(element) {
                element.addEventListener('click', function() {
                    $('#addIncomeModal').modal('hide');
                });
            });

            // Form validation before submission
            document.getElementById('submit_income').addEventListener('click', function(event) {
                event.preventDefault(); // Prevent form submission

                // Get form values
                const expendDate = document.getElementById('expendDate');
                const expendAmount = document.getElementById('expendAmount');
                const expendType = document.getElementById('expendType');
                const vocherNo = document.getElementById('vocherNo');
                const naration = document.getElementById('naration');

                let isValid = true; // Flag to track if all fields are valid

                // Reset borders before validation
                [expendDate, expendAmount, expendType, chequeNo, vocherNo, naration].forEach(field => field.style.border = '');

                // Validate date
                if (!expendDate.value) {
                    expendDate.style.border = '2px solid red';
                    isValid = false;
                }

                // Validate amount
                if (!expendAmount.value) {
                    expendAmount.style.border = '2px solid red';
                    isValid = false;
                }

                // Validate naration
                if (!expendType.value) {
                    expendType.style.border = '2px solid red';
                    isValid = false;
                }

                // Validate amount
                if (!vocherNo.value) {
                    vocherNo.style.border = '2px solid red';
                    isValid = false;
                }

                // Validate naration
                if (!naration.value) {
                    naration.style.border = '2px solid red';
                    isValid = false;
                }

                // Show error alert if any field is invalid
                if (!isValid) {
                    Swal.fire('Error', 'Please fill all fields!', 'error');
                    return;
                }

                // If all fields are valid, submit the form
                document.getElementById('add_income_form').submit();
            });
        });
    </script>

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>