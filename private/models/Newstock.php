<?php

/**
 * 
 * Newstock Model
 */
class Newstock extends Model
{
   protected $ran;
   protected $allowedColumns = [
      'bookid',
      'quantity',
      'stockofficer',
      'seasonid',
   ];

   protected $afterSelect = [
      'get_Book',
      'get_Class',
      'get_Subject',
      'get_Type',
      'get_Officer',
   ];

   public function validate($data)
   {
      $this->errors = array();
      //checking errors for School Name
      if (empty($data['quantity'])) {
         $this->errors['quantity'] = "Quantity can't be empty";
      }
      if ($data['quantity'] < 0) {
         $this->errors['quantity'] = "Quantity can't be empty";
      }
      if (!is_numeric($data['quantity'])) {
         $this->errors['quantity'] = "Only numbers allowed in this field";
      }

      //check if the errors are empty
      if (count($this->errors) == 0) {
         return true;
      }
      return false;
   }


   public function get_Book($data)
   {
      $books = new Book();
      foreach ($data as $key => $row) {
         $result = $books->where('id', $row->bookid);
         $data[$key]->books = is_array($result) ? $result[0] : array();
      }
      return $data;
   }
   public function get_Class($data)
   {
      $level = new Level();
      foreach ($data as $key => $row) {
         $result = $level->where('id', $row->books->classid);
         $data[$key]->level = is_array($result) ? $result[0] : array();
      }
      return $data;
   }
   public function get_Subject($data)
   {
      $subject = new Subject();
      foreach ($data as $key => $row) {
         $result = $subject->where('id', $row->books->subjectid);
         $data[$key]->subject = is_array($result) ? $result[0] : array();
      }
      return $data;
   }

   public function get_Type($data)
   {
      $booktype = new Type();
      foreach ($data as $key => $row) {
         $result = $booktype->where('id', $row->books->typeid);
         $data[$key]->booktype = is_array($result) ? $result[0] : array();
      }
      return $data;
   }
   public function get_Officer($data)
   {
      $user = new User();
      foreach ($data as $key => $row) {
         $result = $user->where('username', $row->stockofficer);
         $data[$key]->officer = is_array($result) ? $result[0] : array();
      }
      return $data;
   }
}
