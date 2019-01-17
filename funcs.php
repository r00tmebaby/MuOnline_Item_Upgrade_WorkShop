<?php
define("access",true);

if(!defined('access')) {
          header('HTTP/1.0 403 Forbidden');
          exit;
}
function csrf_token(){
if (!isset($_SESSION['token'])) {
    if (function_exists('mcrypt_create_iv')) {
        $_SESSION['token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_RAND));
    } else {
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
  }
}

function cat($item){
	$check = id_type($item);

		return($check);
	
}

function acc_exists($username){
      $username = protect($username);     
      if(mssql_num_rows(mssql_query("Select * from MEMB_INFO where memb___id='{$username}'")) == 1){
		return true;  
	  }
	  else{
		  return false;
	  }
}

function ip() {
    $ipaddress = '0.0.0.0';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function logged(){
	require("config.php");
	if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
		return false;
	}
	else{
		if($_SESSION['username'] == $option['admin_acc']){
			$admin= 1;
		}
        else{
        	$admin= 0;
        }
        return array($admin);	
	}
   return false;
}

function login(){	
$show_msg='';
      if(isset($_POST['account']) && isset($_POST['password'])){
		$acc =  $_POST['account'];
		$pass = $_POST['password'];
		
		if(empty($acc) OR empty($pass))
		{
			$show_msg = "<div class='container'><div class='col-md-12'><div class='text-center alert alert-danger'>Do not leave empty fields</div></div></div>";
		}
		elseif(preg_match('/[^a-zA-Z0-9\_\-]/', $acc) OR preg_match('/[^a-zA-Z0-9\_\-]/', $pass))
		{
			$show_msg  = "<div class='container'><div class='col-md-12'><div class='text-center alert alert-danger'>You have typed bad characters</div></div></div>";
		}
		else
		{
			$is_acc_pass = mssql_num_rows(mssql_query("SELECT memb___id FROM MEMB_INFO WHERE memb___id='". $acc ."' AND memb__pwd='". $pass ."'"));	
			if($is_acc_pass == 0){
				$show_msg = "<div class='container'><div class='col-md-12'><div class='text-center alert alert-danger'>Wrong Credentials</div></div></div>";
			}
			else{
			
				$_SESSION['username'] = $acc;
				$_SESSION['password'] = $pass;
				header("Location:/");
			}
		}
		echo $show_msg;
		}
}

function item_opt_ren($item,$max,$column,$text,$plus4e=0,$exl=""){
	$check_all = mssql_fetch_array(mssql_query("Select * from r00tme_Upgrade_Item where itemid='".$item."'"));
	$op = 0;
	$anc_tbla = "";
	    for($a=0;$a < $max; $a++){
			    $ancient  = json_decode($check_all[$column.'_price']);	
			    if($ancient[$a] <> 0){
			        $anc_dec    = explode(":",$ancient[$a]);
			    	$anc_sc = $anc_dec[0];
			    	$anc_pr = $anc_dec[1];
			    	$ancs_tbl = "rgba(207,255,191,0.1)";
			    }
			    else{
			    	$anc_sc = "";
			    	$anc_pr = "";
			    	$ancs_tbl = "rgba(255,191,191,0.1)";
			    }
				$op   += $plus4e;
				if($column  === "exl"){
					$nadpis  = $exl[$a];
				}
				else{
					$nadpis  = $text.' + '.$op;
				}
			    $anc_tbla .= '								
						<div  class="col-md-4" style="border:3px solid #ffffff;background:'.$ancs_tbl.'">
						  <span  >
						   <div style="padding:10px 10px;background:#2196f3;color:white">'.$nadpis.'</div>
						     <input class="form-control" value="'.$anc_sc.'" type="number" placeholder="Success Rate" name="'.$column.'_'.$a.'_succ"/>  &#37; 
						     <input class="form-control" value="'.$anc_pr.'" type="number" placeholder="Price" name="'.$column.'_'.$a.'_price"/> Credits</div> 
						   </span>
						</div>
						'; 
				}
			return '
			    <tr> 
					<td  colspan="12" style="text-align:center;background:#eeeeee;color:#000000;font-weight:600">Add '.$text.'
					   <tr><td colspan="12">'.$anc_tbla.'</tr>
					</td>                                   											   
				</tr>					  
                ';	
}

function item_admin($max,$post){
$push = array();
$mek  = array();
$tran =0;
  for($p=0;$p <$max;$p++){
	if(isset($_POST[$post.'_'.$p.'_succ']) && isset($_POST[$post.'_'.$p.'_price'])){
	 if($_POST[$post.'_'.$p.'_succ'] > 0 && $_POST[$post.'_'.$p.'_price'] > 0){
		  $push[] = $_POST[$post.'_'.$p.'_succ'] .":". $_POST[$post.'_'.$p.'_price'];	    		 										
	    }
	    else{
		  $push[] = 0; 
	    }	  
	}
	else{
		$push[] = 0; 
	} 	
  }
  return json_encode($push); 
}

function logout(){
   if(isset($_POST['logout'])){
	   session_destroy();
	   header("Location:/");
   }	
}

function skill_add($item_id){
	if(mssql_num_rows(mssql_query("Select * from r00tme_Upgrade_Item where itemid='".$item_id."' and skill = 1")) == 1){
		return true;
	}
	else{
		return false;
	}
}
function luck_add($item_id){
	if(mssql_num_rows(mssql_query("Select * from r00tme_Upgrade_Item where itemid='".$item_id."' and luck = 1")) == 1){
		return true;
	}
	else{
		return false;
	}
}

function item_lenght(){
	require("config.php");
	switch($options['server_ver']){
		case 1: return 20; break;
		case 2: return 32; break;
		case 3: return 64; break;
		default: return false; break;
	}
}

function is_upgradable($item){
	$real_item  = base64_decode($item);
	
	if(is_item_ware($real_item,item_lenght()) === true){	
	 $cur_item  = ItemInfoUser($real_item);
	   if(mssql_num_rows(mssql_query("Select * from r00tme_Upgrade_Item where type='".$cur_item['type']."' and id= '".$cur_item['id']."' and upgradable = 1")) == 1){
	   	return true;
	   }
	   else{
		 return false;  
	   }
	}
	else{
		return false;
	}
}

function encrypt($data){
	require("config.php");
	if(function_exists('openssl_random_pseudo_bytes')){
        $encryption_key = base64_decode( $option['enc_key']);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
	}
	else{
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128,$option['enc_key'],$data,MCRYPT_MODE_CBC,"\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"));
	}
}

