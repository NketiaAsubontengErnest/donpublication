<?php

/**
 * Subjects controller
 */
class Subjects extends Controller
{
    function index($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }

        $subject = new Subject();
        $level = new Level();
        $types = new Type();
        if (Auth::access('stores')) {
            if (isset($_POST['booktype'])) {
                $_POST['booktype'] = strtoupper($_POST['booktype']);
                $types->insert($_POST);
                $_SESSION['messsage'] = "Book Type Added Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
            }

            if (isset($_POST['subject'])) {
                $_POST['subject'] = strtoupper($_POST['subject']);
                $subject->insert($_POST);
                $_SESSION['messsage'] = "Subject Added Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
            }

            if (isset($_POST['class'])) {
                $_POST['class'] = strtoupper($_POST['class']);
                $level->insert($_POST);
                $_SESSION['messsage'] = "Class / Level Added Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
            }
        } else {
            $crumbs[] = ['Access Denied', ''];
            return $this->view('access-denied', ['crumbs' => $crumbs,]);
        }

        $data['subject'] = $subject->findAll();
        $data['levels'] = $level->findAll();
        $data['types'] = $types->findAll();



        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'Books';
        $hiddenSearch = "yeap";
        return $this->view('subject_level', [
            'rows' => $data,
            'crumbs' => $crumbs,
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
        if (count($_POST) > 0 && Auth::access('stores')) {
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

        $actives = 'Books';
        if (Auth::access('director')) {

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

    public function edittype($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        $errors = array();
        $types = new Type();
        if (count($_POST) > 0 && Auth::access('stores')) {
            $_POST['booktype'] = strtoupper($_POST['booktype']);
            $types->update($id, $_POST);
            return $this->redirect('subjects');
        }

        $row = $types->where('id', $id);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Book Type', 'subjests'];
        $crumbs[] = ['Edit Book Type', ''];

        $actives = 'Books';
        if (Auth::access('stores')) {
            $hiddenSearch = "yeap";
            return $this->view('subjects.edittype', [
                'errors' => $errors,
                'crumbs' => $crumbs,
                'hiddenSearch' => $hiddenSearch,
                'rows' => $row,
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

    public function summary($id = null)
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

        $books = new Book();

        if (isset($_GET['startdate'])) {
            $arry['startdate'] = $_GET['startdate'];
            $arry['enddate'] = $_GET['enddate'];
            $query = "SELECT books.* , SUM(orders.quantsupp) AS total_quantity_sold FROM books JOIN orders ON books.id = orders.bookid WHERE orders.verifiedDate BETWEEN :startdate AND :enddate GROUP BY books.id";
            $rows = $books->where_query($query, $arry);
        } else {
            $rows = $books->findAll($limit, $offset);
        }

        if (isset($_POST['exportexl'])) {
            $fields = array('Book', 'Opening stock', 'Quantiry Added', 'Total Quantiry', 'Qty Supplied', 'Actual Supply', 'Sample Qty', 'Qty Returned', 'Closing Instock');
            $excelData = implode("\t", array_values($fields)) . "\n";
            $rowsex = $books->findAll();
            if ($rowsex) {
                foreach ($rowsex as $row) {
                    try {
                        $totalQty = (($row->Openstock) + ($row->ttadded->ttAdded));
                    } catch (\Throwable $th) {
                        $totalQty = 0;
                    }
                    $lineData = array(esc($row->level->class . ' ' . $row->subject->subject . ' ' . $row->booktype->booktype), esc(number_format($row->Openstock)), esc(number_format($row->ttadded->ttAdded)), esc(number_format($totalQty)), esc(number_format($row->ttSupply->ttSupply)), esc(number_format($row->ttSupply->actualSupply)), esc(number_format($row->ttSampleSupply->ttSampleSupply)), esc(number_format($row->ttreturns->ttreturns)), esc(number_format($row->quantity)));
                    $excelData .= implode("\t", array_values($lineData)) . "\n";
                }
                export_data_to_excel($fields, $excelData, 'Books_Report');
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
            $hiddenSearch = "yeap";
            return $this->view('subjects.summary', [
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
    
    public function editlevel($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        $errors = array();
        $level = new Level();
        if (count($_POST) > 0 && Auth::access('stores')) {
            $_POST['class'] = strtoupper($_POST['class']);
            $level->update($id, $_POST);
            $_SESSION['messsage'] = "Class Updated Successfully";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
            return $this->redirect('subjects');
        }

        $row = $level->where('id', $id);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Level', 'subjests'];
        $crumbs[] = ['Edit Level', ''];

        $actives = 'Books';
        $hiddenSearch = "yeap";
        if (Auth::access('stores')) {
            return $this->view('subjects.editlevel', [
                'errors' => $errors,
                'crumbs' => $crumbs,
                'hiddenSearch' => $hiddenSearch,
                'rows' => $row,
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

    public function editsubj($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        $errors = array();
        $subject = new Subject();
        if (count($_POST) > 0 && Auth::access('stores')) {
            $_POST['subject'] = strtoupper($_POST['subject']);
            $subject->update($id, $_POST);
            $_SESSION['messsage'] = "Subject Updated Successfully";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
            return $this->redirect('subjects');
        }

        $row = $subject->where('id', $id);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Subject', 'subjects'];
        $crumbs[] = ['Edit Subject', ''];

        $actives = 'books';
        $hiddenSearch = "yeap";
        if (Auth::access('stores')) {
            return $this->view('subjects.editsubj', [
                'errors' => $errors,
                'crumbs' => $crumbs,
                'hiddenSearch' => $hiddenSearch,
                'rows' => $row,
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

    public function del($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        $errors = array();
        $agents = new Book();
        if (count($_POST) > 0 && Auth::access('director')) {
            $agents->delete($id);
            return $this->redirect('subject');
        }
        $row = $agents->where('id', $id);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Agents', 'agents'];
        $crumbs[] = ['Delete Agents', ''];

        $actives = 'books';
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
}
