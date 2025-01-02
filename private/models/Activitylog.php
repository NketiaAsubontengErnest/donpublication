<?php 
/**
 * 
 * Seasons Model
 */
class Activitylog extends Model{
   protected $ran;
   protected $allowedColumns = [
      'userid',
      'activity',
      'loclink',
   ];

   protected $afterSelect = [
      'get_User',
   ];
   
   public function validate($data){
      $this->errors = array();
      //checking errors for School Name
      
      if(empty($data['activity'])){
         $this->errors['activity'] = "Activity can't be empty";
      }

      //check if the errors are empty
      if(count($this->errors) ==0){
         return true;
      }
      return false;
   }

   public function get_User($data){
      $stores = new User();   
      foreach($data as $key =>$row){
          $result = $stores->where('username', $row->userid);
          $data[$key]->users = is_array($result) ? $result[0] : array();           
      }
      return $data;
  }
}