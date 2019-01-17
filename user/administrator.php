<?php
if(!defined('access')) {
          header('HTTP/1.0 403 Forbidden');
          exit;
}
     if(isset($_POST['search']) && !empty($_POST['itema'])){											   
  	    $itms = protect($_POST['itema']);
  	    $qry = "and name like '%".$itms."%'";
     }
	 else{
		 $itms = "";
		 $qry  = "";
	 }
	 
	 if(isset($_POST['addall'])){
		 $opt_price   = '["50:50","50:50","50:50","50:50","50:50","50:50"]';
		 $luck_price  = '[50,50]';
		 $lvl_price   = '["50:50","50:50","50:50","50:50","50:50","50:50","50:50","50:50","50:50","50:50","50:50","50:50","50:50","50:50","50:50"]';
		 $exl_price   = '["50:50","50:50","50:50","50:50","50:50","50:50"]';
		 $anc_price   = '["30:50","30:50"]';
		 $skill_price = '[30:50]';
		 $query       = "Update [r00tme_Upgrade_Item] set [upgradable]   =  1;";
		 $query      .= "Update [r00tme_Upgrade_Item] set [opt_price]    = '".$opt_price."' where [option]  = 1;";
		 $query      .= "Update [r00tme_Upgrade_Item] set [exl_price]       = '".$exl_price."';";
		 $query      .= "Update [r00tme_Upgrade_Item] set [anc_price]    = '".$anc_price."' where [ancient] = 1;";
		 $query      .= "Update [r00tme_Upgrade_Item] set [luck_price]   = '".$luck_price."' where [luck]   = 1;";
		 $query      .= "Update [r00tme_Upgrade_Item] set [skill_price]  = '".$skill_price."' where [skill] = 1;";
		 $query      .= "Update [r00tme_Upgrade_Item] set [lvl_price]    = '".$lvl_price."';";
		 mssql_query($query);
	 }
	 if(isset($_POST['clearall'])){
		 mssql_query("Update [r00tme_Upgrade_Item] set [upgradable] =0,[opt_price]='',[exl_price]='',[anc_price]='',[luck_price]='',[skill_price]='',[lvl_price]=''");
	 }
?>

<body>
    <div class="wrapper">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-content">
									<div class="col-sm-4">
	                                   <div>
									       <form class="form" method="post">
	                                          <input class="btn btn-primary" type="submit" name="addall" value="Add All"/>
											  <input class="btn btn-primary" type="submit" name="clearall" value="Clear All"/>
	                                        </form>
									   </div>
	                                </div>
<div class="toolbar">
    <form method='post' id="itm_names" class='form-inline'>
      <div class="col-sm-auto">
         <div class="input-group mb-2 mr-sm-2 mb-sm-0">
            <div class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></div>
            <input id="search-input" onkeyup="functions('itm_names')" value="<?php echo $itms?>" class="form-control input-lg" autocomplete="off" name='itema' spellcheck="false" autocorrect="off" tabindex="1">
          <div id="itm_namesm"></div>
		 </div>
		
		 <input type='submit' name='search' class='btn btn-primary' value='Search'/> 
      </div>
	</form>
