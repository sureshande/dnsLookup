<html>
    <head>
        <style type="text/css">body{ font-family: Arial; font-size: 12px; line-height: 150%; }</style>
        <title>DNSBL IP lookup</title>
    </head>
    <body>
        <?php
/***************************************************************************************
This is a simple PHP script to lookup for blacklisted IP against multiple DNSBLs at once.

You are free to use the script, modify it, and/or redistribute the files as you wish.

Homepage: http://dnsbllookup.com
****************************************************************************************/
function flush_buffers(){ 
    ob_end_flush(); 
    flush(); 
    ob_start(); 
} 

function dnsbllookup($ip)
{
    $dnsbl_json = file_get_contents('dnsLookup/dnsbl.json');
    $parsedArr = json_decode($dnsbl_json,true);
    $dnsbl_lookup = array_column($parsedArr, 'DnsZone');

    $AllCount = count($dnsbl_lookup);
    $BadCount = 0;
    $BadEntries = '';
    $GoodEntries = '';

    if($_GET['ip'])
    {
        $reverse_ip = implode(".", array_reverse(explode(".", $ip)));
        foreach($dnsbl_lookup as $host)
        {
            if(checkdnsrr($reverse_ip.".".$host.".", "A"))
            {
                $BadEntries .= "<span style=\"color: red;\">Listed on ".$reverse_ip.'.'.$host."!</span><br/>";
                flush_buffers();
                $BadCount++;
            }
            else
            {
                $GoodEntries .= "<span style=\"color: green;\">Not listed on ".$reverse_ip.'.'.$host."!</span><br/>";
                flush_buffers();
            }
        }
    }
    else
    {
        echo "Empty ip!<br/>";
        flush_buffers();
    }
    echo $BadEntries.$GoodEntries."<small>This ip has ".$BadCount." bad listings of ".$AllCount."!</small><br/>";
    flush_buffers();

}

if(preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/",@$_GET['ip']) == true) {
    dnsbllookup($_GET['ip']);
}
?></body></html>