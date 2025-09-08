<?php

/**
 * 
 * Uninvoicedret Model
 */
class Uninvoicedret extends Model
{
    protected $ran;
    protected $allowedColumns = [
        'orderid',
        'bookid',
        'quant',
        'ordernumber',
        'datereturned',
        'verifOfficer',
    ];

    protected $afterSelect = [
        'get_Book',
        'get_Order',
    ];

    public function validate($data)
    {
        $this->errors = array();

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

    public function get_Order($data)
    {
        $book = new Book();
        foreach ($data as $key => $row) {
            if (!empty($row->orderid)) {
                $result = $book->query('SELECT `quantord` FROM `orders` WHERE `id` = :orderid', ['orderid' => $row->orderid]);
                $data[$key]->ordered = is_array($result) ? $result[0]->quantord : array();
            }
        }
        return $data;
    }
}
