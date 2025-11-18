<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row m-2">
                            <h4 class="card-title">List Payments: <?= $cust->customername ?> -
                                <?= $cust->custlocation . "-" . $cust->region ?>
                                (<?= $cust->custphone ?>)</h4>
                        </div>

                        <?php if (Auth::getRank() == 'verification' || Auth::getRank() == 'account') : ?>
                            <form id="paymentForm" method="POST">
                                <input name="officerid" value="<?= $cust->officerid ?>" class="typeahead" type="text" placeholder="154255102656854" hidden>
                                <div class="col-lg-12 row">
                                    <div class="col">
                                        <label>Transaction Id</label>
                                        <input name="transid" id="transid" class="typeahead form-control" type="text" placeholder="154255102656854" required>
                                    </div>
                                    <div class="col">
                                        <label>Receipt No.</label>
                                        <input name="reciept" id="reciept" class="typeahead form-control" type="text" placeholder="0000012" required>
                                    </div>
                                    <div class="col">
                                        <label>Amount GHC</label>
                                        <input name="amount" id="amount" class="typeahead form-control"
                                            type="text"
                                            placeholder="<?= number_format($totals['ttSales']->totalNetSales - $totals['ttPayment']->totalpayed, 2); ?>"
                                            required
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/^0+/, '');">
                                    </div>

                                    <div class="col">
                                        <label>Mode</label>
                                        <select name="modeofpayment" id="modeofpayment" class="form-control" required>
                                            <option value=""> -- Payment Mode / Bank --</option>
                                            <?php if ($banks): ?>
                                                <?php foreach ($banks as $bank) : ?>
                                                    <option value="<?= $bank->abrv ?>"><?= $bank->abrv ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label>Date</label>
                                        <input name="paymentdate" id="paymentdate" type="date" required class="form-control form-control-lg">
                                    </div>
                                    <div class="col">
                                        <label>&nbsp</label>
                                        <br>
                                        <button type="button" onclick="validateAndSubmit()" class="btn btn-primary">Add Payment</button>
                                    </div>
                                </div>
                            </form>
                        <?php endif ?>
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
                                            Date
                                        </th>
                                        <th>
                                            Receipt No.
                                        </th>
                                        <th>
                                            Transaction Id
                                        </th>
                                        <th>
                                            Mode
                                        </th>
                                        <th>
                                            Amount
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
                                                    <?= esc(get_date($row->paymentdate)) ?>
                                                </td>
                                                <td>
                                                    <?= esc($row->reciept) ?>
                                                </td>
                                                <td>
                                                    <?= ucfirst(esc($row->transid)) ?>
                                                </td>
                                                <td>
                                                    <?= ucfirst(esc($row->modeofpayment)) ?>
                                                </td>

                                                <td>
                                                    GHC <?= esc(number_format($row->amount, 2)) ?>
                                                    <?php if ($row->updateamount != 0) : ?>
                                                        Edited to <?= esc(number_format($row->updateamount, 2)) ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ((Auth::getRank() == 'verification' || Auth::getRank() == 'account')) : ?>
                                                        <a href="<?= HOME ?>/payments/edit/<?= $row->id ?>">
                                                            <i class="m-2"> Edit</i>
                                                        </a>
                                                    <?php endif ?>


                                                    <?php if (Auth::access('g-account') && $row->updateamount != NULL) : ?>
                                                        <a href="<?= HOME ?>/payments/approveedit/<?= $row->id ?>">
                                                            <i class="m-2"> Confirm Edit</i>
                                                        </a>
                                                    <?php endif ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="7" class="align-middle text-center text-sm">
                                                No Payments Received
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
                <div class="col-lg-12 ">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="row m-2">
                                    <h1 class="card-title">Totals</h1>
                                </div>
                            </div>
                            <div class="row  m-2">
                                <div class="col-md-2">
                                    <b>Gross Sales:</b> <?= number_format($totals['ttSales']->totalGrossSales, 2); ?>
                                </div>
                                <div class="col-md-2">
                                    <b>Discount Amt:</b> <?= number_format($totals['ttSales']->totalDiscount, 2); ?>
                                </div>
                                <div class="col-md-2">
                                    <b>Net Sales:</b> <?= number_format($totals['ttSales']->totalNetSales, 2); ?>
                                </div>
                                <div class="col-md-2">
                                    <b>Total Payment:</b> <?= number_format($totals['ttPayment']->totalpayed, 2); ?>
                                </div>
                                <div class="col-md-2">
                                    <b>Balance:</b> <?= number_format($totals['ttSales']->totalNetSales - $totals['ttPayment']->totalpayed, 2); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= ASSETS ?>/js/flush_arlert1.js"></script>
    <script>
        // Function to add commas to amount dynamically while typing
        document.getElementById('amount').addEventListener('input', function(e) {
            let value = e.target.value.replace(/,/g, ''); // Remove commas from previous formatting

            // Allow '.' as a valid decimal separator and retain it in the value
            let decimalPart = '';
            if (value.includes('.')) {
                let parts = value.split('.');
                value = parts[0]; // The whole number part
                decimalPart = '.' + parts[1]; // The decimal part
            }

            if (!isNaN(value) && value !== '') {
                e.target.value = Number(value).toLocaleString() + decimalPart; // Format with commas and keep decimal part
            }
        });


        // Function to validate form fields and show SweetAlert if any are empty
        function validateAndSubmit() {
            // Get references to the form fields
            let transid = document.getElementById('transid');
            let reciept = document.getElementById('reciept');
            let amount = document.getElementById('amount');
            let modeofpayment = document.getElementById('modeofpayment');
            let paymentdate = document.getElementById('paymentdate');

            // Initialize an array to collect error messages
            let errorMessages = [];

            // Initialize a variable to track if any field is empty
            let hasErrors = false;

            // Clear previous error styles
            [transid, reciept, amount, modeofpayment, paymentdate].forEach(field => {
                field.classList.remove('is-invalid');
            });

            // Check each field and collect error messages if they are empty
            if (!transid.value.trim()) {
                transid.classList.add('is-invalid');
                errorMessages.push('Transaction Id');
                hasErrors = true;
            }
            if (!reciept.value.trim()) {
                reciept.classList.add('is-invalid');
                errorMessages.push('Receipt No.');
                hasErrors = true;
            }
            if (!amount.value.replace(/,/g, '').trim()) {
                amount.classList.add('is-invalid');
                errorMessages.push('Amount');
                hasErrors = true;
            }
            if (!modeofpayment.value.trim()) {
                modeofpayment.classList.add('is-invalid');
                errorMessages.push('Payment Mode');
                hasErrors = true;
            }
            if (!paymentdate.value.trim()) {
                paymentdate.classList.add('is-invalid');
                errorMessages.push('Date');
                hasErrors = true;
            }

            if (hasErrors) {
                Swal.fire({
                    title: 'Missing Information',
                    html: 'Please fill out the following fields',
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // If all fields are filled, proceed to confirmation
            confirmAddPayment();
        }

        // Function to show SweetAlert confirmation for payment submission
        function confirmAddPayment() {
            let amount = document.getElementById('amount').value.replace(/,/g, ''); // Remove commas for calculation
            let formattedAmount = Number(amount).toLocaleString();

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to add a payment of GHC " + formattedAmount + "?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, add payment!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form if the user confirms
                    document.getElementById('paymentForm').submit();
                }
            });
        }
    </script>

    <!-- Add this CSS to style invalid fields -->
    <style>
        .is-invalid {
            border-color: #dc3545;
        }
    </style>
    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>