function decrypt($data){
	require("config.php"); 
    if(function_exists('openssl_random_pseudo_bytes')){	
        $encryption_key = base64_decode($option['enc_key']);
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
    }
	else{
	  $decode = base64_decode($data);return mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$option['enc_key'],$decode,MCRYPT_MODE_CBC,"\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0");    
	}
}


function win($success){
    return (mt_rand(1, 100) < $success);
}

function protect($var=NULL) {
$newvar = @preg_replace('/[^a-zA-Z0-9\_\-\.]/', '', $var);
if (@preg_match('/[^a-zA-Z0-9\_\-\.]/', $var)) { }
return $newvar;
}


function is_item_ware($item,$item_lenght=20,$character=NULL){
	$items = array();
	if(isset($_SESSION['username']) && !empty($_SESSION['username'])){
	   $i=-1;
	   while ($i < 119){
	   	$i++;
	   	$items[] = substr(all_items($_SESSION['username'],1200), ($item_lenght * $i), $item_lenght);
	   }
	   if(in_array($item,$items)){
	 	 return true;
	   }
	   else{
		 return false;
	  }
	}
	else{
		return false;
	}
}

function make_log($file_name, $content)
{
	$file_date = date('d_m_Y', time());
    $log_date = date('h:i:s', time());
	$log_content='<hr style="height:5px;background:black"></br><span style="color:orange;text-shadow:0.4px 0.4px #000; font-weight:900;">Date: </span> '.$log_date .' | ' . $content . "\r\n";
	file_put_contents($file_name.'['.$file_date.'].html', $log_content, FILE_APPEND); 
}

function add_opt($itemid,$item){
	$item_info   = mssql_fetch_array(mssql_query("Select * from r00tme_Upgrade_Item where itemid='".$itemid."'"));
    $optionssa   = json_decode($item_info['opt_price']);
	if($optionssa <> NULL){
	    foreach($optionssa as $key => $value){
	    	
	    	if($item['id'] < 6 && $item['opts'] < 24){
	    		
	    		$opt_price   = explode(":",$optionssa[($item['opts']/4)]);
	    	}
	    	elseif($item['id'] > 6 && $item['opts'] < 30){
	    		$opt_price   = explode(":",$optionssa[($item['opts']/5)]);
	    	}
	    	else{
	    		return false;
	    	}
	    	
	    	if($opt_price[0] > 0 && $opt_price[1] > 0){			
                 return  array($opt_price[0],$opt_price[1]);
	    	}
	    	else{
	    		 return false;
	        }
        }
	}
}

function all_items($account,$lenght,$table = "warehouse",$user_col="AccountId",$item_col="items"){
	
    if(phpversion() >= "5.3"){
		$itemsa = mssql_fetch_array(mssql_query("SELECT [".$item_col."] FROM [".$table."] WHERE [".$user_col."]='".$account."'"));
		  return strtoupper(bin2hex($itemsa[$item_col]));
	}
	else{
		  return substr(mssql_get_last_message(mssql_query("declare @items varbinary(".$lenght."); set @items=(select [".$item_col."] from [".$table."] where [".$user_col."]='".$account."'); print @items;")),2);	        	
	}
}	
	
