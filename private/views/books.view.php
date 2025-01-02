<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button name="add" id="add" class="btn btn-primary float-right" data-toggle="modal" data-target="#bookModal">
                        <i class="m-2 mdi mdi mdi-gamepad"> Add Book</i>
                    </button>
                    <div class="row m-2">
                        <h4 class="card-title">List of Books</h4>
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
                    <?php if (isset($errors['bookexist'])) : ?>
                        <div class="alert alert-warning alert-dismissible" role="alert">
                            <?= $errors['bookexist'] ?>
                        </div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Level</th>
                                    <th>Subject</th>
                                    <th>Book Type</th>
                                    <th>Quantity</th>
                                    <?php if (Auth::access('director')) : ?>
                                        <th>Profit</th>
                                        <th>Tithe</th>
                                    <?php endif ?>
                                    <th></th>
                                    <th align="centre" colspan="3">
                                        Action
                                    </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows) : ?>
                                    <?php foreach ($rows as $row) : ?>
                                        <tr>
                                            <td><?= esc($row->level->class) ?></td>
                                            <td><?= esc($row->subject->subject) ?></td>
                                            <td><?= esc($row->booktype->booktype) ?></td>
                                            <td><label class="<?= $row->quantity <= $row->treshhold ? 'badge badge-danger' : '' ?>"><?= esc(number_format($row->quantity)) ?></label></td>
                                            <?php if (Auth::access('director')) : ?>
                                                <td><?= esc($row->profit) ?></td>
                                                <td><?= esc($row->tithe) ?></td>
                                            <?php endif ?>
                                            <td colspan="3">
                                                <?php if (isset($_SESSION['seasondata'])) : ?>
                                                    <a href="<?= HOME ?>/books/add/<?= $row->id ?>">
                                                        <i class="m-2 mdi mdi-plus"> Add</i>
                                                    </a>
                                                <?php else : ?>
                                                    <code>No Season set</code>
                                                <?php endif ?>
                                            </td>
                                            <td>
                                                <?php if (Auth::access('director')) : ?>
                                                    <a href="<?= HOME ?>/books/setprofit/<?= $row->id ?>">
                                                        <i class="m-2 mdi mdi-piggy text-succes"> Set Profit</i>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (Auth::access('director')) : ?>
                                                    <a href="<?= HOME ?>/books/damaged/<?= $row->id ?>">
                                                        <i class="m-2 mdi mdi-block-helper text-danger"> Damaged</i>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="8">
                                            No Book Added
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
    </div>

    <div class="modal fade" id="bookModal" tabindex="-1" role="dialog" aria-labelledby="bookModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookModalLabel">Add New Book</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="addBookForm">
                        <!-- Subject Select -->
                        <div class="form-group">
                            <label for="subjectSelect">Subject</label>
                            <select name="subjectid" class="form-control" id="subjectSelect">
                                <option value="">-- Select Subject --</option>
                                <?php if ($rows1['subject']) : ?>
                                    <?php foreach ($rows1['subject'] as $row) : ?>
                                        <option value="<?= esc($row->id) ?>"><?= esc($row->subject) ?></option>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <option>No subjects available</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Class Select -->
                        <div class="form-group">
                            <label for="classSelect">Class</label>
                            <select name="classid" class="form-control" id="classSelect">
                                <option value="">-- Select Class --</option>
                                <?php if ($rows1['level']) : ?>
                                    <?php foreach ($rows1['level'] as $row) : ?>
                                        <option value="<?= esc($row->id) ?>"><?= esc($row->class) ?></option>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <option>No classes available</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Book Type Select -->
                        <div class="form-group">
                            <label for="typeSelect">Type</label>
                            <select name="typeid" class="form-control" id="typeSelect">
                                <option value="">-- Select Book Type --</option>
                                <?php if ($rows1['types']) : ?>
                                    <?php foreach ($rows1['types'] as $row) : ?>
                                        <option value="<?= esc($row->id) ?>"><?= esc($row->booktype) ?></option>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <option>No types available</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary btn-block">Add Book</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('addBookForm').addEventListener('submit', function(event) {
                event.preventDefault();

                const subject = document.getElementById('subjectSelect').value;
                const classLevel = document.getElementById('classSelect').value;
                const type = document.getElementById('typeSelect').value;

                if (!subject || !classLevel || !type) {
                    Swal.fire('Error', 'Please complete all fields before submitting!', 'error');
                    return;
                }

                // Perform your form submission or AJAX call here
                this.submit();
            });
        });
    </script>