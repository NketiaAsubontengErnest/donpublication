<?php

/**
 * Subjects controller
 */
class Audits extends Controller
{   

    function auditlist($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $data = array();
        $orders = new Order();
        $acti = new Activitylog();

        if (count($_POST) > 0 && isset($_POST['auditdone']) && Auth::access('g-account')) {
            $_POST['ordernum'] = $id;
            $_POST['AuditorId'] = Auth::getUsername();
            $query = "UPDATE `orders` SET `audit`= :auditdone, `AuditorId` = :AuditorId WHERE `ordernumber` = :ordernum";           

            $orders->query($query, $_POST);
        }

        if (count($_POST) > 0 && isset($_POST['findings']) && Auth::access('g-account')) {
            $_POST['ordernum'] = $id;
            $_POST['AuditorId'] = Auth::getUsername();
            $query = "UPDATE `orders` SET `audit`= :findings, `AuditorId` = :AuditorId WHERE `ordernumber` = :ordernum";           

            $orders->query($query, $_POST);
        }

        if (isset($_GET['search'])) {
            $searching = '%' . $_GET['search'] . '%';
            $query = "SELECT * FROM `users` WHERE (`rank` =! 'agent') && (`firstname` LIKE :search || `lastname` LIKE :search || `othername` LIKE :search ||`user_id` LIKE :search ||`email` LIKE :search)  ORDER BY id DESC";
            $arr['search'] = $searching;
            $data = $orders->query($query, $arr);
        } else {
            $data = $orders->where('ordernumber', $id);
        }

        $disc = $id;

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'audits';
        $hiddenSearch = "";
        if (Auth::access('g-account')) {
            return $this->view('audits.auditlist', [
                'rows' => $data,
                'disc' => $disc,
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

    function officersalelist_unaudit($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $data = array();
        $offci = array();
        $orders = new Order();
        $officer = new User();

        $arr['officerid'] = $id;

        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            $query = "SELECT orders.ordernumber, orders.invoiceno, orders.discount, orders.ordertype, orders.officerid, orders.verificid, orders.customerid, orders.issureid, orders.orderdate,  customers.customername FROM orders  JOIN customers  ON orders.customerid = customers.id WHERE orders.`audit` = '0' AND orders.`seasonid` ={$seasid} AND (orders.ordertype != '1' AND orders.officerid = :officerid) AND (orders.ordernumber LIKE :search OR customers.customername LIKE :search OR orders.invoiceno LIKE :search) GROUP BY orders.ordernumber LIMIT $limit OFFSET $offset";
            $arr['search'] = $searching;
        } else {
            $query = "SELECT ordernumber, `invoiceno`, `discount`, `ordertype`, orders.`officerid`, `verificid`, `customerid`, `issureid`, `orderdate` FROM `orders` JOIN customers  ON orders.customerid = customers.id WHERE orders.`audit` = 0 AND orders.`seasonid` ={$seasid} AND (orders.`ordertype` != '1' AND orders.`officerid` = :officerid)  GROUP BY orders.ordernumber  LIMIT $limit OFFSET $offset";
        }

        $data = $orders->findAllDistinct($query, $arr);
        $data = $orders->get_TotalSalesOrder($data);

        if (isset($_POST['exportexl'])) {
            if (isset($_GET['search_box'])) {
                $searching = '%' . $_GET['search_box'] . '%';
                $query = "SELECT orders.ordernumber, orders.invoiceno, orders.discount, orders.ordertype, orders.officerid, orders.verificid, orders.customerid, orders.issureid, orders.orderdate, customers.customername FROM orders  JOIN customers  ON orders.customerid = customers.id WHERE orders.`audit` = 0 AND orders.`seasonid` ={$seasid} AND (orders.ordertype != '1' AND orders.officerid = :officerid) AND (orders.ordernumber LIKE :search OR customers.customername LIKE :search OR orders.invoiceno LIKE :search) GROUP BY orders.ordernumber";
                $arr['search'] = $searching;
            } else {
                $query = "SELECT ordernumber, `invoiceno`, `discount`, `ordertype`, orders.`officerid`, `verificid`, `customerid`, `issureid`, `orderdate` FROM `orders` JOIN customers  ON orders.customerid = customers.id WHERE orders.`audit` = 0 AND orders.`seasonid` ={$seasid} AND (orders.`ordertype` != '1' AND orders.`officerid` = :officerid)  GROUP BY orders.ordernumber ";
            }

            $data1 = $orders->findAllDistinct($query, $arr);
            $data1 = $orders->get_TotalSalesOrder($data1);
            $fields = array('Order Number', 'Invoice', 'Customer', 'Location', 'Phone', 'Region', 'Gross', 'Discount', 'Discount Amount', 'Net Amount');
            $excelData = implode("\t", array_values($fields)) . "\n";
            if ($data1) {
                foreach ($data1 as $row) {
                    $lineData = array(esc($row->ordernumber), esc($row->invoiceno), esc($row->customers->customername), esc($row->customers->custlocation), esc($row->customers->custphone), esc($row->customers->region), esc(number_format($row->totalOrderSale->totalGrossSales, 2)), esc(number_format($row->discount) . '%'), esc(number_format($row->totalOrderSale->totalDiscount, 2)), esc(number_format($row->totalOrderSale->totalNetSales, 2)));
                    $excelData .= implode("\t", array_values($lineData)) . "\n";
                }
                export_data_to_excel($fields, $excelData, $data1[0]->makerter->firstname . 's_Supply_' . $id);
            } else {
                $excelData .= 'No records found...' . "\n";
            }
        }

        $offci = $officer->where('id', $id)[0];

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'audits';
        $hiddenSearch = "";
        if (Auth::access('g-account')) {
            return $this->view('audits.officersalelist_unaudit', [
                'rows' => $data,
                'crumbs' => $crumbs,
                'pager' => $pager,
                'offci' => $offci,
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

    function officersalelist_findings($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $data = array();
        $offci = array();
        $orders = new Order();
        $officer = new User();

        $arr['officerid'] = $id;
        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";
        
        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            $query = "SELECT orders.ordernumber, orders.invoiceno, orders.discount, orders.ordertype, orders.officerid, orders.verificid, orders.customerid, orders.issureid, orders.orderdate,  customers.customername FROM orders  JOIN customers  ON orders.customerid = customers.id WHERE orders.`audit` = 2 AND orders.`seasonid` ={$seasid} AND (orders.ordertype != '1' AND orders.officerid = :officerid) AND (orders.ordernumber LIKE :search OR customers.customername LIKE :search OR orders.invoiceno LIKE :search) GROUP BY orders.ordernumber LIMIT $limit OFFSET $offset";
            $arr['search'] = $searching;
        } else {
            $query = "SELECT ordernumber, `invoiceno`, `discount`, `ordertype`, orders.`officerid`, `verificid`, `customerid`, `issureid`, `orderdate` FROM `orders` JOIN customers  ON orders.customerid = customers.id WHERE orders.`audit` = 2 AND orders.`seasonid` ={$seasid} AND (orders.`ordertype` != '1' AND orders.`officerid` = :officerid)  GROUP BY orders.ordernumber  LIMIT $limit OFFSET $offset";
        }

        $data = $orders->findAllDistinct($query, $arr);
        $data = $orders->get_TotalSalesOrder($data);

        $offci = $officer->where('id', $id)[0];

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'audits';
        $hiddenSearch = "";
        if (Auth::access('g-account')) {
            return $this->view('audits.officersalelist_findings', [
                'rows' => $data,
                'crumbs' => $crumbs,
                'pager' => $pager,
                'offci' => $offci,
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

    function officersalelist_audited($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $data = array();
        $offci = array();
        $orders = new Order();
        $officer = new User();

        $arr['officerid'] = $id;

        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            $query = "SELECT orders.ordernumber, orders.invoiceno, orders.discount, orders.ordertype, orders.officerid, orders.verificid, orders.customerid, orders.issureid, orders.orderdate,  customers.customername FROM orders  JOIN customers  ON orders.customerid = customers.id WHERE orders.`audit` = 1 AND orders.`seasonid` ={$seasid} AND (orders.ordertype != '1' AND orders.officerid = :officerid) AND (orders.ordernumber LIKE :search OR customers.customername LIKE :search OR orders.invoiceno LIKE :search) GROUP BY orders.ordernumber LIMIT $limit OFFSET $offset";
            $arr['search'] = $searching;
        } else {
            $query = "SELECT ordernumber, `invoiceno`, `discount`, `ordertype`, orders.`officerid`, `verificid`, `customerid`, `issureid`, `orderdate` FROM `orders` JOIN customers  ON orders.customerid = customers.id WHERE orders.`audit` = 1 AND orders.`seasonid` ={$seasid} AND (orders.`ordertype` != '1' AND orders.`officerid` = :officerid) GROUP BY orders.ordernumber  LIMIT $limit OFFSET $offset";
        }

        $data = $orders->findAllDistinct($query, $arr);
        $data = $orders->get_TotalSalesOrder($data);

        $offci = $officer->where('id', $id)[0];

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'audits';
        $hiddenSearch = "";
        if (Auth::access('g-account')) {
            return $this->view('audits.officersalelist_audited', [
                'rows' => $data,
                'crumbs' => $crumbs,
                'pager' => $pager,
                'offci' => $offci,
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

    function unaudited($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 20;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $data = array();
        $arr = array();
        $arrPay = array();

        $user = new User();
        $payment = new Payment();

        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        if (isset($_GET['search_box']) && $_GET['search_box'] != '') {
            $searching = '%' . $_GET['search_box'] . '%';
            if (Auth::access('account')) {
                $query = "SELECT * FROM `users` WHERE `rank` ='marketer' AND (`phone` LIKE :search OR `firstname` LIKE :search OR `lastname` LIKE :search OR `username` =:search) LIMIT $limit OFFSET $offset";
            } elseif (Auth::access('verification')) {
                $query = "SELECT * FROM `users` WHERE `rank` ='marketer' AND (`phone` LIKE :search OR `firstname` LIKE :search OR `lastname` LIKE :search OR `username` =:search AND  `officer` = " . Auth::getId() . ") LIMIT $limit OFFSET $offset";
            } else {
                $query = "SELECT * FROM `users` WHERE `rank` ='marketer' AND (`phone` LIKE :search OR `firstname` LIKE :search OR `lastname` LIKE :search OR `username` =:search AND `id` = " . Auth::getId() . ") LIMIT $limit OFFSET $offset";
            }
            $arr['search'] = $searching;
        } else {
            if (Auth::access('account')) {
                $query = "SELECT * FROM `users` WHERE `rank` ='marketer' LIMIT $limit OFFSET $offset";
            } elseif (Auth::access('verification')) {
                $query = "SELECT * FROM `users` WHERE `rank` ='marketer' AND `officer` = " . Auth::getId() . " LIMIT $limit OFFSET $offset";
            } else {
                $query = "SELECT * FROM `users` WHERE `rank` ='marketer' AND `id` = " . Auth::getId() . " LIMIT $limit OFFSET $offset";
            }
        }
        $data = $user->query($query, $arr);
        if (isset($_GET['startDate'])) {
            $arrPay['startDate'] = $_GET['startDate'];
        }
        if (isset($_GET['endDate'])) {
            $arrPay['endDate'] = $_GET['endDate'];
        }
        $data = $payment->get_OfficTotalD($data,$seasid, $arrPay);
        $data = $payment->get_OfficTotalDeptD($data, $seasid);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Payment', ''];
        $actives = 'audits';
        $hiddenSearch = "";
        if (Auth::access('g-account')) {
            return $this->view('audits.unaudited', [
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
                'actives' => $actives
            ]);
        }
    }

    function findings($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 20;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $data = array();
        $arr = array();
        $arrPay = array();

        $user = new User();
        $payment = new Payment();

        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        if (isset($_GET['search_box']) && $_GET['search_box'] != '') {
            $searching = '%' . $_GET['search_box'] . '%';
            if (Auth::access('account')) {
                $query = "SELECT * FROM `users` WHERE `rank` ='marketer' AND (`phone` LIKE :search OR `firstname` LIKE :search OR `lastname` LIKE :search OR `username` =:search) LIMIT $limit OFFSET $offset";
            } elseif (Auth::access('verification')) {
                $query = "SELECT * FROM `users` WHERE `rank` ='marketer' AND (`phone` LIKE :search OR `firstname` LIKE :search OR `lastname` LIKE :search OR `username` =:search AND  `officer` = " . Auth::getId() . ") LIMIT $limit OFFSET $offset";
            } else {
                $query = "SELECT * FROM `users` WHERE `rank` ='marketer' AND (`phone` LIKE :search OR `firstname` LIKE :search OR `lastname` LIKE :search OR `username` =:search AND `id` = " . Auth::getId() . ") LIMIT $limit OFFSET $offset";
            }
            $arr['search'] = $searching;
        } else {
            if (Auth::access('account')) {
                $query = "SELECT * FROM `users` WHERE `rank` ='marketer' LIMIT $limit OFFSET $offset";
            } elseif (Auth::access('verification')) {
                $query = "SELECT * FROM `users` WHERE `rank` ='marketer' AND `officer` = " . Auth::getId() . " LIMIT $limit OFFSET $offset";
            } else {
                $query = "SELECT * FROM `users` WHERE `rank` ='marketer' AND `id` = " . Auth::getId() . " LIMIT $limit OFFSET $offset";
            }
        }
        $data = $user->query($query, $arr);
        if (isset($_GET['startDate'])) {
            $arrPay['startDate'] = $_GET['startDate'];
        }
        if (isset($_GET['endDate'])) {
            $arrPay['endDate'] = $_GET['endDate'];
        }
        $data = $payment->get_OfficTotalD($data,$seasid, $arrPay);
        $data = $payment->get_OfficTotalDeptD($data, $seasid);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Payment', ''];
        $actives = 'audits';
        $hiddenSearch = "";
        if (Auth::access('g-account')) {
            return $this->view('audits.findings', [
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
                'actives' => $actives
            ]);
        }
    }

    function audited($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 20;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $data = array();
        $arr = array();
        $arrPay = array();

        $user = new User();
        $payment = new Payment();

        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        if (isset($_GET['search_box']) && $_GET['search_box'] != '') {
            $searching = '%' . $_GET['search_box'] . '%';
            if (Auth::access('account')) {
                $query = "SELECT * FROM `users` WHERE `rank` ='marketer' AND (`phone` LIKE :search OR `firstname` LIKE :search OR `lastname` LIKE :search OR `username` =:search) LIMIT $limit OFFSET $offset";
            } elseif (Auth::access('verification')) {
                $query = "SELECT * FROM `users` WHERE `rank` ='marketer' AND (`phone` LIKE :search OR `firstname` LIKE :search OR `lastname` LIKE :search OR `username` =:search AND  `officer` = " . Auth::getId() . ") LIMIT $limit OFFSET $offset";
            } else {
                $query = "SELECT * FROM `users` WHERE `rank` ='marketer' AND (`phone` LIKE :search OR `firstname` LIKE :search OR `lastname` LIKE :search OR `username` =:search AND `id` = " . Auth::getId() . ") LIMIT $limit OFFSET $offset";
            }
            $arr['search'] = $searching;
        } else {
            if (Auth::access('account')) {
                $query = "SELECT * FROM `users` WHERE `rank` ='marketer' LIMIT $limit OFFSET $offset";
            } elseif (Auth::access('verification')) {
                $query = "SELECT * FROM `users` WHERE `rank` ='marketer' AND `officer` = " . Auth::getId() . " LIMIT $limit OFFSET $offset";
            } else {
                $query = "SELECT * FROM `users` WHERE `rank` ='marketer' AND `id` = " . Auth::getId() . " LIMIT $limit OFFSET $offset";
            }
        }
        $data = $user->query($query, $arr);
        if (isset($_GET['startDate'])) {
            $arrPay['startDate'] = $_GET['startDate'];
        }
        if (isset($_GET['endDate'])) {
            $arrPay['endDate'] = $_GET['endDate'];
        }
        $data = $payment->get_OfficTotalD($data, $seasid, $arrPay);
        $data = $payment->get_OfficTotalDeptD($data, $seasid);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Payment', ''];
        $actives = 'audits';
        $hiddenSearch = "";
        if (Auth::access('g-account')) {
            return $this->view('audits.audited', [
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
                'actives' => $actives
            ]);
        }
    }
}
