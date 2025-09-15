<?php

/**
 * 
 * Payment Model
 */
class Payment extends Model
{
    protected $ran;
    protected $allowedColumns = [
        'transid',
        'reciept',
        'amount',
        'customerid',
        'status',
        'officerid',
        'seasonid',
        'modeofpayment',
        'paymentdate',
        'titheid',
    ];

    protected $beforeInset = [
        // 'make_course_id',
    ];
    protected $afterSelect = [
        'get_Customer',
        'get_Officer',
        'get_Marketer',
    ];

    public function validate($data)
    {
        $this->errors = array();

        if (empty($data['transid']) && array_key_exists('ret', $data)) {
            $this->errors['transid'] = "Enter transaction number";
        }

        if (empty($data['reciept']) && array_key_exists('ret', $data)) {
            $this->errors['reciept'] = "Enter receipt number";
        }
        if (empty($data['amount']) && array_key_exists('ret', $data)) {
            $this->errors['amount'] = "Enter amount";
        }

        if ($this->query("SELECT * FROM `payments` WHERE `transid` = '{$data['transid']}' AND `datepaid` = CURRENT_DATE AND `modeofpayment` = '{$data['modeofpayment']}' AND `seasonid` = {$_SESSION['seasondata']->id}")) {
            $this->errors['transid'] = "Transaction number used already";
        }

        //check if the errors are empty
        if (count($this->errors) == 0) {
            return true;
        }
        return false;
    }

    public function get_Customer($data)
    {
        $customer = new Customer();
        if ($data) {
            foreach ($data as $key => $row) {
                $result = $customer->where('id', $row->customerid);
                $data[$key]->customers = is_array($result) ? $result[0] : array();
            }
        }
        return $data;
    }

    public function get_Officer($data)
    {
        $verific = new User();
        if ($data) {
            foreach ($data as $key => $row) {
                $result = $verific->where('username', $row->officerid);
                $data[$key]->verificOff = is_array($result) ? $result[0] : array();
            }
        }
        return $data;
    }
    public function get_Marketer($data)
    {
        $marketers = new User();
        if ($data) {
            foreach ($data as $key => $row) {
                $result = $marketers->where('id', $row->officerid);
                $data[$key]->marketer = is_array($result) ? $result[0] : array();
            }
        }
        return $data;
    }

    public function get_Total($data,  $seasid, $payArr = array())
    {
        $payment = new Payment();
        if ($data) {
            foreach ($data as $key => $row) {
                if (count($payArr) != 0) {
                    $query = "SELECT SUM(`amount`) AS totalpayed FROM `payments` WHERE `customerid` = $row->cid AND `seasonid` = $seasid  AND (paymentdate >= :startDate AND paymentdate <= :endDate)";
                } else {
                    $query = "SELECT SUM(`amount`) AS totalpayed FROM `payments` WHERE `customerid` = $row->cid AND `seasonid` = $seasid";
                }
                $result = $payment->query($query, $payArr);
                $data[$key]->totalpayment = is_array($result) ? $result[0] : array();
            }
        }
        return $data;
    }
    public function get_TotalDept($data, $seasid)
    {
        $order = new Order();
        if ($data) {
            foreach ($data as $key => $row) {
                $arr['custid'] = $row->cid;
                $arr['seasid'] = $seasid;
                $query = "SELECT SUM((orders.`quantsupp` - orders.`retverquant`) * orders.`unitprice`) AS totaldept, SUM((`retverquant` * `unitprice`)* ((100 - `discount`)/100)) AS total_net_returns, SUM(orders.`retverquant` * orders.`unitprice`) AS totalReturns, SUM(((orders.`quantsupp` - orders.`retverquant`) * orders.`unitprice`)* (orders.`discount`/100)) AS totaldisc FROM `orders` WHERE `customerid` =:custid AND `seasonid` =:seasid";
                $result = $order->query($query, $arr);
                $data[$key]->amout_disco = is_array($result) ? $result[0] : array();
            }
        }
        return $data;
    }

