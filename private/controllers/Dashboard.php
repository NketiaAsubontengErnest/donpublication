<?php

/**
 * Dashboard controller
 */
class Dashboard extends Controller
{
    function index()
    {
        if (!Auth::logged_in()) {
            $this->redirect('login');
        }

        $books = new Book();
        $season = new Season();
        $customers = new Customer();
        $visited = new Visitor();
        $orders = new Order();

        $data = array();
        $arr = [];

        //get current Season
        $ss = $season->selctingLastId();
        $ss2 = $season->findAll();

        if (count($_POST) > 0 && isset($_POST['season'])) {
            $_SESSION['seasondata'] = $data['season'] = $season->where('id', $_POST['season'])[0];
        }

        if (!isset($_SESSION['seasondata'])) {
            $arr['seasonid'] = isset($ss[0]->id) ? $ss[0]->id : '';
            $_SESSION['seasondata'] = $ss[0];
            $data['season'] = $ss[0];
        } else {
            $arr['seasonid'] = $_SESSION['seasondata']->id;
            $data['season'] = $_SESSION['seasondata'];
        }

        $querysup = '';
        $userid = Auth::getId();
        if (Auth::getRank() == 'marketer') {
            $querysup = "SELECT SUM(`quantsupp`) AS quantsupp, SUM(`retverquant`) AS retverquant FROM `orders` WHERE `seasonid`=:seasonid AND `officerid` = {$userid}";

            $querysupsamp = "SELECT SUM(`quantsupp`) AS quantsuppsamp FROM `orders` WHERE `seasonid`={$arr['seasonid']} AND `officerid` = {$userid} AND `ordertype` = 1";
            $ordsamp = $orders->query($querysupsamp)[0];
            $data['ordersamp'] = isset($ordsamp) ? $ordsamp : '';

            $queryqtyorders = "SELECT COUNT(DISTINCT `ordernumber`) AS orderss FROM `orders` WHERE `officerid` = {$userid} AND `seasonid`= {$arr['seasonid']} AND `quantsupp` !=''";
            $ord = $orders->query($queryqtyorders)[0];
            $data['orders'] = isset($ord) ? $ord : '';

            $queryqtyorder = "SELECT COUNT(DISTINCT `ordernumber`) AS ordersnv FROM `orders` WHERE `officerid` = {$userid} AND `quantsupp` !='' AND `seasonid`= {$arr['seasonid']}";
            $ordnv = $orders->query($queryqtyorder)[0];
            $data['ordersnotver'] = isset($ordnv) ? $ordnv : '';

            $cust = $customers->selectCountWhere("officerid", $userid, 'ttcust')[0];
            $data['ttcusts'] = isset($cust) ? $cust : '';

            $queryvisited = "SELECT count(*) AS totalvisited FROM `visitors` WHERE `seasonid` = {$arr['seasonid']} AND `officerid` = {$userid};";
            $custv = $visited->query($queryvisited)[0];
            $data['ttcustsv'] = isset($custv) ? $custv->totalvisited : '';

            $query = "SELECT SUM(`textbook` + `workbook`) as ttshered FROM `visitors` WHERE `officerid` = {$userid} AND `seasonid` = {$arr['seasonid']};";
            $ttshered = $visited->query($query)[0];
            $data['ttshered'] = isset($ttshered) ? $ttshered : '';
        } else {
            $query = "SELECT SUM(`quantity`) AS quantity FROM `books`;";
            $data['books'] = isset($books->query($query)[0]) ? $books->query($query)[0] : '';

            $querysup = "SELECT SUM(`quantsupp`) AS quantsupp, SUM(`retverquant`) AS retverquant FROM `orders` WHERE `seasonid`=:seasonid;";

            $query = "SELECT COUNT(*) AS les FROM `books` WHERE `quantity` <= `treshhold`";
            $bkq = $books->query($query)[0];
            $data['outstock'] = isset($bkq) ? $bkq  : '';

            $bb = $books->selectCount()[0];
            $data['ttbooks'] = isset($bb) ? $bb : '';

            $querysample = "SELECT SUM(`quantsupp`) AS quantsuppSamp FROM `orders` WHERE `seasonid`=:seasonid AND `ordertype` = '1';";
            $samp = $books->query($querysample, $arr)[0];
            $data['sampleorder'] = isset($samp) ? $samp : '';
        }

        $bbb = $books->query($querysup, $arr)[0];
        $data['order'] = isset($bbb) ? $bbb : '';

        $msg = " Logged in successfully";
        $crumbs[] = ['Dashboard', ''];
        $actives = 'dashboard';
        $hiddenSearch = "yeap";
        return $this->view('dashboard', [
            'rows' => $data,
            'crumbs' => $crumbs,
            'hiddenSearch' => $hiddenSearch,
            'actives' => $actives,
            'season' => $ss2,
            'msg' => $msg
        ]);
    }
}
