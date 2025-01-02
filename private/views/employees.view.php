<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <button id="show-add-book" class="btn btn-primary float-right">
                        <i class="mdi mdi-account-multiple-plus"></i>
                        Add User
                    </button>
                    <div class="row m-2">
                        <h4 class="card-title">List of Users</h4>
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
                                    <th>
                                        Image
                                    </th>
                                    <th>
                                        ID Number
                                    </th>
                                    <th>
                                        Full Name
                                    </th>
                                    <th>
                                        Phone Number
                                    </th>
                                    <th>
                                        Rank
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($rows) : ?>
                                    <?php foreach ($rows as $row) : ?>
                                        <?php if ($row->rank != 'director') : ?>
                                            <tr>
                                                <td class="py-1">
                                                    <?php
                                                    $image = ASSETS . "/images/male_user.png";

                                                    if ($row->imagelink != null) {
                                                        $image = ROOT . "/" . $row->imagelink;
                                                    }
                                                    ?>
                                                    <img src="<?= $image ?>" alt="image" />
                                                </td>
                                                <td>
                                                    <?= esc($row->username) ?>
                                                </td>
                                                <td>
                                                    <?= ucfirst(esc($row->firstname)) ?> <?= ucfirst(esc($row->lastname)) ?>

                                                    <?php
                                                    if (isset($row->officer->firstname)) {
                                                        echo "(" . ucfirst(esc($row->officer->firstname)) . " " . ucfirst(esc($row->officer->lastname)) . ")" . "[" . esc($row->customerTotal->numbers) . "]";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?= esc($row->phone) ?>
                                                </td>
                                                <td>
                                                    <?= ucfirst(esc($row->rank)) ?>
                                                </td>
                                                <td>
                                                    <?php if ($row->rank != 'director') : ?>
                                                        <?php if ($row->rank == 'marketer') : ?>
                                                            <a href="<?= HOME ?>/employee/assign/<?= $row->id ?>">
                                                                <code><i class="mdi mdi mdi-account-convert"></i></code>
                                                            </a>
                                                        <?php endif ?>
                                                        <a href="<?= HOME ?>/employee/edit/<?= $row->id ?>">
                                                            <i class="m-2 mdi mdi-table-edit"></i>
                                                        </a>
                                                        <a href="<?= HOME ?>/employee/blockuser/<?= $row->id ?>">
                                                            <?= $row->status == 0 ? "Block" : "Un-Block" ?>
                                                        </a>

                                                        <a href="<?= HOME ?>/employee/transfer/<?= $row->id ?>">
                                                            <i class="m-2 mdi"> Transfer</i>
                                                        </a>
                                                    <?php endif ?>

                                                </td>
                                            </tr>
                                        <?php endif ?>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="7" class="align-middle text-center text-sm">
                                            No Employee Added
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
    <div class="popup">
        <div class="row mx-0">
            <div class="col-lg-12 mx-auto">
                <button class="close-btn"><b>&times;</b></button>
                <div class="auth-form-light">
                    <h4>Add New Employee</h4>
                    <form method="post">
                        <div class="form-group">
                            <label for="exampleInputName1">First Name</label>
                            <input type="text" class="form-control" id="exampleInputName1" name="firstname" required placeholder="First Name">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail3">Last Name</label>
                            <input type="text" class="form-control" id="exampleInputEmail3" name="lastname" required placeholder="Last Name">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputCity1">Phone Number</label>
                            <input type="text" name="phone" class="form-control" id="exampleInputCity1" required placeholder="0554....">
                        </div>

                        <div class="form-group">
                            <label for="exampleSelectGender">Role</label>
                            <select class="form-control" name="rank" required id="exampleSelectGender">
                                <option value="">-- Select a role --</option>
                                <option value="marketer">Marketer</option>
                                <option value="stores">Stores Manager</option>
                                <option value="driver">Driver</option>
                                <option value="security">Security</option>
                                <option value="verification">Verification Officer</option>
                                <option value="account">Account Officer</option>
                                <option value="g-account">General Account Officer</option>
                                <option value="auditor">Auditor</option>
                                <option value="director">Director</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary float-right">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>