    public function get_Book_Sales($data, $seasid)
    {
        $order = new Order();
        if ($data) {
            foreach ($data as $key => $row) {
                $arr['bookid'] = $row->id;
                $arr['seasid'] = $seasid;
                $query = "SELECT SUM((orders.`quantsupp` - orders.`retverquant`) * orders.`unitprice`) AS totalBookGross, SUM((`retverquant` * `unitprice`)* ((100 - `discount`)/100)) AS total_net_returns, SUM(orders.`retverquant` * orders.`unitprice`) AS totalBookReturns, SUM(((orders.`quantsupp` - orders.`retverquant`) * orders.`unitprice`) * (orders.`discount` / 100)) AS totalBookNet, SUM(CASE WHEN orders.`accountofficer` IS NOT NULL AND orders.`accountofficer` != '' THEN orders.`quantsupp` ELSE 0 END) AS totalQuantSuppAccountOfficerNotEmpty FROM `orders` WHERE `bookid` = :bookid AND `seasonid` = :seasid AND `ordertype` != 1;";
                $result = $order->query($query, $arr);
                $data[$key]->bookSales = is_array($result) ? $result[0] : array();
            }
        }
        return $data;
    }

    public function get_TotalCustomer($id, $seasid = "")
    {
        $order = new Order();
        $arr['custid'] = $id;
        $arr['seasid'] = $seasid;
        $query = "SELECT SUM((orders.`quantsupp` - orders.`retverquant`) * orders.`unitprice`) AS totalGrossSales, SUM((`retverquant` * `unitprice`)* ((100 - `discount`)/100)) AS total_net_returns, SUM((orders.`quantsupp` - orders.`retverquant`)) AS totalQtySold, SUM((orders.`discount` / 100) * ((orders.`quantsupp` - orders.`retverquant`) * orders.`unitprice`)) AS totalDiscount, SUM(((orders.`quantsupp` - orders.`retverquant`) * orders.`unitprice`) - ((orders.`discount` / 100) * ((orders.`quantsupp` - orders.`retverquant`) * orders.`unitprice`))) AS totalNetSales, SUM(orders.`quantsupp` - orders.`retverquant` * books.`tithe`) AS totalTithe FROM `orders` LEFT JOIN books ON orders.`bookid` = books.`id` WHERE `ordertype` != '1' AND `customerid` =:custid AND `seasonid` =:seasid";
        $result = $order->query($query, $arr);
        return is_array($result) ? $result[0] : array();;
    }

    public function get_TotalPay($id)
    {
        $payme = new Payment();
        $arr['custid'] = $id;
        $arr['seasonid'] = $_SESSION['seasondata']->id ?? '';
        $query = "SELECT SUM(`amount`) AS totalpayed FROM `payments` WHERE `customerid` =:custid AND `seasonid` =:seasonid";
        $result = $payme->query($query, $arr);
        return is_array($result) ? $result[0] : array();;
    }

    public function get_OfficTotal($data)
    {
        $payment = new Payment();
        $arr['seasonid'] = $_SESSION['seasondata']->id ?? '';
        if ($data) {
            foreach ($data as $key => $row) {
                $query = "SELECT SUM(`amount`) AS totalpayed FROM `payments` WHERE `ordertype` != '1' AND `officerid` = $row->id AND `seasonid` = {$arr['seasonid']}";
                $result = $payment->query($query);
                $data[$key]->OfficTotal = is_array($result) ? $result[0] : array();
            }
        }
        return $data;
    }
    public function get_OfficTotalDept($data)
    {
        $order = new Order();
        $arr['seasonid'] = $_SESSION['seasondata']->id ?? '';
        if ($data) {
            foreach ($data as $key => $row) {
                $query = "SELECT SUM((`quantsupp` - `retverquant`) * `unitprice`) AS totaldept, SUM((`retverquant` * `unitprice`)* ((100 - `discount`)/100)) AS total_net_returns, SUM(`retverquant` * `unitprice`) AS totalReturns, SUM(((`quantsupp` - `retverquant`) * `unitprice`)* (`discount`/100)) AS totaldisc FROM `orders` WHERE `ordertype` != '1' AND `officerid` = $row->id AND `seasonid` = {$arr['seasonid']}";
                $result = $order->query($query);
                $data[$key]->OfficTotalDept = is_array($result) ? $result[0] : array();
            }
        }
        return $data;
    }

