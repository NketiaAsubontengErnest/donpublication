<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                <div class="row m-2">
                        <h4 class="card-title">Transfer Customer from:
                            <?= $user->firstname . "-" . $user->lastname ?>
                            (<?= $user->username ?>)</h4>
                    </div>

                    <form action="" method="post" id="items_form">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <select name="officerid" class="form-control form-control-lg" id="select_box" required>
                                    <option value="">-- Officer --</option>
                                    <?php if ($rowc) : ?>
                                        <?php foreach ($rowc as $row) : ?>
                                            <option value="<?= esc($row->id) ?>"><?= esc($row->firstname) ?> <?= esc($row->lastname) ?> (<?= esc($row->username) ?>)</option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option>No Officer Found</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <button name="add" id="add" class="btn btn-primary float-right" data-toggle="modal" data-target="#customerModal">
                            <i class="mdi mdi-plus-circle"> Transfer of Customer</i>
                        </button>

                        <div class="table-responsive">
                            <table class="table table-striped" id="item_data">
                                <thead>
                                    <tr>
                                        <th>Customer ID</th>
                                        <th>Customer Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                        <button type="submit" name="insert" id="insert" class="btn btn-primary mr-2 mt-2">
                            <i class="mdi mdi-sd"> Submit</i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Modal for adding customers -->
    <div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerModalLabel">Add Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select Customer</label>
                        <select name="customer" class="form-control form-control-lg" id="customer">
                            <option value="">-- Customer --</option>
                            <?php if ($rows) : ?>
                                <?php foreach ($rows as $row) : ?>
                                    <option value="<?= esc($row->id) ?>"><?= esc($row->customername) ?> (<?= esc($row->region) ?> - <?= esc($row->custlocation) ?>)</option>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <option>No Customer Found</option>
                            <?php endif; ?>
                        </select>
                        <span id="error_customer" class="text-danger"></span>
                    </div>

                    <div class="form-group" align="center">
                        <input type="hidden" name="row_id" id="hidden_row_id" />
                        <button type="button" name="savecustomer" id="savecustomer" class="btn btn-info">Add to List</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="action_alert" title="Action"></div>

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let count = 0;
            let allcustomers = [];

            // Function to handle adding customer data to the table
            document.querySelector("#savecustomer").addEventListener("click", function() {
                const customer = document.getElementById('customer').value;

                // Validate input
                if (!customer) {
                    Swal.fire('Error', 'Customer is required!', 'error');
                    return;
                }

                // Ensure the customer is not already added
                if (allcustomers.includes(customer)) {
                    Swal.fire('Error', 'Customer already exists in the list!', 'error');
                    return;
                }

                count++;
                const customerText = document.querySelector(`#customer option[value="${customer}"]`).textContent;

                let output = `
            <tr id="row_${count}">
                <td>${customer} <input type="hidden" name="hidden_customer_id[]" value="${customer}" /></td>
                <td>${customerText} <input type="hidden" name="hidden_customer_name[]" value="${customerText}" /></td>
                <td><button type="button" name="remove_customer" class="btn btn-danger btn-xs remove_customer" id="${count}" data-customer-id="${customer}">Remove</button></td>
            </tr>
        `;

                allcustomers.push(customer);
                document.querySelector('#item_data tbody').insertAdjacentHTML('beforeend', output);

                // Reset modal form fields
                document.getElementById('customer').value = '';

                // Close the modal
                $('#customerModal').modal('hide');
            });

            // Handle removing rows
            document.addEventListener('click', function(event) {
                if (event.target && event.target.classList.contains('remove_customer')) {
                    const rowId = event.target.getAttribute('id');
                    const customerId = event.target.getAttribute('data-customer-id');

                    // Remove the row from the table
                    document.querySelector(`#row_${rowId}`).remove();

                    // Remove the customer from the allcustomers array
                    allcustomers = allcustomers.filter(customer => customer !== customerId);

                    // Notify user that item has been removed
                    Swal.fire('Removed!', 'The customer has been removed from the list.', 'success');
                }
            });
            // Initialize the searchable dropdown after the SweetAlert is opened
            setTimeout(() => {
                const selectBoxElement = document.getElementById('customer');
                dselect(selectBoxElement, {
                    search: true,
                    searchPlaceholder: 'Search for a customer...',
                    searchMaxHeight: 300
                });
            }, 50);
        });
    </script>