</div>
   <?php

		$upgr           = "No";
		$luck           = "No";
		$skill          = "No";
		$exl            = "No";
		$ancse          = "No";
		$exl_opts       = "";
		$upgr_selected  = "";
		$upgr_selected1 = "selected";
		$anc_tbl        = "";
		$color          = "#ffffff";
		$skill_upd      = "";
		$luck_upd       = "";
	    $skill_tbl      = '';
	    $exl_count      = 6;
   		if(isset($_GET['item']) && !isset($_POST['search'])){												      
	        $sel_itm = (int)$_GET['item'];
			 if(!empty($sel_itm)){
				$check_all = mssql_num_rows(mssql_query("Select * from r00tme_Upgrade_Item")); 
				if($sel_itm < $check_all){
					$check_all = mssql_fetch_array(mssql_query("Select * from r00tme_Upgrade_Item where itemid='".$sel_itm."'"));
					 if(isset($_POST['upd_item'])){
                      	  $upgradable  = (int) $_POST['upgradable'];
						  
						  if(isset($_POST['skill_price']) && isset($_POST['skill_succ'])){
							$skill_price = (int) $_POST['skill_price']; 
                            $skill_succ  = (int) $_POST['skill_succ'];							
						  }	 
                          else{
                          	$skill_price = 0;
							$skill_succ  = 0;
                          }						  
						  $luck_succ   = (int) $_POST['luck_succ'];
						  $luck_price  = (int) $_POST['luck_price'];
						  if($skill_succ > 0 && $skill_price > 0){
						      $skill_upd   = json_encode(array($skill_succ,$skill_price));
						  }
						  if($luck_succ > 0 && $luck_price > 0){
						      $luck_upd   = json_encode(array($luck_succ,$luck_price));
						  }
					      if($check_all['exeopt']===2){
					     	 $exl_count  = 5;
					      }
                           	  mssql_query("Update r00tme_Upgrade_Item set
                      	                upgradable   = '".$upgradable."',
						            	skill_price  = '".$skill_upd."',
						            	luck_price   = '".$luck_upd."',
						            	lvl_price    = '".item_admin(15,"lvl")."',
						            	exl_price    = '".item_admin($exl_count,"exl")."',
						            	opt_price    = '".item_admin(6,"opt")."',
						            	anc_price    = '".item_admin(2,"anc")."'
                      	                where itemid = '".$check_all['itemid']."'	  
                      	            ");                     	 
                       }
					$check_all = mssql_fetch_array(mssql_query("Select * from r00tme_Upgrade_Item where itemid='".$sel_itm."'"));
// Update Query ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////					
				   
					    switch ($check_all['exeopt']){
 		                             	//All Weapons
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
                                            break;
		                             	//Wings Only ** DB Equal to DarkMaster Server files Item.kor - 97/99XT 
                                        case 2:
                                            $op1 = ' Life + Increased';
                                            $op2 = ' Mana + Increased';
                                            $op3 = ' 10% Mana loss instead of Life';
                                            $op4 = ' +50 of damage transfered as Life';
                                            $op5 = ' Increase Attacking(wizardry)speed +5';
                                            $op6 = '';
                                            $inf = 'Additional Damage';
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
		                             		break;
                                    }
									
			$exl_opt    = array($op1,$op2,$op3,$op4,$op5,$op6);
			$skills     = json_decode($check_all['skill_price']);
            $lucks      = json_decode($check_all['luck_price']);			
        
			if($check_all['upgradable'] == 1){
				$upgr_selected = "selected";
				$upgr_selected1 = "";
				$upgr = "Yes";
				$color = "#cecebf";
			}
			if($check_all['skill'] === 1){
				$skill_tbl= '	<tr> 
                                    <td style="text-align:center;background:#eeeeee;color:#000000;font-weight:600">Add Skill											         
		    			                    <td width="200px"><input class="form-control" value="'.$skills[0].'"  title= "Type Success Rate in &#37;" type="number" placeholder="Success Rate &#37;" name="skill_succ"/> </td>
		    			                    <td><input class="form-control" value="'.$skills[1].'"  type="number" placeholder="Skill Price"  name="skill_price"/> Credits</td>
		    				        </td>                                     											   
		    			       </tr>';
			}
            if($check_all['luck'] == 1){
				$luck = "Yes";
			}
			if($check_all['skill'] == 1){
				$skill = "Yes";
			}
			if($check_all['exeopt'] == 1){
				$exl = "Yes";
			}
		 	
		   if($check_all['type'] < 7){
		        $opt_txt = "Damage"; 
		        $m = 4;
		   }
		   else{
		        $opt_txt = "Defence"; 
		        $m = 5;
		   }
		   if($check_all['ancient'] == 1){
               $anc_tbl   = item_opt_ren($sel_itm,2,"anc","Stamina",5);						 
		   }
		   if($check_all['exeopt']===2){
					$exl_count  = 5;
			}   
		echo 
		    '
		  
		      <div class="col-lg-auto">
		          <form method="post" class="form-inline">
		    	     <div class="material-datatables">								
                        <table class="table table-stripped text-left" cellspacing="0" >
                            <thead  class="col-lg-auto" style="font-weight:900;font-size:13pt;text-shadow:1px 1px #000;background:#2196f3;color:white;"> 
		    			        <tr>
		    			           <td colspan="11" class="text-center">'.$check_all['name'].'</td>
		    				    </tr> 
		    			    </thead>
							
                             <tbody>
		    				    <tr> 
                               	   <td style="text-align:center;background:#eeeeee;color:#000000;font-weight:600" >Upgradable										         
		    			              <td class="col-sm-2">
		    						       <select  style="width:150px;background:#2196f3;height:40px;margin-top:10px;padding:10px 10px;" data-header="Available For Upgrades" class="btn btn-primary"  data-style="btn-primary" name="upgradable">
		    						           <option value="1" '.$upgr_selected.'>Yes</option>
		    						           <option value="0" '.$upgr_selected1.'>No</option>
		    						       </select>
									  </td>
	                                   <td ><input type="submit" name="upd_item" class="btn btn-primary add_opt" value="Update"/></td>
		    				       </td>                                        											   
		    			        </tr>
                             '.$skill_tbl.'
		    			       <tr> 
                                   <td style="text-align:center;background:#eeeeee;color:#000000;font-weight:600">Add Luck											         
		    			                    <td><input class="form-control" value="'.$lucks[0].'" type="number" title= "Type Success Rate in &#37;" placeholder="Success Rate &#37;" name="luck_succ"/> </td>
		    			                    <td><input class="form-control" value="'.$lucks[1].'" type="number" placeholder="Luck Price" name="luck_price"/> Credits</td>
		    			       	   </td>                                        											   
		    			       </tr>
                               <tr><td></td></tr>
	                              '.item_opt_ren($sel_itm,15,"lvl","Level",1).'    	
                                  '.item_opt_ren($sel_itm,$exl_count,"exl","Excellent",1,$exl_opt).'		    	
		    				      '.item_opt_ren($sel_itm,6,"opt",$opt_txt,$m).'		    	
                                  '.$anc_tbl.'												   
		    			  <tbody>
		    		   </table>
		    		</div>
		         </form>
		      </div>  
		
		';														
	}
	else{
		 echo "Please choose an existing item";
			}
		  }
		}		 
	else {
		
    echo '

              <div class="table-responsive col-md-12">
                                        <table id="datatables" class="table table-striped table-hover" style="margin-bottom:50px;border:5px solid #2196f3" cellspacing="0" width="100%" style="width:100%">
                                      		<thead>
                                                <tr style="background:#2196f3;color:white">
                                                    <th>Name</th>
                                                    <th>Type/Id</th>
                                                    <th>Luck</th>
                                                    <th>Skill</th>                              
													<th>Ancient</th>
													<th>Upgradable</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
		 ';
	   

			     
			    $all_items = mssql_query("Select * from r00tme_Upgrade_Item where type < 14 and name not like 'Scroll%' and name not like 'horn%' and name not like 'orb%' and name not like 'jewel%' ".$qry."");
			      while($items = mssql_fetch_array($all_items)){
					  												 										
					  if($items['upgradable'] == 1){
						  $upgr = "Yes";
						  $color = "rgba(191,255,207,0.3)";
					  }
					  else{
						  $upgr = "No";
						  $color = "rgba(255,191,191,0.3)";						  
					  }
                      if($items['luck'] == 1){$luck = "<div style='font-weight:600;color:white;text-shadow:1px 1px #000;background:#2196f3'>Yes</div>";}else{$luck = "<div style='font-weight:600;color:white;text-shadow:1px 1px #000;background:#ff9999'>No</div>";}
					  if($items['skill'] == 1){$skill = "<div style='font-weight:600;color:white;text-shadow:1px 1px #000;background:#2196f3'>Yes</div>";}else{$skill = "<div style='font-weight:600;color:white;text-shadow:1px 1px #000;background:#ff9999'>No</div>";}		
					  if($items['ancient'] == 1){$ancse = "<div style='font-weight:600;color:white;text-shadow:1px 1px #000;background:#2196f3'>Yes</div>";}else{$ancse = "<div style='font-weight:600;color:white;text-shadow:1px 1px #000;background:#ff9999'>No</div>";}
					  echo 
					  '
					  <tbody >
					   <tr style="background:'.$color.'">
                               <th>'.$items['name'].'</th>
                               <th>'.$items['type'].'/'.$items['id'].'</th>
                               <th style="text-align:center;">'.$luck.'</th>
                               <th style="text-align:center;">'.$skill.'</th>
                    
							   <th style="text-align:center;">'.$ancse.'</th>
						<th>'.$upgr.'</th>
                               <th><a href="?p=666&item='.$items['itemid'].'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></th>
                              </tr> 
					  </tbody>				  
					  ';
				  }
	}											  						
  echo'                                </table>';
 ?>										
                                    </div>
                                </div>                            
                            </div>                          
                        </div>                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>


