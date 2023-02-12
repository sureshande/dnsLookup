<?php
    
// Load the NetDNS2 library
require_once('Net/DNS2.php');    

// Set up the resolver to use opendns
$resolver = new Net_DNS2_Resolver(array('nameservers' => array(
    '208.67.222.222',
    '8.8.8.8',
    '76.76.2.0',
    '9.9.9.9',
    '208.67.222.222',
    '1.1.1.1',
    '185.228.168.9',
    '76.76.19.19',
    '94.140.14.14'
)));

if(isset($_GET['host'])) { 
    $ip = $_GET['host'];
    if(file_exists('dnsbl.json')) {
        $json = file_get_contents('dnsbl.json');
        $tempArr = json_decode($json,true);
        $host = gethostbyname($ip);
        if($host == $ip) {
            $parsedArr = $tempArr['ip'];
        } else {
            $parsedArr = $tempArr['host']; 
        }
        
    } else {
        echo "script execution stopped.";exit;
    }
}
$repeat = count($parsedArr);

if(isset($_GET['id'])) {
    $id = $_GET['id'];
}

$msg = '';
if ($ip === '') {
    $msg = 'No IP given';
}

// Make sure output buffering is turned off
@ob_end_flush();

// Message times repeat
$reverse_ip = implode(".", array_reverse(explode(".", $ip)));
$listed = '';
if(isset($_GET['id'])) {
    $dnsblArr = arraySearch($parsedArr, 'Id',$id)[0];
    if(!empty($dnsblArr)) {
        $dnsr = $reverse_ip . "." . $dnsblArr['DnsZone'];
        $time_start = microtime(true);
        if (checkdnsrr($dnsr, "A")) {
            $urlData = parse_url($dnsblArr['Url']);
            $listed = "BlackListed";
            $res = $resolver->query($dnsr, 'TXT');
            if ($res->answer) {
                $record = $res->answer;
                foreach ($record as $eachRec) {
                    if (stripos($eachRec->rdata, '127.0.0.2') !== true || stripos($eachRec->rdata, '127.0.0.3') !== true) {
                        $listed = "WhiteListed";
                    }
                }
            }
        } else {
            $listed = "NotListed";
        }
        $openDnsHosts = array('pbl.spamhaus.org','zen.spamhaus.org','dnsbl.spfbl.net');
        if(in_array($dnsblArr['DnsZone'], $openDnsHosts) && $reverse_ip == '65.165.87.47') {
            $response = $resolver->query($dnsr, 'A');
            if ($response->answer) {
                // Loop through the answer resource records
                foreach ($response->answer as $rr) {
                    // Check if the resource record is an A record
                    if ($rr->type == 'A') {
                        // Extract the address from the resource record
                        $address = $rr->address;
                        // Check if the address contains 127
                        if (preg_match('/^127\./', $address)) {
                            $listed = "BlackListed";
                            break;
                        } else {
                            $listed = "WhiteListed";
                            break;
                        }
                    }
                }
            }            
        }
        $time_end = microtime(true);
        $time = $time_end - $time_start;
        $time = ($time >= 1.0) ? round($time, 2) . 's' : round($time * 1000, 2) . 'ms';
        dataToStreamEvent(
            'item',
            [
                'name' => $dnsblArr['Name'],
                'host' => $dnsblArr['DnsZone'],
                'listed' => $listed,
                'time' => $time,
                'url' => $dnsblArr['Url']
            ]
        );
    }
} else {
    $returnArr = ["success" => 1 ,"count" => count($parsedArr)];
    foreach ($parsedArr as $key => $value) {
        $dnsbl_data[] = $value['DnsZone']."|".$value['Id'];
    }
    $returnArr['result'] = $dnsbl_data; 
    echo json_encode($returnArr);exit;
}

/**
 * @param string $type
 * @param array $data
 * @param string $id
 * @return string text/event-stream formatted string
 */
function dataToStreamEvent($type, array $data, $id = '') {
    $result = [
        "success" => true,
        "event" => $type,
        "id" => $id,
        "result" => $data
    ];
    echo json_encode($result);exit;
}

function arraySearch($array, $key, $value)
{
    $results = array();
    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }
        foreach ($array as $subarray) {
            $results = array_merge($results, arraySearch($subarray, $key, $value));
        }
    }
    return $results;
}