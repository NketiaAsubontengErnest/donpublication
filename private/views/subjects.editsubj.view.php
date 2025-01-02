<?php $this->view('includes/header', ['crumbs'=>$crumbs, 'actives'=>$actives, 'hiddenSearch'=>$hiddenSearch,])?>
<div class="content-wrapper">
    <!-- partial -->
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Subject</h4>
                    <p class="card-description">
                        Change the name if needed
                    </p>
                   
                    <form method="POST" class="forms-sample"> 
                        <?php if($rows):?>
                            <div class="form-group">
                                <label for="exampleInputUsername1">Subject</label>
                                <input type="text" class="form-control" name="subject"
                                    value="<?=esc($rows[0]->subject)?>"
                                    id="exampleInputUsername1" placeholder="Username" required>
                            </div>
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="mdi mdi-sd"> Edit</i>                            
                            </button> 
                        <?php endif?>
                        <a class="btn btn-light" href="<?=HOME?>/subjects">Cancel</a>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->


    <?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/)?>