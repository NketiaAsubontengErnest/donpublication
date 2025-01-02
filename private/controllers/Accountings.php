<?php

/**
 * Accountings controller
 */
class Accountings extends Controller
{
    function expendituretype($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }

        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $data = array();
        $expendType = new Expendtype();

        if ($_POST) {
            if (isset($_POST['dels'])) {
                $expendType->delete($_POST['dels']);
            } else {
                if ($expendType->validate($_POST)) {
                    $_POST['expendtype'] = strtoupper($_POST['expendtype']);
                    $expendType->insert($_POST);
                }
            }
            return $this->redirect('accountings/expendituretype');
        }


        $data = $expendType->findAll();


        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'expend';
        $hiddenSearch = "";
        if (Auth::access('g-account')) {
            return $this->view('accountings.expendituretype', [
                'rows' => $data,
                'crumbs' => $crumbs,
                'pager' => $pager,
                'hiddenSearch' => $hiddenSearch,
                'actives' => $actives
            ]);
        } else {
            $crumbs[] = ['Access Denied', ''];
            return $this->view('access-denied', [
                'crumbs' => $crumbs,
                'pager' => $pager,
                'actives' => $actives
            ]);
        }
    }

    function incomes($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }

        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $data = array();
        $dataSum = array();
        $incomes = new Income();

        $season = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        if ($_POST) {
            $_POST['seasonid'] = $season;
            $_POST['userid'] = Auth::getID();
            $incomes->insert($_POST);
            return $this->redirect('accountings/incomes');
        }

        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            $query = "SELECT * FROM `incomes` WHERE `incomeamount` LIKE :search OR `naration` LIKE :search  AND (`seasonid` = $season) LIMIT $limit OFFSET $offset";
            $arr['search'] = $searching;
            $data = $incomes->query($query, $arr);
        } else {
            $data = $incomes->where('seasonid', $season, $limit, $offset);
        }

        $dataSum = $incomes->query("SELECT (SELECT SUM(`expendAmount`) FROM `expenditures` WHERE `seasonid` = $season) AS totalExpendAmount, (SELECT SUM(`incomeamount`) FROM `incomes` WHERE `seasonid` = $season) AS totalIncomeAmount;")[0];


        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'expend';
        $hiddenSearch = "";
        if (Auth::access('g-account')) {
            return $this->view('accountings.incomes', [
                'rows' => $data,
                'dataSum' => $dataSum,
                'crumbs' => $crumbs,
                'pager' => $pager,
                'hiddenSearch' => $hiddenSearch,
                'actives' => $actives
            ]);
        } else {
            $crumbs[] = ['Access Denied', ''];
            return $this->view('access-denied', [
                'crumbs' => $crumbs,
                'pager' => $pager,
                'actives' => $actives
            ]);
        }
    }

    function expenditures($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }

        // Setting pagination
        $limit = 20;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $data = array();
        $expends = new Expenditure();
        $expenType = new Expendtype();

        $season = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        if ($_POST) {
            $_POST['seasonid'] = $season;
            $_POST['userid'] = Auth::getID();
            $expends->insert($_POST);
            return $this->redirect('accountings/expenditures');
        }

        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            $query = "SELECT * FROM `expenditures` WHERE `chequeNo` LIKE :search OR `vocherNo` LIKE :search OR`naration` LIKE  :search ORDER BY id DESC";
            $arr['search'] = $searching;
            $data = $expends->where_query($query, $arr);
        }elseif (isset($_GET['search_expendType'])) {
            $searching =  $_GET['search_expendType'];
            $query = "SELECT * FROM `expenditures` WHERE `expendType` LIKE :search ORDER BY id DESC";
            $arr['search'] = $searching;
            $data = $expends->where_query($query, $arr);
        } else {
            $data = $expends->where('seasonid', $season, $limit, $offset);
        }

        $expens = $expenType->findAll();
        $dataSum = $expends->query("SELECT (SELECT SUM(`expendAmount`) FROM `expenditures` WHERE `seasonid` = $season) AS totalExpendAmount, (SELECT SUM(`incomeamount`) FROM `incomes` WHERE `seasonid` = $season) AS totalIncomeAmount;")[0];

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'expend';
        $hiddenSearch = "";
        if (Auth::access('g-account')) {
            return $this->view('accountings.expenditures', [
                'rows' => $data,
                'expens' => $expens,
                'dataSum' => $dataSum,
                'crumbs' => $crumbs,
                'pager' => $pager,
                'hiddenSearch' => $hiddenSearch,
                'actives' => $actives
            ]);
        } else {
            $crumbs[] = ['Access Denied', ''];
            return $this->view('access-denied', [
                'crumbs' => $crumbs,
                'pager' => $pager,
                'actives' => $actives
            ]);
        }
    }
}
