<?php

/**
 * 
 * Books Model
 */
class Book extends Model
{
   protected $ran;
   protected $allowedColumns = [
      'subjectid',
      'classid',
      'quantity',
      'treshhold',
      'typeid',
      'quantity',
      'treshhold'
   ];

   protected $beforeInset = [
      // 'make_course_id',
   ];
   protected $afterSelect = [
      'get_Class',
      'get_Subject',
      'get_Type',
      'get_Added',
      'get_Supply',
      'get_Sample_Supply',
      'get_Returns',
   ];

   public function validate($data)
   {
      $this->errors = array();
      //checking errors for School Name
      if (empty($data['subjectid'])) {
         $this->errors['subjectid'] = "Please choose a Subject";
      }

      if (empty($data['classid'])) {
         $this->errors['classid'] = "Please choose a Class / Level";
      }

      if (empty($data['typeid'])) {
         $this->errors['typeid'] = "Please choose a book type";
      }

      $dd = $data;
      unset($dd['add']);
      $query = "SELECT * FROM `books` WHERE `subjectid` =:subjectid AND `classid` =:classid AND `typeid` =:typeid;";
      if ($this->query($query, $dd)) {
         $this->errors['bookexist'] = "Books already added";
      }

      //check if the errors are empty
      if (count($this->errors) == 0) {
         return true;
      }
      return false;
   }

   public function get_Class($data)
   {
      $level = new Level();
      foreach ($data as $key => $row) {
         $result = $level->where('id', $row->classid);
         $data[$key]->level = is_array($result) ? $result[0] : array();
      }
      return $data;
   }
   public function get_Subject($data)
   {
      $subject = new Subject();
      foreach ($data as $key => $row) {
         $result = $subject->where('id', $row->subjectid);
         $data[$key]->subject = is_array($result) ? $result[0] : array();
      }
      return $data;
   }
   public function get_Added($data)
   {
      $newstoc = new Newstock();
      foreach ($data as $key => $row) {
         $seas = new Season();
         //get current Season
         $seasid = isset($seas->selctingLastId()[0]->id) ? $seas->selctingLastId()[0]->id : '';
         $arr['bookid'] = $row->id;
         $arr['seasonid'] = $seasid;

         $query = "SELECT SUM(`quantity`) AS ttAdded FROM `newstocks` WHERE `bookid` =:bookid AND `seasonid` =:seasonid";
         $result = $newstoc->query($query, $arr);
         $data[$key]->ttadded = is_array($result) ? $result[0] : array();
      }
      return $data;
   }
   public function get_Supply($data)
   {
      $order = new Order();
      foreach ($data as $key => $row) {
         $seas = new Season();
         //get current Season
         $seasid = isset($seas->selctingLastId()[0]->id) ? $seas->selctingLastId()[0]->id : '';
         $arr['bookid'] = $row->id;
         $arr['seasonid'] = $seasid;

         $query = "SELECT SUM(`quantsupp`) AS ttSupply, (SUM(`quantsupp` - `retverquant`)) AS actualSupply FROM `orders` WHERE `bookid` =:bookid AND `seasonid`=:seasonid AND `ordertype` != '1'";
         $result = $order->query($query, $arr);
         $data[$key]->ttSupply = is_array($result) ? $result[0] : array();
      }
      return $data;
   }

   public function get_Makerter_Supply($data, $id)
   {
      $order = new Order();
      foreach ($data as $key => $row) {
         $seas = new Season();
         //get current Season
         $seasid = isset($seas->selctingLastId()[0]->id) ? $seas->selctingLastId()[0]->id : '';
         $arr['bookid'] = $row->id;
         $arr['seasonid'] = $seasid;
         $arr['officerid'] = $id;

         $query = "SELECT SUM(`quantsupp`) AS ttSupply, (SUM(`quantsupp` - `retverquant`)) AS actualSupply, SUM((`quantsupp` - `retverquant`) * `unitprice`) AS book_gross, SUM(((`quantsupp` - `retverquant`) * `unitprice`) - (((`quantsupp` - `retverquant`) * `unitprice`) * (`discount`/100))) AS book_net, SUM((`retverquant` * `unitprice`) - ((`retverquant` * `unitprice`) * (`discount`/100))) AS return_net FROM `orders` JOIN `customers` ON customers.`id` = `customerid` WHERE customers.`custtype` = 'school' AND `bookid` =:bookid AND `seasonid`=:seasonid AND orders.`officerid` =:officerid AND `ordertype` != '1'";
         $result = $order->query($query, $arr);
         $data[$key]->ttMarketSupply = is_array($result) ? $result[0] : array();
      }
      return $data;
   }

   public function get_Sample_Supply($data)
   {
      $order = new Order();
      foreach ($data as $key => $row) {
         $seas = new Season();
         //get current Season
         $seasid = isset($seas->selctingLastId()[0]->id) ? $seas->selctingLastId()[0]->id : '';
         $arr['bookid'] = $row->id;
         $arr['seasonid'] = $seasid;

         $query = "SELECT SUM(`quantsupp`) AS ttSampleSupply FROM `orders` WHERE `bookid` =:bookid AND `seasonid`=:seasonid AND orders.`ordertype` = '1'";
         $result = $order->query($query, $arr);
         $data[$key]->ttSampleSupply = is_array($result) ? $result[0] : array();
      }
      return $data;
   }

