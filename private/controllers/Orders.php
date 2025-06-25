<?php

/**
 * Orders controller
 */
class Orders extends Controller
{
    function index($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 20;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $data = array();
        $orders = new Order();

        $season = isset($_SESSION['seasondata']) ? $_SESSION['seasondata']->id : '';

        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            if (Auth::access('verification') || Auth::access('stores')) {
                $query = "SELECT orders.*, customers.customername FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id  WHERE orders.`verificid` = '' AND orders.`seasonid` =:season AND (customers.customername LIKE :search OR orders.ordernumber LIKE :search) GROUP BY orders.`ordernumber` ORDER BY id ASC LIMIT $limit OFFSET $offset";
            } else {
                $query = "SELECT orders.*, customers.customername FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id  WHERE orders.`officerid` = :officerid AND orders.`seasonid` =:season AND (customers.customername LIKE :search OR orders.ordernumber LIKE :search) GROUP BY orders.`ordernumber` ORDER BY id DESC LIMIT $limit OFFSET $offset";
            }
            $arr['search'] = $searching;
            $arr['officerid'] = Auth::getId();
            $arr['season'] = $season;

            $data = $orders->findAllDistinct($query, $arr);
        } else {
            if (Auth::access('verification')) {
                $query = "SELECT * FROM `orders` WHERE `verificid` = '' AND orders.`seasonid` ={$season}  GROUP BY `ordernumber` ORDER BY id ASC LIMIT $limit OFFSET $offset";
            } elseif (Auth::access('stores')) {
                $query = "SELECT * FROM `orders` WHERE `issureid` = '' AND orders.`seasonid` ={$season} GROUP BY `ordernumber` ORDER BY id ASC LIMIT $limit OFFSET $offset";
            } else {
                $query = "SELECT * FROM `orders` WHERE `officerid` = " . Auth::getId() . " AND orders.`seasonid` ={$season}  GROUP BY `ordernumber` ORDER BY id DESC LIMIT $limit OFFSET $offset";
            }
            $data = $orders->findAllDistinct($query);
        }


        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'order';
        $hiddenSearch = "";
        return $this->view('orders', [
            'rows' => $data,
            'pager' => $pager,
            'crumbs' => $crumbs,
            'pager' => $pager,
            'hiddenSearch' => $hiddenSearch,
            'actives' => $actives
        ]);
    }
    function issued($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $data = array();
        $orders = new Order();
        $season = isset($_SESSION['seasondata']) ? $_SESSION['seasondata']->id : '';

        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';

            $query = "SELECT orders.*, customers.customername FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id  WHERE orders.`issureid` != '' AND orders.`seasonid` =:season AND (customers.customername LIKE :search OR orders.ordernumber LIKE :search) GROUP BY `ordernumber` ORDER BY id DESC LIMIT $limit OFFSET $offset";

            $arr['season'] =  $season;
            $arr['search'] = $searching;

            $data = $orders->findAllDistinct($query, $arr);
        } else {
            if (Auth::access('stores')) {
                $query = "SELECT * FROM `orders`  WHERE orders.`issureid` != '' AND orders.`seasonid` ={$season}  GROUP BY `ordernumber` ORDER BY id DESC LIMIT $limit OFFSET $offset";
            }
            $data = $orders->findAllDistinct($query);
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'order';
        $hiddenSearch = "";
        return $this->view('orders.issued', [
            'rows' => $data,
            'crumbs' => $crumbs,
            'pager' => $pager,
            'pager' => $pager,
            'hiddenSearch' => $hiddenSearch,
            'actives' => $actives
        ]);
    }


    public function edit($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $data = array();
        $errors = array();
        $orders = new Order();
        $books = new Book();

        if (count($_POST) > 0) {
            $orderid = $_POST['orderid'];
            unset($_POST['orderid']);

            if ($orders->validate($_POST)) {
                $orders->update($id, $_POST);
                $_SESSION['messsage'] = "Order Edited Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
                return $this->redirect('orders/list/' . $orderid);
            } else {
                $errors = $orders->errors;
                $_SESSION['messsage'] = "Order Not Updated";
                $_SESSION['status_code'] = "error";
                $_SESSION['status_headen'] = "OOP's!";
            }
        }

        $data = $orders->where('id', $id);;

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Agents', 'agents'];
        $crumbs[] = ['Edit Agents', ''];
        $hiddenSearch = "yeap";
        $actives = 'order';
        if (Auth::access('marketer') ||  Auth::access('stores')) {
            return $this->view('orders.edit', [
                'errors' => $errors,
                'crumbs' => $crumbs,
                'pager' => $pager,
                'rows' => $data,
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

    public function all_data($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $data = array();
        $errors = array();
        $orders = new Order();
        $books = new Book();
        $type = new Ordertype();

        if (count($_POST) > 0) {
            $orderid = $_POST['orderid'];
            unset($_POST['orderid']);

            if ($orders->validate($_POST)) {
                $orders->update($id, $_POST);
                $_SESSION['messsage'] = "Order Data Edited Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
                return $this->redirect('orders/listprice/' . $orderid);
            } else {
                $errors = $orders->errors;
                $_SESSION['messsage'] = "Order Not Updated";
                $_SESSION['status_code'] = "error";
                $_SESSION['status_headen'] = "OOP's!";
            }
        }

        $data = $orders->where('id', $id);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Agents', 'agents'];
        $crumbs[] = ['Edit Agents', ''];
        $hiddenSearch = "yeap";
        $actives = 'order';
        if (Auth::access('director')) {
            return $this->view('orders.all_data', [
                'errors' => $errors,
                'crumbs' => $crumbs,
                'pager' => $pager,
                'typedata' => $type->findAll(),
                'rows' => $data,
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

    public function placeorder($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        if (isset($_SESSION['ordernum'])) {
            unset($_SESSION['ordernum']);
        }

        $errors = array();
        $books = new Book();
        $orders = new Order();
        $customer = new Customer();
        $seas = new Season();
        $type = new Ordertype();

        $arr = array();
        $arrb = array();
        $cust = array();
        $custs = array();

        $_SESSION['ordernum'] = $id;

        if ($_SESSION['ordernum'] != null) {
            $custs = $orders->where('ordernumber', $id)[0];
            $cust = $custs->customers;
        }

        if (count($_POST) > 0 && Auth::access('marketer')) {

            if (isset($_POST['hidden_book'])) {
                $ordnumnew = 0;
                $ordnumn = "";
                $arr['offiserid'] = Auth::getID();

                if ($_SESSION['ordernum'] != null) {
                    $_POST['ordertype'] = $custs->ordertype;
                    $_POST['customerid'] = $custs->customerid;
                    $getordnum = $id;
                } else {
                    $query = 'SELECT ordernumber FROM `orders` WHERE `officerid` = :offiserid ORDER BY id DESC LIMIT 1 ';
                    $ordnum = $orders->query($query, $arr);

                    $year = date("y");
                    $mount = date("m");
                    $offs = substr(Auth::getUsername(), 4);

                    $getordnum = '';
                    $zeros = '';
                    if ($ordnum) {
                        $ordnumn = $ordnum[0]->ordernumber;
                        $ordnumnew = substr($ordnumn, -3);
                    }
                    $ordnumnew += 1;

                    if ($ordnumnew > 99) {
                        $zeros = '';
                    } elseif ($ordnumnew > 9) {
                        $zeros = '0';
                    } else {
                        $zeros = '00';
                    }
                    $getordnum = $offs . '' . $year . '' . $_POST['customerid'] . '' . $zeros . '' . $ordnumnew;
                }
                //get current Season
                $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

                for ($count = 0; $count < count($_POST['hidden_book']); $count++) {
                    $data = array(
                        'bookid' => $_POST['hidden_book'][$count],
                        'customerid' => $_POST['customerid'],
                        'ordernumber' => $getordnum,
                        'seasonid' => $seasid,
                        'ordertype' => $_POST['ordertype'],
                        'officerid' => Auth::getId(),
                        'quantord' => $_POST['hidden_ord_quant'][$count]
                    );
                    $orders->insert($data);
                }

                if (isset($_SESSION['ordernum'])) {
                    unset($_SESSION['ordernum']);
                    $_SESSION['messsage'] = "Order Added Successfully";
                    $_SESSION['status_code'] = "success";
                    $_SESSION['status_headen'] = "Good job!";
                    return $this->redirect('/orders/list/' . $id);
                }
                $_SESSION['messsage'] = "Order Placed Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
            } else {
                $errors = $orders->errors;
                $_SESSION['messsage'] = "Order Not Placed";
                $_SESSION['status_code'] = "error";
                $_SESSION['status_headen'] = "OOp's!";
            }
        }

        $custom = $customer->where('officerid', Auth::getId());
        $queryBooks = "SELECT * FROM `books` WHERE `quantity` > :qunant;";
        $arrb['qunant'] = 0;
        $row = $books->where_query($queryBooks, $arrb);

        $typddata = $type->where('typestatus', '1');
        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Agents', 'agents'];
        $crumbs[] = ['Edit Agents', ''];
        $hiddenSearch = "yeap";
        $actives = 'order';

        if (Auth::access('marketer')) {
            return $this->view('orders.placeorder', [
                'errors' => $errors,
                'crumbs' => $crumbs,
                'pager' => $pager,
                'hiddenSearch' => $hiddenSearch,
                'typedata' => $typddata,
                'cust' => $cust,
                'rows' => $row,
                'rowc' => $custom,
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

    public function applyprices($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }

        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $errors = array();
        $orders = new Order();

        $order = $orders->where('ordernumber', $id);

        if (count($_POST) > 0 && Auth::access('verification')) {
            if (isset($_POST['hidden_book'])) {
                for ($count = 0; $count < count($_POST['hidden_book']); $count++) {
                    $data = array(
                        'unitprice' => $_POST['hidden_ord_quant'][$count],
                        'discount' => $_POST['discount'],
                        'invoiceno' => $_POST['invoiceno'],
                        'accountofficer' => Auth::getUsername(),
                        'pricedate' => date("Y-m-d"),
                    );

                    $ids = $_POST['hidden_book'][$count];
                    $orders->update($ids, $data);
                }

                $_SESSION['messsage'] = "Price Applied Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
                return $this->redirect("/orders/officersalelist/" . $order[0]->officerid);
            } else {
                $errors = $orders->errors;
                $_SESSION['messsage'] = "Order Not Placed";
                $_SESSION['status_code'] = "error";
                $_SESSION['status_headen'] = "OOp's!";
            }
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Agents', 'agents'];
        $crumbs[] = ['Edit Agents', ''];
        $hiddenSearch = "yeap";
        $actives = 'order';
        if (Auth::access('verification')) {
            return $this->view('orders.applyprices', [
                'errors' => $errors,
                'crumbs' => $crumbs,
                'pager' => $pager,
                'hiddenSearch' => $hiddenSearch,
                'rows' => $order,
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

    public function groupsample($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $errors = array();
        $books = new Book();
        $orders = new Order();
        $customer = new Customer();
        $seas = new Season();
        $type = new Ordertype();
        $arr = array();

        if (count($_POST) > 0 && Auth::access('marketer')) {
            if (isset($_POST['ord_quant_text']) || isset($_POST['ord_quant_work'])) {
                $ordnumnew = 0;
                $ordnumn = "";
                $arr['offiserid'] = Auth::getID();

                $query = 'SELECT ordernumber FROM `orders` WHERE `officerid` = :offiserid ORDER BY id DESC LIMIT 1 ';
                $ordnum = $orders->query($query, $arr);

                $year = date("y");
                $mount = date("m");
                $offs = substr(Auth::getUsername(), 4);

                if ($ordnum) {
                    $ordnumn = $ordnum[0]->ordernumber;
                    $ordnumnew = substr($ordnumn, 7);
                }

                $ordnumnew++;
                $getordnum = '';
                $zeros = '';

                if ($ordnumnew > 99) {
                    $zeros = '';
                } elseif ($ordnumnew > 9) {
                    $zeros = '0';
                } else {
                    $zeros = '00';
                }

                $getordnum = $offs . '' . $year . '' . $mount . '' . $zeros . '' . $ordnumnew;

                //get current Season
                $seasid = $seas->selctingLastId()[0]->id;
                $rows = $books->whereNot('quantity', 0);

                foreach ($rows as  $row) {
                    $quant = 0;
                    if ($row->typeid == 1) {
                        $quant = $_POST['ord_quant_work'];
                    } else {
                        $quant = $_POST['ord_quant_text'];
                    }
                    $data = array(
                        'bookid' => $row->id,
                        'customerid' => $_POST['customerid'],
                        'ordernumber' => $getordnum,
                        'seasonid' => $seasid,
                        'ordertype' => $_POST['ordertype'],
                        'officerid' => Auth::getId(),
                        'quantord' => $quant
                    );
                    $orders->insert($data);
                }

                $_SESSION['messsage'] = "Order Placed Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
            } else {
                $errors = $orders->errors;
                $_SESSION['messsage'] = "Order Not Placed";
                $_SESSION['status_code'] = "error";
                $_SESSION['status_headen'] = "OOp's!";
            }
        }
        $custom = $customer->where('officerid', Auth::getId());

        $typddata = $type->where('typestatus', '1');
        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Agents', 'agents'];
        $crumbs[] = ['Edit Agents', ''];
        $hiddenSearch = "yeap";
        $actives = 'order';

        if (Auth::access('marketer')) {
            return $this->view('orders.groupsample', [
                'errors' => $errors,
                'crumbs' => $crumbs,
                'pager' => $pager,
                'hiddenSearch' => $hiddenSearch,
                'typedata' => $typddata,
                'rowc' => $custom,
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

    function pending($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $data = array();
        $orders = new Order();
        $arr = array();

        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            if (Auth::access('marketer')) {
                $query = "SELECT orders.*, customers.customername FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id  WHERE orders.`verificid` = '' AND `officerid` = " . Auth::getId() . " AND (customers.customername LIKE :search OR orders.ordernumber LIKE :search) GROUP BY `ordernumber`";
            }
            if (Auth::access('verification')) {
                $query = "SELECT orders.*, customers.customername FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id  WHERE orders.`verificid` = '' AND orders.`issureid`!= '' AND (customers.customername LIKE :search OR orders.ordernumber LIKE :search) GROUP BY `ordernumber`";
            }
            $arr['search'] = $searching;
        } else {
            if (Auth::access('marketer')) {
                $query = "SELECT * FROM `orders` WHERE `verificid` = '' AND `officerid` = " . Auth::getId() . " GROUP BY `ordernumber`";
            }
            if (Auth::access('verification')) {
                $query = "SELECT * FROM `orders` WHERE `verificid` = '' AND orders.`issureid`!= '' GROUP BY `ordernumber`";
            }
        }

        $data = $orders->findAllDistinct($query, $arr, $limit, $offset, 'ASC');

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'order';
        $hiddenSearch = "";
        if (Auth::access('marketer') || Auth::access('verification')) {
            return $this->view('orders.pending', [
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

    function pendingentry($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $data = array();
        $orders = new Order();
        $query = '';
        $arr = array();
        $arr['officer'] = Auth::getID();

        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            if (Auth::access('marketer')) {
                $query = "SELECT orders.*, users.officer FROM `orders` LEFT JOIN users ON orders.officerid = users.id LEFT JOIN customers ON orders.customerid = customers.id WHERE (orders.`accountofficer` = '' OR orders.`pricedate` = '') AND orders.`seasonid` ={$seasid} AND orders.verificid != '' AND orders.`officerid` =:officer  AND (customers.customername LIKE :search OR orders.ordernumber LIKE :search) GROUP BY `ordernumber`";
            }
            if (Auth::access('verification')) {
                $query = "SELECT orders.*, users.officer FROM `orders` LEFT JOIN users ON orders.officerid = users.id LEFT JOIN customers ON orders.customerid = customers.id WHERE users.officer = :officer AND (orders.`accountofficer` = '' OR orders.`pricedate` = '') AND orders.`seasonid` ={$seasid} AND orders.verificid != '' AND orders.`issureid`!= '' AND (customers.customername LIKE :search OR orders.ordernumber LIKE :search) GROUP BY `ordernumber`";
            }
            if (Auth::access('g-account')) {
                $arr = array();
                $query = "SELECT orders.*, users.officer FROM `orders` LEFT JOIN users ON orders.officerid = users.id LEFT JOIN customers ON orders.customerid = customers.id WHERE (orders.`accountofficer` = '' OR orders.`pricedate` = '') AND orders.`seasonid` ={$seasid} AND orders.verificid != '' AND orders.`issureid`!= '' AND (customers.customername LIKE :search OR orders.ordernumber LIKE :search) GROUP BY `ordernumber`";
            }
            $arr['search'] = $searching;
        } else {
            if (Auth::access('marketer')) {
                $query = "SELECT orders.*, users.officer FROM `orders` LEFT JOIN users ON orders.officerid = users.id LEFT JOIN customers ON orders.customerid = customers.id WHERE (orders.`accountofficer` = '' OR orders.`pricedate` = '') AND orders.`seasonid` ={$seasid} AND orders.verificid != '' AND orders.`officerid` =:officer AND (orders.retverquant < orders.quantsupp) GROUP BY `ordernumber`";
            }
            if (Auth::access('verification')) {
                $query = "SELECT orders.*, users.officer FROM `orders` LEFT JOIN users ON orders.officerid = users.id LEFT JOIN customers ON orders.customerid = customers.id WHERE users.officer = :officer AND (orders.`accountofficer` = '' OR orders.`pricedate` = '') AND orders.`seasonid` ={$seasid} AND orders.verificid != '' AND orders.`issureid`!= '' AND (orders.retverquant < orders.quantsupp) GROUP BY `ordernumber`";
            }

            if (Auth::access('g-account')) {
                $arr = array();
                $query = "SELECT orders.*, users.officer FROM `orders` LEFT JOIN users ON orders.officerid = users.id LEFT JOIN customers ON orders.customerid = customers.id WHERE (orders.`accountofficer` = '') AND orders.`seasonid` ={$seasid} AND orders.verificid != '' AND orders.`issureid`!= ''  AND (orders.retverquant < orders.quantsupp) GROUP BY `ordernumber`";
            }
        }

        $data = $orders->findAllDistinct($query, $arr, $limit, $offset);

        if (isset($_POST['exportexl'])) {
            $query = "SELECT orders.*, users.officer FROM `orders` LEFT JOIN users ON orders.officerid = users.id LEFT JOIN customers ON orders.customerid = customers.id WHERE (orders.`accountofficer` = '') AND orders.`seasonid` =:season AND orders.verificid != '' AND orders.`issureid`!= '' AND (orders.retverquant < orders.quantsupp) GROUP BY `ordernumber`";
            $arr = array();
            $arr['season'] = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

            $data1 = $orders->findAllDistinct($query, $arr);

            $fields = array('Date', 'Order Number', 'Customer', 'Order Type', 'Markerter');
            $excelData = implode("\t", array_values($fields)) . "\n";
            if ($data1) {
                foreach ($data1 as $row) {
                    $lineData = array(esc($row->orderdate), esc($row->ordernumber), esc($row->customers->customername), isset($row->ordertypes->typename) ? esc($row->ordertypes->typename) : "", ucfirst(esc($row->makerter->firstname)) . " " . ucfirst(esc($row->makerter->lastname)));
                    $excelData .= implode("\t", array_values($lineData)) . "\n";
                }
            } else {
                $excelData .= 'No records found...' . "\n";
            }
            export_data_to_excel($fields, $excelData, 'Pending_Entry_Report');
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'order';
        $hiddenSearch = "";
        if (Auth::access('marketer') || Auth::access('verification')) {
            return $this->view('orders.pendingentry', [
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

    function verified($id = null)
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
        $arr = array();
        $query = '';


        if (isset($_GET['search_box'])) {

            $searching = '%' . $_GET['search_box'] . '%';

            $ordertype = 0;
            if (strtoupper($_GET['search_box'])  == 'CASH') {
                $searching = '3';
                $ordertype = 1;
            } elseif (strtoupper($_GET['search_box'])  == 'CREDIT') {
                $searching = '2';
                $ordertype = 1;
            } elseif (strtoupper($_GET['search_box'])  == 'SAMPLE') {
                $searching = '1';
                $ordertype = 1;
            }

            if ($ordertype == 1) {
                if (Auth::access('marketer')) {
                    $query = "SELECT orders.*, customers.customername FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id  WHERE orders.`verificid` != '' AND `verifiedDate` != '' AND `officerid` = " . Auth::getId() . " AND (orders.ordertype = :search) GROUP BY `ordernumber`";
                }
                if (Auth::access('verification')) {
                    $query = "SELECT orders.*, customers.customername FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id  WHERE orders.`verificid` != '' AND `verifiedDate` != ''  AND (orders.ordertype = :search) GROUP BY `ordernumber`";
                }
                if (Auth::access('g-account')) {
                    $query = "SELECT orders.*, customers.customername FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id  WHERE orders.`verificid` != '' AND `verifiedDate` != '' AND  (orders.ordertype = :search) GROUP BY `ordernumber`";
                }
            } else {

                if (Auth::access('marketer')) {
                    $query = "SELECT orders.*, customers.customername FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id  WHERE orders.`verificid` != '' AND `verifiedDate` != '' AND `officerid` = " . Auth::getId() . " AND (customers.customername LIKE :search OR orders.ordernumber LIKE :search OR orders.ordertype LIKE :search) GROUP BY `ordernumber`";
                }
                if (Auth::access('verification')) {
                    $query = "SELECT orders.*, customers.customername FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id  WHERE orders.`verificid` != '' AND `verifiedDate` != '' AND (customers.customername LIKE :search OR orders.ordernumber LIKE :search OR orders.ordertype LIKE :search) GROUP BY `ordernumber`";
                }
                if (Auth::access('g-account')) {
                    $query = "SELECT orders.*, customers.customername FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id  WHERE orders.`verificid` != '' AND `verifiedDate` != '' AND  (customers.customername LIKE :search OR orders.ordernumber LIKE :search OR orders.ordertype LIKE :search) GROUP BY `ordernumber`";
                }
            }

            $arr['search'] = $searching;
        } else {
            if (Auth::access('marketer')) {
                $query = "SELECT * FROM `orders` WHERE verificid != '' AND `verifiedDate` != '' AND `officerid` = " . Auth::getId() . " GROUP BY `ordernumber`";
            }
            if (Auth::access('verification')) {
                $arr['verificid'] = Auth::getUsername();
                $query = "SELECT * FROM `orders` WHERE verificid != '' AND `verifiedDate` != '' AND `verificid` = :verificid GROUP BY `ordernumber`";
            }
            if (Auth::access('account')) {
                $arr = array();
                $query = "SELECT * FROM `orders` WHERE verificid != '' AND `verifiedDate` != '' GROUP BY `ordernumber`";
            }
        }
        $data = $orders->findAllDistinct($query, $arr, $limit, $offset, rotations: "DESC");


        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'order';
        $hiddenSearch = "";
        if (Auth::access('marketer') || Auth::access('verification')) {
            return $this->view('orders.verified', [
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


    function list($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $data = array();
        $orders = new Order();
        $arr = array();

        if (count($_POST) > 0) {
            $direct = "orders";
            $_SESSION['messsage'] = "Order Removed Successfully";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";

            if (count($_POST) > 0 && Auth::access('stores')) {
                $_POST['issureid'] = Auth::getUsername();
                if (isset($_POST['removeorder'])) {
                    $orderid = $_POST['removeorder'];
                    $query = "DELETE FROM `orders` WHERE `id` =$orderid";
                    $direct = "orders/list/$id";
                } else
                if ($orders->validate($_POST) && isset($_POST['use'])) {
                    unset($_POST['use']);

                    $query = "UPDATE `orders` SET `issureid`=:issureid WHERE `ordernumber` = $id";
                    $orders->query($query, $_POST);
                    $_SESSION['messsage'] = "Order Issued Successfully";
                    $_SESSION['status_code'] = "success";
                    $_SESSION['status_headen'] = "Good job!";
                    return $this->redirect('orders');
                }
            }

            if (isset($_POST['accept']) && Auth::access('marketer')) {
                $query = "UPDATE `orders` SET `maketeraccept` = 'YES' WHERE `ordernumber` =$id";
                $direct = "orders/list/$id";

                $_SESSION['messsage'] = "Order Accepted Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Thank You!";
            }

            if (isset($_POST['removeorder'])) {
                $orderid = $_POST['removeorder'];
                $query = "DELETE FROM `orders` WHERE `id` =$orderid";
                $direct = "orders/list/$id";
            }


            if (isset($_POST['canc'])) {
                $query = "DELETE FROM `orders` WHERE `ordernumber` =$id";
            }

            $orders->query($query);
            return $this->redirect($direct);
        }

        $arr['ordernumber'] = $id;
        if (isset($_GET['search_box'])) {
            $arr['searchuse'] = '%' . $_GET['search_box'] . '%';
            $query = "SELECT books.id AS bid, orders.*, levels.`class`, types.`booktype`, subjects.`subject` FROM `books` LEFT JOIN `orders` ON books.id = orders.bookid LEFT JOIN levels ON books.classid = levels.id LEFT JOIN types on books.typeid = types.id LEFT JOIN subjects ON books.classid = subjects.id WHERE ordernumber = :ordernumber AND (levels.`class` LIKE :searchuse OR types.`booktype` LIKE :searchuse OR subjects.`subject` LIKE :searchuse)";
        } else {
            $query = "SELECT * FROM `orders` WHERE ordernumber = :ordernumber";
        }

        $data = $orders->where_query($query, $arr);

        if (!$data) {
            $data = array();
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'order';
        $hiddenSearch = "";
        if (Auth::access('marketer') || Auth::access('verification') || Auth::access('stores')) {
            return $this->view('orders.list', [
                'rows' => $data,
                'crumbs' => $crumbs,
                'pager' => $pager,
                'id' => $id,
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

    function print($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }

        $data = array();
        $orders = new Order();

        $contents = '';

        $data = $orders->where('ordernumber', $id);
        $ordtype = isset($data[0]->ordertypes->typename) ? esc($data[0]->ordertypes->typename) : "";

        $contents .= '
        		<tr>
        			<td colspan="5" align="center" style="font-size:15px;"><b>LIST OF ' . $ordtype . '</b></td>
        		</tr>
        		<tr>
                    <td width="50%">
                        <b>Book</b>
                    </td">
                    <td width="13%">
                        <b>Q-Ord</b>
                    </td>
                    <td width="13%">    
                        <b>Q-Sup</b>
                    </td">
                    <td width="12%">
                        <b>Q-Ret</b>
                    </td">
                    <td width="12%">
                        <b>Veri. by</b>
                    </td">
        		</tr>
        	';

        foreach ($data as $row) {
            $orderdate = $row->orderdate;
            $offs = '';
            if (isset($row->verificOff->firstname)) {
                $offs = $row->verificOff->firstname;
            }
            $contents .= '
                <tr>
                    <td>
                    '
                . ucfirst(esc($row->books->level->class)) . " "
                . ucfirst(esc($row->books->subject->subject)) . " "
                . ucfirst(esc($row->books->booktype->booktype)) . '
                    </td>
                    <td align="center">
                        ' . esc($row->quantord) . '
                    </td>
                    <td align="center">
                        ' . esc($row->quantsupp) . '
                    </td>
                    <td align="center">
                        ' . esc($row->retverquant) . '
                    </td>
                    <td align="center">
                        ' . esc($offs) . '
                    </td>
                </tr>
            ';
        }

        //this are for breadcrumb
        $title = '';

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('DON PUBLICATION');
        $pdf->SetTitle($id);
        $pdf->SetSubject('ORDER');

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        //    $pdf->SetMargins(PDF_MARGIN_TOP, -5);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');

        // set font
        $pdf->SetFont('times', 'B', 10);

        $pdf->AddPage('P', 'A4');
        //$pdf->Cell(0, 0, 'A4 PORTRAIT', 0, 0, 'C');

        $content = '';
        $content .= '
        <html>
        <head>        
        <script>
        function printTringger(elementId){
            var getMyFrame = document.getElementById(elementId);
            getMyFrame.focus();
            getMyFrame.contentWindow.print();
        }
        </script>

        <style>
            table {
                width: 700px; /* Increase the width to 100% of the parent container */
                border-collapse: collapse;
            }
            table, th, td {
                border: 1px solid black;
            }
        </style>
       
        </head>
        <body>        
            <h2>DON PUBLICATION LTD.</h2>
            <table border="1" cellspacing="0" cellpadding="1"> 
                <tr>
                    <td>
                        ORDER #
                    </td>
                    <td>
                        ' . esc($data[0]->ordernumber) . ' 
                    </td>                    
                    <td>
                        DATE
                    </td>                    
                    <td>
                        ' . get_date($orderdate) . ' 
                    </td>                    
                </tr>
                <tr>
                    <td>
                        MARKETER
                    </td>
                    <td>
                        ' . esc($data[0]->makerter->firstname) . ' ' . esc($data[0]->makerter->lastname) . ' (' . esc($data[0]->makerter->username) . ') 
                    </td>                   
                    <td>
                        CUSTOMER
                    </td>                   
                    <td>
                         ' . esc($data[0]->customers->customername) . '
                    </td>                   
                </tr>
                <tr>
                    <td>
                        LOCATION
                    </td>
                    <td>
                        ' . esc($data[0]->customers->custlocation) . '
                    </td>                 
                    <td>
                        REGION
                    </td>
                    <td>
                        ' . esc($data[0]->customers->region) . '
                    </td>                 
                </tr>
            </table> 

            <br>
            <table border="1" cellspacing="0" cellpadding="1">  
        ';
        $content .= $contents;
        $content .= '
        </table>       
        </body>
        </html>
        ';
        echo $content;
        die;
        // $pdf->IncludeJS("print();");
        // Close and output PDF document
        // $pdf->Output('order_'.$id.'.pdf', 'I');

    }

    function verifylist($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $data = array();
        $orders = new Order();
        $books = new Book();

        if (isset($_GET['search'])) {
            $searching = '%' . $_GET['search'] . '%';
            $query = "SELECT * FROM `users` WHERE (`rank` =! 'agent') && (`firstname` LIKE :search || `lastname` LIKE :search || `othername` LIKE :search ||`user_id` LIKE :search ||`email` LIKE :search) ORDER BY id DESC";
            $arr['search'] = $searching;
            $data = $orders->query($query, $arr);
        } else {
            $data = $orders->where('ordernumber', $id);
        }

        if ((count($_POST) > 0 && Auth::access('stores')) || Auth::access('verification')) {
            if (isset($_POST['removeorder'])) {
                $bokid = $_POST['removeorder'];
                $query = "DELETE FROM `orders` WHERE `id` =$bokid";
                $orders->query($query);
                $_SESSION['messsage'] = "Order Removed Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
                return $this->redirect("/orders/verifylist/" . $id);
            }
        }

        if (count($_POST) > 0 && Auth::access('verification') && $_POST['verific'] ?? null === 'all') {
            unset($_POST['verific']);

            foreach ($data as $da) {
                $_POST['verificid'] = Auth::getUsername();
                $_POST['verifiedDate'] = date("Y-m-d");
                $_POST['orderid'] = $da->id;
                $arr['qty'] = $da->quantord;
                $arr['bookid'] = $da->bookid;

                if ($da->ordertype == 1) {
                    $_POST['unitprice'] = '0.00';
                    $_POST['accountofficer'] = Auth::getUsername();
                    $_POST['pricedate'] = date("Y-m-d");
                } else {
                    $_POST['unitprice'] = '';
                    $_POST['accountofficer'] = '';
                    $_POST['pricedate'] = '';
                }

                if ($da->verificid == '') {
                    $query = "UPDATE `orders` SET `quantsupp`=`quantord`,`verificid`=:verificid,`verifiedDate`=:verifiedDate, `unitprice`=:unitprice, `accountofficer`=:accountofficer, `pricedate`=:pricedate  WHERE `id` =:orderid";
                    $query1 = 'UPDATE `books` SET `quantity` =  `quantity` - :qty WHERE `id` = :bookid';

                    $orders->query($query, $_POST);
                    $books->query($query1, $arr);
                }
            }

            $_SESSION['messsage'] = "All Books Verified Successfully";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
            return $this->redirect("/orders/verifylist/" . $id);
        } elseif (count($_POST) > 0 && Auth::access('verification')) {
            $_POST['verificid'] = Auth::getUsername();
            $_POST['verifiedDate'] = date("Y-m-d");
            $arr['qty'] = $_POST['quantsupp'];
            $arr['bookid'] = $_POST['bookid'];
            unset($_POST['quantsupp']);
            unset($_POST['bookid']);

            $query = "UPDATE `orders` SET `quantsupp`=`quantord`,`verificid`=:verificid,`verifiedDate`=:verifiedDate WHERE `id` =:orderid";
            $query1 = 'UPDATE `books` SET `quantity` =  `quantity` - :qty WHERE `id` = :bookid';

            $books->query($query, $_POST);
            $books->query($query1, $arr);

            return $this->redirect("/orders/verifylist/" . $id);
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'order';
        $hiddenSearch = "yeap";
        if (Auth::access('marketer') || Auth::access('verification')) {
            return $this->view('orders.verifylist', [
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

    function verify($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $data = array();
        $orders = new Order();
        $books = new Book();
        $temp = 0;

        $query = '';
        $queryBook = '';
        $will_work = 0;

        $data = $orders->where('id', $id);

        if (count($_POST) > 0 && Auth::access('verification')) {
            $orderid = $_POST['orderid'];
            $bookid = $_POST['bookid'];
            unset($_POST['orderid']);

            if ($orders->validate($_POST)) {
                $_POST['verificid'] = Auth::getUsername();
                $_POST['verifiedDate'] = date("Y-m-d");

                $arr['id'] = $bookid;
                if ($data[0]->quantsupp != null || $data[0]->quantsupp == 0) {
                    if ($data[0]->quantsupp > $_POST['quantsupp']) {
                        $temp = $data[0]->quantsupp - $_POST['quantsupp'];
                        $queryBook = "UPDATE `books` SET `quantity` =  `quantity` + :qty WHERE `id` = :id ";
                        $will_work = 1;
                    } elseif ($data[0]->quantsupp < $_POST['quantsupp']) {
                        $temp = $_POST['quantsupp']  - $data[0]->quantsupp;
                        $queryBook = "UPDATE `books` SET `quantity` =  `quantity` - :qty WHERE `id` = :id";
                        $will_work = 1;
                    }
                } else {
                    $queryBook = "UPDATE `books` SET `quantity` =  `quantity` - :qty WHERE `id` = :id";
                    $will_work = 1;
                }

                $arr['qty'] = $temp;
                if ($will_work == 1) {
                    $books->query($queryBook, $arr);
                    $will_work = 0;
                }

                if ($_POST['quantsupp'] == 0) {
                    $query = "UPDATE `orders` SET `quantsupp` = NULL, `verificid` = '' WHERE `orders`.`id` = $id";
                    $orders->query($query);
                } else {
                    $orders->update($id, $_POST);
                }


                return $this->redirect('orders/verifylist/' . $orderid);
            } else {
                $errors = $orders->errors;
            }
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'order';
        $hiddenSearch = "yeap";
        if (Auth::access('verification')) {
            return $this->view('orders.verify', [
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

    function salelist($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $data = array();
        $orders = new Order();
        $cust = new Customer();

        $customer = $cust->where('id', $id)[0];
        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        $arr['custid'] = $id;
        if (isset($_GET['search_box'])) {
            $ordertype = 0;
            if (strtoupper($_GET['search_box'])  == 'CASH') {
                $searching = '3';
                $ordertype = 1;
            } elseif (strtoupper($_GET['search_box'])  == 'CREDIT') {
                $searching = '2';
                $ordertype = 1;
            } elseif (strtoupper($_GET['search_box'])  == 'SAMPLE') {
                $searching = '1';
                $ordertype = 1;
            }

            $searching = '%' . $_GET['search_box'] . '%';
            $query = "SELECT `ordernumber`, `invoiceno`, `discount`, `ordertype`, `officerid`, `verificid`, `customerid`, `issureid`, `orderdate` FROM `orders` WHERE `ordertype` != '1' AND orders.`seasonid` ={$seasid} AND `customerid` = :custid AND `orders`.ordernumber LIKE :search GROUP BY `ordernumber` ORDER BY id DESC ";
            // }
            $arr['search'] = $searching;
        } else {
            $query = "SELECT `ordernumber`, `invoiceno`, `discount`, `ordertype`, `officerid`, `verificid`, `customerid`, `issureid`, `orderdate` FROM `orders` WHERE `ordertype` != '1' AND orders.`seasonid` ={$seasid} AND `customerid` = :custid GROUP BY `ordernumber` ORDER BY id DESC";
        }

        $data = $orders->findAllDistinct($query, $arr);
        $data = $orders->get_TotalSalesOrder($data);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'order';
        $hiddenSearch = "";
        if (Auth::access('verification') || Auth::access('marketer')) {
            return $this->view('orders.salelist', [
                'rows' => $data,
                'crumbs' => $crumbs,
                'customer' => $customer,
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

    function officersalelist($id = null)
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
            $query = "SELECT orders.ordernumber, orders.invoiceno, orders.discount, orders.ordertype, orders.officerid, orders.verificid, orders.customerid, orders.issureid, orders.orderdate,  customers.customername FROM orders  JOIN customers  ON orders.customerid = customers.id WHERE customers.custtype = 'school' AND orders.`seasonid` ={$seasid} AND (orders.ordertype != '1' AND orders.officerid = :officerid) AND (orders.ordernumber LIKE :search OR customers.customername LIKE :search OR orders.invoiceno LIKE :search) GROUP BY orders.ordernumber ORDER BY orders.id DESC LIMIT $limit OFFSET $offset";
            $arr['search'] = $searching;
        } else {
            $query = "SELECT ordernumber, `invoiceno`, `discount`, `ordertype`, orders.`officerid`, `verificid`, `customerid`, `issureid`, `orderdate` FROM `orders` JOIN customers  ON orders.customerid = customers.id WHERE customers.custtype = 'school' AND orders.`seasonid` ={$seasid} AND (orders.`ordertype` != '1' AND orders.`officerid` = :officerid)  GROUP BY orders.ordernumber ORDER BY orders.id DESC LIMIT $limit OFFSET $offset";
        }

        $data = $orders->findAllDistinct($query, $arr);
        $data = $orders->get_TotalSalesOrder($data);

        if (isset($_POST['exportexl'])) {
            if (isset($_GET['search_box'])) {
                $searching = '%' . $_GET['search_box'] . '%';
                $query = "SELECT orders.ordernumber, orders.invoiceno, orders.discount, orders.ordertype, orders.officerid, orders.verificid, orders.customerid, orders.issureid, orders.orderdate, customers.customername FROM orders  JOIN customers  ON orders.customerid = customers.id WHERE customers.custtype = 'school' AND orders.`seasonid` ={$seasid} AND (orders.ordertype != '1' AND orders.officerid = :officerid) AND (orders.ordernumber LIKE :search OR customers.customername LIKE :search OR orders.invoiceno LIKE :search) GROUP BY orders.ordernumber";
                $arr['search'] = $searching;
            } else {
                $query = "SELECT ordernumber, `invoiceno`, `discount`, `ordertype`, orders.`officerid`, `verificid`, `customerid`, `issureid`, `orderdate` FROM `orders` JOIN customers  ON orders.customerid = customers.id WHERE customers.custtype = 'school' AND orders.`seasonid` ={$seasid} AND (orders.`ordertype` != '1' AND orders.`officerid` = :officerid)  GROUP BY orders.ordernumber ";
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
        $actives = 'order';
        $hiddenSearch = "";
        if (Auth::access('verification') || Auth::access('marketer')) {
            return $this->view('orders.officersalelist', [
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

    function salesent($id = null)
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

        // Setting pagination
        $limit = 20;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        //$arr['officer'] = Auth::getId();

        if (isset($_GET['search_box'])) {

            $searching = '%' . $_GET['search_box'] . '%';

            $ordertype = 0;
            if (strtoupper($_GET['search_box'])  == 'CASH') {
                $searching = '3';
                $ordertype = 1;
            } elseif (strtoupper($_GET['search_box'])  == 'CREDIT') {
                $searching = '2';
                $ordertype = 1;
            } elseif (strtoupper($_GET['search_box'])  == 'SAMPLE') {
                $searching = '1';
                $ordertype = 1;
            }

            if ($ordertype == 1) {
                if (Auth::access('verification')) {
                    $query = "SELECT orders.customerid AS cid, orders.officerid, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM orders LEFT JOIN customers ON orders.customerid = customers.id LEFT JOIN users ON  orders.officerid = users.id WHERE ordertype = :search GROUP BY customers.customername ORDER BY ID DESC LIMIT $limit OFFSET $offset";
                } else {
                    $query = "SELECT orders.customerid AS cid, orders.officerid, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM orders LEFT JOIN customers ON orders.customerid = customers.id LEFT JOIN users ON  orders.officerid = users.id WHERE (ordertype = :search) AND (users.officer = " . Auth::getId() . " OR users.id = " . Auth::getId() . " AND ordertype != '1') GROUP BY customers.customername ORDER BY ID DESC LIMIT $limit OFFSET $offset";
                }
            } else {
                if (Auth::access('verification')) {
                    $query = "SELECT orders.customerid AS cid, orders.officerid, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM orders LEFT JOIN customers ON orders.customerid = customers.id LEFT JOIN users ON  orders.officerid = users.id WHERE customers.`customername` LIKE :search  OR customers.`custphone` LIKE :search  AND ordertype != '1' GROUP BY customers.customername ORDER BY ID DESC LIMIT $limit OFFSET $offset";
                } else {
                    $query = "SELECT orders.customerid AS cid, orders.officerid, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM orders LEFT JOIN customers ON orders.customerid = customers.id LEFT JOIN users ON  orders.officerid = users.id WHERE (customers.`customername` LIKE :search  OR customers.`custphone` LIKE :search) AND (users.officer = " . Auth::getId() . " OR users.id = " . Auth::getId() . " AND ordertype != '1') GROUP BY customers.customername ORDER BY ID DESC LIMIT $limit OFFSET $offset";
                }
            }
            $arr['search'] = $searching;

            $data = $customer->query($query, $arr);
        } else {
            if (Auth::access('verification')) {
                $query = "SELECT orders.customerid AS cid, orders.officerid, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM orders LEFT JOIN customers ON orders.customerid = customers.id LEFT JOIN users ON  orders.officerid = users.id WHERE ordertype != '1' GROUP BY customers.customername ORDER BY ID DESC LIMIT $limit OFFSET $offset";
            } else {
                $query = "SELECT orders.customerid AS cid, orders.officerid, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM orders LEFT JOIN customers ON orders.customerid = customers.id LEFT JOIN users ON  orders.officerid = users.id WHERE users.officer = " . Auth::getId() . " OR users.id = " . Auth::getId() . " AND ordertype != '1' GROUP BY customers.customername ORDER BY ID DESC LIMIT $limit OFFSET $offset";
            }
            $data = $customer->query($query);
        }


        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'order';
        $hiddenSearch = "";
        if (Auth::access('verification') || Auth::access('marketer')) {
            return $this->view('orders.salesent', [
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

    function special($id = null)
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
        $Customertypeassin = new Customertypeassin();
        $customer = new Order();
        $payment = new Payment();
        $seasons = new Season();


        //get Seasons
        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        // Setting pagination
        $limit = 20;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        //$arr['officer'] = Auth::getId();

        $officSpec = $Customertypeassin->where('verificationOffcer', Auth::getId());

        foreach ($officSpec as $object) {
            $customertypeArray[] = $object->customertype;
        }


        // Add conditions to the array if the customertype exists
        if (in_array('agent', $customertypeArray)) {
            $conditionsArray[] = "customers.custtype = 'agent'";
        }

        if (in_array('garris', $customertypeArray)) {
            $conditionsArray[] = "customers.custtype = 'garris'";
        }

        if (in_array('booksh', $customertypeArray)) {
            $conditionsArray[] = "customers.custtype = 'booksh'";
        }

        // Join the conditions with 'AND'
        $conditions = implode(' OR ', $conditionsArray);

        $conditions = "(" . $conditions . ")";

        if (isset($_GET['search_box'])) {
            // Add customername condition
            $conditions .= " AND (customers.customername LIKE '%" . $_GET['search_box'] . "%')";
        }

        // Construct the SELECT query
        $query = "SELECT orders.customerid AS cid, orders.officerid, customers.`customername`, customers.`custphone`, customers.`custlocation`, customers.`custtype`, customers.`region`, users.* FROM orders LEFT JOIN customers ON orders.customerid = customers.id LEFT JOIN users ON  orders.officerid = users.id";

        if (!empty($conditions)) {
            $query .= " WHERE " . $conditions . " AND (ordertype != '1') AND orders.`seasonid` ={$seasid}  GROUP BY customers.customername ORDER BY ID DESC LIMIT $limit OFFSET $offset";
        }

        $data = $customer->query($query);

        $data = $payment->get_Total($data, $seasid);
        $data = $payment->get_TotalDept($data, $seasid);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'order';
        $hiddenSearch = "";
        if (Auth::access('verification') || Auth::access('marketer')) {
            return $this->view('orders.special', [
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

    function listprice($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $data = array();
        $orders = new Order();
        $acti = new Activitylog();
        $payments = new Payment();
        $coust = new Customer();
        $tithe = new Tithe();

        if (count($_POST) > 0 && isset($_POST['disc']) && Auth::access('verification')) {
            unset($_POST['custname']);
            $query = "UPDATE `orders` SET `discount`= :discount, `invoiceno` = :invoiceno WHERE `ordernumber` = :disc";
            $orders->query($query, $_POST);
            $_SESSION['messsage'] = "Discount Apply Successfully";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
        }

        if (count($_POST) > 0 && isset($_POST['overPayment']) && Auth::access('verification')) {
            if ($_POST['overPayment'] > 0) {
                $sid = $_SESSION['seasondata']->id;
                $ttSales = $payments->get_TotalCustomer($_POST['cid'], $sid);
                $ttPayment = $payments->get_TotalPay($_POST['cid']);

                $amountPaid = $_POST['overPayment'];
                $progitData = $tithe->getTithe($ttSales, $ttPayment, $amountPaid, 'yes');

                $arr['overPayment'] = $progitData['overPayment'];
                $arr['cusid'] = $_POST['cid'];

                if ($arr['overPayment'] < 0) {
                    $arr['overPayment'] = 0;
                }
                $query = "UPDATE `customers` SET `overPayment`=:overPayment WHERE `id` =:cusid ";
                $coust->query($query, $arr);

                if ($progitData['tithe'] > 0) {
                    $tithe->insert($progitData);
                    $_SESSION['messsage'] = "Over Payment Used Successfully";
                    $_SESSION['status_code'] = "success";
                    $_SESSION['status_headen'] = "Good job!";
                }
            }
        }

        if (count($_POST) > 0 && isset($_POST['editdisc']) && Auth::access('verification')) {
            $query = "UPDATE `orders` SET `updatediscount`= :discount, `invoiceno` = :invoiceno WHERE `ordernumber` = :editdisc";
            $custom = $_POST['custname'];
            $olddisc = $_POST['olddisc'];
            $newdiscount = $_POST['discount'];
            unset($_POST['olddisc']);
            unset($_POST['custname']);

            if ($olddisc != $newdiscount) {
                $acs['userid'] = Auth::getUsername();
                $acs['activity'] = "Edited Order $id discount from $olddisc to $newdiscount for $custom";
                $acs['loclink'] = "orders/listprice/$id";
                $_SESSION['messsage'] = "Discount Updated Successfully for Approval";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
                $acti->insert($acs);
            } else {
                $_POST['discount'] = '0';
            }

            $orders->query($query, $_POST);
        }

        if (count($_POST) > 0 && isset($_POST['acceptdisc']) && Auth::access('g-account')) {
            $query = "UPDATE `orders` SET `discount`=:newdisc, `updatediscount` = '0' WHERE `ordernumber` = :acceptdisc";
            $custom = $_POST['custname'];
            $newdiscount = $_POST['newdisc'];
            $olddisc = $_POST['discount'];
            unset($_POST['invoiceno']);
            unset($_POST['discount']);
            unset($_POST['custname']);

            $acs['userid'] = Auth::getUsername();
            $acs['activity'] = "Accepted order $id discount changes from $olddisc to $newdiscount for $custom";

            $acti->insert($acs);

            $orders->query($query, $_POST);
            $_SESSION['messsage'] = "Discount Update Successfully Approved";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
        }

        if (count($_POST) > 0 && isset($_POST['declinetdisc']) && Auth::access('g-account')) {

            $query = "UPDATE `orders` SET `updatediscount` = '0' WHERE `ordernumber` = :declinetdisc";
            $custom = $_POST['custname'];
            $olddisc = $_POST['discount'];
            unset($_POST['custname']);
            unset($_POST['discount']);
            unset($_POST['invoiceno']);
            unset($_POST['newdisc']);

            $acs['userid'] = Auth::getUsername();
            $acs['activity'] = "Decline order $id discount changes from $olddisc to $newdiscount for $custom";

            $acti->insert($acs);

            $orders->query($query, $_POST);
            $_SESSION['messsage'] = "Discount Update Successfully Approved";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
        }

        if (count($_POST) > 0 && isset($_POST['actv']) && Auth::access('g-account')) {
            $_POST['ordernum'] = $id;
            $custom = $_POST['custname'];
            unset($_POST['custname']);
            unset($_POST['actv']);
            $query = "UPDATE `orders` SET `activate`= 0 WHERE `ordernumber` = :ordernum";

            $acs['userid'] = Auth::getUsername();
            $acs['activity'] = "Activated Order $id for editing";

            $acti->insert($acs);

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
        $actives = 'account';
        $hiddenSearch = "";
        if (Auth::access('marketer') || Auth::access('verification')) {
            return $this->view('orders.listprice', [
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

    function applyprice($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $data = array();
        $orders = new Order();
        $acti = new Activitylog();

        if (count($_POST) > 0 && Auth::access('verification')) {

            $orderid = $_POST['orderid'];
            unset($_POST['orderid']);

            if ($orders->validate($_POST)) {
                $_POST['accountofficer'] = Auth::getUsername();
                $_POST['pricedate'] = date("Y-m-d");

                $orders->update($id, $_POST);

                return $this->redirect('orders/listprice/' . $orderid);
            } else {
                $errors = $orders->errors;
            }
        }

        $data = $orders->where('id', $id);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $hiddenSearch = "yeap";
        $actives = 'account';
        if (Auth::access('verification')) {
            return $this->view('orders.applyprice', [
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
    function editprice($id = null)
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

        $data = $orders->where('id', $id);

        if (count($_POST) > 0 && Auth::access('verification')) {

            $orderid = $_POST['orderid'];
            unset($_POST['orderid']);

            if ($orders->validate($_POST)) {
                $_POST['verificid'] = Auth::getUsername();
                $_POST['verifiedDate'] = date("Y-m-d");
                $oldp = $_POST['oldprice'];
                unset($_POST['oldprice']);

                if ($_POST['updateprice'] != $oldp) {
                    $orders->update($id, $_POST);

                    $acs['userid'] = Auth::getUsername();
                    $mess = $data[0]->books->level->class . " " . $data[0]->books->subject->subject . " " . $data[0]->books->booktype->booktype;
                    $custom = $data[0]->customers->customername;
                    $ordernu = $data[0]->ordernumber;

                    $acs['activity'] = "Edited price of $mess on order $ordernu from $oldp to " . $_POST['updateprice'] . " for $custom";
                    $acs['loclink'] = "orders/listprice/$ordernu";

                    $acti->insert($acs);
                    $_SESSION['messsage'] = "Price Edited Successfully for Approval";
                    $_SESSION['status_code'] = "success";
                    $_SESSION['status_headen'] = "Good job!";
                }
                return $this->redirect('orders/listprice/' . $orderid);
            } else {
                $errors = $orders->errors;
            }
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $hiddenSearch = "yeap";
        $actives = 'account';
        if (Auth::access('verification')) {
            return $this->view('orders.editprice', [
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

    function acceptprice($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $data = array();
        $orders = new Order();
        $acti = new Activitylog();

        $data = $orders->where('id', $id);


        if (count($_POST) > 0 && Auth::access('verification')) {

            if (isset($_POST['decl'])) {
                $orderid = $_POST['orderid'];
                $oldp = $_POST['oldprice'];

                $query = "UPDATE `orders` SET `updateprice`= 0 WHERE `id` = $id";
                $orders->query($query);

                $acs['userid'] = Auth::getUsername();
                $mess = $data[0]->books->level->class . " " . $data[0]->books->subject->subject . " " . $data[0]->books->booktype->booktype;

                $acs['activity'] = "Decline price change for $mess from $oldp to " . $_POST['updateprice'];

                $acti->insert($acs);

                $_SESSION['messsage'] = "Price Update Successfully Approved";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";

                return $this->redirect('orders/listprice/' . $orderid);
            } else {
                $orderid = $_POST['orderid'];
                $_POST['verificid'] = Auth::getUsername();
                $_POST['verifiedDate'] = date("Y-m-d");
                $oldp = $_POST['oldprice'];
                $price = $_POST['updateprice'];

                $query = "UPDATE `orders` SET `unitprice`=$price,`updateprice`= 0 WHERE `id` = $id";
                $orders->query($query);

                $acs['userid'] = Auth::getUsername();
                $mess = $data[0]->books->level->class . " " . $data[0]->books->subject->subject . " " . $data[0]->books->booktype->booktype;

                $acs['activity'] = "Confirmed price change for $mess from $oldp to " . $price;

                $acti->insert($acs);

                $_SESSION['messsage'] = "Price Update Successfully Approved";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";

                return $this->redirect('orders/listprice/' . $orderid);
            }
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $hiddenSearch = "yeap";
        $actives = 'account';
        if (Auth::access('verification')) {
            return $this->view('orders.acceptprice', [
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
}
