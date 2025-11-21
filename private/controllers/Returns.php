<?php

/**
 * Returns controller
 */
class Returns extends Controller
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
        $arr = array();

        $data = array();
        $orders = new Order();
        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            if (Auth::access('marketer')) {
                $query = "SELECT orders.*, customers.customername FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id  WHERE `retquant` != 0 AND orders.`officerid` = " . Auth::getId() . " AND orders.`seasonid` ={$seasid} AND  (customers.customername LIKE :search OR orders.ordernumber LIKE :search) GROUP BY `ordernumber` ORDER BY `retdate` DESC LIMIT $limit OFFSET $offset";
            }
            if (Auth::access('stores')) {
                $query = "SELECT orders.*, customers.customername FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id  WHERE `retverquantacc` != 0 AND orders.`seasonid` ={$seasid} AND (customers.customername LIKE :search OR orders.ordernumber LIKE :search) GROUP BY `ordernumber` ORDER BY `retdate` DESC LIMIT $limit OFFSET $offset";
            }
            if (Auth::access('verification')) {
                $query = "SELECT orders.*, customers.customername FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id  WHERE `retverquantacc` != 0 AND orders.`seasonid` ={$seasid} AND (customers.customername LIKE :search OR orders.ordernumber LIKE :search) GROUP BY `ordernumber` ORDER BY `retdate` DESC LIMIT $limit OFFSET $offset";
            }

            $arr['search'] = $searching;
        } else {
            if (Auth::access('marketer')) {
                $query = "SELECT * FROM `orders` WHERE `retquant` != 0 AND orders.`seasonid` ={$seasid} AND `officerid` = " . Auth::getId() . " GROUP BY `ordernumber` ORDER BY `retdate` DESC LIMIT $limit OFFSET $offset";
            }
            if (Auth::access('stores')) {
                $query = "SELECT * FROM `orders` WHERE `retverquant` !=0 AND orders.`seasonid` ={$seasid} GROUP BY `ordernumber` ORDER BY `retdate` DESC LIMIT $limit OFFSET $offset";
            }
            if (Auth::access('verification')) {
                $query = "SELECT * FROM `orders` WHERE `retverquantacc` != 0 AND orders.`seasonid` ={$seasid} GROUP BY `ordernumber` ORDER BY `retdate` DESC LIMIT $limit OFFSET $offset";
            }
        }

        $data = $orders->findAllDistinct($query, $arr);


        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'Books';
        $hiddenSearch = "";
        return $this->view('returns', [
            'rows' => $data,
            'crumbs' => $crumbs,
            'pager' => $pager,
            'hiddenSearch' => $hiddenSearch,
            'actives' => $actives
        ]);
    }

    function rets($id = null)
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

        if (count($_POST) > 0 && Auth::access('marketer')) {

            $orderid = $_POST['orderid'];
            unset($_POST['orderid']);

            if ($orders->validate($_POST)) {
                $das = $orders->where('id', $id)[0];
                $_POST['retquant'] = ($_POST['retquant'] + $das->retverquant);
                if ($_POST['retquant'] <= $_POST['quantsupp']) {
                    unset($_POST['ret']);
                    unset($_POST['quantsupp']);
                    $_POST['retdate'] = date("Y-m-d");
                    $_POST['idd'] = $id;
                    $query = "UPDATE `orders` SET retquant= retquant + :retquant, retdate=:retdate WHERE `id`= :idd";

                    $orders->query($query, $_POST);

                    $_SESSION['messsage'] = "Order Successfully Returned";
                    $_SESSION['status_code'] = "success";
                    $_SESSION['status_headen'] = "Good job!";

                    return $this->redirect('orders/list/' . $orderid);
                } else {
                    $_SESSION['messsage'] = "Quantity Returning is Higher than Quantity Supply";
                    $_SESSION['status_code'] = "error";
                    $_SESSION['status_headen'] = "OOP's!";
                }
            } else {
                $errors = $orders->errors;
            }
        }

        $data = $orders->where('id', $id);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'Return';
        $hiddenSearch = "";
        if (Auth::access('marketer')) {
            return $this->view('returns.rets', [
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

    function retsedit($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $data = array();
        $orders = new Order();

        if (count($_POST) > 0 && Auth::access('marketer')) {

            $orderid = $_POST['orderid'];
            unset($_POST['orderid']);


            $newRetQuant = $_POST['retquant'];

            unset($_POST['ret']);
            // Allow setting returns to 0
            if ($newRetQuant == 0) {
                $_POST['retdate'] = date("Y-m-d");
                $_POST['idd'] = $id;
                unset($_POST['quantsupp']);

                $query = "UPDATE `orders` SET retquant=:retquant, retdate=:retdate WHERE `id`= :idd";
                $orders->query($query, $_POST);
                $_SESSION['messsage'] = "Return quantity set to 0 successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
                return $this->redirect('orders/list/' . $orderid);
            }

            // Only proceed if new return quantity is less than or equal to supplied quantity
            if ($newRetQuant <= $_POST['quantsupp']) {
                unset($_POST['ret']);
                unset($_POST['quantsupp']);
                $_POST['retdate'] = date("Y-m-d");
                $_POST['idd'] = $id;

                // Update the orders table
                $query = "UPDATE `orders` SET retquant=:retquant, retdate=:retdate WHERE `id`= :idd";
                $orders->query($query, $_POST);
                $_SESSION['messsage'] = "Order Successfully Returned";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";

                return $this->redirect('orders/list/' . $orderid);
            } else {
                $_SESSION['messsage'] = "Quantity Returning is Higher than Quantity Supply";
                $_SESSION['status_code'] = "error";
                $_SESSION['status_headen'] = "OOP's!";
            }
        }

        $data = $orders->where('id', $id);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'Return';
        $hiddenSearch = "";
        if (Auth::access('marketer')) {
            return $this->view('returns.retsedit', [
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
        $query = '';
        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            if (Auth::access('marketer')) {
                $query = "SELECT orders.*, customers.customername FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id  WHERE `retquant` > `retverquant` AND orders.`seasonid` ={$seasid} AND orders.`officerid` = " . Auth::getId() . " AND (customers.customername LIKE :search OR orders.ordernumber LIKE :search) GROUP BY `ordernumber`";
            }
            if (Auth::access('stores')) {
                $query = "SELECT orders.*, customers.customername FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id  WHERE `retverquantacc` > `retverquant` AND orders.`seasonid` ={$seasid} AND (customers.customername LIKE :search OR orders.ordernumber LIKE :search) GROUP BY `ordernumber`";
            }
            if (Auth::access('verification')) {
                $query = "SELECT orders.*, customers.customername FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id  WHERE (`retquant` > `retverquant`) AND orders.`seasonid` ={$seasid} AND (customers.customername LIKE :search OR orders.ordernumber LIKE :search) GROUP BY `ordernumber`";
            }

            $arr['search'] = $searching;
        } else {
            if (Auth::access('marketer')) {
                $query = "SELECT * FROM `orders` WHERE `retquant` > 0 AND `retverquantacc` < retquant AND orders.`seasonid` ={$seasid} AND `officerid` = " . Auth::getId() . " GROUP BY `ordernumber`";
            }
            if (Auth::access('stores')) {
                $query = "SELECT * FROM `orders` WHERE `retverquantacc` > `retverquant` AND orders.`seasonid` ={$seasid} GROUP BY `ordernumber`";
            }
            if (Auth::access('verification')) {
                $query = "SELECT * FROM `orders` WHERE `retquant` > `retverquantacc` AND orders.`seasonid` ={$seasid} GROUP BY `ordernumber`";
            }
        }
        $data = $orders->findAllDistinct($query, $arr, $limit, $offset);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $hiddenSearch = "";
        $actives = 'Return';
        if (Auth::access('marketer') || Auth::access('verification') || Auth::access('stores')) {
            return $this->view('returns.pending', [
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

    function verifylist($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        $data = array();
        $orders = new Order();
        $books = new Book();
        $arrBook = array();
        $arr = array();
        $query = '';

        $rets = new Uninvoicedret();

        // Search functionality
        if (isset($_GET['search'])) {
            $searching = '%' . $_GET['search'] . '%';
            $query = "SELECT * FROM `users` WHERE (`rank` != 'agent') AND (`firstname` LIKE :search OR `lastname` LIKE :search OR `othername` LIKE :search OR `user_id` LIKE :search OR `email` LIKE :search) ORDER BY id DESC";
            $arr['search'] = $searching;
            $data = $orders->query($query, $arr);
        } else {
            $data = $orders->where_query(
                "SELECT orders.* FROM orders JOIN books ON orders.bookid = books.id WHERE orders.ordernumber = :ordernumber ORDER BY books.subjectid ASC, books.classid ASC, books.typeid ASC;",
                ['ordernumber' => $id]
            );
        }

        if (count($_POST) > 0 && Auth::access('verification')) {
            if (isset($_POST['returnal'])) {
                unset($_POST['returnal']);
                foreach ($data as $da) {
                    $_POST['verificid'] = Auth::getUsername();
                    $_POST['retdate'] = date("Y-m-d");
                    $_POST['orderid'] = $da->id;
                    $arr['qty'] = $da->quantord;
                    $arr['bookid'] = $da->bookid;

                    $_POST['unitprice'] = '';
                    $_POST['accountofficer'] = '';
                    $_POST['pricedate'] = '';

                    if ($da->retquant > 0 && $da->retverquantacc < $da->retquant) {
                        $query = "UPDATE `orders` SET `retverquantacc`=retverquantacc + `retquant`, `retverofficer`=:verificid, `retdate`=:retdate WHERE `id` =:orderid";
                        $query1 = "UPDATE `books` SET `quantity` =  `quantity` + :qty WHERE `id` = :bookid";

                        $orders->query($query, $_POST);
                        $books->query($query1, $arr);
                    }
                }

                $_SESSION['messsage'] = "All Returns Verified by Verificatin Officer Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";

                return $this->redirect("/returns/verifylist/" . $id);
            } elseif (isset($_POST['verifyret'])) {
                // This is for verifying the uncalculated returns
                $_POST['verificid'] = Auth::getUsername();
                $_POST['retdate'] = date("Y-m-d");
                $_POST['qty'] = $_POST['retquant'];

                $arr['qty'] = $_POST['retquant'];
                $arr['orderid'] = $_POST['orderid'];

                unset($_POST['quantsupp']);
                unset($_POST['verifyret']);

                if ($orders->where('id', $_POST['orderid'])[0]->retquant == 0) {
                    $_SESSION['messsage'] = "This Return has already been Verified";
                    $_SESSION['status_code'] = "error";
                    $_SESSION['status_headen'] = "OOP's!";
                    return $this->redirect("/returns/verifylist/" . $id);
                }

                $query = "UPDATE `orders` SET `quantsupp`= `quantsupp` - :qty, `retquant`= '' WHERE `id` =:orderid";

                $orders->query($query, $arr);

                $query1 = "UPDATE `books` SET `quantity` =  `quantity` + :qty WHERE `id` = :bookid";

                $arrBook['bookid'] = $_POST['bookid'];
                $arrBook['qty'] = $_POST['retquant'];
                $books->query($query1, $arrBook);

                $rets->insert(
                    [
                        'bookid' => $_POST['bookid'],
                        'quant' => $_POST['retquant'],
                        'verifOfficer' => $_POST['verificid'],
                        'ordernumber' => $id,
                        'orderid' => $_POST['orderid'],
                        'datereturned' => date("Y-m-d H:i:s")
                    ]
                );

                $_SESSION['messsage'] = "Uncalculated Return Verified Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";

                return $this->redirect("/returns/verifylist/" . $id);
            } else {
                // This is for verifying the invoiced returns
                $_POST['verificid'] = Auth::getUsername();
                $_POST['retdate'] = date("Y-m-d");
                $arr['qty'] = $_POST['retquant'];
                $arr['bookid'] = $_POST['bookid'];

                unset($_POST['quantsupp']);
                unset($_POST['bookid']);
                unset($_POST['retquant']);

                if ($orders->where('id', $_POST['orderid'])[0]->retquant == 0) {
                    $_SESSION['messsage'] = "This Return has already been Verified";
                    $_SESSION['status_code'] = "error";
                    $_SESSION['status_headen'] = "OOP's!";
                    return $this->redirect("/returns/verifylist/" . $id);
                }

                $query = "UPDATE `orders` SET `retverquantacc`=`retquant`, `retverofficer`=:verificid, `retdate`=:retdate WHERE `id` =:orderid";
                $query1 = 'UPDATE `books` SET `quantity` =  `quantity` + :qty WHERE `id` = :bookid';


                $orders->query($query, $_POST);
                $books->query($query1, $arr);


                return $this->redirect("/returns/verifylist/" . $id);
            }
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'Books';
        $hiddenSearch = "";
        if (Auth::access('marketer') || Auth::access('verification')) {
            return $this->view('returns.verifylist', [
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

    function returnacceptlist($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        $data = array();
        $orders = new Order();
        $books = new Book();

        if (isset($_GET['search'])) {
            $searching = '%' . $_GET['search'] . '%';
            $query = "SELECT * FROM `users` WHERE (`rank` =! 'agent') && (`firstname` LIKE :search || `lastname` LIKE :search || `othername` LIKE :search ||`user_id` LIKE :search ||`email` LIKE :search)";
            $arr['search'] = $searching;
            $data = $orders->query($query, $arr);
        } else {
            $data = $orders->where('ordernumber', $id);
        }

        if (count($_POST) > 0 && Auth::getRank() == 'stores') {
            if (isset($_POST['returnal'])) {
                unset($_POST['returnal']);
                foreach ($data as $da) {
                    $_POST['verificid'] = Auth::getUsername();
                    $_POST['orderid'] = $da->id;
                    $arr['qty'] = $da->retverquantacc;
                    $arr['bookid'] = $da->bookid;

                    if ($da->retverquantacc != 0) {
                        $query = "UPDATE `orders` SET `retverquant`=`retverquantacc`, `stockOffcerReturn`=:verificid WHERE `id` =:orderid";
                        $query1 = 'UPDATE `books` SET `quantity` =  `quantity` + :qty WHERE `id` = :bookid';

                        $orders->query($query, $_POST);
                        $books->query($query1, $arr);
                    }
                }

                $_SESSION['messsage'] = "All Books Retuned Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
                return $this->redirect("/returns/returnacceptlist/" . $id);
            } else {
                $_POST['verificid'] = Auth::getUsername();;
                $arr['qty'] = $_POST['retquant'];
                $arr['bookid'] = $_POST['bookid'];
                unset($_POST['quantsupp']);
                unset($_POST['bookid']);
                unset($_POST['retquant']);

                $query = "UPDATE `orders` SET `retverquant`=`retverquantacc`, `stockOffcerReturn`=:verificid WHERE `id` =:orderid";
                $query1 = 'UPDATE `books` SET `quantity` =  `quantity` + :qty WHERE `id` = :bookid';

                $orders->query($query, $_POST);
                $books->query($query1, $arr);
                return $this->redirect("/returns/returnacceptlist/" . $id);
            }
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'Books';
        $hiddenSearch = "";
        if (Auth::getRank() == 'stores') {
            return $this->view('returns.returnacceptlist', [
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

    function verify($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        $data = array();
        $orders = new Order();

        $data = $orders->where('id', $id);

        if (count($_POST) > 0 && Auth::access('verification')) {

            $TempPost = $_POST;

            if ($orders->validate($_POST)) {
                $_POST['retverdate'] = date("Y-m-d");
                $_POST['retverofficer'] = Auth::getUsername();
                $_POST['ordid'] = $id;

                unset($_POST['retquant']);
                unset($_POST['orderid']);
                unset($_POST['bookid']);

                $query = "UPDATE `orders` SET `retverquantacc` =  `retverquantacc` + :retverquant, `retverofficer` = :retverofficer, `retverdate` = :retverdate WHERE `id` =:ordid";

                if ($_POST['retverquant'] == 0) {
                    $query = "UPDATE `orders` SET `retquant` = retverquant WHERE `id` =$id";
                    $orders->query($query);
                } else {
                    $orders->query($query, $_POST);
                }

                return $this->redirect('returns/verifylist/' . $TempPost['orderid']);
            } else {
                $errors = $orders->errors;
            }
        }



        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'Return';
        $hiddenSearch = "";
        if (Auth::access('verification')) {
            return $this->view('returns.verify', [
                'rows' => $data,
                'crumbs' => $crumbs,
                'hiddenSearch' => $hiddenSearch,
                'actives' => $actives,
                'errors' => $errors ?? []
            ]);
        } else {
            $crumbs[] = ['Access Denied', ''];
            return $this->view('access-denied', [
                'crumbs' => $crumbs,
                'actives' => $actives
            ]);
        }
    }

    public function del($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        $agents = new User();
        if (count($_POST) > 0 && Auth::access('director')) {
            $agents->delete($id);
            return $this->redirect('agents');
        }
        $row = $agents->where('id', $id);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Agents', 'agents'];
        $crumbs[] = ['Delete Agents', ''];

        $actives = 'Return';
        if (Auth::access('director')) {
            return $this->view('agents.delete', [
                'rows' => $row,
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

    function verified($id = null)
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
        $query = '';

        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            if (Auth::access('marketer')) {
                $query = "SELECT orders.*, customers.customername FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id  WHERE retverquant != 0 AND (orders.verificid != '' AND orders.`verifiedDate` != '' AND orders.`seasonid` ={$seasid} AND orders.`officerid` =:userid) AND (customers.customername LIKE :search OR orders.ordernumber LIKE :search) GROUP BY `ordernumber`";
                $arr['userid'] = Auth::getId();
            }
            if (Auth::access('verification')) {
                $query = "SELECT orders.*, customers.customername FROM `orders` LEFT JOIN customers ON orders.customerid = customers.id  WHERE retverquant != 0 AND (orders.verificid != '' AND orders.`verifiedDate` != '') AND orders.`seasonid` ={$seasid}  AND (customers.customername LIKE :search OR orders.ordernumber LIKE :search) GROUP BY `ordernumber`";
            }
            $arr['search'] = $searching;
        } else {
            if (Auth::access('marketer')) {
                $query = "SELECT * FROM `orders` WHERE retverquant != 0 AND verificid != '' AND orders.`seasonid` ={$seasid}  AND `verifiedDate` != '' AND `officerid` = " . Auth::getId() . " GROUP BY `ordernumber`";
            }
            if (Auth::access('verification')) {
                $arr['retverofficer'] = Auth::getUsername();
                $query = "SELECT * FROM `orders` WHERE retverquant != 0 AND `retverofficer` = :retverofficer AND orders.`seasonid` ={$seasid} GROUP BY `ordernumber`";
            }
        }

        $data = $orders->findAllDistinct($query, $arr, $limit, $offset);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'Books';
        $hiddenSearch = "";
        if (Auth::access('marketer') || Auth::access('verification')) {
            return $this->view('returns.verified', [
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

        $arr['ordernumber'] = $id;
        if (isset($_GET['search_box'])) {
            $arr['searchuse'] = '%' . $_GET['search_box'] . '%';
            $query = "SELECT books.id AS bid, orders.*, levels.`class`, types.`booktype`, subjects.`subject` FROM `books` LEFT JOIN `orders` ON books.id = orders.bookid LEFT JOIN levels ON books.classid = levels.id LEFT JOIN types on books.typeid = types.id LEFT JOIN subjects ON books.classid = subjects.id WHERE ordernumber = :ordernumber AND (levels.`class` LIKE :searchuse OR types.`booktype` LIKE :searchuse OR subjects.`subject` LIKE :searchuse)";
        } else {
            $query = "SELECT orders.* FROM orders JOIN books ON orders.bookid = books.id WHERE orders.ordernumber = :ordernumber ORDER BY books.subjectid ASC, books.classid ASC, books.typeid ASC;";
        }

        $data = $orders->where_query($query, $arr);

        if (!$data) {
            $data = array();
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'Return';
        $hiddenSearch = "yeap";
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
}
