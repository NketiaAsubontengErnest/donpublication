<?php 
function get_var($key, $default =""){
    if(isset($_POST[$key])){
        return isset($_POST[$key]) ? $_POST[$key] : "";
    }
    return $default;
}

function filterData(&$str){
    $str = preg_replace("/\t/","\\", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str).'"';
}

function export_data_to_excel($fields = array(), $excelData = array(), $DataFileName = ''){
    $fileName = $DataFileName."_".date('Y-m-d').".xls";
    
    header("Content-Type: application/vnd.ms-excel");    
    header("Content-Disposition: attachment; filename=\"$fileName\""); 
    
    echo $excelData;
    exit();
}

function get_select($key, $value, $default =""){
    if(!empty($default)){
        $_POST[$key] = $default;
    }
    if(isset($_POST[$key])){
        if($_POST[$key]==$value){
            return "selected";
        }
    }
    return "";
}

function esc($var){
    return htmlspecialchars($var);
}

function random_string($length){
    $array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    $text = "";
    for ($x = 0; $x < $length; $x++){
        $random = rand(0, 61);
        $text .= $array[$random];
    }
    return $text;
}

//this print and show all data

function show($data){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

//this get the correct date 
function get_date($date){
    return date("jS F, Y", strtotime($date));
}

//Select correct customer type
function get_Cust_type($typeName){
    $cuslist = array(
        'school'=>"School",
        'booksh'=>"Bookshop",
        'garris'=>"Garrison",
        'agent'=>"Agent",
    );
    return $cuslist[$typeName];
}

