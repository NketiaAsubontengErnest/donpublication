<?php
/**
 * home controller
 */
class Home extends Controller{
    function index(){
        $data1 = array();
        $data = array(); 
        $arr = array();

        $msg = new Message();
        if (count($_POST)> 0){
            if($msg->validate($_POST)){
                $msg->insert($_POST);
            }
        }
        
        $actives = 'home';
        $hiddenSearch  = '';
        $crumbs  = array();
        $this->view('home',[
            'rows1'=>$data1,
            'rows'=>$data,
            'crumbs'=>$crumbs,
            'hiddenSearch'=>$hiddenSearch,
            'actives'=>$actives
        ]);
    }
}