<?php

/**
 * 
 * User Model
 */
class User extends Model
{
   protected $ran;
   protected $allowedColumns = [
      'firstname',
      'lastname',
      'phone',
      'rank',
      'officer'
   ];

   protected $beforeInset = [
      'make_user_id',
      'hash_password',
   ];

   protected $afterSelect = [
      'get_Officer',
      'get_count_customer',
      'get_count_visited',
   ];
   public function validate($data)
   {
      $this->errors = array();
      //checking errors for first name
      if (empty($data['firstname'])) {
         $this->errors['firstname'] = "First name can't be empty";
      }
      if (empty($data['firstname']) || !preg_match('/^[a-z A-Z-]+$/', $data['firstname'])) {
         $this->errors['firstname'] = "Only letters allowed in first name";
      }

      //checking errors for last name
      if (empty($data['lastname'])) {
         $this->errors['lastname'] = "Last name can't be empty";
      }
      if (empty($data['lastname']) || !preg_match('/^[a-z A-Z-]+$/', $data['lastname'])) {
         $this->errors['lastname'] = "Only letters allowed in last name";
      }

      //check if the errors are empty
      if (count($this->errors) == 0) {
         return true;
      }
      return false;
   }

   public function make_user_id($data)
   {
      $ids = $this->selctingId()[0]->username;

      $ids = substr($ids, 3);

      $ids++;
      if ($ids < 10) {
         $ids = "000" . $ids;
      } elseif ($ids < 100) {
         $ids = "00" . $ids;
      } elseif ($ids < 1000) {
         $ids = "0S" . $ids;
      }
      $data['username'] = "DPL" . $ids;
      $data['password'] = "DPL" . $ids;
      return $data;
   }
   public function hash_password($data)
   {
      $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
      return $data;
   }

   public function checkretypepass($data)
   {
      $this->errors = array();
      if (empty($data['password']) || empty($data['retyppassword'])) {
         $this->errors['password'] = "Passwords cant be empty!";
      } elseif ($data['password'] != $data['retyppassword']) {
         $this->errors['password'] = "Passwords missmatch!";
      } elseif (strlen($data['password']) < 8) {
         $this->errors['password'] = "Passwords length should not be < 8";
      } else {
         unset($data['retyppassword']);
         return $this->hash_password($data);
      }
      return false;
   }

   public function get_Agent($data)
   {
      $agent = new User();

      foreach ($data as $key => $row) {
         if (!empty($row->agentid)) {
            $result = $agent->where('user_id', $row->agentid);
            $data[$key]->agent = $result[0];
         }
      }
      return $data;
   }
   public function get_Officer($data)
   {
      $offi = new User();
      foreach ($data as $key => $row) {
         if (!empty($row->id)) {
            $result = $offi->where('id', $row->officer);
            $data[$key]->officer = is_array($result) ? $result[0] : array();
         }
      }
      return $data;
   }

   public function make_agentid()
   {
      $data['agentid'] = Auth::getUser_id();
      return $data;
   }

   public function get_count_customer($data)
   {
      $customer = new Customer();
      foreach ($data as $key => $row) {
         $result = $customer->selectCountWhere('officerid', $row->id);
         $data[$key]->customerTotal = isset($result[0]) ? $result[0] : array();
      }
      return $data;
   }

   public function get_total_books($data)
   {
      $orders = new Order();
      $season = new Season();
      $ss = $season->query('SELECT * FROM `seasons` ORDER BY id DESC LIMIT 1')[0];
      foreach ($data as $key => $row) {
         $query = "SELECT SUM(`quantsupp`) AS quantsupp, SUM(`retverquant`) AS retverquant FROM `orders` WHERE `seasonid`=:seasonid AND `officerid` =:officerid ";
         $arr['seasonid'] = isset($ss->id) ? $ss->id : '';
         $arr['officerid'] = $row->id;
         $result = $orders->query($query, $arr);
         $data[$key]->totalBooks = isset($result[0]) ? $result[0] : array();
      }
      return $data;
   }

   public function get_total_samp_books($data)
   {
      $orders = new Order();
      foreach ($data as $key => $row) {
         $query = "SELECT SUM(`quantsupp` - `retverquant`) AS quantsupp, SUM(`retverquant`) AS retverquant FROM `orders` WHERE `seasonid`=:seasonid AND `officerid` =:officerid AND `ordertype`=1";
         $arr['seasonid'] = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";
         $arr['officerid'] = $row->id;
         $result = $orders->query($query, $arr);
         $data[$key]->totalBooks = isset($result[0]) ? $result[0] : array();
      }
      return $data;
   }

   public function get_total_books_Shared($data)
   {
      $orders = new Order();
      foreach ($data as $key => $row) {
         $query = "SELECT SUM(`textbook` + `workbook`) as ttshered, SUM(`textbook`) AS tttextbook, SUM(`workbook`) AS ttworkbook FROM `visitors` WHERE `officerid` = {$row->id} AND `seasonid`=:seasonid;";
         $arr['seasonid'] = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";
         $result = $orders->query($query, $arr);
         $data[$key]->ttshered = isset($result[0]) ? $result[0] : array();
      }
      return $data;
   }

   public function get_count_visited($data)
   {
      $visited = new Visitor();
      if (isset($_SESSION['seasondata'])) {
         $arr['seasonid'] = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";
         foreach ($data as $key => $row) {
            $query = "SELECT COUNT(*) AS visitor FROM `visitors` WHERE `officerid` =:officerid AND `seasonid` =:seasonid ";
            $arr['officerid'] = $row->id;
            $result = $visited->query($query, $arr);
            $data[$key]->visitors = isset($result[0]) ? $result[0] : array();
         }
      }
      return $data;
   }
}
