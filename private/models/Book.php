

<?php

/**
 * 
 * Books Model
 */
class Book extends Model
{
   protected $ran;
   protected $seasonid = '';
   protected $order;
   protected $newstock;

   public function __construct()
   {
      parent::__construct();
      $this->order = new Order();
      $this->newstock = new Newstock();
      $this->seasonid = $_SESSION['seasondata']->id ?? '';
   }

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
      'attach_Added_Supply_Returns',
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

   public function get_Type($data)
   {
      $booktype = new Type();
      foreach ($data as $key => $row) {
         $result = $booktype->where('id', $row->typeid);
         $data[$key]->booktype = is_array($result) ? $result[0] : array();
      }
      return $data;
   }

   public function get_Sample_Supply($data)
   {
      $order = new Order();
      foreach ($data as $key => $row) {
         $arr['bookid'] = $row->id;
         //get current Season
         $arr['seasonid'] = $_SESSION['seasondata']->id ?? '';

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
         $arr['bookid'] = $row->id;
         //get current Season
         $arr['seasonid'] = $_SESSION['seasondata']->id ?? '';
         $arr['officerid'] = $id;

         $query = "SELECT SUM(`quantsupp`) AS ttMarkSampleSupply, (SELECT SUM(`retverquant`) FROM `orders` WHERE `bookid` = :bookid AND `seasonid` = :seasonid AND `officerid` = :officerid AND `ordertype` = '1') AS retsample FROM `orders` WHERE `bookid` =:bookid AND `seasonid`=:seasonid AND orders.`ordertype` = '1' AND orders.`officerid` =:officerid";
         $result = $order->query($query, $arr);
         $data[$key]->ttMarkSampleSupply = is_array($result) ? $result[0] : array();
      }
      return $data;
   }

   public function get_Sample_Returns($data)
   {
      $order = new Order();
      foreach ($data as $key => $row) {
         $arr['bookid'] = $row->id;
         //get current Season
         $arr['seasonid'] = $_SESSION['seasondata']->id ?? '';

         $query = "SELECT SUM(`retverquant`) AS tt_samp_returns FROM `orders` WHERE `bookid` =:bookid AND `seasonid`=:seasonid AND `ordertype` = '1'";
         $result = $order->query($query, $arr);
         $data[$key]->tt_samp_returns = is_array($result) ? $result[0] : array();
      }
      return $data;
   }

   public function get_Returns($data)
   {
      $order = new Order();
      foreach ($data as $key => $row) {
         $arr['bookid'] = $row->id;
         //get current Season
         $arr['seasonid'] = $_SESSION['seasondata']->id ?? '';

         $query = "SELECT SUM(`retverquant`) AS ttreturns FROM `orders` WHERE `bookid` =:bookid AND `seasonid`=:seasonid AND `ordertype` != '1'";
         $result = $order->query($query, $arr);
         $data[$key]->ttreturns = is_array($result) ? $result[0] : array();
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
         $arr['bookid'] = $row->id;
         //get current Season
         $arr['seasonid'] = $_SESSION['seasondata']->id ?? '';
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
         $arr['bookid'] = $row->id;
         //get current Season
         $arr['seasonid'] = $_SESSION['seasondata']->id ?? '';
         $arr['custtype'] = $specialtype;

         $query = "SELECT SUM(`retverquant`) AS ttSpecialreturns, (SELECT SUM(`retverquant`) FROM `orders` JOIN `customers` ON customers.`id` = `customerid` WHERE customers.`custtype` = :custtype AND `bookid` = :bookid AND `seasonid` = :seasonid AND `ordertype` = '1') AS retsample FROM `orders`  JOIN `customers` ON customers.`id` = `customerid` WHERE customers.`custtype` = :custtype AND `bookid` = :bookid AND `seasonid` = :seasonid AND `ordertype` != '1'";
         $result = $order->query($query, $arr);
         $data[$key]->ttSpecialreturns = is_array($result) ? $result[0] : array();
      }
      return $data;
   }

