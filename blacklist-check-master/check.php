<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Blacklist</title>
</head>
<body>
 	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand center" href="#">Blacklist Check</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navig$
                <span class="navbar-toggler-icon"></span>
            </button>

    </nav> 
    <style>
        .card {
        margin: 0 auto; /* Added */
        float: none; /* Added */
        margin-bottom: 10px; /* Added */
        }
    </style>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <div class="container mt-4 align-self-center">
                    <h2 style="text-align: center">Listado</h2>
                    <hr>
        <?php
        $bl = array(
        "bl.mailspike.net",
        "b.barracudacentral.org",
        "bl.spamcop.net",
        "cbl.abuseat.org",
        "dnsbl-1.uceprotect.net",
        "dnsbl-2.uceprotect.net",
        "dnsbl-3.uceprotect.net",
        "dnsbl.ahbl.org",
        "dnsbl.sorbs.net",
        "dul.dnsbl.sorbs.net",
        "sbl.spamhaus.org",
        "short.rbl.jp",
        "smtp.dnsbl.sorbs.net",
        "socks.dnsbl.sorbs.net",
        "spam.dnsbl.sorbs.net",
        "web.dnsbl.sorbs.net"
        );


        $ip = $_POST['ip'];
        $ip_array = explode('.', $ip);
        $rev = array_reverse($ip_array);
        $reverse = implode('.',$rev);

        for($i = 0; $i <= 17; $i++){
        $com = $reverse.'.'.$bl[$i];
        $resp = @dns_get_record($com, DNS_A);        
        if($resp[0][ip] == '127.0.0.1' || $resp[0][ip] == '127.0.0.2'){
                ?>
                <div class="card text-center" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Listado na <?php echo $bl[$i];?></h5>
                    </div>
                </div>
                <?php
            }else{
                ?>
                <div class="card text-center" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Não está listado na <?php echo $bl[$i];?></h5>
                    </div>
                </div>
                <?php
            }
        }

        ?>
    </body>
</html>
