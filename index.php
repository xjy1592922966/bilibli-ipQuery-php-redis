<?php
require 'vendor/autoload.php';

use IP2Location\Database;
use IP2Location\WebService;



function queryIP2Location($myip) {
    $redis = new Redis();
    $redis->connect('127.0.0.1'); // port 6379 by default
      
    if ($redis->auth('Xiaoye123')) {
        // convert IP address to IP number
        $ipnum = sprintf("%u", ip2long($myip));
        $result = $redis->zRevRangeByScore('DB11', $ipnum, 0, array('limit' => array(0, 1)));
        $result = $result[0];
        $arr = explode("|", $result);

 
        $ipInfo = [
        'IS_China' => $arr[1] === 'CN',
        'Country_Code' => $arr[1] ,
        'Country_Name' => $arr[2],
        'Region_Name' => $arr[3],
        'City_Name' => $arr[4],
        'Latitude' => $arr[5],
        'Longitude' => $arr[6],
        'Time_Zone' =>$arr[8],
        'ZIP_Code' => $arr[7],
             ];
    }
    else {
        echo "Incorrect password\n";
    }
    
    
    $redis->close();
    
     return $ipInfo;
}


// Function to handle the request
function handleRequest() {
    $input = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON input']);
        return;
    }

    $ips = isset($input['ips']) ? $input['ips'] : [];
    
    if (empty($ips)) {
        http_response_code(400);
        echo json_encode(['error' => 'No IP addresses provided']);
        return;
    }

    $results = [];

    foreach ($ips as $ip) {
        // $results[$ip] = getIpInfo($ip, $db, $redis);
        $results[$ip] = queryIP2Location($ip);
    }

    echo json_encode($results);
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleRequest();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Only POST method is allowed']);
}