<?php

/**
 * 
 * Level Income
 */
class Income extends Model
{
    protected $ran;
    protected $allowedColumns = [
        'incomedate',
        'incomeamount',
        'naration',
        'seasonid',
        'userid',
    ];
    protected $afterSelect = [
        'get_User'
    ];

    public function validate($data)
    {
        $this->errors = array();
        //checking errors for School Name
        if (empty($data['incomedate'])) {
            $this->errors['incomedate'] = "Income date can't be empty";
        }
        if (empty($data['incomeamount'])) {
            $this->errors['incomeamount'] = "Income Amount can't be empty";
        }
        if (empty($data['naration'])) {
            $this->errors['naration'] = "Naration can't be empty";
        }
        //   if(!is_numeric($data['coursefees'])){
        //      $this->errors['coursefees'] = "Only numbers allowed in Fees";
        //   }

        //check if the errors are empty
        if (count($this->errors) == 0) {
            return true;
        }
        return false;
    }
    public function get_User($data)
    {
        $users = new User();
        foreach ($data as $key => $row) {
            $result = $users->where('id', $row->userid);
            $data[$key]->user = is_array($result) ? $result[0] : array();
        }
        return $data;
    }
}
