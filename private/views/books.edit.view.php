<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>

<div class="content-wrapper">

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Quantity</h4>
                    <?php if ($rows) : ?>
                        <form class="forms-sample" method="post">
                            <div class="form-group">
                                <label for="exampleInputName1">Book Name</label>
                                <input type="text" class="form-control" id="exampleInputName1" value="<?= $rows->level->class . " " . $rows->subject->subject . " " . $rows->booktype->booktype ?>" readonly>
                            </div>
                            <?php if (isset($errors['firstname'])) : ?>
                                <div class="alert alert-warning alert-dismissible fade show mb-3 text-white" role="alert">
                                    <?= $errors['firstname'] ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"><strong>X</strong></button>
                                </div>
                            <?php endif; ?>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail3">Quantity in Stock</label>
                                    <input type="text" class="form-control" id="exampleInputEmail3" value="<?= $rows->quantity ?>" readonly>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="exampleInputCity1">New Quantity</label>
                                    <input type="number" name="quantity" class="form-control" id="exampleInputCity1" required placeholder="2000">
                                </div>
                                <?php if (isset($errors['quantity'])) : ?>
                                    <div class="alert alert-warning alert-dismissible fade show mb-3 text-white" role="alert">
                                        <?= $errors['quantity'] ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"><strong>X</strong></button>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <button type="submit" class="btn btn-primary mr-2">Update Quantity</button>
                        </form>
                    <?php else : ?>
                        <h6 class="card-title">No book found</h6>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>

    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>