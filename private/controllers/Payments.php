<?php

/**
 * Subjects controller
 */
class Payments extends Controller
{
    function index($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $data = array();

        $customer = new Order();
        $payment = new Payment();
        $seasons = new Season();
        //get Seasons
        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        //$arr['officer'] = Auth::getId();
        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            // if (Auth::access('account')) {
            //     $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id WHERE customers.`customername` LIKE :search  OR customers.`custphone` LIKE :search";
            // }
            // else{
            $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id WHERE (customers.`customername` LIKE :search  OR customers.`custphone` LIKE :search) AND (users.officer = " . Auth::getId() . " OR users.id = " . Auth::getId() . ") LIMIT $limit OFFSET $offset";
            // }
            if (Auth::access('g-account')) {
                $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id WHERE (customers.`customername` LIKE :search  OR customers.`custphone` LIKE :search) LIMIT $limit OFFSET $offset";
            }
            $arr['search'] = $searching;

            $data = $customer->query($query, $arr);
        } else {
            $query = "SELECT orders.customerid AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM orders LEFT JOIN customers ON orders.customerid = customers.id LEFT JOIN users ON  orders.officerid = users.id WHERE ordertype != '1' AND `quantsupp` != 0 AND users.officer = " . Auth::getId() . " OR users.id = " . Auth::getId() . " GROUP BY orders.customerid LIMIT $limit OFFSET $offset";

            if (Auth::access('g-account')) {
                $query = "SELECT orders.customerid AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM orders LEFT JOIN customers ON orders.customerid = customers.id LEFT JOIN users ON  orders.officerid = users.id WHERE ordertype != '1' AND `quantsupp` != 0 GROUP BY orders.customerid LIMIT $limit OFFSET $offset";
            }
            $data = $customer->query($query);
        }

        $data = $payment->get_Total($data, $seasid);
        $data = $payment->get_TotalDept($data, $seasid);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Payment', ''];
        $actives = 'payments';
        $hiddenSearch = "";

