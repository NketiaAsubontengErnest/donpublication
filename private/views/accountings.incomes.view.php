<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button id="show-add-book" class="btn btn-primary float-right">
                        <i class="mdi mdi-account-multiple-plus"></i> Add Income
                    </button>
                    <div class="row m-2">
                        <h4 class="card-title">List of Income</h4>
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
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Naration</th>
                                    <th>By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows) : ?>
                                    <?php foreach ($rows as $row) : ?>
                                        <tr>
                                            <td><?= esc(get_date($row->incomedate)) ?></td>
                                            <td><?= esc($row->incomeamount) ?></td>
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
                                        <td colspan="5" class="align-middle text-center text-sm">
                                            No Income Records Found
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
                                                <h5><b>Total Income: </b> GHC <?=esc(number_format($dataSum->totalIncomeAmount))?> </h5>
                                            </td>
                                            <td colspan="4" class="align-middle">
                                                <h5><b>Total Expeds: </b> GHC <?=esc(number_format($dataSum->totalExpendAmount))?> </h5>
                                            </td>
                                            <td colspan="4" class="align-middle ">
                                                <h5> <b>Total Balance: </b> GHC <?=esc(number_format($dataSum->totalIncomeAmount - $dataSum->totalExpendAmount))?> </h1>
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
                    <h5 class="modal-title" id="addIncomeModalLabel">Add Income</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="add_income_form">
                        <div class="form-group">
                            <label for="incomedate">Date</label>
                            <input type="date" class="form-control" id="incomedate" name="incomedate" required placeholder="dd/mm/yyyy">
                        </div>
                        <div class="form-group">
                            <label for="incomeamount">Amount</label>
                            <input type="text" class="form-control" id="incomeamount" name="incomeamount" required placeholder="20000">
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
                const incomeDate = document.getElementById('incomedate');
                const incomeAmount = document.getElementById('incomeamount');
                const naration = document.getElementById('naration');

                let isValid = true; // Flag to track if all fields are valid

                // Reset borders before validation
                [incomeDate, incomeAmount, naration].forEach(field => field.style.border = '');

                // Validate date
                if (!incomeDate.value) {
                    incomeDate.style.border = '2px solid red';
                    isValid = false;
                }

                // Validate amount
                if (!incomeAmount.value) {
                    incomeAmount.style.border = '2px solid red';
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