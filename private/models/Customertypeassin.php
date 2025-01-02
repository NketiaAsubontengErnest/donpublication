<?php 
/**
 * 
 * Level Model
 */
class Customertypeassin extends Model{
   protected $ran;
   protected $allowedColumns = [
      'customertype',
      'verificationOffcer',
   ];
   
   public function validate($data){
      $this->errors = array();
      //checking errors for School Name
      if(empty($data['customertype'])){
         $this->errors['customertype'] = "Customer ype can't be empty";
      }
    //   if(!is_numeric($data['coursefees'])){
    //      $this->errors['coursefees'] = "Only numbers allowed in Fees";
    //   }

      //check if the errors are empty
      if(count($this->errors) ==0){
         return true;
      }
      return false;
   }
}