        if (Auth::access('verification') || Auth::access('marketer')) {
            return $this->view('payments', [
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

    function genreports($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $data = array();
        $arrPay = array();
        $arr = array();

        $customer = new Order();
        $payment = new Payment();

        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        //$arr['officer'] = Auth::getId();
        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id WHERE customers.`customername` LIKE :search OR customers.`custphone` LIKE :search OR users.firstname LIKE :search OR users.lastname LIKE :search  LIMIT $limit OFFSET $offset";
            $arr['search'] = $searching;
        } else {
            $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id LIMIT $limit OFFSET $offset";
        }
        $data = $customer->query($query, $arr);

        if (isset($_GET['startDate'])) {
            $arrPay['startDate'] = $_GET['startDate'];
        }
        if (isset($_GET['endDate'])) {
            $arrPay['endDate'] = $_GET['endDate'];
        }

        $data = $payment->get_Total($data, $seasid, $arrPay);
        $data = $payment->get_TotalDept($data, $seasid);

        if (isset($_POST['exportexl'])) {
            if (isset($_GET['search_box'])) {
                $searching = '%' . $_GET['search_box'] . '%';
                $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id WHERE customers.`customername` LIKE :search OR customers.`custphone` LIKE :search OR users.firstname LIKE :search OR users.lastname LIKE :search";
                $arr['search'] = $searching;
            } else {
                $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id";
            }
            $data1 = $customer->query($query, $arr);

            if (isset($_GET['startDate'])) {
                $arrPay['startDate'] = $_GET['startDate'];
            }
            if (isset($_GET['endDate'])) {
                $arrPay['endDate'] = $_GET['endDate'];
            }

            $data1 = $payment->get_Total($data1, $seasid, $arrPay);
            $data1 = $payment->get_TotalDept($data1, $seasid);
            $fields = array('Customer Name', 'Phone', 'Region', 'Customer Type', 'Location', 'Officer', 'Gross Amt', 'Return Amt', 'Return Rate(%)', 'Net Return Amt', 'Discount Amt (GHC)', 'Discount Rate(%)', 'Net Sales (GHC)', 'Total Payments (GHC)', 'Recovery Rate(%)', 'Balance (GHC)', 'Balance Rate (%)');
            $excelData = implode("\t", array_values($fields)) . "\n";
            if ($data1) {
                foreach ($data1 as $row) {
                    $recovery = 0;
                    $returns = 0;
                    $balance = 0;
                    $netamt = 0;
                    $discoutper = 0;

                    try {
                        //code...
                        $netamt = $row->amout_disco->totaldept - ($row->amout_disco->totaldisc);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    try {
                        $recovery = ($row->totalpayment->totalpayed / $netamt) * 100;
                    } catch (\Throwable $th) {
                    }
                    try {
                        $discoutper = (($row->amout_disco->totaldisc) / $row->amout_disco->totaldept) * 100;
                    } catch (\Throwable $th) {
                    }
                    try {
                        $returns = ($row->amout_disco->totalReturns / $netamt) * 100;
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    try {
                        $balance = (($netamt - ($row->totalpayment->totalpayed)) / $netamt) * 100;
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    $lineData = array(esc($row->customername), esc($row->custphone), esc($row->region), esc($row->custtype), esc($row->custlocation), esc($row->firstname) . " " . esc($row->lastname), esc(number_format($row->amout_disco->totaldept, 2)), esc(number_format($row->amout_disco->totalReturns, 2)), esc(number_format($returns)) . '%', esc(number_format($row->amout_disco->total_net_returns, 2)), esc(number_format($row->amout_disco->totaldisc, 2)), esc(number_format($discoutper) . '%'), esc(number_format($netamt, 2)), esc(number_format($row->totalpayment->totalpayed, 2)), esc(number_format($recovery)) . '%', esc(number_format(($netamt - ($row->totalpayment->totalpayed)), 2)), esc(number_format($balance)) . '%');
                    $excelData .= implode("\t", array_values($lineData)) . "\n";
                }
            } else {
                $excelData .= 'No records found...' . "\n";
            }
            export_data_to_excel($fields, $excelData, 'General_Report');
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Payment', ''];
        $actives = 'payments';
        $hiddenSearch = "";
        if (Auth::access('account')) {
            return $this->view('payments.genreports', [
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
    function sumreports($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }

        $payment = new Payment();
        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        if (isset($_GET['startDate'])) {
            $arrPay['startDate'] = $_GET['startDate'];
        }
        if (isset($_GET['endDate'])) {
            $arrPay['endDate'] = $_GET['endDate'];
        }
        //$arr['officer'] = Auth::getId();
        if (isset($_GET['seasonid'])) {
            $seasid = $_GET['seasonid'];
            $data['ttSalses'] = $payment->get_TotalSales($seasid);
            $data['agentTtSalses'] = $payment->get_TotalSalesAgent($seasid);
            $data['garisTtSalses'] = $payment->get_TotalSalesGaris($seasid);
            $data['booksTtSalses'] = $payment->get_TotalSalesBookshop($seasid);
            $data['schoolTtSalses'] = $payment->get_TotalSalesSchool($seasid);
        } else {
            $data['ttSalses'] = $payment->get_TotalSales($seasid);
            $data['agentTtSalses'] = $payment->get_TotalSalesAgent($seasid);
            $data['garisTtSalses'] = $payment->get_TotalSalesGaris($seasid);
            $data['booksTtSalses'] = $payment->get_TotalSalesBookshop($seasid);
            $data['schoolTtSalses'] = $payment->get_TotalSalesSchool($seasid);
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Payment', ''];
        $actives = 'payments';
        $hiddenSearch = "";
        if (Auth::access('account')) {
            return $this->view('payments.sumreports', [
                'rows' => $data,
                'crumbs' => $crumbs,
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

    function customerreport($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 25;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $data = array();

        $customer = new Order();
        $payment = new Payment();

        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        //FOR GARRISONS
        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            if (Auth::access('account')) {
                $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id WHERE (customers.`customername` LIKE :search  OR customers.`custphone` LIKE :search) AND customers.`custtype` = 'garris' LIMIT $limit OFFSET $offset";
            } else {
                $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id WHERE (customers.`customername` LIKE :search  OR customers.`custphone` LIKE :search) AND customers.`custtype` = 'garris' AND (users.officer = " . Auth::getId() . " OR users.id = " . Auth::getId() . ") LIMIT $limit OFFSET $offset";
            }
            $arr['search'] = $searching;

            $data['garris'] = $customer->query($query, $arr);
        } else {
            if (Auth::access('account')) {
                $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id WHERE customers.`custtype` = 'garris' LIMIT $limit OFFSET $offset";
            } else {
                $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id WHERE  customers.`custtype` = 'garris' AND users.officer = " . Auth::getId() . " OR users.id = " . Auth::getId() . " LIMIT $limit OFFSET $offset";
            }
            $data['garris'] = $customer->query($query);
        }

        $data['garris'] = $payment->get_Total($data['garris'], $seasid);
        $data['garris'] = $payment->get_TotalDept($data['garris'], $seasid);

        //FOR BOOKSHOPS    
        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            if (Auth::access('account')) {
                $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id WHERE (customers.`customername` LIKE :search  OR customers.`custphone` LIKE :search) AND customers.`custtype` = 'booksh' LIMIT $limit OFFSET $offset";
            } else {
                $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id WHERE (customers.`customername` LIKE :search  OR customers.`custphone` LIKE :search) AND customers.`custtype` = 'booksh' AND (users.officer = " . Auth::getId() . " OR users.id = " . Auth::getId() . ") LIMIT $limit OFFSET $offset";
            }
            $arr['search'] = $searching;

            $data['booksh'] = $customer->query($query, $arr);
        } else {
            if (Auth::access('account')) {
                $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id WHERE customers.`custtype` = 'booksh' LIMIT $limit OFFSET $offset";
            } else {
                $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id WHERE  customers.`custtype` = 'booksh' AND users.officer = " . Auth::getId() . " OR users.id = " . Auth::getId() . " LIMIT $limit OFFSET $offset";
            }
            $data['booksh'] = $customer->query($query);
        }

        $data['booksh'] = $payment->get_Total($data['booksh'], $seasid);
        $data['booksh'] = $payment->get_TotalDept($data['booksh'], $seasid);

        //FOR AGENTS    
        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            if (Auth::access('account')) {
                $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id WHERE (customers.`customername` LIKE :search  OR customers.`custphone` LIKE :search) AND customers.`custtype` = 'agent' LIMIT $limit OFFSET $offset";
            } else {
                $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id WHERE (customers.`customername` LIKE :search  OR customers.`custphone` LIKE :search) AND customers.`custtype` = 'agent' AND (users.officer = " . Auth::getId() . " OR users.id = " . Auth::getId() . ") LIMIT $limit OFFSET $offset";
            }
            $arr['search'] = $searching;

            $data['agent'] = $customer->query($query, $arr);
        } else {
            if (Auth::access('account')) {
                $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id WHERE customers.`custtype` = 'agent' LIMIT $limit OFFSET $offset";
            } else {
                $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id WHERE  customers.`custtype` = 'agent' AND users.officer = " . Auth::getId() . " OR users.id = " . Auth::getId() . " LIMIT $limit OFFSET $offset";
            }
            $data['agent'] = $customer->query($query);
        }

        $data['agent'] = $payment->get_Total($data['agent'], $seasid);
        $data['agent'] = $payment->get_TotalDept($data['agent'], $seasid);


        if (isset($_POST['exportexl'])) {
            $custtype = $_POST['exportexl'];
            $query = "SELECT customers.`id` AS cid, customers.`officerid`, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM `customers` LEFT JOIN users ON customers.officerid = users.id WHERE  customers.`custtype` = '" . $custtype . "'";
            $data1 = $customer->query($query);
            $data1 = $payment->get_Total($data1, $seasid);
            $data1 = $payment->get_TotalDept($data1, $seasid);

            $fields = array('Customer', 'Location', 'Marketer', 'Gross Sales (GHC)', 'Return Amt (GHC)', 'Return Rate(%)', 'Net Return Amt', 'Discount Amt (GHC)', 'Net Sales (GHC)', 'Total Payments (GHC)', 'Recovery Rate(%)', 'Balance (GHC)', 'Balance Rate (%)');
            $excelData = implode("\t", array_values($fields)) . "\n";
            if ($data1) {
                foreach ($data1 as $row) {
                    $recovery = 0;
                    $returns = 0;
                    $balance = 0;
                    $netsales = 0;
                    try {
                        $netsales = (($row->amout_disco->totaldept - ($row->amout_disco->totaldisc)));
                    } catch (\Throwable $th) {
                        //throw $th;
                    }

                    try {
                        $recovery = ($row->totalpayment->totalpayed / ($netsales)) * 100;
                    } catch (\Throwable $th) {
                    }

                    try {
                        $returns = ($row->amout_disco->totalReturns / ($netsales)) * 100;
                    } catch (\Throwable $th) {
                        //throw $th;
                    }

                    try {
                        $balance = ((($netsales) - ($row->totalpayment->totalpayed)) / ($netsales)) * 100;
                    } catch (\Throwable $th) {
                        //throw $th;
                    }

                    $lineData = array(esc($row->customername), esc($row->custlocation), esc($row->firstname . ' ' . $row->lastname), esc(number_format($row->amout_disco->totaldept)), esc(number_format($row->amout_disco->totalReturns, 2)), esc(number_format($returns, 2) . '%'), esc(number_format($row->amout_disco->total_net_returns, 2)), esc(number_format($row->amout_disco->totaldisc, 2)), esc(number_format($netsales, 2)), esc(number_format($row->totalpayment->totalpayed)), esc(number_format($recovery)) . '%', esc(number_format(($netsales) - ($row->totalpayment->totalpayed), 2)), esc(number_format($balance)) . '%');
                    $excelData .= implode("\t", array_values($lineData)) . "\n";
                }
            } else {
                $excelData .= 'No records found...' . "\n";
            }
            export_data_to_excel($fields, $excelData, $custtype . '_Sales');
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Payment', ''];
        $actives = 'payments';
        $hiddenSearch = "";
        if (Auth::access('account')) {
            return $this->view('payments.customerreport', [
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

    function reginals($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 25;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $data = array();

        $orders = new Order();
        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";


        //FOR GARRISONS
        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            $arr['seasonid'] = $_GET['seasonid'];
            $query = "SELECT 
                        customers.region AS region,
                        COALESCE(SUM(payment_data.total_payed), 0) AS total_payed,
                        COALESCE(SUM(order_data.gross_amount), 0) AS gross_amount,
                        COALESCE(SUM(order_data.total_returns), 0) AS total_returns,
                        COALESCE(SUM(order_data.total_net), 0) AS total_net_returns,
                        COALESCE(SUM(order_data.total_disc), 0) AS total_disc
                    FROM 
                        customers
                    LEFT JOIN (
                        SELECT 
                            orders.customerid,
                            SUM((quantsupp - retverquant) * unitprice) AS gross_amount,
                            SUM(retverquant * unitprice) AS total_returns,
                            SUM((`retverquant` * `unitprice`)* ((100 - `discount`)/100)) AS total_net,
                            SUM(((quantsupp - retverquant) * unitprice) * (discount / 100)) AS total_disc
                        FROM 
                            orders
                        WHERE 
                            orders.seasonid =:seasonid
                            AND ordertype != '1' 
                            AND customers.region LIKE :search
                        GROUP BY 
                            orders.customerid
                    ) AS order_data ON order_data.customerid = customers.id
                    LEFT JOIN (
                        SELECT 
                            payments.customerid,
                            SUM(payments.amount) AS total_payed
                        FROM 
                            payments
                            WHERE
                            payments.seasonid =:seasonid
                        GROUP BY 
                            payments.customerid
                    ) AS payment_data ON payment_data.customerid = customers.id
                    GROUP BY 
                        customers.region 
                    LIMIT $limit OFFSET $offset";

            $arr['search'] = $searching;
        } else {
            $arr['seasonid'] = $seasid;
            $query = "SELECT 
                        customers.region AS region,
                        COALESCE(SUM(payment_data.total_payed), 0) AS total_payed,
                        COALESCE(SUM(order_data.gross_amount), 0) AS gross_amount,
                        COALESCE(SUM(order_data.total_returns), 0) AS total_returns,
                        COALESCE(SUM(order_data.total_net), 0) AS total_net_returns,
                        COALESCE(SUM(order_data.total_disc), 0) AS total_disc
                    FROM 
                        customers
                    LEFT JOIN (
                        SELECT 
                            orders.customerid,
                            SUM((quantsupp - retverquant) * unitprice) AS gross_amount,
                            SUM(retverquant * unitprice) AS total_returns,
                            SUM((`retverquant` * `unitprice`)* ((100 - `discount`)/100)) AS total_net,
                            SUM(((quantsupp - retverquant) * unitprice) * (discount / 100)) AS total_disc
                        FROM 
                            orders
                        WHERE 
                            orders.seasonid =:seasonid
                            AND ordertype != '1'
                        GROUP BY 
                            orders.customerid
                    ) AS order_data ON order_data.customerid = customers.id
                    LEFT JOIN (
                        SELECT 
                            payments.customerid,
                            SUM(payments.amount) AS total_payed
                        FROM 
                            payments
                            WHERE
                            payments.seasonid =:seasonid
                        GROUP BY 
                            payments.customerid
                    ) AS payment_data ON payment_data.customerid = customers.id
                    GROUP BY 
                        customers.region LIMIT $limit OFFSET $offset";
        }

        $data = $orders->query($query, $arr);

        if (isset($_POST['exportexl'])) {
            $data1 = $data;
            $fields = array('Region', 'Gross Sales (GHC)', 'Return Amt (GHC)', 'Return Rate(%)', 'Net Return Amt', 'Discount Amt (GHC)', 'Net Sales (GHC)', 'Total Payments (GHC)', 'Recovery Rate(%)', 'Balance (GHC)', 'Balance Rate (%)');
            $excelData = implode("\t", array_values($fields)) . "\n";
            if ($data1) {
                foreach ($data1 as $row) {
                    try {
                        //code...
                        $netamt = $row->gross_amount - ($row->total_disc);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    $recoveryPers = 0;
                    $returnsPers = 0;
                    $balancePrs = 0;
                    try {
                        $recoveryPers = ($row->total_payed / $netamt) * 100;
                    } catch (\Throwable $th) {
                    }
                    try {
                        $returnsPers = ($row->total_returns / $netamt) * 100;
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    try {
                        $balancePrs = (($netamt - $row->total_payed) / $netamt) * 100;
                    } catch (\Throwable $th) {
                        //throw $th;
                    }

                    $lineData = array(esc($row->region), esc(number_format($row->gross_amount, 2)), esc(number_format($row->total_returns, 2)), esc(number_format($returnsPers)) . '%', esc(number_format($row->total_disc, 2)), esc(number_format($row->total_net_returns, 2)), esc(number_format($netamt, 2)), esc(number_format($row->total_payed, 2)), esc(number_format($recoveryPers)) . '%', esc(number_format(($netamt - ($row->total_payed)), 2)), esc(number_format($balancePrs)) . '%');
                    $excelData .= implode("\t", array_values($lineData)) . "\n";
                }
            } else {
                $excelData .= 'No records found...' . "\n";
            }
            export_data_to_excel($fields, $excelData, 'Reginal_Sales');
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Payment', ''];
        $actives = 'payments';
        $hiddenSearch = "";
        if (Auth::access('account')) {
            return $this->view('payments.reginals', [
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

    function officerssales($id = null)
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

        if (isset($_POST['exportexl'])) {
            $fields = array('Officer Name', 'Gross Sales (GHC)', 'Return Amt (GHC)', 'Return Rate(%)', 'Net Return Amount', 'Discount Amt (GHC)', 'Net Sales (GHC)', 'Total Payments (GHC)', 'Recovery Rate(%)', 'Balance (GHC)', 'Balance Rate (%)');
            $excelData = implode("\t", array_values($fields)) . "\n";
            if ($data) { //total_net_returns
                foreach ($data as $row) {
                    $recovery = 0;
                    $returns = 0;
                    $balance = 0;
                    $netamt = 0;

                    try {
                        $netamt = ($row->OfficTotalDept->totaldept - ($row->OfficTotalDept->totaldisc));
                    } catch (\Throwable $th) {
                    }
                    try {
                        $recovery = ($row->OfficTotal->totalpayed / $netamt) * 100;
                    } catch (\Throwable $th) {
                    }
                    try {
                        $returns = ($row->OfficTotalDept->total_net_returns / ($row->OfficTotalDept->total_net_returns + $netamt)) * 100;
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    try {
                        $balance = ((($netamt - $row->OfficTotal->totalpayed)) / $netamt) * 100;
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    $lineData = array(esc($row->firstname) . " " . esc($row->lastname), esc(number_format($row->OfficTotalDept->totaldept, 2)), esc(number_format($row->OfficTotalDept->totalReturns, 2)), esc(number_format($returns)) . '%', esc(number_format($row->OfficTotalDept->total_net_returns, 2)), esc(number_format($row->OfficTotalDept->totaldisc, 2)), esc(number_format($netamt, 2)), esc(number_format($row->OfficTotal->totalpayed, 2)), esc(number_format($recovery)) . '%', esc(number_format(($netamt - ($row->OfficTotal->totalpayed)), 2)), esc(number_format($balance)) . '%');
                    $excelData .= implode("\t", array_values($lineData)) . "\n";
                }
            } else {
                $excelData .= 'No records found...' . "\n";
            }
            export_data_to_excel($fields, $excelData, 'Officers_Sales');
        }


        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Payment', ''];
        $actives = 'payments';
        $hiddenSearch = "";
        if (Auth::access('verification') || Auth::access('marketer')) {
            return $this->view('payments.officerssales', [
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

    public function edit($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }

        $errors = array();
        $payments = new Payment();
        $acti = new Activitylog();
        $banks = new Bank();
        $acs = [];

        $data = $payments->where('id', $id)[0];

        if (count($_POST) > 0 && Auth::access('verification')) {

            $links = "payments/viewpayments/" . $data->customers->id;

            if ($payments->validate($_POST)) {
                $oldmon = $data->amount;
                $newmon = $_POST['updateamount'];

                if ($oldmon != $newmon) {
                    $acs['userid'] = Auth::getUsername();
                    $mess = $data->customers->customername;
                    $cusid = $data->customers->id;

                    $acs['activity'] = "Payment Changed From $oldmon to $newmon for $mess";
                    $acs['loclink'] = "payments/viewpayments/$cusid";
                    //viewpayments

                    $acti->insert($acs);
                    $_SESSION['messsage'] = "Payment Edited Successfully for Approval";
                    $_SESSION['status_code'] = "success";
                    $_SESSION['status_headen'] = "Good job!";
                } else {
                    unset($_POST['updateamount']);
                }
                $payments->update($id, $_POST);

                return $this->redirect($links);
            } else {
                $errors = $payments->errors;
            }
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Payment', 'payments'];
        $crumbs[] = ['Edit', ''];

        $actives = 'payments';

        $hiddenSearch = "";
        if (Auth::access('verification')) {
            return $this->view('payments.edit', [
                'errors' => $errors,
                'rows' => $data,
                'hiddenSearch' => $hiddenSearch,
                'banks' => $banks->where('status', 1),
                'crumbs' => $crumbs,
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

    public function booksales($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 20;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $rows = array();
        $errors = array();

        $book = new Book();
        $payment = new Payment();

        if (isset($_GET['search_box'])) {
            $arr['searchuse'] = '%' . $_GET['search_box'] . '%';
            $query = "SELECT books.*, subjects.subject, levels.class, types.booktype FROM `books` LEFT JOIN subjects ON books.subjectid = subjects.id LEFT JOIN levels ON books.classid = levels.id LEFT JOIN types ON books.typeid = types.id WHERE subjects.subject LIKE :searchuse OR levels.class LIKE :searchuse OR types.booktype LIKE :searchuse LIMIT $limit OFFSET $offset";

            $rows = $book->findSearch($query, $arr);
        } else {
            $rows = $book->findAll($limit, $offset);
        }

        $seasid = $_SESSION['seasondata']->id;

        $rows = $payment->get_Book_Sales($rows, $seasid);

        if (isset($_POST['exportexl'])) {
            $fields = array('Book', 'Total Quantity Supply', 'Gross Sales', 'Return Amount', 'Net Return Amount', 'Discount Amount', 'Net Sales');
            $excelData = implode("\t", array_values($fields)) . "\n";
            $rowsex = $book->findAll();
            $rows = $payment->get_Book_Sales($rowsex, $seasid);
            if ($rowsex) {
                foreach ($rowsex as $row) {
                    $lineData = array(esc($row->level->class . ' ' . $row->subject->subject . ' ' . $row->booktype->booktype), esc(number_format($row->bookSales->totalQuantSuppAccountOfficerNotEmpty)), esc(number_format($row->bookSales->totalBookGross, 2)), esc(number_format($row->bookSales->totalBookReturns, 2)), esc(number_format($row->bookSales->total_net_returns, 2)), esc(number_format($row->bookSales->totalBookNet, 2)), esc(number_format($row->bookSales->totalBookGross - ($row->bookSales->totalBookNet), 2)));
                    $excelData .= implode("\t", array_values($lineData)) . "\n";
                }
                export_data_to_excel($fields, $excelData, 'Books_Sales_Report');
            } else {
                $excelData .= 'No records found...' . "\n";
            }
        }

        if (Auth::access('stores')) {
            //this are for breadcrumb
            $crumbs[] = ['Dashboard', 'dashboard'];
            $crumbs[] = ['Book Type', 'subjests'];
            $crumbs[] = ['Edit Book Type', ''];

            $actives = 'Books';
            $hiddenSearch = "";
            return $this->view('payments.booksales', [
                'errors' => $errors,
                'crumbs' => $crumbs,
                'hiddenSearch' => $hiddenSearch,
                'pager' => $pager,
                'rows' => $rows,
                'actives' => $actives
            ]);
        } else {
            $crumbs[] = ['Access Denied', ''];
            return $this->view('access-denied', ['crumbs' => $crumbs,]);
        }
    }

    public function approveedit($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }

        $errors = array();
        $payments = new Payment();
        $acti = new Activitylog();

        $data = $payments->where('id', $id)[0];

        if (count($_POST) > 0 && Auth::access('g-account')) {
            if ($payments->validate($_POST)) {

                $oldmon = $data->amount;
                $newmon = $_POST['updateamount'];

                if ($oldmon != $newmon) {
                    $acs['userid'] = Auth::getUsername();
                    $mess = $data->customers->customername;

                    $_POST['updateamount'] = '0';
                    $_POST['amount'] = $newmon;

                    $acs['activity'] = "Payment Changed Confirmed From $oldmon to $newmon for $mess";

                    $acti->insert($acs);
                    $_SESSION['messsage'] = "Payment Update Successfully Approved";
                    $_SESSION['status_code'] = "success";
                    $_SESSION['status_headen'] = "Good job!";
                } else {
                    unset($_POST['updateamount']);
                }
                $payments->update($id, $_POST);

                return $this->redirect('setups/activitie');
            } else {
                $errors = $payments->errors;
            }
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Payment', 'payments'];
        $crumbs[] = ['Edit', ''];

        $actives = 'payments';

        $hiddenSearch = "";
        if (Auth::access('g-account')) {
            return $this->view('payments.approveedit', [
                'errors' => $errors,
                'rows' => $data,
                'hiddenSearch' => $hiddenSearch,
                'crumbs' => $crumbs,
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

    public function viewpayments($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $errors = array();
        $payments = new Payment();
        $coust = new Customer();
        $tithe = new Tithe();
        $banks = new Bank();

        $rows = array();

        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        $ttSales = $payments->get_TotalCustomer($id, $seasid);
        $ttPayment = $payments->get_TotalPay($id);

        if (count($_POST) > 0 && Auth::access('verification')) {
            $_POST['amount'] = str_replace(",", "", $_POST['amount']);
            $progitData = $tithe->getTithe($ttSales, $ttPayment, $_POST['amount'], $id);

            $_POST['customerid'] = $id;

            $_POST['seasonid'] = $_SESSION['seasondata']->id;

            if ($payments->validate($_POST)) {
                $progitData['seasonid'] = $_SESSION['seasondata']->id;


                if ($progitData['tithe'] > 0) {
                    $tithe->insert($progitData);
                    $_POST['titheid'] = $tithe->selctingId()[0]->id;
                }

                $payments->insert($_POST);
                return $this->redirect('payments/viewpayments/' . $id);
            } else {
                $errors = $payments->errors;
            }
        }

        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            $query = "SELECT * FROM `payments` WHERE `customerid` =:customerid AND payments.`seasonid` ={$seasid} AND (`transid` LIKE :searchs OR `reciept` LIKE :searchs) ORDER BY id DESC LIMIT $limit OFFSET $offset";
            $arr['searchs'] = $searching;
            $arr['customerid'] = $id;

            $row = $payments->query($query, $arr);
        } else {
            $query = "SELECT * FROM `payments` WHERE `customerid` =:customerid AND payments.`seasonid` ={$seasid} ORDER BY id DESC LIMIT $limit OFFSET $offset";
            $arr['customerid'] = $id;
            $row = $payments->query($query, $arr);
        }

        $rows = $coust->where('id', $id)[0];

        $totals['ttSales'] = $ttSales;
        $totals['ttPayment'] = $ttPayment;

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Agents', 'agents'];
        $crumbs[] = ['Edit Agents', ''];

        $actives = 'payments';
        $hiddenSearch = "";

        if (Auth::access('verification') || Auth::access('marketer')) {
            return $this->view('payments.viewpayments', [
                'errors' => $errors,
                'crumbs' => $crumbs,
                'rows' => $row,
                'banks' => $banks->where('status', 1),
                'cust' => $rows,
                'totals' => $totals,
                'hiddenSearch' => $hiddenSearch,
                'pager' => $pager,
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

    public function officerpayments($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $errors = array();
        $payments = new Payment();
        $tithes = new Tithe();
        $users = new User();

        $acti = new Activitylog();
        $acs = [];

        $rows = array();

        $arr['officerid'] = $id;
        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        if (count($_POST) > 0) {
            $payments->query("DELETE FROM `payments` WHERE `id` =:id", ['id' => $_POST['payid']]);

            if ($_POST['titheid'] !== null) {
                $tithes->query("DELETE FROM `tithes` WHERE `id` =:id", ['id' => $_POST['titheid']]);
            }

            $acs['activity'] = "Payment of " . $_POST['amount'] . " is Deleted From " . $_POST['customer'] . " by " . Auth::getFirstname() . ' ' . Auth::getLastname();
            $acs['loclink'] = "";
            $acs['userid'] = Auth::getUsername();

            //viewpayments
            $acti->insert($acs);

            $_SESSION['messsage'] = "Payment Deleted Successfully Approved";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
            return $this->redirect('payments/officerpayments/' . $id);
        }

        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            $query = "SELECT payments.*, payments.id as pid, customers.* FROM `payments` JOIN customers  ON payments.customerid = customers.id WHERE customers.custtype = 'school' AND payments.`officerid` =:officerid AND payments.`seasonid` ={$seasid} AND (`transid` LIKE :searchs OR customers.`customername` LIKE :searchs OR `reciept` LIKE :searchs) ORDER BY payments.id DESC LIMIT $limit OFFSET $offset";
            $arr['searchs'] = $searching;
            $row = $payments->query($query, $arr);
        } else {
            $query = "SELECT payments.*, payments.id as pid, customers.* FROM `payments` JOIN customers  ON payments.customerid = customers.id WHERE customers.custtype = 'school' AND payments.`officerid` =:officerid AND payments.`seasonid` ={$seasid} ORDER BY payments.id DESC LIMIT $limit OFFSET $offset";
            $row = $payments->query($query, $arr);
        }
        $row = $payments->get_Customer($row);

        $rows = $users->where('id', $id)[0];

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Agents', 'agents'];
        $crumbs[] = ['Edit Agents', ''];

        $actives = 'payments';
        $hiddenSearch = "";

        if (Auth::access('verification') || Auth::access('marketer')) {
            return $this->view('payments.officerpayments', [
                'errors' => $errors,
                'crumbs' => $crumbs,
                'rows' => $row,
                'user' => $rows,
                'hiddenSearch' => $hiddenSearch,
                'pager' => $pager,
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

    public function allpayments($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $errors = array();
        $payments = new Payment();

        $rows = array();

        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            $query = "SELECT * FROM `payments` JOIN customers  ON payments.customerid = customers.id WHERE customers.custtype = 'school' AND payments.`seasonid` ={$seasid} AND (`transid` LIKE :searchs OR customers.`customername` LIKE :searchs OR `reciept` LIKE :searchs OR `amount` LIKE :searchs) ORDER BY payments.id DESC LIMIT $limit OFFSET $offset";
            $arr['searchs'] = $searching;
            $row = $payments->query($query, $arr);
        } else {
            $query = "SELECT * FROM `payments` JOIN customers  ON payments.customerid = customers.id WHERE customers.custtype = 'school' AND payments.`seasonid` ={$seasid} ORDER BY payments.id DESC LIMIT $limit OFFSET $offset";
            $row = $payments->query($query);
        }

        $row = $payments->get_Customer($row);
        $row = $payments->get_Marketer($row);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Payments', 'payments'];
        $crumbs[] = ['All Payments', ''];

        $actives = 'payments';
        $hiddenSearch = "";

        if (Auth::access('verification') || Auth::access('marketer')) {
            return $this->view('payments.allpayments', [
                'errors' => $errors,
                'crumbs' => $crumbs,
                'rows' => $row,
                'user' => $rows,
                'hiddenSearch' => $hiddenSearch,
                'pager' => $pager,
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

    public function viewpaymentss($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $errors = array();
        $payments = new Payment();
        $coust = new Customer();

        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            $query = "SELECT * FROM `payments` WHERE `customerid` =:customerid AND (`transid` LIKE :searchs OR `reciept` LIKE :searchs) AND payments.`seasonid` ={$seasid}  ORDER BY id DESC";
            $arr['searchs'] = $searching;
            $arr['customerid'] = $id;

            $row = $payments->query($query, $arr);
        } else {
            $query = "SELECT * FROM `payments` WHERE `customerid` =:customerid AND payments.`seasonid` ={$seasid}  ORDER BY id DESC";
            $arr['customerid'] = $id;

            $row = $payments->query($query, $arr);
        }


        $rows = $coust->where('id', $id)[0];

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Agents', 'agents'];
        $crumbs[] = ['Edit Agents', ''];

        $actives = 'payments';
        $hiddenSearch = "";
        if (Auth::access('verification') || Auth::access('marketer')) {
            return $this->view('payments.viewpaymentss', [
                'errors' => $errors,
                'crumbs' => $crumbs,
                'rows' => $row,
                'cust' => $rows,
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

    public function tithe($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 20;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $tithe = new Tithe();
        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        if (isset($_POST['payid']) && Auth::access('account')) {
            $query = "UPDATE `tithes` SET `tithePayed`='1' WHERE `id` =:payid";
            $tithe->query($query, $_POST);
            $_SESSION['messsage'] = "Tithe Successfully Payed";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
        }

        if (isset($_POST['payall']) && Auth::access('account')) {
            $query = "UPDATE `tithes` SET `tithePayed`='1' WHERE `tithePayed` = '0'";
            $tithe->query($query);
            $_SESSION['messsage'] = "All Tithe Payed Successfully";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
        }

        $querytithe = "SELECT * FROM `tithes` WHERE tithes.`tithePayed` = 0 AND tithes.`seasonid` ={$seasid} LIMIT $limit OFFSET $offset";
        $row = $tithe->query($querytithe);

        $query = "SELECT SUM(IF(`tithePayed` = 1, tithe, 0)) AS total_paid_tithe, SUM(IF(`tithePayed` = 0, tithe, 0)) AS total_unpaid_tithe, SUM(IF(`tithePayed` = 1, amountPayed, 0)) AS total_unpaid_amountPayed, SUM(IF(`tithePayed` = 0, amountPayed, 0)) AS total_unpaid_amountPayed, SUM(`tithe`) AS totalTithes FROM tithes WHERE tithes.`seasonid` ={$seasid}; ";
        $totalTithes = $tithe->query($query)[0];

        if (isset($_POST['exportexl'])) {

            $query = "SELECT * FROM `tithes` WHERE `tithePayed` = 0 AND `seasonid` = :season";
            $arr['season'] = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

            $data1 = $tithe->query($query, $arr);


            $fields = array('Date Payed', 'Amount Payed', 'Profit', 'Tithe');
            $excelData = implode("\t", array_values($fields)) . "\n";
            if ($data1) {
                foreach ($data1 as $row) {
                    $lineData = array(esc(get_date($row->datepaid)), esc($row->amountPayed), esc($row->profit), esc($row->tithe));
                    $excelData .= implode("\t", array_values($lineData)) . "\n";
                }
            } else {
                $excelData .= 'No records found...' . "\n";
            }
            export_data_to_excel($fields, $excelData, 'Tithe_Report');
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Agents', 'agents'];
        $crumbs[] = ['Delete Agents', ''];

        $hiddenSearch = "";
        $actives = 'payments';
        if (Auth::access('account')) {
            return $this->view('payments.tithe', [
                'rows' => $row,
                'hiddenSearch' => $hiddenSearch,
                'crumbs' => $crumbs,
                'totalTithes' => $totalTithes,
                'pager' => $pager,
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
