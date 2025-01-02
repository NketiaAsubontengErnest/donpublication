<?php $this->view('includes/header', ['crumbs'=>$crumbs, 'actives'=>$actives, 'hiddenSearch'=>$hiddenSearch,])?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Levels / Class</h4>
                    <form action="" method="post">
                        <input type="text" name="class" class="form-control m-2" id="exampleInputCity1" required
                            placeholder="BASIC 1">
                        <button class="btn btn-primary float-right">
                            <i class="mdi mdi mdi-gamepad"> Add Class</i>
                        </button>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Level</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($rows['levels']):?>
                                    <?php foreach ($rows['levels'] as $row):?>
                                    <tr>
                                        <td><?=esc($row->class)?></td>
                                        <td>
                                            <a href="<?=HOME?>/subjects/editlevel/<?=$row->id?>">
                                                <i class="m-2 mdi mdi-table-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="2" class="align-middle text-center text-sm">
                                            No Class Added
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Subject</h4>
                    <form action="" method="post">
                        <input type="text" name="subject" class="form-control m-2" id="exampleInputCity1" required
                            placeholder="COMPUTING">
                        <button class="btn btn-primary float-right">
                            <i class="mdi mdi mdi-gamepad"> Add Subject</i>
                        </button>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Subject Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($rows['subject']):?>
                                    <?php foreach ($rows['subject'] as $row):?>
                                    <tr>
                                        <td><?=esc($row->subject)?></td>
                                        <td>
                                            <a href="<?=HOME?>/subjects/editsubj/<?=$row->id?>">
                                                <i class="m-2 mdi mdi-table-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="2" class="align-middle text-center text-sm">
                                            No Subject Added
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Type of Book</h4>
                    <form action="" method="post">
                        <input type="text" name="booktype" class="form-control m-2" id="exampleInputCity1" required
                            placeholder="TEXTBOOK">
                        <button class="btn btn-primary float-right">
                            <i class="mdi mdi mdi-gamepad"> Add Type</i>
                        </button>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Book Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($rows['types']):?>
                                    <?php foreach ($rows['types'] as $row):?>
                                    <tr>
                                        <td><?=esc($row->booktype)?></td>
                                        <td>
                                            <a href="<?=HOME?>/subjects/edittype/<?=$row->id?>">
                                                <i class="m-2 mdi mdi-table-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="align-middle text-center text-sm">
                                            No Class Added
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>










    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/)?>