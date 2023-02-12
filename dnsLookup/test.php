<?php
function isAllowed($ip){
    echo $ip;
    $whitelist = array('111.111.111.111', '112.112.112.112', '68.71.44.*');

    // If the ip is matched, return true
    if(in_array($ip, $whitelist)) {
        return true;
    }

    foreach($whitelist as $i){
        $wildcardPos = strpos($i, "*");

        // Check if the ip has a wildcard
        if($wildcardPos !== false && substr($ip, 0, $wildcardPos) . "*" == $i) {
            return true;
        }
    }

    return false;
}

if(file_exists('dnsbl.json')) {
    $json = file_get_contents('dnsbl.json');
    $tempArr = json_decode($json,true);
    $parsedArr = $tempArr['ip']; 
}

var_dump(checkdnsrr("65.165.87.47.pbl.spamhaus.org", 'A') && gethostbyname("65.165.87.47.pbl.spamhaus.org") != '204.232.137.207' && gethostbyname("65.165.87.47.pbl.spamhaus.org") !='66.152.109.110');

var_dump(checkdnsrr("65.165.87.47.pbl.spamhaus.org",'A') && !in_array(gethostbyname("65.165.87.47.pbl.spamhaus.org"),gethostbynamel('this_is_a_wrong_url.com')));


/*foreach ($parsedArr as $key => $value) {
    var_dump(isAllowed(gethostbyname("www.".$value['DnsZone'])));
    echo "|".gethostbyname($value['DnsZone'])."|".$value['DnsZone']."<br>";
    /*if(())){
        echo $value['DnsZone']." | false";
        echo '<br>';
    }
}*/
exit;
?>