    public function get_OfficTotalD($data, $seasid, $payArr = array())
    {
        $payment = new Payment();
        if ($data) {
            foreach ($data as $key => $row) {
                $offid  = $row->id;
                if (count($payArr) != 0) {
                    $query = "SELECT SUM(`amount`) AS totalpayed, customers.custtype FROM `payments` LEFT JOIN customers ON payments.customerid = customers.id WHERE payments.`officerid`= $offid AND customers.custtype = 'school' AND payments.`seasonid` = {$seasid} AND (paymentdate >= :startDate AND paymentdate <= :endDate)";
                } else {
                    $query = "SELECT SUM(`amount`) AS totalpayed, customers.custtype FROM `payments` LEFT JOIN customers ON payments.customerid = customers.id WHERE payments.`officerid`= $offid AND customers.custtype = 'school' AND payments.`seasonid` = {$seasid}";
                }
                $result = $payment->query($query, $payArr);
                $data[$key]->OfficTotal = is_array($result) ? $result[0] : array();
            }
        }
        return $data;
    }
    public function get_OfficTotalDeptD($data, $seasid)
    {
        $order = new Order();
        if ($data) {
            foreach ($data as $key => $row) {
                $offid = $row->id;
                $query = "SELECT SUM((`quantsupp` - `retverquant`) * `unitprice`) AS totaldept, SUM(`retverquant` * `unitprice`) AS totalReturns, SUM(((`quantsupp` - `retverquant`) * `unitprice`)* (`discount`/100)) AS totaldisc, SUM((`retverquant` * `unitprice`)* ((100 - `discount`)/100)) AS total_net_returns,  customers.custtype FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id WHERE orders.`ordertype` != '1' AND orders.`officerid` = $offid AND customers.custtype = 'school' AND orders.`seasonid` ={$seasid} ";
                $result = $order->query($query);
                $data[$key]->OfficTotalDept = is_array($result) ? $result[0] : array();
            }
        }
        return $data;
    }


