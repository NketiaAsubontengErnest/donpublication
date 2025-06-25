<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="card shadow">
        <div class="card-header bg-primary text-white"></div>
        <h1 class="text-center">Send SMS to Customers</h1>
        <h6 class="text-center">SMS Balance: <?= esc(number_format($balance['data']['sms_balance'])) ?> | Main Balance: </h6>
        <div class="card-body">
            <form action="" method="GET">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="tableName" class="form-label">Data Source</label>
                        <select id="tableName" name="tableName" class="form-select" required>
                            <option value="">--Select Data Source --</option>
                            <option value="customers">Customers</option>
                            <option value="visitors">Visitors</option>
                            <!-- Add more table options as needed -->
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="dateFrom" class="form-label">Date From:</label>
                        <input type="date" id="dateFrom" name="dateFrom" class="form-control" s>
                    </div>
                    <div class="col-md-4">
                        <label for="dateTo" class="form-label">Date To:</label>
                        <input type="date" id="dateTo" name="dateTo" class="form-control" s>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" name="fetchNumbers" class="btn btn-primary">Fetch Numbers</button>
                </div>
            </form>

            <form action="" method="POST" class="mt-4">
                <style>
                    .textarea-wrapper {
                        position: relative;
                        margin-bottom: 1rem;
                    }

                    .textarea-footer {
                        text-align: right;
                        font-size: 0.875rem;
                        color: #6c757d;
                        margin-top: 0.25rem;
                    }
                </style>

                <div class="mb-3 textarea-wrapper">
                    <label for="phoneNumbers" class="form-label">Phone Numbers:</label>
                    <textarea id="phoneNumbers" name="phoneNumbers" class="form-control" rows="3" readonly><?php echo isset($phoneNumbersString) ? htmlspecialchars($phoneNumbersString . ', 0535172563, 0554013980, 0555217084') : ''; ?></textarea>

                    <?php
                    if (isset($phoneNumbersString) && !empty($phoneNumbersString)) {
                        $phoneNumbersArray = explode(',', $phoneNumbersString);
                        $phoneNumbersCount = count($phoneNumbersArray);
                        echo "<div class='textarea-footer'>Total Numbers: $phoneNumbersCount</div>";
                    }
                    ?>
                </div>

                <?php
                if ($phoneNumbersString !== '') {
                    echo "<div class='alert alert-success mt-3'>Phone numbers fetched successfully!</div>";
                } else {
                    echo "<div class='alert alert-warning mt-3'>No phone numbers found for the selected criteria.</div>";
                }
                ?>
                <div class="mb-3">
                    <label for="message" class="form-label">Message:</label>
                    <textarea id="message" name="message" class="form-control" rows="5" placeholder="Enter your message" required></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" name="sendSMS" class="btn btn-success">Send SMS</button>
                </div>
            </form>
        </div>
    </div>
    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>