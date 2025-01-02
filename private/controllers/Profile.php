<?php
/**
 * Profile controller
 */
class Profile extends Controller{
    function index(){
        if(!Auth::logged_in()){
            return $this->redirect('login');
        }
        //this are for breadcrumb
        $crumbs[] = ['Dashboard','dashboard'];
        $crumbs[] = ['Profile',''];
        $user = new User();
        $errors = array();

        if(count($_FILES) > 0){

            $allowed[] = "image/jpeg";
            $allowed[] = "image/jpg";
            $allowed[] = "image/png";
            //Uploading certificate
            if ($_FILES['image']['error'] == 0 && in_array($_FILES['image']['type'], $allowed)) {
                $certificateFolder = "public/profilePics/";
                if (!file_exists($certificateFolder)) {
                    mkdir($certificateFolder, 0777, true);
                }
                $certificateFolder = "profilePics/";
                // Generate a new file name using user ID and "_certificate"
                $newFileName = Auth::getUsername() . "_pic";
            
                // Extract the original file extension
                $originalExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            
                // Construct the full destination path with the new name and extension
                $destination = $certificateFolder . $newFileName . "." . $originalExtension;

                $_POST['imagelink'] = $destination;
                $user->update(Auth::getId(), $_POST);
                $destination = "public/".$destination;
                // Move uploaded file to the new destination
                move_uploaded_file($_FILES['image']['tmp_name'], $destination);
            } 
            $_SESSION['messsage'] = "Profile Picture Successfully Set";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
        }
        elseif (count($_POST)>0){
            $newData = $user->checkretypepass($_POST);
            if ($newData != false){
                $user->update(Auth::getId(), $newData);
                Auth::logout();
                $_SESSION['messsage'] = "Password Successfully Changed";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
                return $this->redirect('login');
            }else{
                $errors = $user->errors;
            }
        }

        $actives = 'profile';
        $hiddenSearch = "yep";
        return $this->view('profile',[
            'crumbs'=>$crumbs,
            'errors'=>$errors,
            'hiddenSearch'=>$hiddenSearch,
            'actives'=>$actives
        ]);
    }
    
}