function upgrade_item($account,$level_req=0) {
include("config.php");
if(logged()){
	$message     = '';
	$lvl_prices  = '';
	$render_item = '';
	$optgroups   = '';
	$check       = '011111111';
    $msg_fail    = '';
    $add_opt_skl = '';   
	$add_opt_luk = '';
	$add_opt_dmg = '';
	$add_opt_lvl = '';
	$add_opt_exl = '';
	$add_opt_anc = '';
	$max_opt     = 1;
    $xx          = 0;
    $yy          = 1;
    $line        = 1;
    $onn         = 0;
    $il          = 20;
	$gos         = 0;	

if(isset($_GET["nr"]) && !empty($_GET["nr"])){
	
  if(is_upgradable($_GET["nr"]) === true){			
	  $item        = ItemInfoUser(base64_decode($_GET["nr"]));
	  $item_info   = mssql_fetch_array(mssql_query("Select * from r00tme_Upgrade_Item where itemid='".$item['item_id']."'"));
	  $skill_price = json_decode($item_info['skill_price']);
      $luck_price  = json_decode($item_info['luck_price']);
	  $exl_dets    = json_decode($item_info['exl_price']);
	  $level_dets  = json_decode($item_info['lvl_price']);
	  $anc_dets    = json_decode($item_info['anc_price']);
  

	  if($item['anc'] <> NULL){
	  $anc_price   = explode(":",$anc_dets[$item['anc'] - 1]);
	  }
	  else{
		$anc_price   = explode(":",$anc_dets[0]);  
	  }
      if($item['level'] < 15){
			$level_price = explode(":",$level_dets[$item['level']]);  
	  }
      else{
      	$level_price = 0;
      }	
        if($exl_dets <> NULL){  
      	    foreach($exl_dets as $key => $value){
      	    	if($value <> 0){
      	            $ouh[] = ($key+1);
      	    		  if(!in_array($ouh,$item['exl_option'])){			
                          $exl_shift = array_diff($ouh, $item['exl_option']);			
      	    	      }
      	        }
      	    }
        }

      if($item['srch_skill'] == 0 && skill_add($item['item_id']) === true && $skill_price[1] > 0){ 
	      
		  $add_opt_skl = "<option value='2'>Add Skill</option>";
		  
	  }
	  if($item['srch_luck'] == 0 && luck_add($item['item_id']) === true && $luck_price[1] > 0){		
		  $add_opt_luk = "<option value='1'>Add Luck</option>";    	
	  }

	  if(add_opt($item['item_id'],$item) <> false){		  
		  if($item['id'] < 6){
			$add_opt_dmg  = "<option value='4'>Add Damage +4 </option>";        		
		  }
		  else{
			$add_opt_dmg  = "<option value='4'>Add Defense +5</option>";
		  }
	  }
	
	  if($item['exl_count'] < 6 &&  !empty($exl_shift)){
		  	foreach($exl_shift as $missing_exl){
            $exl_price   = explode(":",$exl_dets[($missing_exl - 1)]);				
		    $add_opt_exl  .= '<option value="166'.$missing_exl.'">Add '.$item['exl_text'][($missing_exl-1)].'</option>';	
		    }	    
		
	  }

	  if($level_price[0] <> 0){
		  $next_level  = $item['level'] +1;
		  $add_opt_lvl = "<option value='3'>Add Level +".$next_level." </option>";

	  }


	  if($item_info['ancient'] === 1 && !empty($anc_price[1]) && $item['anc'] < 2){
		  		if($item['anc'] === NULL){
			        $text_anc = "Make ancient (+5 stamina)"; 
		           }
		        else{
			        $text_anc = "Adding +5 stamina (Ancient)";   
		        }
		  $add_opt_anc = "<option value='5'>".$text_anc."</option>";
        
	  }
	  
 	if($add_opt_skl == '' && $add_opt_luk == '' && $add_opt_dmg == '' && $add_opt_lvl == '' && $add_opt_exl == '' && $add_opt_anc == ''){
		$message = "<div class='select-chaos'><div class='col-md-12' style='width:250px;position:absolute;margin-top:30px;color:white;text-align:center'>
		This item is at the maximum upgrade</div></div>";	
	}
	else{
		$optgroups = 
		    "
				<div class='select-chaos'>
                    <form id='success' method='post'>
                               <select class='selectpicker show-menu-arrow show-tick'  onchange=\"functions('upgr_details','".$item_info['itemid']."',$(this).val(),'".$_GET["nr"]."'),addbutton()\" name='option'>				   					             
									<option selected disabled>Select Upgrade</option>
									<optgroup label='Special'> ".$add_opt_skl. $add_opt_luk . " </optgroup> 
						            <optgroup label='Levels'>".$add_opt_lvl . "</optgroup> 
						            <optgroup label='Options'>".$add_opt_dmg .  "</optgroup> 
						            <optgroup label='Excellent'>".$add_opt_exl."</optgroup> 
						            <optgroup label='Ancient'>".$add_opt_anc."</optgroup>
						       </select>                              
				              <input name='item_id' value='".$_GET["nr"]."' type='hidden'/>    	 
				    </form>
                </div>
			<div class='hide' onclick=\"functions('success'),functions('item_effect')\" id='chaos'></div>
		    ";
			$render_item = "<div class=\"someClass\" title=\"".$item['overlib']."\"><img  src='" . $item['thumb'] . "'/></div>";
	    }
	}
	else{
	    $message = "<div class='select-chaos'><div class='col-md-12' style='position:absolute;margin-top:30px;color:white;width:250px;text-align:center'>This item is not available for upgrades</div></div>";
    }
}
echo '
<div id="successm"></div>
    <div  class="row col-lg-10">
	
	
    <audio id="susound">
      <source src="imgs/pLevelUp.wav" type="audio/wav">
        Your browser does not support the audio element.
    </audio>
    <audio id="unsound">
      <source src="imgs/emix.wav" type="audio/wav">
        Your browser does not support the audio element.
    </audio>
   
    <div  class="col-sm-5">	
        <div class="chaos-default contfix_form">  
            <div class="opt-details">'. $message .'
			    <span id="upgr_detailsm"></span>
			</div>	

			<div class="chaos-item"> <div id="item_effectm"></div> '. $render_item .'	     	
			</div>				
                 '. $optgroups .'
				
            </div>  
	    </div>	
	

		   
 <div style="margin-left:20px" class="col-sm-5">

	<table  class=" cont vaults">'; 	

	for($i=0;$i < 120;$i++) {
	if ($xx == 8) {
	    $xx = 1;
	    $yy++;
	}
	else
	$xx++;
	$TT = substr($check, $xx, 1);
	if ((round($i / 8) == $i / 8) && ($i != 0)) {
	echo  "<td class=\"itemblock\" width=\"32\" height=\"32\" align=center><b>".$line."</b></td></tr><tr>";
	$line++;
	}
	$l = $i;
	$item2 = substr(all_items($account,1200), ($il * $i), $il);
	$item = ItemInfoUser(substr(all_items($account,1200), ($il * $i), $il),$level_req);

	if (!$item['y'])
	    $InsPosY = 1;
	else
	    $InsPosY=$item['y'];

	if (!$item['x'])
	    $InsPosX = 1;
	else {
		
	    $InsPosX = $item['x'];
	    $xxx = $xx;
	    $InsPosXX = $InsPosX;
	    $InsPosYY = $InsPosY;
	    while ($InsPosXX > 0) {
		$check = substr_replace($check, $InsPosYY, $xxx, 1);
		$InsPosXX = $InsPosXX - 1;
		$InsPosYY = $InsPosY + 1;
		$xxx++;
	    }
	} 
	$item['name'] = addslashes($item['name']);
	if ($TT > 1)

	    $check = substr_replace($check, $TT - 1, $xx, 1);
	else {
	    unset($plusche, $rqs, $luck, $skill, $option, $exl);
		$key = '';
if ($item['name']) {

echo "

  <td  style='background-image:url(imgs/2.jpg);' align=\"center\" colspan='" . $InsPosX . "' rowspan='" . $InsPosY . "' style='width:" . (32 * $InsPosX) . "px;height:" . (32 * $InsPosY - $InsPosY - 1) . "px;' >    


<a class=\"someClass\" title=\"<br>".$item['overlib']."\" href=\"?p=1&nr=".base64_encode($item2)."\" onclick=\"document.getElementById('item".$i."').submit()\" style='width:" . (32 * $InsPosX) . "px;height:" . (32 * $InsPosY - $InsPosY - 1) . "px;'><img style='width:" . (32 * $InsPosX) . "px;height:" . (32 * $InsPosY - $InsPosY - 1) . "px;' src='" . $item['thumb'] . "' class='m' /></form></a></td>";

?>


<style>
.row {
    float: none;
    display: block;
    margin: 0 auto;
}

</style>

<?php 	
		} 
		else {
echo "<td colspan='0' rowspan='1' style='width:32px;height:32px;border:0px;margin:0px;padding:0px;'><div style='height: 32px;width: 32px;'><img src='imgs/1.jpg' class='m'></div></td>";
	    }	
	  }
    }
	
	echo  "<td class=\"itemblock\" align=center><b>15</b></td></tr><tr><td class=\"itemblock\" align=center height=32><b>1</b></td><td class=\"itemblock\" align=center><b>2</b></td><td class=\"itemblock\" align=center><b>3</b></td><td class=\"itemblock\" align=center><b>4</b></td><td class=\"itemblock\" align=center><b>5</b></td><td class=\"itemblock\" align=center><b>6</b></td><td class=\"itemblock\" align=center><b>7</b></td><td class=\"itemblock\" align=center><b>8</b></td><td class=\"itemblock\" align=center><a class='someClass' title='@r00tme'>X</a></td></tr>
	</table>
   </div>
  </div>
 </div>	
</div>
	";
  }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////

function itemimage($level, $level2, $level3) {
        $level1 = hexdec(substr($level, 0, 1));

        if (($level1 % 2) <> 0) {
            $level2 = "1" . $level2;
            $level1--;
        }

        if (hexdec($level3) >= 128) {
            $level1 += 16;
        }

        $level1 /= 2;
        $level2 = hexdec($level2);

        $images = "imgs/items/{$level1}/{$level2}.gif";
        return $images;
    }
	
function Iteminfouser($_item,$level_req=0) {
    include("config.php");
	    $nocolor = false;
		$nolevel = 1;
		$plusche = '';
		$exl     = '';
		$option  = '';
        if (substr($_item, 0, 2) == '0x') {
            $_item = substr($_item, 2);
        }

        if ((strlen($_item) != 20) || preg_match('/[^\W_ ] /',$_item) || ($_item == 'FFFFFFFFFFFFFFFFFFFF')) {
            return false;
        }

        // Get the hex contents
        $id         = hexdec(substr($_item, 0, 2));  // Item ID
		$ids        = hexdec(substr($_item, 1, 1));  // Item ID
        $lvl        = hexdec(substr($_item, 2, 2));  //Level,Option,Skill,Luck
        $itemdur    = hexdec(substr($_item, 4, 2));  // Item Durability
        $ex         = hexdec(substr($_item, 14, 2)); // Item Excellent Info/ Option
        $serial	    = substr($_item,6,8);            // Item Serial
        $anc	    = hexdec(substr($_item,17,1));   // Item Ancient
		
		if ($lvl < 128) {
            $skill = '';
			$srch_skill = 0;
        } else {
            $skill = 'This weapon has a special skill';
            $lvl = $lvl - 128;
			$srch_skill = 1;
        }
        
        $itemlevel = floor($lvl / 8);
        $lvl = $lvl - $itemlevel * 8;

        if ($lvl < 4) {
            $luck = '';
			$srch_luck = 0;
        } else {
            $luck = "Luck (success rate of jewel of soul +25%)<br>Luck (critical damage rate +5%)";
            $lvl = $lvl - 4;
			$srch_luck = 1;
        }


        if ($ex - 128 >= 0) {
            $ex = $ex - 128;
        }
        if ($ex >= 64) {
            $lvl+=4;
            $ex+=-64;
        }
        if ($ex < 32) {
            $exc6 = 0;
        } else {
            $exc6 = 1;
            $ex+=-32;
        }
        if ($ex < 16) {
            $exc5 = 0;
        } else {
            $exc5 = 1;
            $ex+=-16;
        }
        if ($ex < 8) {
            $exc4 = 0;
        } else {
            $exc4 = 1;
            $ex+=-8;
        }
        if ($ex < 4) {
            $exc3 = 0;
        } else {
            $exc3 = 1;
            $ex+=-4;
        }
        if ($ex < 2) {
            $exc2 = 0;
        } else {
            $exc2 = 1;
            $ex+=-2;
        }
        if ($ex < 1) {
            $exc1 = 0;
        } else {
            $exc1 = 1;
            $ex+=-1;
        }

        $level = substr($_item, 0, 1);
        $level2 = substr($_item, 1, 1);
        $level3 = substr($_item, 14, 2);
        $AA = $level;
        $BB = $level2;
        $CC = $level3;
        $level1 = hexdec(substr($level, 0, 1));
        if (($level1 % 2) <> 0) {
            $level2 = "1" . $level2;
            $level1--;
        }
        if (hexdec($level3) >= 128) {
            $level1 += 16;
        }
        $level1 /= 2;
        $level2 = hexdec($level2);

        $invview = mssql_query("SELECT * FROM [r00tme_Upgrade_Item] WHERE [id]={$level2} AND [type]={$level1}");
        $rows = mssql_fetch_array($invview);
        $exl_opts     = array();
        $iopxltype = $rows['exeopt'];
        $itemname = $rows['name'];
        $itemexl = "";
        switch ($iopxltype) {
                   case 0 :
                       $op1 = 'Increase Mana per kill +8';
                       $op2 = 'Increase hit points per kill +8';
                       $op3 = 'Increase attacking(wizardly) speed +7';
                       $op4 = 'Increase damage +2%';
                       $op5 = 'Increase Damage +level/20';
                       $op6 = 'Excellent Damage Rate +10%';
                       $inf = 'Additional Damage';
                       break;
		          //All Armors
                   case 1:
                       $op1 = 'Increase Zen After Hunt +40%';
                       $op2 = 'Defense success rate +10%';
                       $op3 = 'Reflect damage +5%';
                       $op4 = 'Damage Decrease +4%';
                       $op5 = 'Increase MaxMana +4%';
                       $op6 = 'Increase MaxHP +4%';
                       $inf = 'Additional Defense';			
                       $skill = '';
                       $nocolor = false;
                       break;
		          //Wings Only ** DB Equal to DarkMaster Server files Item.kor - 97/99XT 
                   case 2:
                       $op1 = ' Life +' . (50 + ($itemlevel * 5)) . ' Increased';
                       $op2 = ' Mana +' . (50 + ($itemlevel * 5)) . ' Increased';
                       $op3 = ' 10% Mana loss instead of Life';
                       $op4 = ' +50 of damage transfered as Life';
                       $op5 = ' Increase Attacking(wizardry)speed +5';
                       $op6 = '';
                       $inf = 'Additional Damage';
                       $skill = '';
                       $nocolor = true;
		          	break;
		          //Rings Only ** DB Equal to DarkMaster Server files Item.kor - 97/99XT 
		          case 3:
                       $op1 = ' + HP 4%';
                       $op2 = ' + MANA 4%';
                       $op3 = ' Reduce DMG +4%';
                       $op4 = ' Reflect DMG + 5%';
                       $op5 = ' Defence Rate + 10%';
                       $op6 = ' Zen After Hunting +40%';
                       $inf = ' Additional Damage';
                       $skill = '';
                       $nocolor = true;
		          	break;
		          //Pendants Only **  DB Equal to DarkMaster Server files Item.kor - 97/99XT 
		          case 4:
                       $op1 = ' Exe DMG Rate +10%';
                       $op2 = ' + DMG LVL/20';
                       $op3 = ' + DMG 2%';
                       $op4 = ' Wizard Speed +7';
                       $op5 = ' +LIFE After Hunting (LIFE/8)';
                       $op6 = ' +MANA After Hunting (MANA/8)';
                       $inf = ' Additional Damage';
                       $skill = '';
                       $nocolor = true;
		    break;
		}
		$opt_array = array($op1,$op2,$op3,$op4,$op5,$op6);
        $add=0;
		

		
        if ($exc1 == 1) {
            $itemexl .= '<br>' . $op1;
			$add  = 1;
			$exl_opts[] = 1;
        }
        if ($exc2 == 1) {
            $itemexl .= '<br>' . $op2;
			$add += 1;
			$exl_opts[] = 2;
        }
        if ($exc3 == 1) {
            $itemexl .= '<br>' . $op3;
			$add += 1;
			$exl_opts[] = 3;
        }
        if ($exc4 == 1) {
            $itemexl .= '<br>' . $op4;
			$add += 1;
			$exl_opts[] = 4;
        }
        if ($exc5 == 1) {
            $itemexl .= '<br>' . $op5;
			$add += 1;
			$exl_opts[] = 5;
        }
        if ($exc6 == 1) {
            $itemexl .= '<br>' . $op6;
			$add  += 1;
			$exl_opts[] = 6;
        }

        if ($rows['exeopt'] == 0) {
            $itemoption = $lvl * 4;
        } else if ($rows['exeopt'] == 4) {
            $itemoption = ($lvl) . '%';
            $inf = ' Automatic HP Recovery rate ';
        } else {
            $itemoption = $lvl * 5;
            $inf = 'Additional Defense rate ';
        }
        $c = '#FFFFFF'; // White -> Normal Item
        if (($lvl > 1) || ($luck != '')) {
            $c = '#8CB0EA';
        }
        if ($itemlevel > 6) {
            $c = '#F4CB3F';
        }
        if ($itemexl != '') {
            $c = '#ccff99';
        } // Green -> Excellent Item 
        if ($nocolor) {
            $c = '#F4CB3F';
        }
        if ($itemoption == 0) {
			$itm        = 0;
            $itemoption = '';
        } else {
			$itm   = $itemoption;
            $itemoption = $inf . " +" . $itemoption;
			
        }
        if (($itemexl != '') && ($itemname) && (!$nocolor)) {
            $itemname = 'Excellent ' . $itemname;
        }

        if ($nolevel == 1) {
            $ilvl = 0;
        } else {
            $ilvl = $itemlevel;
        }
		
		if($anc === 5){
			$anc_opt = 1;		
		}
		elseif ($anc === 9){
			$anc_opt = 2;		
		}
		else{
			$anc_opt = NULL;
			$ancient    = "";
		}
        $output['srch_skill']  = $srch_skill;
        $output['srch_luck']   = $srch_luck;
        $output['clearname']   = $rows['name'];
        $output['name']        = $itemname;
		$output['id']          = $level2;
		$output['anc']         = $anc_opt;
		$output['type']        = $level1;
        $output['opt']         = $itemoption;
        $output['exl_count']   = $add;
		$output['exl_option']  = $exl_opts;
		$output['exl_text']    = $opt_array;
		$output['opts']        = $itm;
        $output['exl']         = $itemexl;
		$output['hex']         = substr($_item,0,4);
        $output['luck']        = $luck;
        $output['skill']       = $skill;
        $output['dur']         = $itemdur;
        $output['x']           = $rows['x'];
        $output['y']           = $rows['y'];
        $output['color']       = $c;
		$output['item_id']     = $rows['itemid'];
        $output['thumb']       = itemimage($AA, $BB, $CC);
        $output['level']       = $itemlevel;
		$output['full_hex']    = $_item;
		$output['ids']         = $ids;
		$output['item_type']   = $rows['exeopt'];
	  


        $itemformat = '<div align=center style=\'background:#FF4000;cursor:pointer;border:2px solid #FF9326;margin:10px 10px;border-radius:5px 5px 5px 5px;padding-left: 6px; padding-right:6px;font-family:arial;font-size: 8px;\'><span style=\'font-weight:bold;font-size: 8px;\'>[Name] </span><br>[Skill] [Luck] [Excellent] [Ancient]</font></span></div>';

        if ($output['level']) {
            $plusche = '+' . $output['level'];
        }
        $overlib = str_replace('[Name]', '<span style=color:' . $output['color'] . '>'.$output['name'] . ' ' . $plusche . '</span>', addslashes($itemformat));
        
		if ($output['opt']) {
            $option = '<br><font color=#9aadd5>' . $output['opt'] . '</font>';
        }
							
        $serial='<font style=font-weight:bold;color:#FF7373>'.$serial.'';
        $overlib	= @str_replace('[serial]', $serial.'</font>', $overlib);	
						
        $overlib	= @str_replace('[Type]','<font style=font-weight:bold;color:#FF7373>'.$level1.'</font>', $overlib);
        $overlib	= @str_replace('[ID]', '<font style=font-weight:bold;color:#FF7373>' . $level2.'</font>', $overlib);
         
        if ($output['luck']) {
            $luck = '<br><font color=#9aadd5>' . $output['luck'] . '';
        }
		$overlib = str_replace('[Luck]', $luck . $option . '</font>', $overlib);
		if ($output['anc'] <> NULL) {
			
            $ancient = '<br><font color=#ffbfff>Ancient +' . ($output['anc']*5) . ' stamina</font>';
        }

        $overlib = str_replace('[Ancient]', $ancient , $overlib);

        if ($output['skill']) {
            $skill = '<br><font color=#9aadd5>' . $output['skill'] . '</font>';
        }
        $overlib = str_replace('[Skill]', $skill, $overlib);

        if ($output['exl']) {
            $exl = '<font color=#8CB0EA>' . str_replace('^^', '<br>', $output['exl']);
        }

        $overlib = str_replace('[Excellent]', $exl, $overlib);

        $output['overlib'] = $overlib;
        $output['item_info']   = array($itemname,"Level+".$plusche,);
        return $output;
    }



function id_type($item) {
   $id     = hexdec(substr($item, 0, 2)); // Item ID
   //Item ID Check
   $level  = substr($item, 0, 1);
   $level2 = substr($item, 1, 1);
   $level3 = substr($item, 14, 2);
   $AA     = $level;
   $BB     = $level2;
   $CC     = $level3;
   $level1 = hexdec(substr($level, 0, 1));

   if(($level1 % 2) <> 0) {
      $level2 = "1" . $level2;
      $level1--;
   }

   if(hexdec($level3) >= 128) {
      $level1+=16;
   }

   $level1/=2;
   $level2 = hexdec($level2);

   $output['level1'] = $level1;
   $output['level2'] = $level2;

   return $output;
}

function strip($var){
$banned = array(";", "'","=","#","--");
$onlyconsonants = str_replace($banned, "", $var);
return $onlyconsonants;
}  

function message($messages,$success = 0){
		 foreach ($messages as $msg){
		 switch($success){
			 case 1: $class='success';break; 
			 default:$class='error';break;
			 }
		 echo "<div class='".$class."'>" . $msg ." </div>";
   } 
}
// EpayBG
function hmac($algo,$data,$passwd){
        /* md5 and sha1 only */
        $algo=strtolower($algo);
        $p=array('md5'=>'H32','sha1'=>'H40');
        if(strlen($passwd)>64) $passwd=pack($p[$algo],$algo($passwd));
        if(strlen($passwd)<64) $passwd=str_pad($passwd,64,chr(0));

        $ipad=substr($passwd,0,64) ^ str_repeat(chr(0x36),64);
        $opad=substr($passwd,0,64) ^ str_repeat(chr(0x5C),64);

        return($algo($opad.pack($p[$algo],$algo($ipad.$data))));
}

function epay_prepay($info = false){
require $_SERVER['DOCUMENT_ROOT']."/config.php";
   	if(isset($_POST['epitem_id']) && isset($_SESSION['username']))
	{
		$donate_id  = (int)$_POST['epitem_id'];
		$donate_id  = $donate_id -1;
        $username   = $_SESSION['username'];
		$rand       = rand(565,8966);
		$prices     = array_values($option['epay_prices']);
		$credits    = array_keys($option['epay_prices']);
		if($donate_id >=0 && $donate_id < count($prices))
		{
		    $packet     = json_encode(array($credits[$donate_id],$prices[$donate_id]));
			
               if(!$info){
		          $inv        = mssql_query("Insert into [DTweb_EpayBG_Orders] (account,packet,time,ip,invoice) values ('{$username}','{$packet}','".time()."','".ip2long(ip())."','".$rand."')");
		        }
				
					$invoice_nr = mssql_fetch_array(mssql_query("Select TOP 1 * from  DTweb_EpayBG_Orders where account = '".$username."' order by id desc"));
			       
					return array($rand,$invoice_nr['id'],$username,$prices[$donate_id],$credits[$donate_id],$option['epay_client_nr'],$option['epay_email'],$option['epay_sec_key'],$invoice_nr['time'],$option['epay_inv_exp'],$option['epay_test']);		      
            }
		else
	    {
	    	 return false;
	    }
    }
  	 else
	{
	    return false;
	}
}

function pgol_prepay($info = false){
require $_SERVER['DOCUMENT_ROOT']."/config.php";
   	if(isset($_POST['pgol_id']) && isset($_SESSION['username']))
	{
		$donate_id  = (int)$_POST['pgol_id'];
		$donate_id  = $donate_id -1;
        $username   = $_SESSION['username'];
		$prices     = array_values($option['pgol_prices']);
		$credits    = array_keys($option['pgol_prices']);
		if($donate_id >=0 && $donate_id < count($prices))
		{
		    $packet     = json_encode(array($credits[$donate_id],$prices[$donate_id]));
			
               if(!$info){
		          $inv        = mssql_query("Insert into [DTweb_PayGol_Orders] (account,packet,time,ip) values ('{$username}','{$packet}','".time()."','".ip2long(ip())."')");
		        }
				
					$invoice_nr = mssql_fetch_array(mssql_query("Select TOP 1 * from  DTweb_PayGol_Orders where account = '".$username."' order by id desc"));
			       
					return array($invoice_nr['id'],$username,$prices[$donate_id],$credits[$donate_id],$option['pgol_currency'],$option['pgol_sec_key'],$option['pgol_ser_id']);		      
            }
		else
	    {
	    	 return false;
	    }
    }
  	 else
	{
	    return false;
	}
}

function paygol_prove(){
require $_SERVER['DOCUMENT_ROOT']."/config.php";
if(pgol_prepay()){	

$info       = pgol_prepay(true);
$hash_item	= md5($info[0] .$info[1].$info[2].$info[3].$info[4].$info[5].uniqid(microtime(),1));		
$rand       = rand(100,900);
mssql_query("Update DTweb_PayGol_Orders set hash = '".$hash_item."' where id='".$info[0]."'");

echo'
<div class="col-lg-7" style="background:#eeeeee">
<form class="new form" name="pg_frm" method="post" action="https://www.paygol.com/pay" >
   <input type="hidden" name="pg_serviceid" value="'.$info[6].'"/>
   <input type="hidden" name="pg_currency" value="'.$info[4].'"/>
   <input type="hidden" name="pg_name" value="Payment for '.$option['web_name'].'"/>
   <input type="hidden" name="pg_custom" value="'.$hash_item.'"/>
   <input type="hidden" name="pg_price" value="'.$info[2].'"/>
   <input type="hidden" name="pg_return_url" value="'.$option['web_address'].'/?p=buycredits&success=1"/>
   <input type="hidden" name="pg_cancel_url" value="'.$option['web_address'].'/?p=buycredits&success=0"/>
    
<table border="0"  max-width="400px" class="table" style="width:300px;margin:0 auto;">
                    <tr><td colspan="2"><img style="margin-top:20x;background:rgba(255,255,255,0.9)" src="imgs/payments/PayGol_img.png"/></td></tr>
                    <tr><td class="title">Invoice No: </td><td style="font-weight:900">'.$rand.'</td></tr>
                    <tr><td class="title">Credits:</td><td style="font-weight:900">x '.$info[3].'</td></tr>
                    <tr><td class="title">Price:</td><td style="font-weight:900"> '.$info[2].'&nbsp; '.$option['pgol_currency'].'</td></tr>
                    <tr><td class="title" style="padding-right:60px;" colspan="2">
					<INPUT value="Agree" class="btn btn-primary" name="pg_button" type="submit"/></TD></tr>
        
        </table>


   </form></div>';


		}	
}

function mobio_config($country,$price){
	require $_SERVER['DOCUMENT_ROOT']."/config.php";
    foreach($option['mobio_services'] as $key => $value)
    {
    	$keys  = array_keys($value);
    	
    	if($keys[0] == $country)
    	{
            foreach($value as $new => $ben)
    		{
                if(is_array($ben))
    			{
                	$key   = array_values($ben);
    	    		if($price == $new)
    				{
						return array($key[0],$key[1],$key[3]);
    	    		}		
                }
            }
    	}
     }
}

function pwal_form($username,$email){
	require $_SERVER['DOCUMENT_ROOT']."/config.php";
	require($_SERVER['DOCUMENT_ROOT']."/lib/paymentwall.php");
	Paymentwall_Config::getInstance()->set(array('api_type'    => Paymentwall_Config::API_VC,'public_key'  => $option["paymentwall_id"],'private_key' => $option["paymentwall_secret"]));
	    $widget = new Paymentwall_Widget(
    	$username,       
    	$option["pwall_widget"],      
    	array(),     
    	array('email' => $email) 
    );
  echo $widget->getHtmlCode();
}



function epay_prove(){
require $_SERVER['DOCUMENT_ROOT']."/config.php";
if(epay_prepay()){	
         $info       = epay_prepay(true);

if($info[10]=== 1){
	$submit_url = 'https://devep2.datamax.bg/ep2/epay2_demo/';
}
else{
	$submit_url = 'https://www.epay.bg/';
}
   
$exp_date   = date("d.m.Y h:i:s",strtotime($info[9],$info[8]));                

$data = <<<DATA
MIN={$info[5]}
INVOICE={$info[1]}
AMOUNT={$info[3]}
EXP_TIME={$exp_date}
DESCR={$info[2]}
DATA;

$ENCODED  = base64_encode($data);
$CHECKSUM = hmac('sha1', $ENCODED, $info[7]); 
mssql_query("Update DTweb_EpayBG_Orders set hash = '".$CHECKSUM."' where id='".$info[1]."'");
echo"
        <form action=".$submit_url." method=POST>
          <input type=hidden name=PAGE value='paylogin'/>
          <input type=hidden name=ENCODED value='".$ENCODED."'/>
          <input type=hidden name=CHECKSUM value='".$CHECKSUM."'/>
          <input type=hidden name=URL_OK value=".$option['web_address']."/?p=buycredits&success=1/>
          <input type=hidden name=URL_CANCEL value='".$option['web_address']."'/?p=buycredits&success=0/>
                <table border='0' width='400px' class='table' style='width:300px;margin:0 auto;'>
				    <tr><td colspan='2'><img style='margin-top:20px;background:rgba(255,255,255,0.9)' src='imgs/payments/epay_img.png'/></td></tr>
                    <tr><td class='title'>Invoice No: </td><td style='font-weight:900'>{$info[0]}</td></tr>
                    <tr><td class='title'>Credits:</td><td style='font-weight:900'>x {$info[4]}</td></tr>
                    <tr><td class='title'>Price:</td><td style='font-weight:900'> {$info[3]} BGN</td></tr>
                    <tr><td class='title' style='padding-right:60px;' colspan='2'><INPUT value='Agree' class='button' type='submit'/></TD></tr>
        </form>
                </table>";
		}	
}

function paypal_prepay(){
require $_SERVER['DOCUMENT_ROOT']."/config.php";
if(isset($_POST['ppitem_id']) && isset($_SESSION['username'])){	
		$donate_id  = (int)$_POST['ppitem_id'];
		$donate_id  = $donate_id -1;
        $username   = $_SESSION['username'];		
		$prices     = array_values($option['paypal_prices']);
		$credits    = array_keys($option['paypal_prices']);
		$currency   = $option['paypal_currency'];
		$hash_item	= md5($username.$prices[$donate_id].$currency.$credits[$donate_id].uniqid(microtime(),1));
		if($donate_id >= 0 && $donate_id < count($prices)){
		$insert_pre_donate	= mssql_query("INSERT INTO DTweb_PayPal_Orders (donate_id,amount,currency,credits,memb___id,hash) VALUES ('{$donate_id}','{$prices[$donate_id]}','{$currency}','{$credits[$donate_id]}','{$username}','{$hash_item}')");
		if($insert_pre_donate){
		    return array($donate_id,$prices[$donate_id],$currency,$credits[$donate_id],$username,$hash_item,$option['paypal_email'],$option['web_address']);		
		}
		else{
			return false;
		}
	}
	else{
		return false;
	}	
  }
  else{
	  return false;
  }
}

function ppal_prove(){
	
	if(paypal_prepay()){	
		$info = paypal_prepay();
		require $_SERVER['DOCUMENT_ROOT']."/config.php";
		   			echo '
					
						<table style="background:#eeeeee" class="table-stripped" border="0" >
						<tr><td colspan="2"><img style="margin-top:20x;background:rgba(255,255,255,0.9)" src="imgs/payments/PayPal_img.png"/></td></tr>
							<tr class="bgcol1">
								<td class="title" align="right" width="50%"><b>Username:</b></td>
								<td class="text_default">'.$info[4].'</td>
							</tr>
							<tr>
								<td class="title" align="right" width="50%"><b>Credits Issued:</b></td>
								<td class="text_default">'.number_format($info[3]).' </td>
							</tr>
							<tr class="bgcol1">
								<td class="title" align="right" width="50%"><b>Donate Amount:</b></td>
								<td class="text_default">'.$info[1].' '.$info[2].'</td>
							</tr>
					
						<form class="form" action="https://www.paypal.com/cgi-bin/webscr" method="post">	
							<tr>
							
								<td>
									<input type="hidden" name="cmd" value="_donations" />
									<input type="hidden" name="business" value="'.$info[6].'" />
									<input type="hidden" name="item_name" value="Donate for '.($option['web_name']).'" />
									<input type="hidden" name="currency_code" value="'.strtoupper($info[2]).'" />
									<input type="hidden" name="amount" value="'.$info[1].'" />
									<input type="hidden" name="no_shipping" value="1" />
									<input type="hidden" name="shipping" value="0.00" />
									<input type="hidden" name="item_number" value="'.$info[5].'"/>
									<input type="hidden" name="return" value="'.$info[7].'/payment_proccess.php?method=paypal" />
									<input type="hidden" name="cancel_return" value="'.$info[7].'/payment_proccess.php?method=paypal" />
									<input type="hidden" name="notify_url" value="'.$info[7].'/payment_proccess.php?method=paypal" />
									<input type="hidden" name="custom" value="'.$info[4].'" />
									<input type="hidden" name="no_note" value="1" />
									<input type="hidden" name="tax" value="0.00" />
								</td>
							</tr>
							<tr class="title"><td colspan="2"><button type="submit" class="btn btn-primary" class="button" >Agree</button></td></tr>
						</table>
					</form>';				
	   }	
}

function check_signature($params_array, $secret) {
   ksort($params_array);

   $str = '';
   foreach ($params_array as $k=>$v) {
     if($k != 'sig') {
       $str .= "$k=$v";
     }
   }
   $str .= $secret;
   $signature = md5($str);

   return ($signature);
}



?>