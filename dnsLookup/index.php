<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="dnsbl.css">
    <title>Blacklist Checker</title>
</head>
<body data-aos-easing="ease-in-out" data-aos-duration="800" data-aos-delay="0">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    var dnsblData = hostData = host = totalCount = incompleteCount = '';
    var count = 0;
    $(document).ready(function(){  
      $("html").stop().animate({scrollTop:1200}, 500, 'swing');
        $('#formSubmit').off('click').on('click', function(ele){
            count = 0;
            $(".count-class").html("("+count+")");
            $("#results-table").html('Completed 0 of 0');
            $("#bl-table_body").html('');
            host = $('#bl-validator').val();
            if(host != '') {
                $.get('getting.php?host='+host,function(data, status) {
                    data = JSON.parse(data);
                    if(data.success) {
                        if(data.count > 0){
                           incompleteCount = totalCount = data.count;
                           hostData = data.result;
                        }
                    }
                });
            }
        });
        var classNames = [];
         $('input[type="checkbox"]').change(function(){
            $('input[type="checkbox"]').each(function(){
               if(($(this).is(":checked"))) {
                  classNames.push($(this).attr('name'));
               }
            })
            $('.bl-table_row.table_body').hide();
            if(classNames.length > 0) {
               classNames.forEach(function(classVal){
                  $('.bl-table_row.table_body.'+classVal).show(); 
               });
            } else {
               $('.bl-table_row.table_body').show();
            }
            classNames = [];
         });
    });   

    $(document).ajaxComplete(function() {
        myRecursiveFunction(hostData);
    });


   function myRecursiveFunction(arrData){
        if(arrData.length == 0) return;
        var item = arrData.shift(); 
        const itemArray = item.split("|");
        id = itemArray[1];
        ajaxRequest(item, id, arrData);
    }

    function ajaxRequest(token, id, myArray) {
        $.get("getting.php?host="+host+"&id="+id,function(data, status) {
            data = JSON.parse(data);
            if(data.success) {
               result = data.result;
               console.log(result.listed);
                
                count++;
                
                $("#results-table").html('Completed '+count+' of '+totalCount);
                iconClass = result.listed == "BlackListed" ? "is-blacklisted" : (result.listed == "WhiteListed" ? "is-whitelisted": "is-not-listed"); 
                html = '<div class="bl-table_row table_body '+iconClass+'">';
                html += '<div class="bl-table_cell cell--size1"><p>'+result.name+'</p></div><div class="bl-table_cell cell--size2"><p>'+result.host+'</p></div><div class="bl-table_cell cell--size3"><a href="http://'+result.url+'" target="_blank"><p>'+result.url+'</p></a></div>';
                html += '<div class="bl-table_cell cell--size4"><div class="blacklist-status"><i class="status-incidator '+iconClass+'"></i><span>'+result.listed+'</span></div>';
                if($('.bl-table_row.table_body').length == 10) {
                    $("html").stop().animate({scrollTop:1600}, 500, 'swing');
                }
                $("#bl-table_body").append(html);
                $('p#'+iconClass).html('('+$('.bl-table_row.'+iconClass).length+')');
                $('p#incomplete-count').html('('+--incompleteCount+')');
                $("#results-count").show();
                $(".bl-table").show();
            }
        }).always(function(){
            myRecursiveFunction(myArray);
        });
    }
