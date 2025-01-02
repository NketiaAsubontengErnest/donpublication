<?php

/**
 * 
 * Order Model
 */
class Order extends Model
{
    protected $ran;
    protected $allowedColumns = [
        'bookid',
        'customerid',
        'quantord',
        'quantsupp',
        'ordernumber',
        'verifiedid',
        'issureid',
        'verificid',
        'invoiceno',
        'officerid',
        'seasonid',
        'ordertype',
    ];

    protected $beforeInset = [
        // 'make_course_id',
    ];
    protected $afterSelect = [
        'get_Book',
        'get_Customer',
        'get_Sto_Officer',
        'get_Officer',
        'get_Type',
        'get_Markerter',
        'get_RetOfficer',
    ];

    public function validate($data)
    {
        $this->errors = array();

        if (empty($data['retquant']) && array_key_exists('ret', $data)) {
            $this->errors['retquant'] = "Enter Quantity Returning";
        }

        if (array_key_exists('quantsupp', $data) && isset($data['retquant'])) {
            if (($data['retquant'] > $data['quantsupp']) && array_key_exists('ret', $data)) {
                $this->errors['retquant'] = "Quantity Returning can't be Higher than Quantity Received";
            }
        }

        //check if the errors are empty
        if (count($this->errors) == 0) {
            return true;
        }
        return false;
    }

    public function get_Book($data)
    {
        $book = new Book();
        foreach ($data as $key => $row) {
            if (!empty($row->bookid)) {
                $result = $book->where('id', $row->bookid);
                $data[$key]->books = is_array($result) ? $result[0] : array();
            }
        }
        return $data;
    }

    public function get_Customer($data)
    {
        $customer = new Customer();
        foreach ($data as $key => $row) {
            $result = $customer->where('id', $row->customerid);
            $data[$key]->customers = is_array($result) ? $result[0] : array();
        }
        return $data;
    }

    public function get_Officer($data)
    {
        $verific = new User();
        foreach ($data as $key => $row) {
            $result = $verific->where('username', $row->verificid);
            $data[$key]->verificOff = is_array($result) ? $result[0] : array();
        }
        return $data;
    }
    public function get_Type($data)
    {
        $ordertyp = new Ordertype();
        foreach ($data as $key => $row) {
            $result = $ordertyp->where('id', $row->ordertype);
            $data[$key]->ordertypes = is_array($result) ? $result[0] : array();
        }
        return $data;
    }
    public function get_Ret_Officer($data)
    {
        $verific = new User();
        foreach ($data as $key => $row) {
            $result = $verific->where('username', $row->retverofficer);
            $data[$key]->verifiRetcOff = is_array($result) ? $result[0] : array();
        }
        return $data;
    }

    public function get_Sto_Officer($data)
    {
        $stores = new User();
        foreach ($data as $key => $row) {
            $result = $stores->where('username', $row->issureid);
            $data[$key]->storesOff = is_array($result) ? $result[0] : array();
        }
        return $data;
    }
    public function get_RetOfficer($data)
    {
        $stores = new User();
        foreach ($data as $key => $row) {
            if (isset($row->retverofficer)) {
                $result = $stores->where('username', $row->retverofficer);
                $data[$key]->retverofficer = is_array($result) ? $result[0] : array();
            }
        }
        return $data;
    }
    public function get_Markerter($data)
    {
        $makerter = new User();
        foreach ($data as $key => $row) {
            $result = $makerter->where('id', $row->officerid);
            $data[$key]->makerter = is_array($result) ? $result[0] : array();
        }
        return $data;
    }
    public function get_TotalSalesOrder($data)
    {
        $orders = new Order();
        if ($data) {
            foreach ($data as $key => $row) {
                $query = "SELECT SUM((`quantsupp` - `retverquant`) * `unitprice`) AS totalGrossSales, SUM((`discount` / 100) * ((`quantsupp` - `retverquant`) * `unitprice`)) AS totalDiscount, SUM(((`quantsupp` - `retverquant`) * `unitprice`) - ((`discount` / 100) * ((`quantsupp` - `retverquant`) * `unitprice`))) AS totalNetSales FROM `orders` WHERE orders.`ordernumber` =:ordernumber;";
                $dd['ordernumber'] = $row->ordernumber;
                $result = $orders->query($query, $dd);
                $data[$key]->totalOrderSale = is_array($result) ? $result[0] : array();
            }
        }
        return $data;
    }
}
