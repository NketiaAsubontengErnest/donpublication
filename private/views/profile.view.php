<?php $this->view('includes/header', ['crumbs'=>$crumbs, 'actives'=>$actives, 'hiddenSearch'=>$hiddenSearch,])?>
<div class="content-wrapper">
<div class="row">

    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Profile</h4>
                <form class="forms-sample" method="POST" >
                    <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Name</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?=esc(Auth::getFirstname())?> <?=esc(Auth::getLastname())?>" id="exampleInputUsername2" placeholder="Username" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Employee ID</label>
                        <div class="col-sm-9">
                        <input type="email" class="form-control" value="<?=esc(Auth::getUsername())?>" id="exampleInputEmail2" placeholder="Email" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputMobile" class="col-sm-3 col-form-label">Mobile</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?=esc(Auth::getPhone())?>"  id="exampleInputMobile" placeholder="Mobile number" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputPassword2" class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-9">
                        <input type="password" name="password" class="form-control" id="exampleInputPassword2" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exampleInputConfirmPassword2" class="col-sm-3 col-form-label">Re Password</label>
                        <div class="col-sm-9">
                        <input type="password" name="retyppassword" class="form-control" id="exampleInputConfirmPassword2" placeholder="Password" required>
                        </div>
                    </div>
                    <?php if(isset($errors['password'])):?>           
                        <div class="alert alert-warning alert-dismissible" role="alert">
                            <?=$errors['password']?>
                        </div> 
                    <?php endif;?>            
                    <button type="submit" class="btn btn-primary mr-2">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Image</h4>
                <form class="forms-sample" method="POST" enctype="multipart/form-data" >                    
                    <div class="">
                        <div class="wrapper " style="">
                        <?php
                            $image = ASSETS."/images/male_user.png";
                            
                            if (Auth::get_image()){
                                $image = ROOT ."/".Auth::get_image();
                            }
                        ?>
                        <img src="<?=$image?>" id="imageDisplay" class="d-block mx-auto border border-primary mb-1" style="width:200px; height: 250px;" 
                            src="../uploads/<?php if($row_to_edit){echo $row_to_edit[0]['picture'];}?>">
                        </div>
                        <input type="file" class="form-control" name="image" style="width:200px;" onchange="readURL(this)" required>
                        
                    </div>
                    <?php if(isset($errors['image'])):?>           
                        <div class="alert alert-warning alert-dismissible" role="alert">
                            <?=$errors['image']?>
                        </div> 
                    <?php endif;?>            
                    <button type="submit" class="btn btn-primary mr-2">Save Image</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    //Read and set image in the image path
function readURL(input) {
    if (input.files && input.files[0]) {
        var theFile = input.files[0];
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#imageDisplay').attr('src', e.target.result);
            $('#image_preview').attr('src', e.target.result);
        }
        if (theFile.type == 'image/jpeg' || theFile.type == 'image/png') {
            if (theFile.size < 3000000) {
                reader.readAsDataURL(input.files[0]);

                document.querySelector(".file_info").innerHTML = 'File Uploaded: ' + theFile.name + ", Size " + (theFile.size / 1000000).toFixed(2) + "MB";

                if (theFile.name != '') {
                    document.querySelector('#title').disabled = false;
                    document.querySelector('#sel_img').innerHTML = '';
                }

            } else {

                $("#fileInput").replaceWith($("#fileInput").clone());
                alert('Image size too big, it must be less than 3.0 Mb');
            }
        } else {
            $("#fileInput").replaceWith($("#fileInput").clone());
            alert('Select only image');
            theFile = "";
            //input.files = theFile;
        }
    }
}
</script>
<?php $this->view('includes/footer'/*, ['crumbs'=>$crumbs, 'actives'=>$actives]*/)?>