<?php 
/**
 * 
 * Level Model
 */
class Level extends Model{
   protected $ran;
   protected $allowedColumns = [
      'class',
   ];
   
   public function validate($data){
      $this->errors = array();
      //checking errors for School Name
      if(empty($data['class'])){
         $this->errors['class'] = "Class Name can't be empty";
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
