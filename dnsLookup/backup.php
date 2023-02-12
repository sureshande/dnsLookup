<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DNS Blacklist Checker</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.1.1.min.js">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- Lato Font -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <style>
    .lds-dual-ring {
        display: inline-block;
        width: 80px;
        height: 80px;
    }
    .lds-dual-ring:after {
        content: " ";
        display: block;
        width: 64px;
        height: 64px;
        margin: 8px;
        border-radius: 50%;
        border: 6px solid #fff;
        border-color: #fff transparent #fff transparent;
        animation: lds-dual-ring 1.2s linear infinite;
    }
    @keyframes lds-dual-ring {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    </style>
</head>
<body>
<script>
    var dnsblData = hostData = host = totalCount = '';
    var count = 0;
    $(document).ready(function(){    
        $('#submit').off('click').on('click', function(ele){
            host = $('#host').val();
            if(host != '') {
                $('#host').prop('readonly',true);
                $.get('getting.php?host='+host,function(data, status) {
                    data = JSON.parse(data);
                    if(data.success) {
                        if(data.count > 0){
                            totalCount = data.count;
                            hostData = data.result;
                        }
                    }
                });
            }
        })
    });   

    $(document).ajaxComplete(function() {
        myRecursiveFunction(hostData);
    });


   function myRecursiveFunction(arrData){
        if(arrData.length == 0) return;
        //remove first item of an array then store it into variable item
        var item = arrData.shift(); 
        const itemArray = item.split("|");
        id = itemArray[1];
        //call our method which will execute AJAX call to server
        ajaxRequest(item, id, arrData);
    }

    function ajaxRequest(token, id, myArray) {
        $.get("getting.php?host="+host+"&id="+id,function(data, status) {
            data = JSON.parse(data);
            if(data.success) {
                result = data.result;
                count++;
                $("#count").html('Completed '+count+' of '+totalCount);
                html = '<div class="col-md-5">'+result.name+'</div><div class="col-md-3">'+result.host+'</div><div class="col-md-3"><a href="http://'+result.url+'">'+result.url+'</a></div><div class="col-md-1">'+result.listed+'</div>';
                $("#appendResult").append(html);
                $("#result").show();
            }
        }).always(function(){
            myRecursiveFunction(myArray);
        });
    }
</script>
<div class="container" style="margin-top: 50px; margin-bottom : 150px;">
    <div class="row">
        <div class="col-md-12" style="padding: 0; border: 1px solid #CCC;">
            <img src="https://www.zerobounce.net/static/blacklist-bg-b8b944f9cb8932ea9271bef6f2a925dd.jpg" alt="myPHPnotes" style="width: 100%; height=150%">
        </div>
        <div class="col-md-12" style="border: 1px solid #CCC;">
            <p style="text-align: center; font-size: 35px; font-weight: 100px;">
                DNS Blacklist Checker
            </p>
            <hr>
            <form autocomplete="off" id="ipForm">
                <label for="host">Website URL/Host</label>   
                <input type="test" id="host" name="host" style="border-radius: 0;" placeholder="facebook.com" class="form-control">
                <br>
            </form>
            <button id="submit" class="brn btn-sm btn-outline-danger">Get DNS Records</button>
            <br><br>
        </div>
        <div class="lds-dual-ring" style="display:none" id="loader"></div>
        <div class="col-md-12" style="border: 1px solid #CCC; width:100%; display:none" id="result">
            <h5 style="font-weight: 500; font-size: 15px;" id="ipTag"></h5>
            <hr>
            <strong id="count"></strong>
            <hr>
            <div class="row" style="width: 100%; font-weight: 600;">
                <div class="col-md-5">Name</div>
                <div class="col-md-3">DNS</div>
                <div class="col-md-3">URL</div>
                <div class="col-md-1">Status</div>
            </div>
            <hr>
                <div class="row" style="width: 100%;" id="appendResult">  
            </div>
        </div>
    </div>
</div>
    
</body>
</html>

