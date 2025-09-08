<?php

/**
 * Employees controller
 */
class Employee extends Controller
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

        $employee = new User();
        if (count($_POST) > 0 && Auth::access('director')) {
            if ($employee->validate($_POST)) {
                $employee->insert($_POST);
                $_SESSION['messsage'] = "Employee Added Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
            } else {
                $errors = $employee->errors;
                if (isset($errors['firstname'])) {
                    $_SESSION['messsage'] = $errors['firstname'];
                    $_SESSION['status_code'] = "warning";
                    $_SESSION['status_headen'] = "Check Well!";
                }
                if (isset($errors['lastname'])) {
                    $_SESSION['messsage'] .= $errors['lastname'];
                    $_SESSION['status_code'] = "warning";
                    $_SESSION['status_headen'] = "Check Well!";
                }
            }
        }

        if (isset($_GET['search_box'])) {
            $searching = '%' . $_GET['search_box'] . '%';
            $arr['search'] = $searching;
            $query = "SELECT * FROM `users` WHERE `firstname` LIKE :search OR `lastname` LIKE :search OR `phone` LIKE :search OR `username` LIKE :search LIMIT $limit OFFSET $offset";
            $data = $employee->query($query, $arr);
            $data = $employee->get_Officer($data);
            $data = $employee->get_count_customer($data);
        } else {
            $data = $employee->findAll($limit, $offset);
        }


        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Employee', ''];
        $actives = 'Employee';
        $hiddenSearch = "";
        return $this->view('employees', [
            'rows' => $data,
            'crumbs' => $crumbs,
            'pager' => $pager,
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
        $employee = new User();
        if (count($_POST) > 0 && Auth::access('director')) {
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

        $actives = 'employee';
        if (Auth::access('director')) {

            return $this->view('employee.add', [
                'errors' => $errors,
                'crumbs' => $crumbs,
                'actives' => $actives
            ]);
        } else {
            $crumbs[] = ['Access Denied', ''];
            return $this->view('access-denied', [
                '
            crumbs' => $crumbs,
                'actives' => $actives
            ]);
        }
    }

    public function transfer($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }

        $errors = array();
        $customers = array();

        $customer = new Customer();
        $orders = new Order();
        $payment = new Payment();
        $marketer = new User();

        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";

        if (count($_POST) > 0 && Auth::access('director')) {
            if (isset($_POST['hidden_customer_id'])) {
                for ($count = 0; $count < count($_POST['hidden_customer_id']); $count++) {
                    $data = array(
                        'officerid' => $_POST['officerid'],
                    );

                    $ids = $_POST['hidden_customer_id'][$count];
                    $customer->update($ids, $data);
                    $orders->query("UPDATE `orders` SET `officerid`=:officerid WHERE `seasonid` ={$seasid} AND `customerid` = $ids", $data);
                    $payment->query("UPDATE `payments` SET `officerid`=:officerid WHERE `seasonid` ={$seasid} AND `customerid` = $ids", $data);
                }
                $_SESSION['messsage'] = "Customers Transfered Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
                return $this->redirect("/employee");
            } else {
                $errors = $customer->errors;
                $_SESSION['messsage'] = "Customers Not Transfered";
                $_SESSION['status_code'] = "error";
                $_SESSION['status_headen'] = "OOp's!";
            }
        }

        $rows = $marketer->where('id', $id)[0];

        $customers = $customer->where('officerid', $id);
        $marketers = $marketer->where('rank', 'marketer');
        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Employee', 'employee'];
        $crumbs[] = ['Employee', ''];
        $hiddenSearch = "yeap";
        $actives = 'employee';

        if (Auth::access('director')) {
            return $this->view('employee.transfer', [
                'errors' => $errors,
                'crumbs' => $crumbs,
                'user' => $rows,
                'hiddenSearch' => $hiddenSearch,
                'rows' => $customers,
                'rowc' => $marketers,
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
        $employee = new User();

        if (count($_POST) > 0 && Auth::access('director') && isset($_POST['reset'])) {
            unset($_POST['reset']);
            $_POST = $employee->hash_password($_POST);
            $employee->update($id, $_POST);
            $_SESSION['messsage'] = "Employee Password Reset Successfully";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
        } elseif (count($_POST) > 0 && Auth::access('director')) {
            $employee->update($id, $_POST);
            $_SESSION['messsage'] = "Employee Updated Successfully";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
            return $this->redirect('employee');
        }

        $data = $employee->where('id', $id);

        $hiddenSearch = "yep";
        $actives = 'employee';

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Employee', 'employees'];
        $crumbs[] = ['Edit Employee', ''];

        if (Auth::access('director')) {
            return $this->view('employees.edit', [
                'errors' => $errors,
                'crumbs' => $crumbs,
                'rows' => $data,
                'hiddenSearch' => $hiddenSearch,
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

    public function blockuser($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        $user = new User();
        $row = $user->where('id', $id);

        if (count($_POST) > 0 && Auth::access('director')) {
            if ($row[0]->status == 0 && $row[0]->rank != 'director') {
                $query = "UPDATE `users` SET `status`='1' WHERE `id` = $id";
            } else {
                $query = "UPDATE `users` SET `status`='0' WHERE `id` = $id";
            }
            $user->query($query);
            $_SESSION['messsage'] = "Employee Blocked Successfully";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
            return $this->redirect('employee');
        }

        $hiddenSearch = "yep";
        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Agents', 'agents'];
        $crumbs[] = ['Delete Agents', ''];

        $actives = 'employee';
        if (Auth::access('director')) {
            return $this->view('employee.blockuser', [
                'rows' => $row,
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
    public function assign($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }

        $errors = array();
        $employee = new User();


        $row = $employee->where('id', $id);
        $query = "SELECT * FROM `users` WHERE `rank` = 'verification' OR `rank` = 'account'";
        $row['officers'] = $employee->query($query);

        if (count($_POST) > 0 && Auth::access('director')) {
            $employee->update($id, $_POST);
            $_SESSION['messsage'] = "Marketer Assign Successfully";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
            return $this->redirect('employee');
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Agents', 'agents'];
        $crumbs[] = ['Delete Agents', ''];

        $hiddenSearch = "yep";
        $actives = 'employee';
        if (Auth::access('director')) {
            return $this->view('employee.assign', [
                'rows' => $row,
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
}
