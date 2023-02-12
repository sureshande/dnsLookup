<?php
    
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
            $ip = gethostbyname($dnsr);
            if ($ip != $dnsr) {
                $listed = "Blacklisted";
            }
            
            if (checkdnsrr($dnsr, "A") && isWhitelisted(strtolower($dnsr))){
                $listed = "Whitelisted"; 
            } else {
                $listed = "Notlisted"; 
            }
            //$listed = dns_get_record($dnsr, DNS_MX + DNS_A + DNS_AAAA) ? true :false;
            /*if (dns_get_record($dnsr, "A") && isBlacklisted(strtolower($dnsr))){
                $listed = "Blacklisted"; 
            } else if (dns_get_record($dnsr, "A") && isWhitelisted(strtolower($dnsr))){
                $listed = "Whitelisted"; 
            } else {
                $listed = "Notlisted"; 
            }*/ 
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

function isWhitelisted($ip){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://dns.google.com/resolve?name=".$ip);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);
    curl_close($ch);
    $obj = $body;
    $bodyArr = json_decode($obj, true);
    if(!isset($bodyArr['Comment'])){
        return true;
    }
    return false;
}

function isBlacklisted($ip){
    $return = true;   
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://dns.google.com/resolve?name=".$ip);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);
    curl_close($ch);
    $obj = $body;
    $bodyArr = json_decode($obj, true);
    if(isset($bodyArr['Answer'])){
        if(str_contains($bodyArr['Answer'][0]['data'], '127.0.0.') || str_contains($bodyArr['Answer'][0]['data'], 'need.to.know.only.')) {
            $return = true;   
        }
    }
    if(isset($bodyArr['Authority'])){
        if(str_contains($bodyArr['Authority'][0]['data'], '127.0.0.') || str_contains($bodyArr['Authority'][0]['data'], 'need.to.know.only.')) {
            $return = true;   
        }
    }
    return $return ? true : false;
}