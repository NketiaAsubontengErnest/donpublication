<?php 
/**
 * 
 * Type Model
 */
class Type extends Model{
   protected $ran;
   protected $allowedColumns = [
      'booktype',
   ];
   
   public function validate($data){
      $this->errors = array();
      //checking errors for School Name
      if(empty($data['booktype'])){
         $this->errors['booktype'] = "Book type Name can't be empty";
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
