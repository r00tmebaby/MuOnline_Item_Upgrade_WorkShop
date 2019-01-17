
<?php

if(!defined('access')) {
          header('HTTP/1.0 403 Forbidden');
          exit;
}
$row     = "";
$content = "";
$i       = 0;
$receipt = "Recipient";
$reply   = "Subject";
$rows    = mssql_query("Select  * from r00tme_Upgrade_Item_Logs where username='".$_SESSION['username']."' order by date desc");
$count   = mssql_num_rows($rows);
    while($row = mssql_fetch_array($rows)){
    	$i++;
    	if($count > $i){$fot = ",";}else{$fot = "";}
		$data     = json_decode($row['data']);
		$items    = ItemInfoUser($row['old_item_hex']);
		$item     = $items['item_info'];
    	$content .= "
		[
		     '".date("D-M-y H:i",$row['date'])."',  
		     '".$row['old_item_hex']."', 
		     '".$row['new_item_hex']."',
			 '".$data[3]."',
			 '".$data[6]."',
			 '".$data[5]." %',
			 '".$data[0]."'
		]" 
		. $fot;
    }
	
	
	$admin  = logged();
    $sender = $_SESSION['username'];
	if(isset($_POST['name']) && isset($_POST['message']) && isset($_POST['subject']))
	{
    	// **TODO:
        // Captcha 
		// Improving fields security
		// Pagination with google charts.table
       if(!empty($_POST['message']) && !empty($_POST['subject']) && !empty($_POST['name']) ){
		   if($admin == 0){
               $sender = "Admin";
		   }
		    if(acc_exists($_POST['name'])){
				if(($_POST['name'] <> $sender )){
		   		mssql_query("Insert into r00tme_Upgrade_Item_Messages 
		           (sender,recipient,subject,message,ip,date) values 
		           ('".$sender."','".protect($_POST['name'])."','".base64_encode(stripcslashes($_POST['subject']))."','".base64_encode(stripcslashes($_POST['message']))."', '".ip2long(ip())."','".time()."' )"); 
                   echo "<div class='alert alert-success' role='alert'>Your message was successfully sent  </div>";
			    }
				else{
					echo "<div class='alert alert-danger' role='alert'>You can not sent message to yourself </div>";
				}
			}
			else{
			echo "<div class='alert alert-danger' role='alert'>This account doest not exist  </div>";	
			}
	   }
	   else{
		   echo "<div class='alert alert-danger' role='alert'>Please fill all required fields</div>";
	   }
		
	}
	if(isset($_POST['resubject']) && isset($_POST['receipter']) && isset($_POST['ids'])){
		if(isset($_POST['delete'])){
			$ids = (int)$_POST['ids'];
			$res = protect($_POST['receipter']);
			$rec = stripcslashes(base64_decode($_POST['resubject']));				
			mssql_query("Delete from r00tme_Upgrade_Item_Messages where id='".$ids."' and sender='".$res."' and recipient='".$_SESSION['username']."'");

		}
		if(isset($_POST['reply'])){
		
		    $reply   = "RE:" . base64_decode($_POST['resubject']);
		    $receipt =  $_POST['receipter'];
		}
	}

?>
			<div class='col-sm-6'>
			    <div class='panel panel-default'>
			      <div class='panel-heading'><h4>Send Message</h4></div>
			        <div class='panel-body'>  
                        <div class="container">
                            <div class="col-md-5">
                                <div class="form-area">  
                                    <form role="form" method="post">
                                       <br style="clear:both">
                                                <div class="form-group">
                            						<input type="text"  class="form-control" id="name" name="name" placeholder="<?php echo $receipt?>" required>
                            					</div>
                            					<div class="form-group">
                            						<input type="text" class="form-control" id="subject" name="subject" placeholder="<?php echo $reply?>" required>
                            					</div>
                                                <div class="form-group">
                                                <textarea class="form-control" type="textarea" required name="message"  placeholder="Message" maxlength="2000" rows="3"></textarea>                                            
                                                </div>					
                                        <div class='center-block'>
                                        <input type="submit" class="btn btn-primary"/>
                                    </div>
                            		</form>
                                </div>
                            </div>
                        </div>					
					<div id='chart_div'></div></div>
	            </div>
	        </div>
			<div class='col-sm-6'>
			    <div class='panel panel-default'>
			      <div class='panel-heading'><h4>Received Messages</h4></div>
			        <div class='panel-body'>
								 <div class="col-lg-12">
					                 <table class="table table-responsive">
									    <tr style="background:#f5f5f5">
									       <td>#</td>
										   <td>From</td>
										   <td>Subject</td>
								           <td>Date</td>
									       <td></td>
										</tr>
                                				
	                    <?php
                           $i = 0;						   
				            $msg = mssql_query("Select  * from r00tme_Upgrade_Item_Messages where recipient = '".($_SESSION['username'])."' order by date desc");
				            while($rows = mssql_fetch_array($msg))
							{
								$i++;
								echo '                                  
                                    <tr>
									    <td>'.$i.'</td>
										<td>'.$rows['sender'].'</td>
										<td>'.base64_decode($rows['subject']).'</td>
									    <td style="text-align:left">'.date("m.d.y  - H:i:s  ",$rows['date']).'</td>							            
										<td><button style="background:#2196f3;color:white;border-radius:5px 5px 5px 5px" id="myBtn'.$i.'"'.$rows['sender'].'/>Read</button></td>										
									</tr>
									
                                    <div class="modal fade" id="myModal'.$i.'" role="dialog">
                                      <div class="modal-dialog">    
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">'.base64_decode($rows['subject']).'</h4>
                                          </div>
                                          <div class="modal-body">
										  <form name="form'.$i.'" method="post">
                                            <p>'.base64_decode($rows['message']).'</p>
                                          </div>
                                          <div class="modal-footer">
										    <input type="hidden" name="ids" type="text" value="'.$rows['id'].'"/>
										    <input type="hidden" name="receipter" type="text" value="'.$rows['sender'].'"/>
										    <input type="hidden" name="resubject" type="text" value="'.$rows['subject'].'"/>
										    <button type="submit" name="reply" class="btn btn-primary" >Reply</button>		       
											<button type="submit" name="delete" class="btn btn-primary" >Delete</button>
										</form> 
                                          </div>
                                        </div>             
                                      </div>
                                   </div>
                                  
                                <script>
                                $("#myBtn'.$i.'").click(function(){
                                  $("#myModal'.$i.'").modal();
                                });
                                </script>';}
		            ?>
		                  	       		
                      </table>
					   </div>	
					</div>
	            </div>
	        </div>
<div class='col-sm-12'>
		 <div class='panel panel-default'>
			<div class='panel-heading'><h4>Placeholder For A Reference </h4></div>
			  <div class='panel-body'> 
                    <script type="text/javascript">
                          google.charts.load('current', {'packages':['table']});
                          google.charts.setOnLoadCallback(drawTable);
                    
                          function drawTable() {
                            var data = new google.visualization.DataTable();
                            data.addColumn('string', 'Date');
                            data.addColumn('string', 'Old Item')
                            data.addColumn('string', 'New Item');
                    		data.addColumn('string', 'Credits Spent');
                    		data.addColumn('string', 'Gambled Option');
                    		data.addColumn('string', 'Success Rate');
                    		data.addColumn('string', 'Result');
                            data.addRows([<?php echo $content; ?>]);                  
                        
                            var table = new google.visualization.Table(document.getElementById('table_div'));
                        
                            table.draw(data, {showRowNumber: true, width: '100%', height: '100%' ,         		         showRowNumber: true,
                            page: 'enable',
                            pageSize: 10,
                            pagingSymbols: {
                                prev: 'prev',
                                next: 'next'
                            },
                            pagingButtonsConfiguration: 'auto'});
                          }  
						  
	                </script>

         <div id="table_div"></div>

	  </div> 
   </div> 
</div>


