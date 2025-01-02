<?php 
/**
 * 
 * Message Model
 */
class Message extends Model{
   protected $ran;
   protected $allowedColumns = [
      'name',
      'email',
      'phone',
      'location',
      'message',
   ];
   
   public function validate($data){
      $this->errors = array();
      //checking errors for School Name
      if(empty($data['name'])){
         $this->errors['name'] = "Name can't be empty";
      }
      if(empty($data['email'])){
         $this->errors['email'] = "Email can't be empty";
      }
      if(empty($data['phone'])){
         $this->errors['phone'] = "Phone can't be empty";
      }
      if(empty($data['location'])){
         $this->errors['location'] = "Location can't be empty";
      }
      if(empty($data['message'])){
         $this->errors['message'] = "Message can't be empty";
      }

      //check if the errors are empty
      if(count($this->errors) ==0){
         return true;
      }
      return false;
   }
}
