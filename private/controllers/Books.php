<?php

/**
 * Employees controller
 */
class Books extends Controller
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

        $data1 = array();
        $data = array();
        $book = new Book();
        $subject = new Subject();
        $level = new Level();
        $types = new Type();

        $data1['subject'] = $subject->findAll();
        $data1['level'] = $level->findAll();
        $data1['types'] = $types->findAll();

        if (count($_POST) > 0) {
            if ($book->validate($_POST)) {
                $book->insert($_POST);
                $_SESSION['messsage'] = "Book Added Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
            } else {
                $errors = $book->errors;
                if (isset($errors['bookexist'])) {
                    $_SESSION['messsage'] = $errors['bookexist'];
                    $_SESSION['status_code'] = "warning";
                    $_SESSION['status_headen'] = "Check Well!";
                }
            }
        }

        if (isset($_GET['search_box'])) {
            $arr['searchuse'] = '%' . $_GET['search_box'] . '%';
            $query = "SELECT books.*, subjects.subject, levels.class, types.booktype FROM `books` LEFT JOIN subjects ON books.subjectid = subjects.id LEFT JOIN levels ON books.classid = levels.id LEFT JOIN types ON books.typeid = types.id WHERE subjects.subject LIKE :searchuse OR levels.class LIKE :searchuse OR types.booktype LIKE :searchuse LIMIT $limit OFFSET $offset";

            $data = $book->findSearch($query, $arr);
        } else {
            $data = $book->findAll($limit, $offset, 'DESC');
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'Books';
        $hiddenSearch = "";
        return $this->view('books', [
            'rows' => $data,
            'rows1' => $data1,
            'hiddenSearch' => $hiddenSearch,
            'pager' => $pager,
            'crumbs' => $crumbs,
            'actives' => $actives
        ]);
    }
    function reorderbooks($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 15;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $data1 = array();
        $data = array();
        $book = new Book();

        if (isset($_POST['add'])) {
            if ($book->validate($_POST)) {
                $book->insert($_POST);
                $_SESSION['messsage'] = "Book Added Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
            } else {
                $errors = $book->errors;
                if (isset($errors['bookexist'])) {
                    $_SESSION['messsage'] = $errors['bookexist'];
                    $_SESSION['status_code'] = "warning";
                    $_SESSION['status_headen'] = "Check Well!";
                }
            }
        }

        if (isset($_GET['search_box'])) {
            $arr['searchuse'] = '%' . $_GET['search_box'] . '%';
            $query = "SELECT books.*, subjects.subject, levels.class, types.booktype FROM `books` LEFT JOIN subjects ON books.subjectid = subjects.id LEFT JOIN levels ON books.classid = levels.id LEFT JOIN types ON books.typeid = types.id WHERE (books.`quantity` < books.`treshhold`) AND subjects.subject LIKE :searchuse OR levels.class LIKE :searchuse OR types.booktype LIKE :searchuse LIMIT $limit OFFSET $offset";

            $data = $book->findSearch($query, $arr);
        } else {
            $query = "SELECT * FROM `books` WHERE `quantity` < `treshhold`";
            $data = $book->findSearch($query);
        }

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'Books';
        $hiddenSearch = "";
        return $this->view('books.reorderbooks', [
            'rows' => $data,
            'rows1' => $data1,
            'hiddenSearch' => $hiddenSearch,
            'pager' => $pager,
            'crumbs' => $crumbs,
            'actives' => $actives
        ]);
    }

    function activities($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        // Setting pagination
        $limit = 20;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $data = array();
        $newstock = new Newstock();   
        
        $seasid = $_SESSION['seasondata'] != null ? $_SESSION['seasondata']->id : "";
        $query = "SELECT * FROM `newstocks` WHERE `seasonid` =$seasid ORDER BY id DESC LIMIT $limit OFFSET $offset";
        $data = $newstock->findSearch($query);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', ''];
        $actives = 'logs';
        $hiddenSearch = "";
        return $this->view('books.activities', [
            'rows' => $data,
            'hiddenSearch' => $hiddenSearch,
            'pager' => $pager,
            'crumbs' => $crumbs,
            'actives' => $actives
        ]);
    }

    public function add($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        $book = new Book();
        $newstock = new Newstock();
        $season = new Season();
        $data['season'] = isset($season->selctingId()[0]->id) ? $season->selctingId()[0]->id : '';
        
        $errors = array();
        if (count($_POST) > 0 && Auth::access('stores')) {
            if ($newstock->validate($_POST)) {
                $query = "UPDATE `books` SET `quantity`= `quantity` + :quantity WHERE `id` = :id;";
                $_POST['id'] = $id;
                $book->query($query, $_POST);

                $_POST['bookid'] = $id;
                $_POST['stockofficer'] = Auth::getUsername();
                $_POST['seasonid'] = $data['season'];

                $newstock->insert($_POST);

                $_SESSION['messsage'] = "Book Quantity Added Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
                return $this->redirect('books');
            }
        }

        $data = $book->where('id', $id)[0];

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', 'books'];
        $crumbs[] = ['Add Books', ''];

        $actives = 'Books';
        $hiddenSearch = "";
        if (Auth::access('stores')) {
            return $this->view('books.add', [
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

    public function setprofit($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        $book = new Book();
        
        $errors = array();
        if (count($_POST) > 0 && Auth::access('stores')) {

            $_POST['tithe'] = 0.1 * $_POST['profit'];
            $_POST['id'] = $id;
            
            $query = "UPDATE `books` SET `profit`= :profit, `tithe`= :tithe WHERE `id` = :id;";
            
            $book->query($query, $_POST);

            $_SESSION['messsage'] = "Book Profit & Tithe Set Successfully";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
            return $this->redirect('books');
        }

        $data = $book->where('id', $id)[0];

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', 'books'];
        $crumbs[] = ['Add Books', ''];

        $actives = 'Books';
        $hiddenSearch = "";
        if (Auth::access('stores')) {
            return $this->view('books.setprofit', [
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

    public function damaged($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        $book = new Book();
        $damagedstock = new Damagedstock();
        $season = new Season();
        $data['season'] = isset($season->findAll()[0]->id) ? $season->findAll()[0]->id : '';

        $errors = array();
        if (count($_POST) > 0 && Auth::access('stores')) {
            if ($damagedstock->validate($_POST)) {
                $query = "UPDATE `books` SET `quantity`= `quantity` - :quantity WHERE `id` = :id;";
                $_POST['id'] = $id;
                $book->query($query, $_POST);

                $_POST['bookid'] = $id;
                $_POST['stockofficer'] = Auth::getUsername();
                $_POST['seasonid'] = $data['season'];

                $damagedstock->insert($_POST);

                $_SESSION['messsage'] = "Damaged Book Recorded Successfully";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_headen'] = "Good job!";
                return $this->redirect('books');
            } else {
                $errors = $damagedstock->errors;
                if (isset($errors['quantity'])) {
                    $_SESSION['messsage'] = $errors['quantity'];
                    $_SESSION['status_code'] = "warning";
                    $_SESSION['status_headen'] = "Check Well!";
                }
            }
        }

        $data = $book->where('id', $id)[0];

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Books', 'books'];
        $crumbs[] = ['Add Books', ''];

        $actives = 'Books';
        $hiddenSearch = "";
        if (Auth::access('stores')) {

            return $this->view('books.damaged', [
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

    public function del($id = null)
    {
        if (!Auth::logged_in()) {
            return $this->redirect('login');
        }
        $errors = array();
        $books = new Book();
        $orders = new Order();
        if (count($_POST) > 0 && Auth::access('director')) {
            $books->delete($id);
            $query = "DELETE FROM `orders` WHERE `bookid` ='$id'";
            $orders->query($query);
            $_SESSION['messsage'] = "Book Deleted Successfully";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_headen'] = "Good job!";
            return $this->redirect('books');
        } else {
            $_SESSION['messsage'] = "Book not deleted";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_headen'] = "OOP's!";
        }

        $row = $books->where('id', $id);

        //this are for breadcrumb
        $crumbs[] = ['Dashboard', 'dashboard'];
        $crumbs[] = ['Agents', 'agents'];
        $crumbs[] = ['Delete Agents', ''];

        $actives = 'Books';
        if (Auth::access('director')) {
            return $this->view('books.delete', [
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