</script>
        <div id="gatsby">
           <div style="outline:none" tabindex="-1" id="gatsby-focus-wrapper">
              <div class="wrapper" style="overflow: hidden;">
                 <main id="main" class="main" role="main" tabindex="-1" style="margin-top: 50px;">
                    <div class="h-box">
                       <div class="h-box_inner">
                          <div class="h-box_background"><img src="blacklist-bg.jpg" alt="Man looking at his cell phone checking his business status to make sure he is not on any email blacklists." width="1800" height="307"></div>
                          <h1 class="h1">Blacklist Checker</h1>
                       </div>
                    </div>
                    <div class="c-block aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                       <div class="c-block_outer">
                          <div class="c-block_inner">
                             <div class="c-block_text">
                                <h2 class="h3">Is your domain or IP blacklisted?</h2>
                                <p>Being added to an email blacklist affects your entire email communication. Transactional and marketing emails accomplish their goals only when they reach people’s inboxes, and getting blacklisted won’t help.</p>
                                <p>Email servers and spam filters use blacklisting to help curb mass emailing and SPAM communications. These web hosting companies sometimes block an entire IP range if an address is labeled a SPAMMER. And if you’re in the same range, you get blocked as well – even if you are a legitimate sender.</p>
                                <p>Lawful businesses get added to blacklists every day. Their email sending is blocked and they can’t reach their customers. There’s great value in being aware when your IP or domain are on a blacklist.</p>
                                <p>That’s why we created this tool: to help you keep a healthy sender score and maximize your inbox placement.</p>
                                <p>Using our blacklist checker tool will allow you to verify your status with over 300 IP blacklists. And if anything is wrong, you’ll know who to contact about having your IP removed from the blacklist.</p>
                                <p>We’ll guide you through the process: please reach out to our customer support team if you have any questions.</p>
                                <p>Don’t forget to check BOTH your IP address and domain.</p>
                             </div>
                             <div class="c-block_body">
                                <div class="bl-valid">
                                   <form id="blacklistForm" action="javascript:void(0);">
                                      <div class="bl-validator_form-control"><label for="bl-validator">IP or Domain Name</label><input type="text" class="" aria-label="IP or Domain Name" id="bl-validator" name="bl-validator" data-gtm-form-interact-field-id="0"></div>
                                      <button class="btn btn--primary bl-validator_form-btn" id="formSubmit">Submit</button>
                                   </form>
                                </div>
                             </div>
                          </div>
                       </div>
                    </div>
                    <div id="results-count" style="display:none">
                       <h3 class="h5 text-center mb-4" id="results-table"></h3>
                    </div>
                    <div class="bl-table" style="display:none">
                       <div class="bl-table_inner">
                          <div class="bl-table_filters">
                             <h3 class="h4">Filter</h3>
                             <ul>
                                <li>
                                   <div class="bl-table_filter">
                                      <input type="checkbox" name="field-incomplete" id="field-incomplete">
                                      <label for="field-incomplete">
                                         <span class="bl-table_filter-icon">
                                            <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                               <g fill="none" fill-rule="evenodd">
                                                  <path d="M1.377 5.018c.719.548 1.438 1.067 2.156 1.587C5.06 4.614 6.617 2.653 8.143.69c.63.433 1.228.865 1.857 1.327A1746.043 1746.043 0 003.982 9.69C2.665 8.711 1.317 7.758 0 6.777c.449-.577.898-1.154 1.377-1.76v.001z" fill="#FFF"></path>
                                               </g>
                                            </svg>
                                         </span>
                                         <div class="blacklist-status"><i class="status-incidator is-incomplete"></i><span>incomplete</span></div>
                                         &nbsp;<p id="incomplete-count" class="count-class">(0)</p> 
                                      </label>
                                   </div>
                                </li>
                                <li>
                                   <div class="bl-table_filter">
                                      <input type="checkbox" name="is-not-listed" id="field-notlisted">
                                      <label for="field-notlisted">
                                         <span class="bl-table_filter-icon">
                                            <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                               <g fill="none" fill-rule="evenodd">
                                                  <path d="M1.377 5.018c.719.548 1.438 1.067 2.156 1.587C5.06 4.614 6.617 2.653 8.143.69c.63.433 1.228.865 1.857 1.327A1746.043 1746.043 0 003.982 9.69C2.665 8.711 1.317 7.758 0 6.777c.449-.577.898-1.154 1.377-1.76v.001z" fill="#FFF"></path>
                                               </g>
                                            </svg>
                                         </span>
                                         <div class="blacklist-status"><i class="status-incidator is-not-listed"></i><span>notlisted</span></div>
                                         &nbsp; <p id="is-not-listed" class="count-class">(0)</p> 
                                      </label>
                                   </div>
                                </li>
                                <li>
                                   <div class="bl-table_filter">
                                      <input type="checkbox" name="is-blacklisted" id="field-blacklisted">
                                      <label for="field-blacklisted">
                                         <span class="bl-table_filter-icon">
                                            <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                               <g fill="none" fill-rule="evenodd">
                                                  <path d="M1.377 5.018c.719.548 1.438 1.067 2.156 1.587C5.06 4.614 6.617 2.653 8.143.69c.63.433 1.228.865 1.857 1.327A1746.043 1746.043 0 003.982 9.69C2.665 8.711 1.317 7.758 0 6.777c.449-.577.898-1.154 1.377-1.76v.001z" fill="#FFF"></path>
                                               </g>
                                            </svg>
                                         </span>
                                         <div class="blacklist-status"><i class="status-incidator is-blacklisted"></i><span>blacklisted</span></div>
                                         &nbsp; <p id="is-blacklisted" class="count-class">(0)</p>
                                      </label>
                                   </div>
                                </li>
                                <li>
                                   <div class="bl-table_filter">
                                      <input type="checkbox" name="field-other" id="field-other">
                                      <label for="field-other">
                                         <span class="bl-table_filter-icon">
                                            <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                               <g fill="none" fill-rule="evenodd">
                                                  <path d="M1.377 5.018c.719.548 1.438 1.067 2.156 1.587C5.06 4.614 6.617 2.653 8.143.69c.63.433 1.228.865 1.857 1.327A1746.043 1746.043 0 003.982 9.69C2.665 8.711 1.317 7.758 0 6.777c.449-.577.898-1.154 1.377-1.76v.001z" fill="#FFF"></path>
                                               </g>
                                            </svg>
                                         </span>
                                         <div class="blacklist-status">
                                            <i class="status-incidator is-other">
                                               <svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
                                                  <g fill-rule="nonzero" fill="none">
                                                     <path d="M5.916 5.982h6c0 3.198-2.706 5.838-6 5.838a6.137 6.137 0 01-2.94-.75l2.94-5.088z" fill="#00C"></path>
                                                     <path d="M6 5.898l-3 5.196C.234 9.492-.696 5.832.948 2.982A6.17 6.17 0 013.066.81L6 5.898z" fill="#FFF200"></path>
                                                     <path d="M6.048 5.982l-3-5.196C5.82-.816 9.456.216 11.1 3.06c.516.888.798 1.896.822 2.916H6.048v.006z" fill="#593F12"></path>
                                                  </g>
                                               </svg>
                                            </i>
                                            <span>other</span>
                                         </div>
                                         &nbsp; <p id="other-count" class="count-class">(0)</p>
                                      </label>
                                   </div>
                                </li>
                                <li>
                                   <div class="bl-table_filter">
                                      <input type="checkbox" name="is-whitelisted" id="field-whitelisted">
                                      <label for="field-whitelisted">
                                         <span class="bl-table_filter-icon">
                                            <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                               <g fill="none" fill-rule="evenodd">
                                                  <path d="M1.377 5.018c.719.548 1.438 1.067 2.156 1.587C5.06 4.614 6.617 2.653 8.143.69c.63.433 1.228.865 1.857 1.327A1746.043 1746.043 0 003.982 9.69C2.665 8.711 1.317 7.758 0 6.777c.449-.577.898-1.154 1.377-1.76v.001z" fill="#FFF"></path>
                                               </g>
                                            </svg>
                                         </span>
                                         <div class="blacklist-status"><i class="status-incidator"></i><span>whitelisted</span></div>
                                         &nbsp; <p id="is-whitelisted" class="count-class">(0)</p>
                                      </label>
                                   </div>
                                </li>
                             </ul>
                          </div>
                          <div class="bl-table_content">
                             <div class="bl-table_head">
                                <div class="bl-table_row">
                                   <div class="bl-table_cell cell--size1"><strong>Name</strong></div>
                                   <div class="bl-table_cell cell--size2"><strong>DNS</strong></div>
                                   <div class="bl-table_cell cell--size3"><strong>URL</strong></div>
                                   <div class="bl-table_cell cell--size4"><strong>Status</strong></div>
                                </div>
                             </div>
                             <div class="bl-table_body" id="bl-table_body">
                               
                             </div>
                          </div>
                       </div>
                    </div>
                 </main>
              </div>
           </div>
           <div id="gatsby-announcer" style="position:absolute;top:0;width:1px;height:1px;padding:0;overflow:hidden;clip:rect(0, 0, 0, 0);white-space:nowrap;border:0" aria-live="assertive" aria-atomic="true"></div>
        </div>
        <div id="vldt-unmoderated-testing" class="vldt-installed" style="display: none;"></div>
     </body>
</html>

