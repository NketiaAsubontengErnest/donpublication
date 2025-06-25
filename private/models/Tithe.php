<?php

/**
 * 
 * Tithe Model
 */
class Tithe extends Model
{
    protected $ran;
    protected $allowedColumns = [
        'custid',
        'amountPayed',
        'profit',
        'tithe',
        'datepaid',
        'seasonid',
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

    public function getTithe($ttSales, $ttPayment, $amountPaid, $cid)
    {
        $seas = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";
        $tithes = new Tithe();
        $query = "SELECT SUM(`tithe`) AS ttTithe FROM `tithes` WHERE `custid` = $cid AND seasonid = $seas";
        $ttTithe = $tithes->query($query)[0]->ttTithe;


        $tithe = 0;
        try {
            $balance = ($ttSales->totalNetSales) - $ttPayment->totalpayed; // calculate for balance
            $remainingTithe = $ttSales->totalTithe - $ttTithe; // check the remaining tithe

            $chekOverPay = $balance - $amountPaid; // calculage for over payment

            if ($chekOverPay < 0) { // check the if over payments
                $amountPaid = $amountPaid - $chekOverPay; // equalize the numerator
            }

            $tithe = ($amountPaid / $balance) * $remainingTithe;
        } catch (\Throwable $th) {
            //throw $th;
        }

        $data['custid'] = $cid;
        $data['amountPayed'] = $amountPaid;
        $data['profit'] = $tithe * 10;
        $data['tithe'] = $tithe;

        return $data;
    }
}