   public function get_Maketer_Sample_Supply($data, $id)
   {
      $order = new Order();
      foreach ($data as $key => $row) {
         $seas = new Season();
         //get current Season
         $seasid = isset($seas->selctingLastId()[0]->id) ? $seas->selctingLastId()[0]->id : '';
         $arr['bookid'] = $row->id;
         $arr['seasonid'] = $seasid;
         $arr['officerid'] = $id;

         $query = "SELECT SUM(`quantsupp`) AS ttMarkSampleSupply, (SELECT SUM(`retverquant`) FROM `orders` WHERE `bookid` = :bookid AND `seasonid` = :seasonid AND `officerid` = :officerid AND `ordertype` = '1') AS retsample FROM `orders` WHERE `bookid` =:bookid AND `seasonid`=:seasonid AND orders.`ordertype` = '1' AND orders.`officerid` =:officerid";
         $result = $order->query($query, $arr);
         $data[$key]->ttMarkSampleSupply = is_array($result) ? $result[0] : array();
      }
      return $data;
   }

   public function get_Returns($data)
   {
      $order = new Order();
      foreach ($data as $key => $row) {
         $seas = new Season();
         //get current Season
         $seasid = isset($seas->selctingLastId()[0]->id) ? $seas->selctingLastId()[0]->id : '';
         $arr['bookid'] = $row->id;
         $arr['seasonid'] = $seasid;

         $query = "SELECT SUM(`retverquant`) AS ttreturns FROM `orders` WHERE `bookid` =:bookid AND `seasonid`=:seasonid AND `ordertype` != '1'";
         $result = $order->query($query, $arr);
         $data[$key]->ttreturns = is_array($result) ? $result[0] : array();
      }
      return $data;
   }

   public function get_Makerter_Returns($data, $id)
   {
      $order = new Order();
      foreach ($data as $key => $row) {
         $seas = new Season();
         //get current Season
         $seasid = isset($seas->selctingLastId()[0]->id) ? $seas->selctingLastId()[0]->id : '';
         $arr['bookid'] = $row->id;
         $arr['seasonid'] = $seasid;
         $arr['officerid'] = $id;

         $query = "SELECT SUM(`retverquant`) AS ttmarketreturns, (SELECT SUM(`retverquant`) FROM `orders` JOIN `customers` ON customers.`id` = `customerid` WHERE customers.`custtype` = 'school' AND `bookid` = :bookid AND `seasonid` = :seasonid AND orders.`officerid` = :officerid AND `ordertype` = '1') AS retsample FROM `orders`  JOIN `customers` ON customers.`id` = `customerid` WHERE customers.`custtype` = 'school' AND `bookid` = :bookid AND `seasonid` = :seasonid AND orders.`officerid` = :officerid AND `ordertype` != '1'";
         $result = $order->query($query, $arr);
         $data[$key]->ttmarketreturns = is_array($result) ? $result[0] : array();
      }
      return $data;
   }
   public function get_Customers_suplies($data, $id)
   {
      $order = new Order();
      foreach ($data as $key => $row) {
         $seas = new Season();
         //get current Season
         $arr['bookid'] = $row->id;
         $arr['seasonid'] = $_SESSION['seasondata']->id ?? '';
         $arr['customerid'] = $id;

         $query = "SELECT SUM(quantord) AS grosquant, SUM(retverquant) AS ret_quant FROM `orders` WHERE `bookid`=:bookid AND `seasonid` =:seasonid AND `ordertype` != 1 AND `customerid`=:customerid";
         $result = $order->query($query, $arr);
         $data[$key]->ttCustreturns = is_array($result) ? $result[0] : array();
      }
      return $data;
   }
   public function get_Special_Supply($data, $specialtype)
   {
      $order = new Order();
      foreach ($data as $key => $row) {
         $seas = new Season();
         //get current Season
         $seasid = isset($seas->selctingLastId()[0]->id) ? $seas->selctingLastId()[0]->id : '';
         $arr['bookid'] = $row->id;
         $arr['seasonid'] = $seasid;
         $arr['custtype'] = $specialtype;

         $query = "SELECT SUM(`quantsupp`) AS ttSpecialSupply, (SELECT SUM(`retverquant`) FROM `orders` JOIN `customers` ON customers.`id` = `customerid` WHERE customers.`custtype` = :custtype AND `bookid` = :bookid AND `seasonid` = :seasonid AND `ordertype` != '1') AS retsample FROM `orders` JOIN `customers` ON customers.`id` = `customerid` WHERE customers.`custtype` = :custtype AND `bookid` =:bookid AND `seasonid`=:seasonid AND `ordertype` != '1'";
         $result = $order->query($query, $arr);
         $data[$key]->ttSpecialSupply = is_array($result) ? $result[0] : array();
      }
      return $data;
   }
   public function get_Special_Returns($data, $specialtype)
   {
      $order = new Order();
      foreach ($data as $key => $row) {
         $seas = new Season();
         //get current Season
         $seasid = isset($seas->selctingLastId()[0]->id) ? $seas->selctingLastId()[0]->id : '';
         $arr['bookid'] = $row->id;
         $arr['seasonid'] = $seasid;
         $arr['custtype'] = $specialtype;

         $query = "SELECT SUM(`retverquant`) AS ttSpecialreturns, (SELECT SUM(`retverquant`) FROM `orders` JOIN `customers` ON customers.`id` = `customerid` WHERE customers.`custtype` = :custtype AND `bookid` = :bookid AND `seasonid` = :seasonid AND `ordertype` = '1') AS retsample FROM `orders`  JOIN `customers` ON customers.`id` = `customerid` WHERE customers.`custtype` = :custtype AND `bookid` = :bookid AND `seasonid` = :seasonid AND `ordertype` != '1'";
         $result = $order->query($query, $arr);
         $data[$key]->ttSpecialreturns = is_array($result) ? $result[0] : array();
      }
      return $data;
   }

   public function get_Type($data)
   {
      $booktype = new Type();
      foreach ($data as $key => $row) {
         $result = $booktype->where('id', $row->typeid);
         $data[$key]->booktype = is_array($result) ? $result[0] : array();
      }
      return $data;
   }
}
