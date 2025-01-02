<?php 
/**
 * 
 * Ordertype Model
 */
class Ordertype extends Model{
   protected $ran;
   protected $allowedColumns = [
      'typename',
      'typestatus',
   ];
   
   public function validate($data){
      $this->errors = array();
      //checking errors for School Name
      if(empty($data['typename'])){
         $this->errors['typename'] = "Type Name can't be empty";
      }
      

      //check if the errors are empty
      if(count($this->errors) ==0){
         return true;
      }
      return false;
   }
}
