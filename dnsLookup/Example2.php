<?php

include_once( 'ipblocklist.class.php' );

$ips = array(   '192.168.1.0',
                 '192.168.1.1',
                 '192.168.1.2',
                 '127.0.0.1',
                 '200.0.0.10',
                 '256.128.64.32' );

$ip1 = new IPObj('192.168.1.1');

foreach( $ips as $ip ) {
    try {
        $ip2 = new IPObj( $ip );

        switch( $ip1->_Cmp( $ip2 ) ) {
        case -1:
            $op = ' < ';
            break;
        case 0:
            $op = ' == ';
            break;
        case 1:
            $op = ' > ';
            break;
        default:
            $op = ' ? ';
            break;
        }
        print( $ip1.$op.$ip2.'<br/>');
    }
    catch( Exception $e ) {
        echo $e->getMessage() . '<br/>';
    }
}

?>