    public function get_TotalSales($useid)
    {
        $orders = new Order();
        $query = "SELECT SUM((`quantsupp` - `retverquant`) * `unitprice`) AS totalGrossSales, SUM((`retverquant` * `unitprice`)* ((100 - `discount`)/100)) AS total_net_returns, SUM((`discount` / 100) * ((`quantsupp` - `retverquant`) * `unitprice`)) AS totalDiscount, SUM(((`quantsupp` - `retverquant`) * `unitprice`) - ((`discount` / 100) * ((`quantsupp` - `retverquant`) * `unitprice`))) AS totalNetSales  FROM `orders` WHERE orders.`ordertype` != '1' AND `seasonid` = :seasonid";
        $query1 = "SELECT SUM(`amount`) AS totalPayment FROM `payments` WHERE `seasonid` =:seasonid";
        $dd['seasonid'] = $useid;
        $result['totalSales'] = $orders->query($query, $dd)[0];
        $result['totalPayments'] = $orders->query($query1, $dd)[0];
        $result['balance'] = $result['totalSales']->totalNetSales - $result['totalPayments']->totalPayment;
        return is_array($result) ? $result : array();
    }
    public function get_TotalSalesAgent($useid)
    {
        $orders = new Order();
        $query = "SELECT SUM((`quantsupp` - `retverquant`) * `unitprice`) AS totalGrossSales, SUM((`retverquant` * `unitprice`)* ((100 - `discount`)/100)) AS total_net_returns, SUM((`discount` / 100) * ((`quantsupp` - `retverquant`) * `unitprice`)) AS totalDiscount, SUM(((`quantsupp` - `retverquant`) * `unitprice`) - ((`discount` / 100) * ((`quantsupp` - `retverquant`) * `unitprice`))) AS totalNetSales,  customers.custtype FROM `orders` LEFT JOIN customers ON customers.id = orders.customerid WHERE orders.`ordertype` != '1' AND customers.custtype =:custtype AND `seasonid` =:seasonid;";
        $query1 = "SELECT SUM(`amount`) AS totalPayment, customers.custtype FROM `payments` LEFT JOIN customers ON customers.id = payments.customerid WHERE customers.custtype =:custtype AND `seasonid` =:seasonid";
        $dd['seasonid'] = $useid;
        $dd['custtype'] = 'agent';
        $result['agentTotalSales'] = $orders->query($query, $dd)[0];
        $result['agentTotalPayments'] = $orders->query($query1, $dd)[0];
        $result['balance'] = $result['agentTotalSales']->totalNetSales - $result['agentTotalPayments']->totalPayment;
        return is_array($result) ? $result : array();
    }
    public function get_TotalSalesGaris($useid)
    {
        $orders = new Order();
        $query = "SELECT SUM((`quantsupp` - `retverquant`) * `unitprice`) AS totalGrossSales, SUM((`retverquant` * `unitprice`)* ((100 - `discount`)/100)) AS total_net_returns, SUM((`discount` / 100) * ((`quantsupp` - `retverquant`) * `unitprice`)) AS totalDiscount, SUM(((`quantsupp` - `retverquant`) * `unitprice`) - ((`discount` / 100) * ((`quantsupp` - `retverquant`) * `unitprice`))) AS totalNetSales,  customers.custtype FROM `orders` LEFT JOIN customers ON customers.id = orders.customerid WHERE orders.`ordertype` != '1' AND customers.custtype =:custtype AND `seasonid` =:seasonid;";
        $query1 = "SELECT SUM(`amount`) AS totalPayment, customers.custtype FROM `payments` LEFT JOIN customers ON customers.id = payments.customerid WHERE customers.custtype =:custtype AND `seasonid` =:seasonid";
        $dd['seasonid'] = $useid;
        $dd['custtype'] = 'garris';
        $result['GarisTotalSales'] = $orders->query($query, $dd)[0];
        $result['GarisTotalPayments'] = $orders->query($query1, $dd)[0];
        $result['balance'] = $result['GarisTotalSales']->totalNetSales - $result['GarisTotalPayments']->totalPayment;
        return is_array($result) ? $result : array();
    }
    public function get_TotalSalesBookshop($useid)
    {
        $orders = new Order();
        $query = "SELECT SUM((`quantsupp` - `retverquant`) * `unitprice`) AS totalGrossSales, SUM((`retverquant` * `unitprice`)* ((100 - `discount`)/100)) AS total_net_returns, SUM((`discount` / 100) * ((`quantsupp` - `retverquant`) * `unitprice`)) AS totalDiscount, SUM(((`quantsupp` - `retverquant`) * `unitprice`) - ((`discount` / 100) * ((`quantsupp` - `retverquant`) * `unitprice`))) AS totalNetSales,  customers.custtype FROM `orders` LEFT JOIN customers ON customers.id = orders.customerid WHERE orders.`ordertype` != '1' AND customers.custtype =:custtype AND `seasonid` =:seasonid;";
        $query1 = "SELECT SUM(`amount`) AS totalPayment, customers.custtype FROM `payments` LEFT JOIN customers ON customers.id = payments.customerid WHERE customers.custtype =:custtype AND `seasonid` =:seasonid";
        $dd['seasonid'] = $useid;
        $dd['custtype'] = 'booksh';
        $result['BoshTotalSales'] = $orders->query($query, $dd)[0];
        $result['BoshTotalPayments'] = $orders->query($query1, $dd)[0];
        $result['balance'] = $result['BoshTotalSales']->totalNetSales - $result['BoshTotalPayments']->totalPayment;
        return is_array($result) ? $result : array();
    }
    public function get_TotalSalesSchool($useid)
    {
        $orders = new Order();
        $query = "SELECT SUM((`quantsupp` - `retverquant`) * `unitprice`) AS totalGrossSales, SUM((`discount` / 100) * ((`quantsupp` - `retverquant`) * `unitprice`)) AS totalDiscount, SUM((`retverquant` * `unitprice`)* ((100 - `discount`)/100)) AS total_net_returns, SUM(((`quantsupp` - `retverquant`) * `unitprice`) - ((`discount` / 100) * ((`quantsupp` - `retverquant`) * `unitprice`))) AS totalNetSales,  customers.custtype FROM `orders` LEFT JOIN customers ON customers.id = orders.customerid WHERE orders.`ordertype` != '1' AND customers.custtype =:custtype AND `seasonid` =:seasonid;";
        $query1 = "SELECT SUM(`amount`) AS totalPayment, customers.custtype FROM `payments` LEFT JOIN customers ON customers.id = payments.customerid WHERE customers.custtype =:custtype AND `seasonid` =:seasonid";
        $dd['seasonid'] = $useid;
        $dd['custtype'] = 'school';
        $result['SchoolTotalSales'] = $orders->query($query, $dd)[0];
        $result['SchoolTotalPayments'] = $orders->query($query1, $dd)[0];
        $result['balance'] = $result['SchoolTotalSales']->totalNetSales - $result['SchoolTotalPayments']->totalPayment;
        return is_array($result) ? $result : array();
    }
}
