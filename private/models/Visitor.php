<?php

/**
 * 
 * Customer Model
 */
class Visitor extends Model
{
    protected $ran;
    protected $allowedColumns = [
        'customername',
        'custphone',
        'custlocation',
        'contactperson',
        'custtype',
        'region',
        'workbook',
        'textbook',
        'officerid',
        'seasonid',
    ];

    protected $beforeInset = [
        //'make_user_id',
    ];

    protected $afterSelect = [
        'get_Officer',
    ];
    public function validate($data)
    {
        $this->errors = array();
        if (isset($data['oldcustomer'])) {
            if (!empty($data['oldcustomer']) && empty($data['customername'])) {
                $data['customername'] = $data['oldcustomer'];
                unset($data['oldcustomer']);
            } elseif (empty($data['oldcustomer']) && !empty($data['customername'])) {
                unset($data['oldcustomer']);
            } else {
                $this->errors['allset'] = "you can't set Old and New Customer";
            }
        }
        //checking errors for customer name
        if (empty($data['customername']) || !preg_match('/^[a-zA-Z 0-9&\-\.]+$/', $data['customername'])) {
            $this->errors['customername'] = "Only letters allowed in customer name";
        }
        //checking errors for customer location      
        if (empty($data['custlocation'])) {
            $this->errors['custlocation'] = "Customer location can't be empty";
        }
        //checking errors for customer location      
        if (empty($data['contactperson'])) {
            $this->errors['contactperson'] = "Contact person can't be empty";
        }

        //checking errors for customer location      
        if (strlen($data['custphone']) != 10) {
            $this->errors['custphone'] = "Customer phone can't be less or more than 10";
        }

        if ($this->where('custphone', $data['custphone'])) {
            $this->errors['custphone'] = "Customer alrady exists";
        }

        //checking errors for customer type
        if (empty($data['region'])) {
            $this->errors['region'] = "Select customer region";
        }


        //check if the errors are empty
        if (count($this->errors) == 0) {
            return true;
        }
        return false;
    }

    public function make_user_id($data)
    {
        $data['officerid'] = Auth::getId();
        return $data;
    }

    public function get_Officer($data)
    {
        $agent = new User();
        if ($data) {
            foreach ($data as $key => $row) {
                if (!empty($row->officerid)) {
                    $result = $agent->where('id', $row->officerid);
                    $data[$key]->marketer = isset($result[0]) ? $result[0] : array();
                }
            }
        }
        return $data;
    }
}
