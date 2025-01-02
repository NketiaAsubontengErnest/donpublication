<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button name="add" id="add" class="btn btn-primary float-right" data-toggle="modal" data-target="#bookModal">
                        <i class="mdi mdi-plus-circle"> Add Price</i>
                    </button>

                    <div class="row">
                        <h4 class="card-title">Add Price List</h4>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-5">
                            <h6 class="card-title"><b>School:</b> <?= esc($rows[0]->customers->customername) ?></h6>
                        </div>
                        <div class="col-md-5">
                            <h6 class="card-title"><b>Marketer:</b> <?= esc($rows[0]->makerter->firstname) ?> <?= esc($rows[0]->makerter->lastname) ?></h6>
                        </div>
                        <div class="col-md-2">
                            <h6 class="card-title"><?= get_date(esc($rows[0]->orderdate)) ?></h6>
                        </div>
                    </div>

                    <form action="" method="post" id="items_form">
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label>Discount %</label>
                                <input type="text" name="discount" class="form-control form-control-lg" required>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Invoice No.:#</label>
                                <input type="text" name="invoiceno" class="form-control form-control-lg" required>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped" id="item_data">
                                <thead>
                                    <tr>
                                        <th>Book No.</th>
                                        <th>Book</th>
                                        <th>Price</th>
                                        <th>Sub Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" align="right"><strong>Grand Total:</strong></td>
                                        <td id="grand_total">0</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
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

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="bookModal" tabindex="-1" role="dialog" aria-labelledby="bookModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookModalLabel">Set Price to Books</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select Book</label>
                        <select name="book" class="form-control form-control-lg" id="book">
                            <option value="">-- Book --</option>
                            <?php if ($rows) : ?>
                                <?php foreach ($rows as $row) : ?>
                                    <option value="<?= esc($row->id) ?>"><?= esc($row->books->subject->subject) ?> <?= esc($row->books->level->class) ?> <?= esc($row->books->booktype->booktype) ?> (<?= esc($row->quantsupp) ?>)</option>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <option>No Book Found</option>
                            <?php endif; ?>
                        </select>
                        <span id="error_book" class="text-danger"></span>
                    </div>

                    <div class="form-group">
                        <label>Enter Price</label>
                        <input type="number" name="ord_quant" id="ord_quant" class="form-control form-control-lg" />
                        <span id="error_ord_quant" class="text-danger"></span>
                    </div>

                    <div class="form-group" align="center">
                        <input type="hidden" name="row_id" id="hidden_row_id" />
                        <button type="button" name="save" id="save" class="btn btn-info">Add to List</button>
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
            let allbooks = [];
            let grandTotal = 0; // Track the grand total

            // Function to update the Grand Total in the table
            function updateGrandTotal() {
                document.getElementById('grand_total').textContent = grandTotal.toFixed(2); // Update the displayed Grand Total
            }

            // Function to handle adding book data to the table
            document.querySelector("#save").addEventListener("click", function() {
                const book = document.getElementById('book').value;
                const ordQuant = document.getElementById('ord_quant').value;

                // Validate input
                if (!book || !ordQuant) {
                    Swal.fire('Error', 'Both Book and Price are required!', 'error');
                    return;
                }

                // Check if the book already exists in the list
                if (allbooks.includes(book)) {
                    Swal.fire({
                        title: 'Item Already Exists',
                        text: 'This book is already added to the list. Would you like to update the quantity or price?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Update',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Handle updating the item if the user clicks 'Update'
                            const rowId = allbooks.indexOf(book) + 1;
                            const row = document.querySelector(`#row_${rowId}`);
                            const quantityMatch = row.querySelector('td:nth-child(2)').textContent.match(/\((\d+)\)/);
                            const quantity = quantityMatch ? parseInt(quantityMatch[1], 10) : 1;
                            const newTotal = quantity * ordQuant;

                            // Update the total and Grand Total
                            const oldTotal = parseFloat(row.querySelector('td:nth-child(4)').textContent);
                            grandTotal -= oldTotal; // Subtract the old total from the Grand Total

                            row.querySelector('td:nth-child(3)').innerHTML = `${ordQuant} <input type="hidden" name="hidden_ord_quant[]" value="${ordQuant}" />`;
                            row.querySelector('td:nth-child(4)').innerHTML = `${newTotal} <input type="hidden" name="hidden_total[]" value="${newTotal}" />`;

                            grandTotal += newTotal; // Add the new total to the Grand Total
                            updateGrandTotal();
                            Swal.fire('Updated!', 'The item has been updated.', 'success');
                        }
                    });
                    return;
                }

                // Extract quantity from the book option text, assuming it's in parentheses
                const bookOption = document.querySelector(`#book option[value="${book}"]`);
                const bookText = bookOption.textContent;
                const quantityMatch = bookText.match(/\((\d+)\)/);
                const quantity = quantityMatch ? parseInt(quantityMatch[1], 10) : 1;

                const total = quantity * ordQuant;

                count++;
                let output = `
            <tr id="row_${count}">
                <td>${book} <input type="hidden" name="hidden_book[]" value="${book}" /></td>
                <td>${bookText} <input type="hidden" name="hidden_book_name[]" value="${bookText}" /></td>
                <td>${ordQuant} <input type="hidden" name="hidden_ord_quant[]" value="${ordQuant}" /></td>
                <td>${total} <input type="hidden" name="hidden_total[]" value="${total}" /></td>
                <td><button type="button" name="remove_details" class="btn btn-danger btn-xs remove_details" id="${count}" data-book-id="${book}">Remove</button></td>
            </tr>
        `;

                allbooks.push(book);
                document.querySelector('#item_data tbody').insertAdjacentHTML('beforeend', output);

                // Update the Grand Total
                grandTotal += total;
                updateGrandTotal();

                // Reset modal form fields
                document.getElementById('book').value = '';
                document.getElementById('ord_quant').value = '';

                // Close the modal
                $('#bookModal').modal('hide');
            });

            // Handle removing rows
            document.addEventListener('click', function(event) {
                if (event.target && event.target.classList.contains('remove_details')) {
                    const rowId = event.target.getAttribute('id');
                    const bookId = event.target.getAttribute('data-book-id');

                    // Remove the row from the table
                    const row = document.querySelector(`#row_${rowId}`);
                    const rowTotal = parseFloat(row.querySelector('td:nth-child(4)').textContent);
                    row.remove();

                    // Remove the book from the allbooks array and update Grand Total
                    allbooks = allbooks.filter(book => book !== bookId);
                    grandTotal -= rowTotal; // Subtract the row's total from the grand total
                    updateGrandTotal();

                    Swal.fire('Removed!', 'The item has been removed from the list.', 'success');
                }
            });
        });
    </script>