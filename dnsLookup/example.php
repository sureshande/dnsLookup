<?php

include_once "ipblocklist.class.php";

$ips = array(	'127.0.0.1',
                '::1',
				'200.0.0.1',
				'200.0.0.2',
				'100.2.4.6',
				'78.34.23.71',
				'156.0.45.45',
				'25.128.64.0',
				'25.128.25.25',
				'100.0.0.1',
				'10.8.0.1',
				'2001:0db8:85a3:0042:1000:8a2e:0370:7334',
				'2001:0db8:85a3:0042:1000:8a2e:0370:7335',
				'2001:0db8:85a3:0041:1000:8a2e:0370:7335',
				'1f00::100',
				'1f00::110',
				'f555::1' );

$checklist = new IpBlockList( );
foreach ($ips as $ip ) {

	$result = $checklist->ipPass( $ip );

	if ( $result ) {
		$msg = "PASSED: "."(".$checklist->status().") ".$checklist->message();
	}
	else {
		$msg = "FAILED: "."(".$checklist->status().") ".$checklist->message();
	}
	print( $msg . '<br/>' );
}

?>
