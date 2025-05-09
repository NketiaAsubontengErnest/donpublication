<?php

/**
 * 
 * Seasons Model
 */
class Season extends Model
{
   protected $ran;
   protected $allowedColumns = [
      'seasonname',
      'seasonstatus',
   ];

   protected $afterSelect = [
      'get_OfficTotalPayed',
      'get_OfficTotalDept',
   ];

   public function validate($data)
   {
      $this->errors = array();
      //checking errors for School Name
      if (empty($data['seasonname'])) {
         $this->errors['seasonname'] = "Season Name can't be empty";
      }


      //check if the errors are empty
      if (count($this->errors) == 0) {
         return true;
      }
      return false;
   }

   public function get_OfficTotalPayed($data)
   {
      $payment = new Payment();
      if ($data) {
         foreach ($data as $key => $row) {
            $seasid = $row->id;
            if (Auth::getRank() == 'marketer') {
               $offid  = Auth::getId();
               $query = "SELECT SUM(`amount`) AS totalpayed, customers.custtype FROM `payments` LEFT JOIN customers ON payments.customerid = customers.id WHERE payments.`officerid`= $offid AND customers.custtype = 'school' AND payments.`seasonid` = {$seasid} ";
            } else {
               $query = "SELECT SUM(`amount`) AS totalpayed FROM `payments` WHERE payments.`seasonid` = {$seasid}";
            }
            $result = $payment->query($query);
            $data[$key]->OfficTotal = is_array($result) ? $result[0] : array();
         }
      }
      return $data;
   }
   public function get_OfficTotalDept($data)
   {
      $order = new Order();
      if ($data) {
         foreach ($data as $key => $row) {
            $seasid = $row->id;
            if (Auth::getRank() == 'marketer') {
               $offid  = Auth::getId();
               $query = "SELECT SUM((`quantsupp` - `retverquant`) * `unitprice`) AS totaldept, SUM(`retverquant` * `unitprice`) AS totalReturns, SUM(((`quantsupp` - `retverquant`) * `unitprice`)* (`discount`/100)) AS totaldisc, SUM((`retverquant` * `unitprice`)* ((100 - `discount`)/100)) AS total_net_returns,  customers.custtype FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id WHERE orders.`ordertype` != '1' AND orders.`officerid` = $offid AND customers.custtype = 'school' AND orders.`seasonid` ={$seasid} ";
            } else {
               $query = "SELECT SUM((`quantsupp` - `retverquant`) * `unitprice`) AS totaldept, SUM(`retverquant` * `unitprice`) AS totalReturns, SUM(((`quantsupp` - `retverquant`) * `unitprice`)* (`discount`/100)) AS totaldisc, SUM((`retverquant` * `unitprice`)* ((100 - `discount`)/100)) AS total_net_returns,  customers.custtype FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id WHERE orders.`ordertype` != '1' AND orders.`seasonid` ={$seasid} ";
            }
            $result = $order->query($query);
            $data[$key]->OfficTotalDept = is_array($result) ? $result[0] : array();
         }
      }
      return $data;
   }
}
