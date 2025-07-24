<?php
function get_var($key, $default = "")
{
    if (isset($_POST[$key])) {
        return isset($_POST[$key]) ? $_POST[$key] : "";
    }
    return $default;
}

function filterData(&$str)
{
    $str = preg_replace("/\t/", "\\", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

function export_data_to_excel($fields = array(), $excelData = array(), $DataFileName = '')
{
    $fileName = $DataFileName . "_" . date('Y-m-d') . ".xls";

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$fileName\"");

    echo $excelData;
    exit();
}

function sendSms($message, $numbers)
{
    // Send SMS logic here
    // You can use an SMS gateway API to send the message to the phone numbers
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://sms.arkesel.com/api/v2/sms/send',
        CURLOPT_HTTPHEADER => ['api-key: OnhIWHcwU1ZMalBzUldnSUU='],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => http_build_query([
            'sender' => 'DON SERIES',
            'message' => $message,
            'recipients' => $numbers,
            // When sending SMS to Nigerian recipients, specify the use_case field
            // 'use_case' => 'transactional'
        ]),
    ]);

    $json = curl_exec($curl);
    $response = json_decode($json, true);
    curl_close($curl);

    return $response;
}

function singlesendSms($message, $numbers)
{
    // Send SMS logic here
    // You can use an SMS gateway API to send the message to the phone numbers
    $response = "";

    $curl = curl_init();

    $from = urlencode('DON SERIES');
    $message = urlencode($message);

    $url = 'https://sms.arkesel.com/sms/api?action=send-sms'
        . '&api_key=OnhIWHcwU1ZMalBzUldnSUU='
        . '&to=' . urlencode($numbers)
        . '&from=' . $from
        . '&sms=' . $message;

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
    ]);

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function checkSmsBalance()
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://sms.arkesel.com/api/v2/clients/balance-details',
        CURLOPT_HTTPHEADER => ['api-key: OnhIWHcwU1ZMalBzUldnSUU='],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ]);

    $json = curl_exec($curl);
    $response = json_decode($json, true);
    curl_close($curl);

    return $response;
}

function shortenText($text, $maxLength = 150)
{
    if (strlen($text) <= $maxLength) {
        return $text;
    }
    return substr($text, 0, $maxLength - 3) . '...';
}

function get_select($key, $value, $default = "")
{
    if (!empty($default)) {
        $_POST[$key] = $default;
    }
    if (isset($_POST[$key])) {
        if ($_POST[$key] == $value) {
            return "selected";
        }
    }
    return "";
}

function esc($var)
{
    return htmlspecialchars($var);
}

function random_string($length)
{
    $array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    $text = "";
    for ($x = 0; $x < $length; $x++) {
        $random = rand(0, 61);
        $text .= $array[$random];
    }
    return $text;
}

//this print and show all data

function show($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

//this get the correct date 
function get_date($date)
{
    return date("jS F, Y", strtotime($date));
}

//Select correct customer type
function get_Cust_type($typeName)
{
    $cuslist = array(
        'school' => "School",
        'booksh' => "Bookshop",
        'garris' => "Garrison",
        'agent' => "Agent",
    );
    return $cuslist[$typeName];
}
