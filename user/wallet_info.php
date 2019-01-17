
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/bootstrap-select.min.js"></script>
<script src="https://assets.fortumo.com/fmp/fortumopay.js" type="text/javascript"></script>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
$('#character').change(function(){
if($('#character').val() === '1'){$('#showdiv1').css('display', 'block'); }else{$('#showdiv1').css('display', 'none');}
if($('#character').val() === '2'){$('#showdiv2').css('display', 'block'); }else{$('#showdiv2').css('display', 'none');}
if($('#character').val() === '3'){$('#showdiv3').css('display', 'block'); }else{$('#showdiv3').css('display', 'none');}
if($('#character').val() === '4'){$('#showdiv4').css('display', 'block'); }else{$('#showdiv4').css('display', 'none');}
if($('#character').val() === '5'){$('#showdiv5').css('display', 'block'); }else{$('#showdiv5').css('display', 'none');}
if($('#character').val() === '6'){$('#showdiv6').css('display', 'block'); }else{$('#showdiv6').css('display', 'none');}
});
});
</script>


<?php

if(!defined('access')) {
          header('HTTP/1.0 403 Forbidden');
          exit;
}

if(logged()){

///////////////////////////
// Buy credits DTweb 2.0 //
//     by r00tme         //
///////////////////////////

require_once($_SERVER['DOCUMENT_ROOT']."/lib/paymentwall.php");
$username = $_SESSION['username'];
$messages = array();
$success  = 0;
$i        = 0;
$active   = array_values($option['buycredits_methods']);
$details  = mssql_fetch_array(mssql_query("Select * from Memb_Info where memb___id='".$username."'"));
$credits  = mssql_fetch_array(mssql_query("Select * from Memb_Credits where memb___id='".$username."'"));
echo'

<div class="panel panel-default">
			<div class="panel-heading"><h4>Payment Systems </h4></div>
			  <div class="panel-body"> 
          <div class="col-lg-4" >
             <form style="float:left" class="form" method="post">
                <div class="form-group">
    		      <select  class="form-control" name="method" id="character">
			         <option disabled selected >Select Payment Method</option>';					   
			            foreach($option['buycredits_methods'] as $methods => $value){
				  	      $i++;
			      	      if($value == 1){
				  	    	  echo "<option value='".$i."'>".$methods."   ".$option[$methods.'_cr_bonus']."</option>";
				  	      }
			            }
				   echo '	  
				  	
		          </select>				
               </div>
            </form>
		</div>
		';
   if(isset($_GET['success'])){
	   $suc = (int)$_GET['success'];
	   switch($suc){
		   case 1: echo "<div id='message' style='display:none;' class='success'><p>Thanks for your payment.</p><p> Your order will be automatically delivered shortly</p></div>";break;
		   default: echo "<div id='message' style='display:none;' class='error'><p>We are sorry to hear that you have canceled the order.</p><p> Please do not hesitate to contact us if you need any assistance</div></p>"; break;
	   }
   }
   
// PayPal Form Start ->
echo'  
    <div id="showdiv1" class="col-lg-7 method" style="display:none;">
          <form name="paypal_donate" class="form-inline" method="POST">
             <div style="margin-right:5px" class="form-group mx-sm-3 mb-2">
                 <img src="imgs/payments/paypal.png" width="130px" height="40px"/>
             </div>			
				    <div class="form-group mx-sm-3 mb-2">
					<select class="form-control" name="ppitem_id" >';
		$count	= 0;
		foreach ($option['paypal_prices'] as $credits => $price) {
				$count++;
				echo ' <option value="'.$count.'"> x'.$credits.' Credits For '.$price . " " . $option['paypal_currency'].'</option> ';
			
		}
        echo "	 </select></div>";
		if ($count == 0) {
				$messages[] = ('There is no active payment right now');
		}
		else {
			echo '<input type="submit" class="btn btn-primary"  value="Checkout">';
		}
        
		echo '</form>';
		
		$check_payments = mssql_query("Select * from DTweb_PayPal_Transactions where memb___id='".$username."' order by id desc");
		if(mssql_num_rows($check_payments) > 0){
	echo'		
       <table class="table">
	        <tr class="title">
			    <td>#</td>
				<td>Paid</td>
				<td>Credits</td>
				<td>Date</td>
				<td>Status</td>
			</tr>';
		unset ($i);
		$i=0;
		while($dets = mssql_fetch_array($check_payments)){
			$i++;
			echo
			'
			  <tr>
			      <td style="font-size:10pt">'.$i.'</td>
				  <td style="font-size:10pt">'.$dets['amount'].' &nbsp;'.$dets['currency'].'</td>
				  <td style="font-size:10pt"><img width="20px"src="../imgs/payments/dtcoins.png"/> '.$dets['credits'].'</td>
				  <td style="font-size:10pt">'.server_time($dets['order_date']).'</td>
				  <td style="font-size:10pt">'.$dets['status'].'</td>
			  </tr>			
			';
		}
		echo '</table>';
		}
		
    echo'			
</div>';
// PayPal End /////////////
//-------------------------
// EpayBG Form Start ->
ppal_prove();
echo '
<div id="showdiv2" class=" method col-lg-7" style="display:none;">
     
			<form name="epay_donate" class="form-inline"  method="POST">
			        <div style="margin-right:5px" class="form-group mx-sm-3 mb-2">
           <img src="imgs/payments/epay.png" width="130px" height="40px"/>
        </div>	<div class="form-group mx-sm-3 mb-2">
						  <select class="form-control"  name="epitem_id" >';
		$count	= 0;
		foreach ($option['epay_prices'] as $credits => $price) {
				$count++;
				echo ' <option value="'.$count.'"> x'.$credits.' Credits For '.$price . '&nbsp;BGN</option> ';
			
		}
        echo "	 </select></div>";
		if ($count == 0) {
				$messages[] = ('There is no active payment right now');
		}
		else {
			echo '		
					
						<input type="submit" class="btn btn-primary" value="Checkout">
					
				';
		}

		echo '</form>';
	
    unset($check_payments);	
	$check_payments = mssql_query("Select * from [DTweb_EpayBG_Orders] where account='".$username."' and verified = 1 order by id desc");

	if(mssql_num_rows($check_payments) > 0){
	echo'		
       <table class="table">
	        <tr class="title">
			    <td>#</td>
				<td>Invoice</td>
				<td>Paid</td>
				<td>Credits</td>
				<td>Date</td>
				<td>IP</td>
				<td>Status</td>
			</tr>';
		unset ($i);
		$i=0;
		while($dets = mssql_fetch_array($check_payments)){
			$prices = json_decode($dets['packet']);
			$i++;
			echo
			'
			  <tr>
			      <td style="font-size:10pt">'.$i.'</td>
				  <td style="font-size:10pt">'.$dets['invoice'].'</td>
				  <td style="font-size:10pt">'.$prices[1].'</td>
				  <td style="font-size:10pt"><img width="20px"src="../imgs/payments/dtcoins.png"/> '.$prices[0].'</td>
				  <td style="font-size:10pt">'.server_time($dets['time']).'</td>
				  <td style="font-size:10pt">'.long2ip($dets['ip']).'</td>
				  <td style="font-size:10pt">Confirmed</td>
			  </tr>			
			';
		}
		echo '</table>';
		}
	echo'

</div>
';
// EpayBG End //////////////
//-------------------------
// PayGol Form Start ->	

echo'

<div id="showdiv3" class=" method col-lg-7" style="display:none;">
			<form name="paygol_donate" class="form-inline" method="POST">
			        <div style="margin-right:5px" class="form-group mx-sm-3 mb-2">
           <img src="imgs/payments/paygol.png" width="120px" height="40px"/>
        </div>
					 <div class="form-group mx-sm-3 mb-2"> <select class="form-control" name="pgol_id">';
		$count	= 0;
		foreach ($option['pgol_prices'] as $credits => $price) {
				$count++;
				echo ' <option value="'.$count.'"> x'.$credits.' Credits For '.$price . '&nbsp;'.$option['pgol_currency'].'</option> ';
			
		}
        echo "	 </select></div>";
		if ($count == 0) {
				$messages[] = ('There is no active payment right now');
		}
		else {
			echo '		
				
						<input type="submit" class="btn btn-primary"  value="Checkout">
				
				';
		}

		echo '</form>';
    unset($check_payments);
	$check_payments = mssql_query("Select * from [DTweb_PayGol_Orders] where account='".$username."' and verified = 1 order by id desc");

		if(mssql_num_rows($check_payments) > 0){
	echo'		
       <table class="table">
	        <tr class="title">
			    <td>#</td>
				<td>Paid</td>
				<td>Credits</td>
				<td>Date</td>
				<td>IP</td>
				<td>Status</td>
			</tr>';
		unset ($i);
		$i=0;
		while($dets = mssql_fetch_array($check_payments)){
			$prices = json_decode($dets['packet']);
			$i++;
			echo
			'
			  <tr>
			      <td style="font-size:10pt">'.$i.'</td>
				  <td style="font-size:10pt">'.$prices[1].'</td>
				  <td style="font-size:10pt"><img width="20px"src="../imgs/payments/dtcoins.png"/> '.$prices[0].'</td>
				  <td style="font-size:10pt">'.server_time($dets['time']).'</td>
				  <td style="font-size:10pt">'.long2ip($dets['ip']).'</td>
				  <td style="font-size:10pt">Confirmed</td>
			  </tr>			
			';
		}
		echo '</table>';
		}
	
     echo' 
</div>';
paygol_prove();
//========= Paygol Form End	==//
//------------------------------
//========= Mobio Start ======//

echo ' <div id="showdiv4" class=" method col-lg-7" style="display:none; padding:5px 5px">';
 
$countries = "";
$price     = "";
   foreach($option['mobio_services'] as $key => $value){
	  $keys  = array_keys($value);
	  $keys1 = array_values($value);

      $countries .="<option value='".$keys[0]."'>".$keys1[0]."</option>";
	   foreach($value as $new => $ben){	   	   
           if(is_array($ben)){
           	    $key   = array_values($ben);
           		$price    .= "<option value='".$key[3]."'>".$key[3]."</option>";
           }
	   }
   }
   
echo '
<div style="background:rgba(238,238,238,0.5);"> 
    <ul class="list-group">
	  <li class="list-group-item active" >How to get credits with Mobio SMS service ?</li>
      <li  class="list-group-item" >  Use the form and select your country and desired donation</li>
      <li  class="list-group-item" >  Please be aware of the message and type is properly</li> 
      <li  class="list-group-item" >  We do not take any responsibility if you use different message-phone number combinations</li>
      <li  class="list-group-item" >  You will receive your reward soon after your payment has been made</li>';
	  if($option['mobio_punish'] === 1){
		  echo '<li class="error" class="list-group-item"> You will be banned for cheating !</li>';
	  }
 echo'
    </ul>
</div>
    <form id="mobio" class="form-inline">
			<div style="margin-right:5px" class="form-group mx-sm-3 mb-2">
           <img src="imgs/payments/mobio.png" width="120px" height="40px"/>
        </div>	  
    	   <input type="hidden" name="account" value="'.$username.'"/>
             <div class="form-group mx-sm-3 mb-2">   <select name="country" class="form-control" id="country" onchange="functions(\'mobio\')">'.$countries.'</select></div>
             <div class="form-group mx-sm-3 mb-2">  <select name="price" class="form-control" onchange="functions(\'mobio\')" id="price"><option disabled selected>-</option>'.$price.'</select></div>
           <div class="form-group mx-sm-3 mb-2"> <div id="mobios"></div></div>
    </form>

';
 unset($check_payments);
	$check_payments = mssql_query("Select * from [DTweb_Mobio_Orders] where account='".$username."' order by id desc");

		if(mssql_num_rows($check_payments) > 0){
	echo'		
       <table class="table">
	        <tr class="title">
			    <td>#</td>
				<td>Paid</td>
				<td>Credits</td>
				<td>Date</td>
				<td>Country</td>
				<td>Number</td>
				<td>Status</td>
			</tr>';
		unset ($i);
		$i=0;
		while($dets = mssql_fetch_array($check_payments)){
			$prices = json_decode($dets['packet']);
			$i++;
			if($dets['verified']===1){
				$status = "Completed";
			}
			else{
				$status = "Failed";
			}
			
			echo
			'
			  <tr>
			      <td style="font-size:10pt">'.$i.'</td>
				  <td style="font-size:10pt">'.base64_decode($prices[1]).'</td>
				  <td style="font-size:10pt"><img width="20px"src="../imgs/payments/dtcoins.png"/> '.$prices[0].'</td>
				  <td style="font-size:10pt">'.server_time($dets['time']).'</td>
				  <td style="font-size:10pt">'.($dets['country']).'</td>
				  <td style="font-size:10pt">'.($dets['number']).'</td>
				  <td style="font-size:10pt">'.$status.'</td>
			  </tr>			
			';
		}
		echo '</table>';
		}

echo'
</div>';

//===== Mobio End ==//
//------------------------------
//==== Fortumo Start ====//
echo'
<div id="showdiv5" class="col-lg-7" style="display:none; padding:5px 5px">
 
    <ul class="list-group">
	 <li  class="list-group-item active" >How to get credits with Fortumo SMS service ?</li>
      <li  class="list-group-item" > Click the button <span style="color:#d6611f;font-weight:600">\'Pay Now\'</span> and a popup window will be opened</li>
      <li  class="list-group-item" >Choose the desired credits and select the payment amount</li> 
      <li  class="list-group-item" > You will receive your reward soon after your payment has been made</li>';
	  if($option['fortumo_punish'] === 1){
		  echo '<li class="error"> You will be banned for cheating !</li>';
	  }
echo '
    </ul>

<form id="fortumo" class="form" method="post">
    <input type="hidden" name="acc" value="'.$username.'"/>
    <a id="fmp-button" rel="'.$option["fortumo_id"].'/'.$username.$option["fortumo_id"].hmac("SHA1",$username.$details['reg_ip'].$details['reg_date'],$option["fortumo_id"]).'"><input class="btn btn-primary" type="submit" onclick="functions(\'fortumo\')" name="submit" value="Pay Now"/></a>
    <img style="opacity:0.7;margin-top:10px;background:rgba(255,255,255,0.9)" src="imgs/payments/fortumo_img.png"/>
</form>';
   unset($check_payments);
	$check_payments = mssql_query("Select * from [DTweb_Fortumo_Orders] where account='".$username."' and verified= 1 order by id desc");

		if(mssql_num_rows($check_payments) > 0){
	echo'		
       <table class="table">
	        <tr class="title">
			    <td>#</td>
				<td>Paid</td>
				<td>Credits</td>
				<td>Date</td>
				<td>Status</td>
			</tr>';
		unset ($i);
		$i=0;
		while($dets = mssql_fetch_array($check_payments)){
			$prices = json_decode($dets['packet']);
			$i++;			
			echo
			'
			  <tr>
			      <td style="font-size:10pt">'.$i.'</td>
				  <td style="font-size:10pt">'.$prices[1].'</td>
				  <td style="font-size:10pt"><img width="20px"src="../imgs/payments/dtcoins.png"/> '.$prices[0].'</td>
				  <td style="font-size:10pt">'.server_time($dets['time']).'</td>
				  <td style="font-size:10pt">Completed</td>
			  </tr>			
			';
		}
		echo '</table>';
		}

 echo'
';
//===== Fortumo End ===//
//------------------------------
//==== PaymentWall Start ====//
echo'
<div id="showdiv6" class=" method col-lg-7" style="display:none; float:left;padding:5px 5px">
<div style="margin:0 auto;" class="form">
   <img src="../imgs/payments/paymentwall_img.png"/>';
   pwal_form($username,$details['mail_addr'],$details['reg_date'],$details['reg_ip']);
echo'                
</div>';

     unset($check_payments);
	$check_payments = mssql_query("Select * from [DTweb_PaymentWall_Orders] where account='".$username."' order by id desc");

	if(mssql_num_rows($check_payments) > 0){
	echo'		
       <table  class="table">
	        <tr class="title">
			    <td>#</td>
				<td>Transaction id</td>
				<td>Credits</td>
				<td>Date</td>
				<td>Ip</td>
				<td>Status</td>
			</tr>';
		unset ($i);
		$i=0;
		while($dets = mssql_fetch_array($check_payments)){
			$i++;			
			echo
			'
			  <tr>
			      <td style="font-size:10pt">'.$i.'</td>
				  <td style="font-size:10pt">'.$dets['transaction_id'].'</td>
				  <td style="font-size:10pt"><img width="20px"src="../imgs/payments/dtcoins.png"/> '.$dets['packet'].'</td>
				  <td style="font-size:10pt">'.server_time($dets['time']).'</td>
				  <td style="font-size:10pt">'.long2ip($dets['ip']).'</td>
				  <td style="font-size:10pt">'.$dets['status'].'</td>
			  </tr>			
			';
		}
		echo '</table>';
		}
		
echo'
</div>
</div>	</div>	
</div>	
';

//===========PaymentWall Ends ===//

}
message($messages,$success);

epay_prove();

?>

<script>


$(function(){
  $('.selectpicker').selectpicker({});
  
  $(document).on('change','.dropdown-menu inner',function(e){
    var img = $('selectpicker option:selected').attr('data-xxx');
    alert(img);
  });
});


function functions(opt){
    var str = $('#'+opt).serialize();
    $.ajax({
        url: '../jax.php?f='+opt,
        type: "POST",
        data: str,
        success: function(msgc)
        {
            $('#'+opt+'s').empty().append(""+msgc+"").hide().fadeIn("fast");
        }
    });	
}
</script>

<style>
#showdiv1, #showdiv2, #showdiv3, #showdiv4, #showdiv5, #showdiv6 {
	background: #eeeeee;
	border:1px solid #000;
	margin-left:20px;
}


</style>


