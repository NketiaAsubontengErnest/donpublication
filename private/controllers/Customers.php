<?php

/**
 * Customers controller
 */
class Customers extends Controller
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

        $errors = array();

        $customer = new Customer();

        if (isset($_POST['delcust']) && count($_POST) > 0) {
            $customer->delete($_POST['delcust']);
            $_SESSION['messsage'] = "Customer Deleted Successfully";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
        } elseif (count($_POST) > 0 && Auth::access('marketer')) {
            if ($customer->validate($_POST)) {
                $customer->insert($_POST);
                $_SESSION['messsage'] = "Customer Added Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
            } else {
                $errors = $customer->errors;
                if (isset($errors['custphone'])) {
                    $_SESSION['messsage'] = $errors['custphone'];
                    $_SESSION['status_code'] = "warning";
                    $_SESSION['status_headen'] = "Check Well!";
                }
                if (isset($errors['customername'])) {
                    $_SESSION['messsage'] .= $errors['customername'];
                    $_SESSION['status_code'] = "warning";
                    $_SESSION['status_headen'] = "Check Well!";
                }
                if (isset($errors['custtype'])) {
                    $_SESSION['messsage'] .= $errors['custtype'];
                    $_SESSION['status_code'] = "warning";
                    $_SESSION['status_headen'] = "Check Well!";
                }
                if (isset($errors['region'])) {
                    $_SESSION['messsage'] = $errors['region'];
                    $_SESSION['status_code'] = "warning";
                    $_SESSION['status_headen'] = "Check Well!";
                }
            }
        }

        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            $arr['search'] = $searching;
            $arr['officerid'] = Auth::getId();
            $query = "SELECT * FROM `customers` WHERE `officerid` =:officerid AND (`customername` LIKE :search OR `custphone` LIKE :search) LIMIT $limit OFFSET $offset";
            $data = $customer->query($query, $arr);
        } else {
            $data = $customer->where('officerid', Auth::getId(), $limit, $offset);
        }


        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Customers', ''];
        $actives = 'customers';
        $hiddenSearch = "";
        return $this->view('customers', [
            'rows' => $data,
            'crumbs' => $crumbs,
            'pager' => $pager,
            'hiddenSearch' => $hiddenSearch,
            'actives' => $actives
        ]);
    }
    function visited($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }

        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $customer = new Customer();
        $errors = array();
        $newcus = array();
        $alldata = array();

        $visitors = new Visitor();

        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        if (isset($_POST['movetocustomer'])) {
            $vis = $visitors->where('id', $_POST['movetocustomer'])[0];

            //$newcus = $vis;
            $newcus['officerid'] = $vis->officerid;
            $newcus['customername'] = $vis->customername;
            $newcus['custphone'] = $vis->custphone;
            $newcus['custlocation'] = $vis->custlocation;
            $newcus['region'] = $vis->region;
            $newcus['custtype'] = 'school';
            if ($customer->validate($newcus)) {
                $customer->insert($newcus);
                $dd['donedata'] = 1;
                $visitors->update($vis->id, $dd);
                $_SESSION['messsage'] = "Customer Transfered Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
                return $this->redirect('customers');
            } else {
                $_SESSION['messsage'] = "Customer Not Transfered or already exist";
                $_SESSION['status_code'] = "warning";
                $_SESSION['status_headen'] = "Check Well!";
            }
        }

        if (isset($_POST['exportexl'])) {
            $query = "SELECT * FROM `visitors` WHERE `seasonid` =$seasid";
            $data1 = $visitors->query($query);

            $data1 = $visitors->get_Officer($data1);

            $fields = array('Customer Name', 'Contact Person', 'Phone Number', 'Location', 'Region', 'Marketer', 'Work Book', 'Text Book', 'Total Books', 'Visit Type', 'Date');
            $excelData = implode("\t", array_values($fields)) . "\n";
            if ($data1) {
                foreach ($data1 as $row) {
                    try {
                        $totalQty = (($row->workbook) + ($row->textbook));
                    } catch (\Throwable $th) {
                        $totalQty = 0;
                    }
                    $lineData = array(esc($row->customername), esc($row->contactperson), esc($row->custphone), esc($row->custlocation), esc($row->region), esc($row->marketer->firstname) . " " . esc($row->marketer->lastname), esc(number_format($row->workbook)), esc(number_format($row->textbook)), esc(number_format($totalQty)), esc($row->visittype), esc($row->dateadded));
                    $excelData .= implode("\t", array_values($lineData)) . "\n";
                }
                export_data_to_excel($fields, $excelData, 'Sample_Report');
            } {
                $excelData .= 'No records found...' . "\n";
            }
        }

        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            $arr['search'] = $searching;
            $query = "SELECT * FROM `visitors` WHERE (`customername` LIKE :search OR `custphone` LIKE :search) AND `seasonid` = $seasid LIMIT $limit OFFSET $offset";
            $data = $visitors->query($query, $arr);

            $query1 = "SELECT `officerid`, `customername`, `custphone`, `custlocation`, `region`, 'Customer' AS `status` FROM customers WHERE `customername` LIKE :search OR `custphone` LIKE :search UNION SELECT `officerid`, `customername`, `custphone`, `custlocation`, `region`, 'Visited' AS `status` FROM visitors WHERE (`customername` LIKE :search OR `custphone` LIKE :search) AND `seasonid` = $seasid; ";
            $alldata = $visitors->query($query1, $arr);

            $alldata = $visitors->get_Officer($alldata);
        } else {
            $query = "SELECT * FROM `visitors` WHERE `seasonid` = $seasid";
            $data1 = $visitors->query($query);
            if (Auth::access('marketer')) {
                $arr['useris'] = Auth::getID();
                $query = "SELECT * FROM `visitors` WHERE (`officerid` = :useris OR `withother` = :useris) AND `seasonid` = $seasid LIMIT $limit OFFSET $offset";
                $data = $visitors->query($query, $arr);
            }

            if (Auth::access('stores')) {
                $arr['useris'] = Auth::getID();
                $query = "SELECT * FROM `visitors` WHERE `seasonid` = $seasid LIMIT $limit OFFSET $offset";
                $data = $visitors->query($query);
            }

            $alldata = array();
        }

        $data = $visitors->get_Officer($data);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Customers', ''];
        $actives = 'marketers';
        $hiddenSearch = "";
        return $this->view('customers.visited', [
            'rows' => $data,
            'alldata' => $alldata,
            'crumbs' => $crumbs,
            'pager' => $pager,
            'hiddenSearch' => $hiddenSearch,
            'actives' => $actives
        ]);
    }

    function smssending($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }

        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $marketers = new User();
        $customer = new Customer();
        $alldata = array();

        $data = array();
        $phoneNumbers = [];
        $phoneNumbersString = "";

        $visitors = new Visitor();

        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";


        if (isset($_GET['fetchNumbers'])) {

            $tableName = $_GET['tableName'];
            $dateFrom = $_GET['dateFrom'];
            $dateTo = $_GET['dateTo'];

            if ($tableName == 'visitors') {
                $data = $visitors->query("SELECT custphone FROM $tableName WHERE dateadded BETWEEN :dateFrom AND :dateTo", [
                    'dateFrom' => $dateFrom,
                    'dateTo' => $dateTo
                ]);
            } else if ($tableName == 'customers') {
                $data = $customer->query("SELECT custphone FROM $tableName");
            }

            $phoneNumbers = [];

            if (!is_array($data)) {
                $data = [];
            }

            foreach ($data as $row) {
                if (strlen($row->custphone) == 10) {
                    $phoneNumbers[] = $row->custphone;
                }
            }

            $phoneNumbersString = implode(', ', $phoneNumbers);
        } else {
            $data = $visitors->query("SELECT custphone FROM visitors WHERE dateadded =:dateFrom", [
                'dateFrom' => date('Y-m-d'),
            ]);

            if (!is_array($data)) {
                $data = [];
            }
            foreach ($data as $row) {
                if (strlen($row->custphone) == 10) {
                    $phoneNumbers[] = $row->custphone;
                }
            }

            $phoneNumbersString = implode(', ', $phoneNumbers);
        }

        if (isset($_POST['testsms'])) {
            $phoneNumbersString = $_POST['phoneNumber'];
            $message = $_POST['individualMessage'];
            $response = singlesendSms($message, $phoneNumbersString);
            $status = $response['code'];
            if (!$status == 'ok') {
                $_SESSION['messsage'] = "SMS sending failed " . $response['message'];
                $_SESSION['status_code'] = "error";
                $_SESSION['status_headen'] = "Oops!";
            } else {
                $_SESSION['messsage'] = "SMS sent successfully!";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
            }
        } elseif (isset($_POST['sendIndividualSMS'])) {
            unset($_POST['sendIndividualSMS']);

            $tableName = $_POST['tableName'];

            $response = [];

            $dataOfficer = $marketers->where('rank', 'marketer');

            foreach ($dataOfficer as $rowOfficer) {

                if ($tableName == 'visitors') {
                    $data = $visitors->query("SELECT custphone FROM $tableName where  custphone != '' AND `seasonid` = :seasonid AND `officerid` = :officerid", ['seasonid' => $seasid, 'officerid' => $rowOfficer->id]);
                } else if ($tableName == 'customers') {
                    $data = $customer->query("SELECT custphone FROM $tableName where custphone != '' AND `officerid` = :officerid", ['officerid' => $rowOfficer->id]);
                }

                $marketerName = $rowOfficer->firstname ?? "Don"; // fallback from row itself
                $marketerPhone = $rowOfficer->phone ?? "0554013980";   // corrected to use phone field

                $message = "Hello! Place your order now, kindly contact our marketer on $marketerPhone. $marketerName will be happy to assist you with your needs and finalize your order.";

                foreach ($data as $row) {
                    if (strlen($row->custphone) == 10) {
                        $phoneNumbers[] = $row->custphone;
                    }
                }

                $response = sendSms($message, $phoneNumbers);

                $phoneNumbers = [];
            }

            $status = $response['status'];

            if (!$status == 'success') {
                $_SESSION['messsage'] = "SMS sending failed " . $response['message'];
                $_SESSION['status_code'] = "error";
                $_SESSION['status_headen'] = "Oops!";
            } else {
                $_SESSION['messsage'] = "SMS sent successfully!";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
            }

            $this->redirect('customers/smssending');
        } elseif (isset($_POST['sendSMS'])) {
            unset($_POST['sendSMS']);

            $message = $_POST['message'];
            $phoneNumbers = array_map('trim', explode(',', $_POST['phoneNumbers']));



            $response = sendSms($message, $phoneNumbers);

            // Access the status
            $status = $response['status'];

            if (!$status == 'success') {
                $_SESSION['messsage'] = "SMS sending failed " . $response['message'];
                $_SESSION['status_code'] = "error";
                $_SESSION['status_headen'] = "Oops!";
            } else {
                $_SESSION['messsage'] = "SMS sent successfully!";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
            }
            $this->redirect('customers/smssending');
        }



        $balancedata = checkSmsBalance();

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Customers', ''];
        $actives = 'marketers';
        $hiddenSearch = "";
        return $this->view('customers.smssending', [
            'crumbs' => $crumbs,
            'pager' => $pager,
            'balance' => $balancedata,
            'phoneNumbersString' => $phoneNumbersString,
            'hiddenSearch' => $hiddenSearch,
            'actives' => $actives
        ]);
    }

    public function add()
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        $errors = array();
        if (count($_POST) > 0 && Auth::access('maketer')) {
            $employee = new User();
            if ($employee->validate($_POST)) {

                $employee->insert($_POST);
                return $this->redirect('employee');
            } else {
                $errors = $employee->errors;
            }
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Employee', 'Employee'];
        $crumbs[] = ['Add Employee', ''];

        $actives = 'customers';
        if (Auth::access('maketer')) {

            return $this->view('employee.add', [
                'errors' => $errors,
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
    public function visitededit($id = '')
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }

        $data = array();
        $visitors = new Visitor();
        $errors = array();
        if (count($_POST) > 0) {
            if ($_POST['textbook'] != '') {
                $_POST['textbook'] = $_POST['textbook'] + $_POST['oldtextbook'];
            } else {
                $_POST['textbook'] = $_POST['oldtextbook'];
            }
            if ($_POST['workbook'] != '') {
                $_POST['workbook'] = $_POST['workbook'] + $_POST['oldworkbook'];
            } else {
                $_POST['workbook'] = $_POST['oldworkbook'];
            }
            unset($_POST['oldworkbook']);
            unset($_POST['oldtextbook']);

            $visitors->update($id, $_POST);
            $_SESSION['messsage'] = "Visitor Updated Successfully";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
            return $this->redirect('customers/visited');
        } else {
            $errors = $visitors->errors;
            $_SESSION['messsage'] = $errors;
            $_SESSION['status_code'] = "error";
            $_SESSION['status_headen'] = "Opps!";
        }

        $data = [];
        if ($id) {
            $data = $visitors->where('id', $id)[0];
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Customers', 'Customers'];
        $crumbs[] = ['Visisters', ''];

        $actives = 'smpcustomers';
        $hiddenSearch = "";

        return $this->view('customers.visitededit', [
            'errors' => $errors,
            'crumbs' => $crumbs,
            'row' => $data,
            'hiddenSearch' => $hiddenSearch,
            'actives' => $actives
        ]);
    }

    public function visitedit($id = '')
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }

        $data = array();
        $visitors = new Visitor();
        $errors = array();
        if (count($_POST) > 0) {
            if ($_POST['textbook'] != '') {
                $_POST['textbook'] = $_POST['textbook'];
            } else {
                $_POST['textbook'] = $_POST['oldtextbook'];
            }
            if ($_POST['workbook'] != '') {
                $_POST['workbook'] = $_POST['workbook'];
            } else {
                $_POST['workbook'] = $_POST['oldworkbook'];
            }
            unset($_POST['oldworkbook']);
            unset($_POST['oldtextbook']);

            $visitors->update($id, $_POST);
            $_SESSION['messsage'] = "Visitor Edited Successfully";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";

            return $this->redirect('customers/visited');
        } else {
            $errors = $visitors->errors;
            $_SESSION['messsage'] = $errors;
            $_SESSION['status_code'] = "error";
            $_SESSION['status_headen'] = "Opps!";
        }

        $data = [];
        if ($id) {
            $data = $visitors->where('id', $id)[0];
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Customers', 'Customers'];
        $crumbs[] = ['Visisters', ''];

        $actives = 'smpcustomers';
        $hiddenSearch = "";

        return $this->view('customers.visitedit', [
            'errors' => $errors,
            'crumbs' => $crumbs,
            'row' => $data,
            'hiddenSearch' => $hiddenSearch,
            'actives' => $actives
        ]);
    }

    public function addvisited($id = '')
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }

        $data = array();
        $customers = new Customer();
        $visitors = new Visitor();
        $season = new Season();

        //get current Season
        $ss = $season->findAll()[0];
        $datas['season'] = isset($ss) ? $ss : '';

        $errors = array();
        if (count($_POST) > 0) {
            $_POST['seasonid'] = isset($season->selctingLastId()[0]->id) ? $season->selctingLastId()[0]->id : '';
            $_POST['officerid'] = Auth::getId();

            if ($visitors->validate($_POST)) {
                $visitors->insert($_POST);
                $_SESSION['messsage'] = "Customer Added Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
                return $this->redirect('customers/visited');
            } else {
                $errors = $visitors->errors;
                $_SESSION['messsage'] = trim($errors['errors'], ', ');
                $_SESSION['status_code'] = "error";
                $_SESSION['status_headen'] = "Opps!";
            }
        }

        $data = [];
        if ($id) {
            $data = $customers->where('id', $id)[0];
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Customers', 'Customers'];
        $crumbs[] = ['Visisters', ''];

        $actives = 'smpcustomers';
        $hiddenSearch = "";

        return $this->view('customers.addvisited', [
            'errors' => $errors,
            'crumbs' => $crumbs,
            'row' => $data,
            'hiddenSearch' => $hiddenSearch,
            'actives' => $actives
        ]);
    }

    public function booksdetails($id = '')
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 20;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $errors = array();

        $books = new Book();
        $custs = new Customer();

        $dataCkust = $custs->where('id', $id)[0];

        $row = $books->findAll($limit, $offset);
        $row = $books->get_Customers_suplies($row, $id);

        if (isset($_POST['exportexl'])) {
            $data1 = $books->findAll();
            $data1 = $books->get_Customers_suplies($data1, $id);

            $fields = array('Book', 'Qty Supplied', 'Qty Returned',  'Total Supply');
            $excelData = implode("\t", array_values($fields)) . "\n";
            if ($data1) {
                foreach ($data1 as $row) {
                    $lineData = array(esc($row->level->class . ' ' . $row->subject->subject . ' ' . $row->booktype->booktype), esc(number_format($row->ttCustreturns->grosquant)), esc(number_format($row->ttCustreturns->ret_quant)), esc(number_format($row->ttCustreturns->grosquant - $row->ttCustreturns->ret_quant)));
                    $excelData .= implode("\t", array_values($lineData)) . "\n";
                }
                export_data_to_excel($fields, $excelData, 'Books_Report_for_' . $dataCkust->customername);
            } else {
                $excelData .= 'No records found...' . "\n";
            }
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Book Sample', ''];
        $crumbs[] = ['Edit Book Type', ''];
        $actives = 'marketers';
        if (Auth::access('stores')) {
            $hiddenSearch = "yeap";
            return $this->view('customers.booksdetails', [
                'errors' => $errors,
                'crumbs' => $crumbs,
                'hiddenSearch' => $hiddenSearch,
                'pager' => $pager,
                'rows' => $row,
                'rowCust' => $dataCkust,
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

    public function addmarkvisited($id = '')
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 20;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $errors = array();

        $books = new Book();
        $users = new User();

        $use = $users->where('id', $id)[0]->firstname;

        $row = $books->findAll($limit, $offset);
        $row = $books->get_Maketer_Sample_Supply($row, $id);
        $row = $books->get_Makerter_Supply($row, $id);
        $row = $books->get_Makerter_Returns($row, $id);

        if (isset($_POST['exportexl'])) {
            $data1 = $books->findAll();
            $data1 = $books->get_Maketer_Sample_Supply($data1, $id);
            $data1 = $books->get_Makerter_Supply($data1, $id);
            $data1 = $books->get_Makerter_Returns($data1, $id);

            $fields = array('Book', 'Qty Supplied', 'Sample Qty', 'Qty Returned', 'Sample Ret', 'Total Supply', 'Total sample', 'Total Gross', 'Net Return', 'Total Net');
            $excelData = implode("\t", array_values($fields)) . "\n";
            if ($data1) {
                foreach ($data1 as $row) {
                    $lineData = array(esc($row->level->class . ' ' . $row->subject->subject . ' ' . $row->booktype->booktype), esc(number_format($row->ttMarketSupply->ttSupply)), esc(number_format($row->ttMarkSampleSupply->ttMarkSampleSupply)), esc(number_format($row->ttmarketreturns->ttmarketreturns)), esc(number_format($row->ttmarketreturns->retsample)), esc(number_format($row->ttMarketSupply->actualSupply)), esc(number_format($row->ttMarkSampleSupply->ttMarkSampleSupply - $row->ttmarketreturns->retsample)), esc(number_format($row->ttMarketSupply->book_gross, 2)), esc(number_format($row->ttMarketSupply->return_net, 2)), esc(number_format($row->ttMarketSupply->book_net, 2)));
                    $excelData .= implode("\t", array_values($lineData)) . "\n";
                }
                export_data_to_excel($fields, $excelData, $use . '_Individual_Books_Report');
            } else {
                $excelData .= 'No records found...' . "\n";
            }
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Book Sample', ''];
        $crumbs[] = ['Edit Book Type', ''];
        $actives = 'marketers';

        $hiddenSearch = "yeap";
        return $this->view('customers.addmarkvisited', [
            'errors' => $errors,
            'crumbs' => $crumbs,
            'hiddenSearch' => $hiddenSearch,
            'pager' => $pager,
            'rows' => $row,
            'maketer' => $use,
            'actives' => $actives
        ]);
    }

    public function specialssupply($id = '')
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 20;
        $pager = new Pager($limit);
        $offset = $pager->offset;


        $errors = array();

        $books = new Book();

        $row = $books->findAll($limit, $offset);
        $row = $books->get_Special_Supply($row, $_GET['type']);
        $row = $books->get_Special_Returns($row, $_GET['type']);

        if (isset($_POST['exportexl'])) {
            $data1 = $books->findAll();
            $data1 = $books->get_Special_Supply($data1, $_GET['type']);
            $data1 = $books->get_Special_Returns($data1, $_GET['type']);

            $fields = array('Book', 'Qty Supplied',  'Qty Returned', 'Total Supply');
            $excelData = implode("\t", array_values($fields)) . "\n";
            if ($data1) {
                foreach ($data1 as $row) {
                    $lineData = array(esc($row->level->class . ' ' . $row->subject->subject . ' ' . $row->booktype->booktype), esc(number_format($row->ttSpecialSupply->ttSpecialSupply)), esc(number_format($row->ttSpecialreturns->ttSpecialreturns)), esc(number_format($row->ttSpecialSupply->ttSpecialSupply - $row->ttSpecialreturns->ttSpecialreturns)));
                    $excelData .= implode("\t", array_values($lineData)) . "\n";
                }
                export_data_to_excel($fields, $excelData, 'Books_Report_for_' . $_GET['type']);
            } else {
                $excelData .= 'No records found...' . "\n";
            }
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Book Sample', ''];
        $crumbs[] = ['Edit Book Type', ''];
        if (Auth::access('stores')) {
            $actives = 'Books';
            $hiddenSearch = "yeap";
            return $this->view('customers.specialssupply', [
                'errors' => $errors,
                'crumbs' => $crumbs,
                'hiddenSearch' => $hiddenSearch,
                'pager' => $pager,
                'rows' => $row,
                'actives' => $actives
            ]);
        } else {
            $crumbs[] = ['Access Denied', ''];
            return $this->view('access-denied', ['crumbs' => $crumbs,]);
        }
    }

    public function market()
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }

        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $marketers = new User();
        $data1 = array();

        if (isset($_POST['exportexl'])) {
            $data1 = $marketers->where('rank', 'marketer', rotations: "ASC");
            $data1 = $marketers->get_total_samp_books($data1);
            $data1 = $marketers->get_total_books_Shared($data1);

            $fields = array('Marketer', 'Total Book(s) Taken Out', 'No. of School', 'No. of Workbook(s)', 'No. of Textbook(s)', 'Total Book(s) Shared');
            $excelData = implode("\t", array_values($fields)) . "\n";
            if ($data1) {
                foreach ($data1 as $row) {

                    $lineData = array(
                        ucfirst(esc($row->firstname)) . " " . ucfirst(esc($row->lastname)),
                        esc(isset($row->totalBooks->quantsupp) ? $row->totalBooks->quantsupp : 0),
                        esc(isset($row->visitors->visitor) ? $row->visitors->visitor : 0),
                        esc(isset($row->ttshered->ttworkbook) ? $row->ttshered->ttworkbook : 0),
                        esc(isset($row->ttshered->tttextbook) ? $row->ttshered->tttextbook : 0),
                        esc(isset($row->ttshered->ttshered) ? $row->ttshered->ttshered : 0),
                    );
                    $excelData .= implode("\t", array_values($lineData)) . "\n";
                }
                export_data_to_excel($fields, $excelData, 'Shared_table_Report');
            } {
                $excelData .= 'No records found...' . "\n";
            }
        }

        if (Auth::getRank() == 'marketer') {
            $data = $marketers->where('id', Auth::getId());
        } else {
            $data = $marketers->where('rank', 'marketer', rotations: "ASC", limit: $limit, offset: $offset);
        }

        $data = $marketers->get_total_samp_books($data);
        $data = $marketers->get_total_books_Shared($data);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Marketers', 'Marketers'];
        $crumbs[] = ['Marketers', ''];

        $actives = 'marketers';
        $hiddenSearch = "";

        return $this->view('customers.market', [
            'crumbs' => $crumbs,
            'rows' => $data,
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
        $errors = array();
        $cust = new Customer();

        if (count($_POST) > 0 && Auth::access('marketer')) {
            $cust->update($id, $_POST);
            $_SESSION['messsage'] = "Customer Updated Successfully";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
            return $this->redirect('customers');
        }
        $data = $cust->where('id', $id);

        $hiddenSearch = "yeap";
        $actives = 'customers';
        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Agents', 'agents'];
        $crumbs[] = ['Edit Agents', ''];
        if (Auth::access('marketer')) {

            return $this->view('customers.edit', [
                'errors' => $errors,
                'hiddenSearch' => $hiddenSearch,
                'crumbs' => $crumbs,
                'rows' => $data,
                'actives' => $actives
            ]);
        } else {
            $crumbs[] = ['Access Denied', ''];
            return $this->view('access-denied', [
                'crumbs' => $crumbs,
                'actives' => $actives,
                'hiddenSearch' => $hiddenSearch
            ]);
        }
    }

    public function del($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        $errors = array();
        $agents = new User();
        if (count($_POST) > 0 && Auth::access('maketer')) {
            $agents->delete($id);
            $_SESSION['messsage'] = "Customer Deleted Successfully";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
            return $this->redirect('customers');
        }
        $row = $agents->where('id', $id);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Agents', 'agents'];
        $crumbs[] = ['Delete Agents', ''];

        $actives = 'customers';
        if (Auth::access('maketer')) {
            return $this->view('customers.delete', [
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
}
