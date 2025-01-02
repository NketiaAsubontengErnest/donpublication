<?php

/**
 * 
 * Level Expenditure
 */
class Expenditure extends Model
{
   protected $ran;
   protected $allowedColumns = [
      'expendDate',
      'expendAmount',
      'expendType',
      'chequeNo',
      'vocherNo',
      'naration',
      'seasonid',
      'userid',
   ];
   protected $afterSelect = [
      'get_Type',
      'get_User'
   ];
   public function validate($data)
   {
      $this->errors = array();
      //checking errors for School Name
      if (empty($data['expendDate'])) {
         $this->errors['expendDate'] = "Expend Date can't be empty";
      }
      if (empty($data['expendAmount'])) {
         $this->errors['expendAmount'] = "Income Amount can't be empty";
      }
      if (empty($data['expendType'])) {
         $this->errors['expendType'] = "Expend Type can't be empty";
      }
      // if(empty($data['vocherNo'])){
      //    $this->errors['vocherNo'] = "Vocher No. can't be empty";
      // }
      if (empty($data['naration'])) {
         $this->errors['naration'] = "Naration can't be empty";
      }
      //   if(!is_numeric($data['coursefees'])){
      //      $this->errors['coursefees'] = "Only numbers allowed in Fees";
      //   }

      //check if the errors are empty
      if (count($this->errors) == 0) {
         return true;
      }
      return false;
   }

   public function get_Type($data)
   {
      $expenType = new Expendtype();
      foreach ($data as $key => $row) {
         $result = $expenType->where('id', $row->expendType);
         $data[$key]->extype = is_array($result) ? $result[0] : array();
      }
      return $data;
   }
   public function get_User($data)
   {
      $users = new User();
      foreach ($data as $key => $row) {
         $result = $users->where('id', $row->userid);
         $data[$key]->user = is_array($result) ? $result[0] : array();
      }
      return $data;
   }
}
