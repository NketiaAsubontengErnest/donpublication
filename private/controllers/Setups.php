<?php

/**
 * Setups controller
 */
class Setups extends Controller
{
    function season($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 20;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $seas = new Season();
        $books = new Book();

        if (count($_POST) > 0 && Auth::access('director')) {
            if (isset($_POST['dels'])) {
                $seas->delete($_POST['dels']);
                $_SESSION['messsage'] = "Seasson Deleted Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
            } else
            if ($seas->validate($_POST)) {
                $seasid = isset($seas->selctingLastId()[0]->id) ? $seas->selctingLastId()[0]->id : '';
                $subdata['SeasonStatus'] = "END";
                $_POST['seasonid'] = $seasid;
                $seas->update($seasid, $subdata);
                $_POST['seasonname'] = strtoupper($_POST['seasonname']);
                $seas->insert($_POST);
                $books->query("UPDATE `books` SET `Openstock` = `quantity`");
                $_SESSION['messsage'] = "Seasson Added Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
            } else {
                $errors = $seas->errors;
                $_SESSION['messsage'] = "Seasson Not Added Successfully";
                $_SESSION['status_code'] = "error";
                $_SESSION['status_headen'] = "OOP's!";
            }
        }

        $data = $seas->findAll($limit, offset: $offset);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Season', ''];
        $actives = 'Season';
        $hiddenSearch = "yeap";
        if (Auth::access('stores')) {
            return $this->view('setups.season', [
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
    function activitie($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }

        // Setting pagination
        $limit = 20;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $acti = new Activitylog();

        if (count($_POST) > 0) {
            $query = "UPDATE `activitylogs` SET `loclink`='' WHERE `id`=:actdone";
            $acti->query($query, $_POST);
        }

        $data = $acti->findAll($limit, offset: $offset, rotations:"DESC");

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Season', ''];
        $actives = 'logs';
        $hiddenSearch = "yeap";
        if (Auth::access('g-account')) {
            return $this->view('setups.activitie', [
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

    public function ordertype()
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        $errors = array();
        $type = new Ordertype();
        $orders = new Order();
        if (count($_POST) > 0 && Auth::access('director')) {

            if (isset($_POST['activs'])) {
                $dd['typestatus'] = '1';
                $type->update($_POST['activs'], $dd);
                $_SESSION['messsage'] = "Order Type Updated Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
            } else
            if (isset($_POST['block'])) {
                $dd['typestatus'] = '0';
                $type->update($_POST['block'], $dd);
                $_SESSION['messsage'] = "Order Type Blocked Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
            } else
            if (isset($_POST['dels'])) {
                $type->delete($_POST['dels']);
                $ids = $_POST['dels'];
                $query = "DELETE FROM `orders` WHERE `ordertype` ='$ids'";
                $orders->query($query);
                $_SESSION['messsage'] = "Order Type Deleted Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
            } else
            if ($type->validate($_POST)) {
                $type->insert($_POST);
                $_SESSION['messsage'] = "Order Type Added Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
            } else {
                $errors = $type->errors;
                $_SESSION['messsage'] = "Operation Not Successfull";
                $_SESSION['status_code'] = "error";
                $_SESSION['status_headen'] = "OOP's!";
            }
        }

        $data = $type->findAll();

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Setup', 'setup'];
        $crumbs[] = ['Ordertyoe', ''];
        $actives = 'setup';
        $hiddenSearch = "yeap";
        if (Auth::access('director')) {
            return $this->view('setups.ordertype', [
                'errors' => $errors,
                'crumbs' => $crumbs,
                'hiddenSearch' => $hiddenSearch,
                'rows' => $data,
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
        $agents = new User();
        if (count($_POST) > 0 && Auth::access('director')) {
            $_POST['editing'] = "Edit";
            if ($agents->validate($_POST)) {
                $agents->update($id, $_POST);
                return $this->redirect('agents');
            } else {
                $errors = $agents->errors;
            }
        }

        $row = $agents->where('id', $id);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Agents', 'agents'];
        $crumbs[] = ['Edit Agents', ''];

        $actives = 'setup';
        if (Auth::access('director')) {
            return $this->view('agents.edit', [
                'errors' => $errors,
                'crumbs' => $crumbs,
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

        $actives = 'setup';
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
