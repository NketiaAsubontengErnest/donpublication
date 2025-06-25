<?php

/**
 * home controller
 */

class Home extends Controller
{
    function index()
    {
        $data1 = array();
        $data = array();

        $actives = 'home';
        $hiddenSearch  = '';
        $crumbs  = array();
        $this->view('home', [
            'rows1' => $data1,
            'rows' => $data,
            'crumbs' => $crumbs,
            'hiddenSearch' => $hiddenSearch,
            'actives' => $actives
        ]);
    }

    function contact()
    {
        $data1 = array();
        $data = array();

        $arr = array();

        $msg = new Message();
        if (count($_POST) > 0) {
            if ($msg->validate($_POST)) {
                $msg->insert($_POST);
            }
        }

        $actives = 'contact';
        $hiddenSearch  = '';
        $crumbs  = array();
        $this->view('home.contact', [
            'rows1' => $data1,
            'rows' => $data,
            'crumbs' => $crumbs,
            'hiddenSearch' => $hiddenSearch,
            'actives' => $actives
        ]);
    }

    function shop()
    {
        $data1 = array();
        $data = array();

        $arr = array();

        $msg = new Message();
        if (count($_POST) > 0) {
            if ($msg->validate($_POST)) {
                $msg->insert($_POST);
            }
        }

        $actives = 'shop';
        $hiddenSearch  = '';
        $crumbs  = array();
        $this->view('home.shop', [
            'rows1' => $data1,
            'rows' => $data,
            'crumbs' => $crumbs,
            'hiddenSearch' => $hiddenSearch,
            'actives' => $actives
        ]);
    }

    function about()
    {
        $data1 = array();
        $data = array();

        $actives = 'about';
        $hiddenSearch  = '';
        $crumbs  = array();
        $this->view('home.about', [
            'rows1' => $data1,
            'rows' => $data,
            'crumbs' => $crumbs,
            'hiddenSearch' => $hiddenSearch,
            'actives' => $actives
        ]);
    }
}
