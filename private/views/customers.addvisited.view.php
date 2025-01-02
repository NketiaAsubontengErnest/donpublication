<?php $this->view('includes/header', ['crumbs' => $crumbs, 'actives' => $actives, 'hiddenSearch' => $hiddenSearch,]) ?>

<div class="content-wrapper">

  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Visited Customer For Sample</h4>
          <?php if($row):?>
          <form class="forms-sample" method="post">
            <div class="form-group">
              <label for="exampleInputName1">Customer Name</label>
              <input type="text" value="<?= get_var('customername', $row->customername) ?>" class="form-control" id="exampleInputName1" name="customername" required placeholder="Name">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Contact Person</label>
              <input type="text" value="<?= get_var('contactperson') ?>" class="form-control" id="exampleInputName1" name="contactperson" required placeholder="Name">
            </div>

            <div class="form-group">
              <label for="exampleInputCity1">Phone Number</label>
              <input type="text" value="<?= get_var('custphone', $row->custphone) ?>" name="custphone" class="form-control" id="exampleInputCity1" required placeholder="0554013980">
            </div>

            <div class="form-group">
              <label for="exampleInputEmail3">Location</label>
              <input type="text" value="<?= esc(get_var('custlocation', $row->custlocation)) ?>" class="form-control" id="exampleInputEmail3" name="custlocation" required placeholder="Location">
            </div>

            <div class="form-group">
              <label for="exampleInputCity1">Region</label>
              <select class="form-control" name="region" required id="exampleSelectGender">
                <option value="">-- Select Region --</option>
                <option <?= get_select('region', 'Greater Accra', $row->region); ?> value="Greater Accra">Greater Accra</option>
                <option <?= get_select('region', 'Ashanti', $row->region); ?> value="Ashanti">Ashanti</option>
                <option <?= get_select('region', 'Central', $row->region); ?> value="Central">Central</option>
                <option <?= get_select('region', 'Eastern', $row->region); ?> value="Eastern">Eastern</option>
                <option <?= get_select('region', 'Western', $row->region); ?> value="Western">Western</option>
                <option <?= get_select('region', 'Bono', $row->region); ?> value="Bono">Bono</option>
                <option <?= get_select('region', 'Bono East', $row->region); ?> value="Bono East">Bono East</option>
                <option <?= get_select('region', 'Ahafo', $row->region); ?> value="Ahafo">Ahafo</option>
                <option <?= get_select('region', 'Northern', $row->region); ?> value="Northern">Northern</option>
                <option <?= get_select('region', 'Savannah', $row->region); ?> value="Savannah">Savannah</option>
                <option <?= get_select('region', 'North East', $row->region); ?> value="North East">North East</option>
                <option <?= get_select('region', 'Upper East', $row->region); ?> value="Upper East">Upper East</option>
                <option <?= get_select('region', 'Upper West', $row->region); ?> value="Upper West">Upper West</option>
                <option <?= get_select('region', 'Volta', $row->region); ?> value="Volta">Volta</option>
                <option <?= get_select('region', 'Oti', $row->region); ?> value="Oti">Oti</option>
                <option <?= get_select('region', 'Western', $row->region); ?> value="Western">Western</option>
                <option <?= get_select('region', 'Western North', $row->region); ?> value="Western North">Western North</option>
              </select>
            </div>

            <div class="form-group">
              <label for="exampleInputCity1">Text Book Qty</label>
              <input type="text" value="<?= get_var('textbook') ?>" name="textbook" class="form-control" id="exampleInputCity1" required placeholder="10">
            </div>

            <div class="form-group">
              <label for="exampleInputEmail3">Work Book Qty</label>
              <input type="text" value="<?= esc(get_var('workbook')) ?>" class="form-control" id="exampleInputEmail3" name="workbook" required placeholder="10">
            </div>
            <button type="submit" class="btn btn-primary mr-2">
              <i class="mdi mdi-content-save-settings"></i>
              Add Visitor
            </button>
            <a class="btn btn-light float-right" href="<?= HOME ?>/customers/visited">Cancel</a>
          </form>
          <?php else:?>
            <form class="forms-sample" method="post">
            <div class="form-group">
              <label for="exampleInputName1">Customer Name</label>
              <input type="text" value="<?= get_var('customername') ?>" class="form-control" id="exampleInputName1" name="customername" required placeholder="Name">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Contact Person</label>
              <input type="text" value="<?= get_var('contactperson') ?>" class="form-control" id="exampleInputName1" name="contactperson" required placeholder="Name">
            </div>

            <div class="form-group">
              <label for="exampleInputCity1">Phone Number</label>
              <input type="text" value="<?= get_var('custphone') ?>" name="custphone" class="form-control" id="exampleInputCity1" required placeholder="0554013980">
            </div>

            <div class="form-group">
              <label for="exampleInputEmail3">Location</label>
              <input type="text" value="<?= esc(get_var('custlocation')) ?>" class="form-control" id="exampleInputEmail3" name="custlocation" required placeholder="Location">
            </div>

            <div class="form-group">
              <label for="exampleInputCity1">Region</label>
              <select class="form-control" name="region" required id="exampleSelectGender">
                <option value="">-- Select Region --</option>
                <option <?= get_select('region', 'Greater Accra'); ?> value="Greater Accra">Greater Accra</option>
                <option <?= get_select('region', 'Ashanti'); ?> value="Ashanti">Ashanti</option>
                <option <?= get_select('region', 'Central'); ?> value="Central">Central</option>
                <option <?= get_select('region', 'Eastern'); ?> value="Eastern">Eastern</option>
                <option <?= get_select('region', 'Western'); ?> value="Western">Western</option>
                <option <?= get_select('region', 'Bono'); ?> value="Bono">Bono</option>
                <option <?= get_select('region', 'Bono East'); ?> value="Bono East">Bono East</option>
                <option <?= get_select('region', 'Ahafo'); ?> value="Ahafo">Ahafo</option>
                <option <?= get_select('region', 'Northern'); ?> value="Northern">Northern</option>
                <option <?= get_select('region', 'Savannah'); ?> value="Savannah">Savannah</option>
                <option <?= get_select('region', 'North East'); ?> value="North East">North East</option>
                <option <?= get_select('region', 'Upper East'); ?> value="Upper East">Upper East</option>
                <option <?= get_select('region', 'Upper West'); ?> value="Upper West">Upper West</option>
                <option <?= get_select('region', 'Volta'); ?> value="Volta">Volta</option>
                <option <?= get_select('region', 'Oti'); ?> value="Oti">Oti</option>
                <option <?= get_select('region', 'Western'); ?> value="Western">Western</option>
                <option <?= get_select('region', 'Western North'); ?> value="Western North">Western North</option>
              </select>
            </div>

            <div class="form-group">
              <label for="exampleInputCity1">Text Book Qty</label>
              <input type="text" value="<?= get_var('textbook') ?>" name="textbook" class="form-control" id="exampleInputCity1" required placeholder="10">
            </div>

            <div class="form-group">
              <label for="exampleInputEmail3">Work Book Qty</label>
              <input type="text" value="<?= esc(get_var('workbook')) ?>" class="form-control" id="exampleInputEmail3" name="workbook" required placeholder="10">
            </div>
            <button type="submit" class="btn btn-primary mr-2">
              <i class="mdi mdi-content-save-settings"></i>
              Add Visitor
            </button>
            <a class="btn btn-light float-right" href="<?= HOME ?>/customers/visited">Cancel</a>
          </form>
          <?php endif?>
        </div>
      </div>
    </div>
  </div>

  <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/) ?>