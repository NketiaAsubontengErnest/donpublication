<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button name="add" id="add" class="btn btn-primary float-right" data-toggle="modal" data-target="#bookModal">
                        <i class="mdi mdi-plus-circle"> Add Items</i>
                    </button>

                    <div class="row">
                        <h4 class="card-title">Add Books to Order List</h4>
                    </div>

                    <form action="" method="post" id="items_form">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <?php if (!empty($cust)) : ?>
                                    <select name="customerid" class="form-control form-control-lg" id="customerid" required>
                                        <option value="<?= $cust->id ?>"><?= $cust->customername ?></option>
                                    </select>
                                <?php else : ?>
                                    <select name="customerid" class="form-control form-control-lg" id="customerid" required>
                                        <option value="">-- Customer --</option>
                                        <?php if ($rowc) : ?>
                                            <?php foreach ($rowc as $row) : ?>
                                                <option value="<?= esc($row->id) ?>"><?= esc($row->customername) ?> (<?= esc($row->custlocation) ?>)</option>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <option value="">No Customer Found</option>
                                        <?php endif; ?>
                                    </select>
                                <?php endif; ?>
                            </div>
                            <div class="form-group col-lg-6">
                                <?php if (empty($cust)) : ?>
                                    <select name="ordertype" id="ordertype" class="form-control form-control-lg" required>
                                        <option value="">-- Order Type --</option>
                                        <?php if ($typedata) : ?>
                                            <?php foreach ($typedata as $row) : ?>
                                                <option value="<?= esc($row->id) ?>"><?= esc($row->typename) ?></option>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <option value="">No Order Found</option>
                                        <?php endif; ?>
                                    </select>
                                <?php else : ?>
                                    <b> <label for="">ORDER NUM#: <?= $_SESSION['ordernum'] ?></label> </b>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped" id="item_data">
                                <thead>
                                    <tr>
                                        <th>Book No.</th>
                                        <th>Book</th>
                                        <th>Quantity</th>
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
                                    <option value="<?= esc($row->id) ?>"><?= esc($row->subject->subject) ?> <?= esc($row->level->class) ?> <?= esc($row->booktype->booktype) ?></option>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <option>No Book Found</option>
                            <?php endif; ?>
                        </select>
                        <span id="error_book" class="text-danger"></span>
                    </div>

                    <div class="form-group">
                        <label>Enter Quantity</label>
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

            // Function to handle adding book data to the table
            document.querySelector("#save").addEventListener("click", function() {
                const book = document.getElementById('book').value;
                const ordQuant = document.getElementById('ord_quant').value;

                // Validate input
                if (!book || !ordQuant) {
                    Swal.fire('Error', 'Both Book and Price are required!', 'error');
                    return;
                }

                // Ensure the book is not already added
                if (allbooks.includes(book)) {
                    Swal.fire('Error', 'Book already exists in the list!', 'error');
                    return;
                }

                count++;
                const bookText = document.querySelector(`#book option[value="${book}"]`).textContent;

                let output = `
            <tr id="row_${count}">
                <td>${book} <input type="hidden" name="hidden_book[]" value="${book}" /></td>
                <td>${bookText} <input type="hidden" name="hidden_book_name[]" value="${bookText}" /></td>
                <td>${ordQuant} <input type="hidden" name="hidden_ord_quant[]" value="${ordQuant}" /></td>
                <td><button type="button" name="remove_details" class="btn btn-danger btn-xs remove_details" id="${count}" data-book-id="${book}">Remove</button></td>
            </tr>
        `;

                allbooks.push(book);
                document.querySelector('#item_data tbody').insertAdjacentHTML('beforeend', output);

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
                    document.querySelector(`#row_${rowId}`).remove();

                    // Remove the book from the allbooks array
                    allbooks = allbooks.filter(book => book !== bookId);

                    // Notify user that item has been removed
                    Swal.fire('Removed!', 'The item has been removed from the list.', 'success');
                }
            });
        });
    </script>