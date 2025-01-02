<?php 
/**
 * 
 * Seasons Model
 */
class Season extends Model{
   protected $ran;
   protected $allowedColumns = [
      'seasonname',
      'seasonstatus',
   ];
   
   public function validate($data){
      $this->errors = array();
      //checking errors for School Name
      if(empty($data['seasonname'])){
         $this->errors['seasonname'] = "Season Name can't be empty";
      }
      

      //check if the errors are empty
      if(count($this->errors) ==0){
         return true;
      }
      return false;
   }
}
