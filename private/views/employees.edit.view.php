<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>

<div class="content-wrapper">

  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Edit Employee</h4>
          <form class="forms-sample" method="post">
            <div class="form-group">
              <label for="exampleInputName1">First Name</label>
              <input type="text" value="<?= esc($rows[0]->firstname) ?>" class="form-control" id="exampleInputName1" name="firstname" required placeholder="First Name">
            </div>

            <div class="form-group">
              <label for="exampleInputEmail3">Last Name</label>
              <input type="text" value="<?= esc($rows[0]->lastname) ?>" class="form-control" id="exampleInputEmail3" name="lastname" required placeholder="Last Name">
            </div>

            <div class="form-group">
              <label for="exampleInputCity1">Phone Number</label>
              <input type="text" name="phone" value="<?= esc($rows[0]->phone) ?>" class="form-control" id="exampleInputCity1" required placeholder="0554013980">
            </div>

            <div class="form-group">
              <label for="exampleSelectGender">Role</label>
              <select class="form-control" name="rank" required id="exampleSelectGender">
                <option value="">-- Select a role --</option>
                <option <?= get_select('rank', 'marketer', $rows[0]->rank); ?> value="marketer">Marketer</option>
                <option <?= get_select('rank', 'stores', $rows[0]->rank); ?> value="stores">Stores Manager</option>
                <option <?= get_select('rank', 'driver', $rows[0]->rank); ?> value="driver">Driver</option>
                <option <?= get_select('rank', 'security', $rows[0]->rank); ?> value="security">Security</option>
                <option <?= get_select('rank', 'verification', $rows[0]->rank); ?> value="verification">Verification Officer</option>
                <option <?= get_select('rank', 'account', $rows[0]->rank); ?> value="account">Account Officer</option>
                <option <?= get_select('rank', 'g-account', $rows[0]->rank); ?> value="g-account">General Account Officer</option>
                <option <?= get_select('rank', 'director', $rows[0]->rank); ?> value="director">Director</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary mr-2">
              <i class="mdi mdi-content-save-settings"></i>
              Save Edit
            </button>
          </form>
          <a class="btn btn-light float-right" href="<?= HOME ?>/employee">Cancel</a>
          <form method="POST">
            <button name="reset" class="btn btn-success float-centre mr-2">
              <input type="text" name="password" value="<?=$rows[0]->username?>" hidden>
              <i class="mdi mdi-lock-reset"></i>
              Reset Password
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>