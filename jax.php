<?php

session_start();
require("config.php");
require("funcs.php");

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

if(logged() === false){
	exit();
}
else{
   
$account = $_SESSION['username'];
switch(trim($_GET['f'])){
	
// Item Upgrade Function
 	
  case "success": 
  
    if(isset($_POST['option'])){
	 	
    switch($options['fail_option']){
		case 1: $upgr_text= "downgraded or remain the same";break;
		default: $upgr_text= "permanently deleted"; break;
		}
		
     if(is_upgradable($_POST['item_id']) === true){
        $real_item     = base64_decode($_POST['item_id']);	   
		$cur_item      = ItemInfoUser($real_item);
		$result        = count(count_chars(substr($real_item,6,8), 1));
	    $cur_lvl       = hexdec(substr($real_item, 2, 2));
		$cur_opt       = hexdec(substr($real_item, 14, 2));
		$cur_anc       = hexdec(substr($real_item, 16, 2));
		$item_info     = mssql_fetch_array(mssql_query("Select * from r00tme_Upgrade_Item where itemid='".$cur_item['item_id']."'"));
        $optionssa     = json_decode($item_info['opt_price']);
	    $skill_price   = json_decode($item_info['skill_price']);
        $luck_price    = json_decode($item_info['luck_price']);
	    $exl_dets      = json_decode($item_info['exl_price']);
	    $level_dets    = json_decode($item_info['lvl_price']);
        $level_price   = explode(":",$level_dets[$cur_item['level']]);	
	    $anc_dets      = json_decode($item_info['anc_price']);
	    if($cur_item['anc'] <> NULL){
	    $anc_price     = explode(":",$anc_dets[$cur_item['anc'] - 1]);
	    }
		else{
		$anc_price   = explode(":",$anc_dets[0]);  
	    }	

		$empty       = str_repeat('F',20);
	    $error       = 1;
        $fail        = 3;
	    $maikati     = array(1,2,4,8,16,32);
		$errors      = array();
		foreach($exl_dets as $key => $value){
			if($value <> 0){
		        $ouh[] = ($key+1);
				if(!in_array($ouh,$cur_item['exl_option'])){			
	              $exl_shift = array_diff($ouh, $cur_item['exl_option']);			
			}
		  }
		}
		
    if(substr($real_item,6,8) <> "00000000" && $result <> 2){
	 
		$check_res = mssql_fetch_array(mssql_query("Select * from [".$options['credits_tbl']."] where [".$options['credits_usr']."]='".$account."'"));
		$add_opt   = (int)$_POST['option'];	
        $exl_opn   = substr($add_opt,3,1);
		// Add Excellent
		if($add_opt > 1660 && $add_opt < 1667 && in_array($exl_opn,$exl_shift)){
        if(!in_array($exl_opn,$cur_item['exl_option'])){$error = 0;}else{$errors[] = "This item has that option";$error = 1; }			
            $exl_price = explode(":",$exl_dets[$exl_opn-1]);	  	
		    $credits   = $exl_price[1];
			$text="+Excellent";
            if((bool)win($exl_price[0]) == true){				
			     $new_item = substr_replace($real_item, sprintf("%02X", $cur_opt + $maikati[$exl_opn-1]),14,2);
				 $fail     = 0;
			 }
             else{
             	 $fail     = 1;
                  if($options['fail_option'] == 0){
			        $new_item = $empty;
			        $msg_fail = "deleted";
			      }
			      else{
			        $new_item = $real_item; 
			        $msg_fail = "downgraded";
			    }	
            }		
		 }
		else{
			switch($add_opt){
				case 1: // Add Luck 
				if($cur_item['srch_luck'] == 0 && luck_add($cur_item['item_id'])=== true){}else{ $errors[]= "This item has luck already or luck can not apply to this item";}
				$credits = $luck_price[1] ; $text="+Luck";  			         
						 if((bool)win($luck_price[0]) == true){
                           $new_item = substr_replace($real_item,sprintf("%02X", $cur_lvl + 4),2,2);  
                           $fail     = 0;						   
						 }						 
						 else{
							 $fail     = 1;
                           	   if($options['fail_option'] == 0){
								   $new_item = $empty;
								   $msg_fail = "deleted";
							   }
							   else{
								   $new_item = $real_item;
								   $msg_fail = "downgraded";
							   }
                           }						 
				    break;
				case 2: // Add Skill				
				if($cur_item['srch_skill'] == 0 && skill_add($cur_item['item_id'])=== true){$error = 0;}else{$errors[]= "This item has skill already or skill can not apply to this item";}
				$credits = $skill_price[1]; $text="+Skill"; 
						if((bool)win($skill_price[0]) == true){
                           $new_item = substr_replace($real_item,sprintf("%02X", $cur_lvl + 128),2,2);
                           $fail     = 0;						   
						 }						 
						 else{
							   $fail     = 1;
                           	   if($options['fail_option'] == 0){
								   $new_item = $empty;
								    $msg_fail = "deleted";
							   }
							   else{
								   $new_item = $real_item;
								    $msg_fail = "downgraded";
							   }
                           }
				    break;
				case 3: // Add Level
				   if(($cur_item['level']+1) < 16){$error=0;}else{$errors[]= "This item is at maximum level";}
		           $credits = $level_price[1]; $text="+Level"; 
			            if((bool)win($level_price[0]) == true){
                           $new_item = substr_replace($real_item,sprintf("%02X", $cur_lvl + 8),2,2);	
                           $fail     = 0;						   
						 }						 
						 else{
							  $fail     = 1;
                           	   if($options['fail_option'] == 0){
								   $new_item = $empty;
								   $msg_fail = "deleted";
	
							   }
							   else{
								 
								   if($cur_item['level'] > 0){
									   $low_lvl = $cur_lvl - (bcmul(mt_rand(0,($cur_item['level']-1)),8));
									   $new_item = substr_replace($real_item,sprintf("%02X",$low_lvl),2,2);
									   $msg_fail = "downgraded";

								   }
								   else{
									  $new_item = $real_item;
									  $msg_fail = "downgraded";
								   }	
							   }
                           }			  
				  break;
				case 4: // Add Options 
	
				if($add_opt == 4 && add_opt($cur_item['item_id'],$cur_item) <> false){$error = 0;}else{$errors[]= "This item option can not be upgraded";}
				 $opt_price = add_opt($cur_item['item_id'],$cur_item);
				 $credits = $opt_price[1]; $text="+Option";
				 $checck = $cur_lvl-3;				
				  if($cur_item['id'] > 5){$armor_change = 15;}else{$armor_change = 12;}			  
				   	    if($cur_item['opts'] == $armor_change && $cur_item['exl_count'] == 0){
                           if((bool)win($opt_price[0]) == true){                            						   
			                 $new_item = substr_replace($real_item, sprintf("%02X", $cur_opt + 64),14,2);
						     $new_item = substr_replace($new_item,sprintf("%02X",$checck),2,2); 
							      $fail = 0;
						   }
                           else{
							   $fail = 1;
                           	   if($options['fail_option'] == 0){
								   $new_item = $empty;
								   $msg_fail = "deleted";
							   }
							   else{
						           $new_item = substr_replace($real_item,sprintf("%02X",rand(0,3)),2,2); 
								   $msg_fail = "downgraded";
							   }
                           }						   
			            }
						elseif($cur_item['opts'] >$armor_change && $cur_item['exl_count'] == 0){
							if((bool)win($opt_price[0]) == true){	
							       $new_item = substr_replace($real_item,sprintf("%02X", $cur_lvl + 1),2,2);
							       $fail     = 0;
							}
							else{
								$fail     = 1;
                           	   if($options['fail_option'] == 0){
								   $new_item = $empty;
								   $msg_fail = "deleted";
							   }
							   else{
								   $new_item = substr_replace($real_item, sprintf("%02X", ($cur_opt - 64)),14,2);
								   $msg_fail = "downgraded";
							   }
                           }
						}
						elseif($cur_item['opts'] == $armor_change && $cur_item['exl_count'] > 0){
							if((bool)win($opt_price[0]) == true){	
							   $new_item = substr_replace($real_item, sprintf("%02X", $cur_opt + 64),14,2);
							   $new_item = substr_replace($new_item,sprintf("%02X",$checck),2,2); 
							      $fail     = 0;
						    }
							else{
								$fail     = 1;
                           	   if($options['fail_option'] == 0){
								   $new_item = $empty;
								   $msg_fail = "deleted";
							   }
							   else{
								   $new_item = substr_replace($real_item, sprintf("%02X", ($cur_opt - 64)),14,2);
								   $msg_fail = "downgraded";								   
							   }
                            }
						}
						else{
						   $new_item = substr_replace($real_item,sprintf("%02X", $cur_lvl + 1),2,2);
                            $fail     = 0;						   
						}				     
					 break;
					 case 5: // Add Ancient
					    if($cur_item['anc']  === NULL || $cur_item['anc'] < 2){$error = 0;}else{$errors[]= "This item option can not be upgraded";}
					    $credits = $anc_price[1]  ; $text="+Stamina";
					    if((bool)win($anc_price[0]) == true){
                             if($cur_anc == 0){
							     $new_item = substr_replace($real_item, sprintf("%02X", $cur_anc + 5),16,2);	
							   }
                             else{
                               $new_item = substr_replace($real_item, sprintf("%02X", $cur_anc + 4),16,2);
                             }								    
							   $fail     = 0;
						    }
						else{
							  $fail     = 1;
                              if($options['fail_option'] == 0){
							     $new_item = $empty;
							     $msg_fail = "deleted";
						      }
						      else{
							     $new_item = substr_replace($real_item, sprintf("%02X", 0),16,2);
							     $msg_fail = "downgraded";								 
						       }
                            }
						
					 break;
				default: $error = 1; 
			break;
		}
	}
	
	if($check_res[$options['credits_col']] >= $credits){
       $error = 0;
	}
	else{
		$errors[]= "You do not have enough credits";
		$error = 1;
		}
	}
  else{
      $errors[] = "This item can not be upgraded";$error = 1;
}		
	
foreach($errors as $err_message){
      echo "<div style='position:absolute;top:0;margin-top:80px;z-index:22000' class='container'><div class='col-md-11'><div class='text-center alert alert-danger'>".$err_message."</div></div></div>"; break;
}

if($error === 0 && count($errors) == 0){
	$_SESSION['error'] = 0;
    if($fail === 0){
      $suc = "Success";		
	  echo '<script>document.getElementById(\'susound\').play();</script>';
      echo "<div style='position:absolute;top:0;margin-top:80px;z-index:22000;' class='col-lg-10'><div class='text-center alert alert-success'>Great! You have successfully upgraded your item <img src='imgs/kef.gif'/></div></div>";
      $time = 2300;
    }
    elseif($fail === 1){
	
        $suc = $upgr_text;			
		echo '<script>document.getElementById(\'unsound\').play();</script>';
        echo "<div style='position:absolute;top:0;margin-top:80px;z-index:22000;'class='col-lg-10' ><div class='text-center alert alert-danger'>Unfortunatelly you didn't have enough luck. Your item was ".$msg_fail. " <img src='imgs/qd.gif'/></div></div>";	
        $time = 2300;
      }	 
		 $dataa = json_encode(array($suc,$options['fail_option'],$check_res[$options['credits_col']],$credits,($check_res[$options['credits_col']]-$credits),$level_price[0],$text));
         $mynewitems = str_replace($real_item, $new_item, all_items($account,1200)); 
	     mssql_query("Insert into [r00tme_Upgrade_Item_Logs] (username,old_item_hex,new_item_hex,data,date,ip) Values ('".$_SESSION['username']."','".$real_item."','".$new_item."','".$dataa."','".time()."','".ip()."')");
	     mssql_query("Update [warehouse] set [Items]=0x" . $mynewitems . " WHERE [AccountId]='" . $account . "'");
         mssql_query("Update [".$options['credits_tbl']."] set [".$options['credits_col']."] = [".$options['credits_col']."] - ".$credits." where [".$options['credits_usr']."]='".$_SESSION['username']."'");    
         echo "<script> setTimeout(function(){location.href='?p=1&nr=".base64_encode($new_item)."'} , '".$time."');  </script>";    
	   }
        else{
	      $_SESSION['error'] = 1;
       }	   
     }
	 else{
       $_SESSION['error'] = 1;	
	 }	
   }
  break;
  
// Item Upgrade Names   

case  "itm_names":
              echo " <div class='col-sm-4' style='text-align:left;position:absolute;overflow-y:auto;z-index:1000;height:250px;min-width:250px'><div class='list-group'>";
              $names = mssql_query("Select * from r00tme_Upgrade_Item where name like '%".$_POST['itema']."%'");
		      while($itm_names = mssql_fetch_array($names)){
		          echo "<a class='list-group-item' href='?p=666&item="  . $itm_names['itemid'] . "'>"  . $itm_names['name'] . "</a>";
		      }
			  echo "</div></div>";
  break;
  
// Item Upgrade Text 
 
case "upgr_details":

	if(is_item_ware(base64_decode($_GET["itemhex"]),20) === true){
	  $itemid      = (int)$_GET['item'];
	  $opt         = (int)$_GET['opt'];
	  $itemhex     = base64_decode($_GET["itemhex"]);
	  $item        = ItemInfoUser($itemhex);
	  if(!empty($item) && !empty($opt)){
		  $add_txt     = '';
  	      $item_info   = mssql_fetch_array(mssql_query("Select * from r00tme_Upgrade_Item where itemid='".$itemid."'"));
	      $skill_price = json_decode($item_info['skill_price']);
          $luck_price  = json_decode($item_info['luck_price']);
	      $exl_dets    = json_decode($item_info['exl_price']);
	      $level_dets  = json_decode($item_info['lvl_price']);
	      $anc_dets    = json_decode($item_info['anc_price']);	
	  	  $anc_price   = explode(":",$anc_dets[0]); 
          if($item['level'] < 15){
			$level_price = explode(":",$level_dets[$item['level']]);  
		  }		
		  
        if($opt === 1){  	  
	  	     	$add_txt = "Adding Luck </br><i><span style='font-size:8pt'>Cost ".$luck_price[1]." credtis / Success Chance ".$luck_price[0]." &#37;</i></span>";
		}
		elseif($opt === 1){	  	     
	  	     	$add_txt = "Adding Skill </br><i><span style='font-size:8pt'>Cost ".$skill_price[1]." credtis  / Success Chance ".$skill_price[0]." &#37;</i></span>";
		}
	    elseif($opt === 3){
	  	        $next_level  = $item['level'] +1;
	  	     	$add_txt = "Adding Level + ".$next_level." </br><i><span style='font-size:8pt'>Cost ".$level_price[1]." credtis/ Success Chance ".$level_price[0]." &#37;</i></span>";
	    }
	    elseif($opt === 4){
		 if((add_opt($itemid,$item) <> false)){
			$opt_price = add_opt($itemid,$item);
			if($item['id'] < 6){
			$add_txt = "Adding ".($item['opts']+4)." Damage </br><i><span style='font-size:8pt'>Cost ".$opt_price[1]." credtis / Success Chance ".$opt_price[0]."</i></span>";      		
		  }
		  else{
		    $add_txt = "Adding ".($item['opts']+5)." Defense </br><i><span style='font-size:8pt'>Cost ".$opt_price[1]." credtis / Success Chance ".$opt_price[0]."</i></span>"; 
		  }	
		 }		
	    }
	    elseif($opt === 5){
			   if($item['anc'] === NULL){
			        $text_anc = "Make ancient (+5 stamina)"; 
		           }
		       else{
			        $text_anc = "Adding +5 stamina (Ancient)";   
		        }
			    $add_txt = "".$text_anc."</br><i><span style='font-size:8pt'>Cost ".$anc_price[1]." credtis/ Success Chance ".$anc_price[0]." &#37;</i></span>";			
	    }
		elseif($opt > 1660 && $opt < 1667 ){
				    $exl_opn    = substr($opt,3,1);
					$exl_price  = explode(":",$exl_dets[$exl_opn-1]);	
					$add_txt    = "Adding ".$item['exl_text'][($exl_opn-1)]." </br><i><span style='font-size:8pt'>Cost ".$exl_price[1]." credtis | Success Chance ".$exl_price[0]." &#37;</i></span></li>";			
	    }
		else{
			        $add_txt = '';  
	    }
	       echo $add_txt;
            			   
	      }
	}
	else{
		echo "    <div class='panel panel-default'>
                         <div class='list-group-item list-group-item-info '>
	  	  			   <div class='text-left'>
                             <div class='panel-body' style='color:#444;' >
            			        <ul class='list-group'>       				    
            					  This item doesn't belong to you!
            			        </ul>
            			     </div>
                           </div>
	  	  			</div>
	  	  		 </div>";
	}
    break;
	
// Item Upgrade Effects 

    case "item_effect":
        if($_SESSION['error'] == 0){
  	      echo' 		
		  <div class="col-sm-1" style="position:absolute;"><img style="width:100px;height:100px;margin-left:80px" id="images" src="imgs/fire.gif" /></div>
		  ';
        }
    break;
	
// Payment Function Mobio	

  	case "mobio":
                if(isset($_POST['price']) && isset($_POST['account']) && isset($_POST['country']))
	            {	
	                $phone_code = "";
	            	$sms_text   = "";
	            	$credits    = "";
	            	$price      = "";
	            	$account    = $_POST['account'];
	                foreach($option['mobio_services'] as $key => $value)
	            	{
	            		$keys  = array_keys($value);
	            		if($keys[0] == $_POST['country'])
	            		{
	                        foreach($value as $new => $ben)
	            			{
                                if(is_array($ben))
	            				{
                                	$key   = array_values($ben);
	            					$price      .= "<option value='".$key[3]."'>".$key[3]."</option>";
	            		    		if($_POST['price'] == $key[3])
	            					{
	            		    			$phone_code .=$key[4];
	            		    			$sms_text   .=$key[2];
	            		    			$credits    .=$key[1];
	            		    		}		
                                }
	                        }
	            		}
                     }
	            	 if($_POST['country']<>"BG"){
	            		 $account = "";
	            	 }
	            	 if($phone_code <> NULL && $sms_text <> NULL  && $credits <> NULL ){
	            		echo '
	            	       <div style="background:#ffffff;padding:10px 10px;border-radius:5px 5px 5px 5px ">
	            	          Please send message with text <span style="color:#337ab7;font-weight:700"> '.$sms_text.'&nbsp;'.$account.'</span>  to number <span style="color:#337ab7;font-weight:700">'.$phone_code.' </span>	
	            	         and you will automatically receive <span style="color:#337ab7;font-weight:700">'.$credits.'</span> credits into your account
	            	       </div>'; 
	            	 }
	            	 else{
	            		 echo '<div style="background:#ffffff;padding:10px 10px;border-radius:5px 5px 5px 5px ">Please selected related to the chosen  country amount and currency.</div>';
	            	 }
                
	            }
				break;
				
// Payment Function Fortumo	

case "fortumo":
				    if(!empty($_POST['acc'])){
				           $account = trim($_POST['acc']);
				           $details = mssql_fetch_array(mssql_query("Select * from Memb_Info where memb___id='".$account."'"));
			        
				       if($details['memb___id'] <> null){
				    	   $hash    = hmac("SHA1",$account.$details['reg_ip'].$details['reg_date'],$option["fortumo_id"]);
				    	 mssql_query("Insert into DTweb_Fortumo_Orders (account,time,ip,hash) values ('".$account."','".time()."','".ip2long(ip())."','".$hash."')");   
				       }
   		            }
			break;
  }
 }
}
else{
	echo "<script> setTimeout(function(){location.href='/'} , '1');  </script>"; 
}
?>