   public function attach_Added_Supply_Returns($data)
   {
      $bookIds = array_column($data, 'id');
      if (empty($bookIds)) return $data;

      $placeholders = implode(',', array_fill(0, count($bookIds), '?'));

      // Added (newstocks)
      $addedRows = (new Newstock())->query(
         "SELECT bookid, SUM(quantity) AS ttAdded FROM newstocks WHERE seasonid = ? AND bookid IN ($placeholders) GROUP BY bookid",
         array_merge([$this->seasonid], $bookIds)
      );
      if (!is_array($addedRows)) $addedRows = [];

      // Supply & Returns (orders)
      $orderQuery = "
      SELECT 
         bookid,
         SUM(CASE WHEN ordertype != '1' THEN quantsupp ELSE 0 END) AS ttSupply,
         SUM(CASE WHEN ordertype = '1' THEN quantsupp ELSE 0 END) AS ttSampleSupply,
         SUM(CASE WHEN ordertype != '1' THEN retverquant ELSE 0 END) AS ttreturns,
         SUM(CASE WHEN ordertype = '1' THEN retverquant ELSE 0 END) AS tt_samp_returns
      FROM orders
      WHERE seasonid = ? AND bookid IN ($placeholders)
      GROUP BY bookid
   ";
      $orderRows = $this->order->query($orderQuery, array_merge([$this->seasonid], $bookIds));
      if (!is_array($orderRows)) $orderRows = [];

      $addedMap = array_column($addedRows, null, 'bookid');
      $orderMap = array_column($orderRows, null, 'bookid');

      foreach ($data as $key => $row) {
         $id = $row->id;
         $data[$key]->ttadded = $addedMap[$id] ?? (object)['ttAdded' => 0];
         $stats = $orderMap[$id] ?? (object)[
            'ttSupply' => 0,
            'ttSampleSupply' => 0,
            'ttreturns' => 0,
            'tt_samp_returns' => 0,
         ];
         $data[$key]->ttSupply = (object)['ttSupply' => $stats->ttSupply];
         $data[$key]->ttSampleSupply = (object)['ttSampleSupply' => $stats->ttSampleSupply];
         $data[$key]->ttreturns = (object)['ttreturns' => $stats->ttreturns];
         $data[$key]->tt_samp_returns = (object)['tt_samp_returns' => $stats->tt_samp_returns];
      }

      return $data;
   }


   // You can optimize other methods like these:
   public function get_Makerter_Supply($data, $id)
   {
      foreach ($data as $key => $row) {
         $arr = [
            'bookid' => $row->id,
            'seasonid' => $this->seasonid,
            'officerid' => $id
         ];

         $query = "SELECT 
            SUM(quantsupp) AS ttSupply, 
            SUM(quantsupp - retverquant) AS actualSupply,
            SUM((quantsupp - retverquant) * unitprice) AS book_gross,
            SUM(((quantsupp - retverquant) * unitprice) * (1 - discount/100)) AS book_net,
            SUM((retverquant * unitprice) * (1 - discount/100)) AS return_net
         FROM orders 
         JOIN customers ON customers.id = customerid 
         WHERE customers.custtype = 'school' 
            AND bookid = :bookid 
            AND seasonid = :seasonid 
            AND orders.officerid = :officerid 
            AND ordertype != '1'";

         $result = $this->order->query($query, $arr);
         $data[$key]->ttMarketSupply = $result[0] ?? (object)[];
      }
      return $data;
   }

   public function get_Makerter_Returns($data, $id)
   {
      foreach ($data as $key => $row) {
         $arr = [
            'bookid' => $row->id,
            'seasonid' => $this->seasonid,
            'officerid' => $id
         ];

         $query = "SELECT 
            SUM(retverquant) AS ttmarketreturns, 
            (SELECT SUM(retverquant) FROM orders 
             JOIN customers ON customers.id = customerid 
             WHERE customers.custtype = 'school' 
             AND bookid = :bookid 
             AND seasonid = :seasonid 
             AND orders.officerid = :officerid 
             AND ordertype = '1') AS retsample
         FROM orders 
         JOIN customers ON customers.id = customerid 
         WHERE customers.custtype = 'school' 
         AND bookid = :bookid 
         AND seasonid = :seasonid 
         AND orders.officerid = :officerid 
         AND ordertype != '1'";

         $result = $this->order->query($query, $arr);
         $data[$key]->ttmarketreturns = $result[0] ?? (object)[];
      }
      return $data;
   }
}
