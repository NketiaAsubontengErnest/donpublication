<?php

/**
 * 
 * Bank Model
 */
class Bank extends Model
{
    protected $ran;
    protected $allowedColumns = [
        'bankname',
        'abrv',
        'status',
    ];

    public function validate($data)
    {
        $this->errors = array();
        //checking errors for School Name
        if (empty($data['bankname'])) {
            $this->errors['bankname'] = "Bank Name can't be empty";
        }

        if (empty($data['abrv'])) {
            $this->errors['abrv'] = "Abbreviation can't be empty";
        }

        if ($this->where('bankname', $data['bankname']) || $this->where('abrv', $data['abrv'])) {
            $this->errors['bankname'] = "Bank Name already exists";
        }


        //check if the errors are empty
        if (count($this->errors) == 0) {
            return true;
        }
        return false;
    }
}
