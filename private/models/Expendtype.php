<?php 
/**
 * 
 * Level Expendtype
 */
class Expendtype extends Model{
   protected $ran;
   protected $allowedColumns = [
      'expendtype',
   ];
   
   public function validate($data){
      $this->errors = array();
      //checking errors for School Name
      if(empty($data['expendtype'])){
         $this->errors['expendtype'] = "Expend Type can't be empty";
      }
     

      //check if the errors are empty
      if(count($this->errors) ==0){
         return true;
      }
      return false;
   }
}
