<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<?php

if(!defined('access')) {
          header('HTTP/1.0 403 Forbidden');
          exit;
}

$rows  = mssql_query("Select * from r00tme_Upgrade_Item_Logs where username='".$_SESSION['username']."' order by date desc");
$i   = 0;
$op  = 0;
$opt = 0;
$sta = 0;
$lvl = 0;
$ski = 0;
$luc = 0;
$exl = 0;
$sword = 0;$axes = 0;$maces = 0;$scepters = 0;$spears = 0;$bows = 0;$crossbows = 0;$staffs = 0;$shields = 0;
$helm  = 0;$armor = 0;$pants = 0;$gloves = 0;$boots = 0;$wings = 0;$rings = 0;$pendants = 0;
echo "
    <div class='row'>
        <div class='col-lg-12'>
			        <div class='col-sm-4'>
			    <div class='panel panel-default'>
			      <div class='panel-heading'><h4>Upgrade Success Percentage</h4></div>
			        <div class='panel-body'>    <div id='chart_div'></div></div>
	            </div>
	        </div>
				        <div class='col-sm-8'>
						  <div class='col-sm-6'>
			               <div class='panel panel-default'>
			                 <div class='panel-heading'><h4> Weapons Categories</h4></div>
			                   <div class='panel-body' style='margin:10px 10px'>
							   <div  id='chart_div1'></div>
							   
							   </div>
	                         </div>
							 </div>
							  <div class='col-sm-6'>
			               <div class='panel panel-default'>
			                 <div class='panel-heading'><h4>Armor Categories</h4></div>
			                   <div class='panel-body' style='margin:10px 10px'>
							   <div  id='chart_div2'></div>
							   
							   </div>
	                         </div>
							 </div>
	                     </div>
		    <div class='col-sm-12'>
			 <div class='panel panel-default'>
			 <div class='panel-heading'><h4>Items Upgrade History</h4></div>
			 <div class='panel-body'>
			<table style='background:#ffffff' class='table table-bordered table-responsive' >
			  <thead style='background:#d4d4d4;font-size:10pt;'>
			    <tr>
				   <th scope='col'>#</th>
				   <th scope='col'>Date</th>
				   <th scope='col'>Old Item</th>
				   <th scope='col'>New Item</th>
				   <th scope='col'>Credits Before</th>
				   <th scope='col'>Spent</th>
				   <th scope='col'>Option Attempt</th>
				   <th scope='col'>Success Rate</th>
				   <th scope='col'>Result</th>
				</tr>
			   </thead>
";
    
    while($row = mssql_fetch_array($rows)){
		$data   = json_decode($row['data']);
		$item   = ItemInfoUser($row['old_item_hex']);
		$item1  = ItemInfoUser($row['new_item_hex']);
        switch($item['type']){
			case 0: $sword++; break;
			case 1: $axes++; break;
			case 2: 
                if ($item['id'] >= 8){
				    $scepters++;
			    }
				else{
					$maces++;
				}
				break;
			case 3: $spears++; break;
			case 4: 
                if ($item['id'] >= 8){
				    $crossbows++;
			    }else{
					$bows++;
				}
				break;
			case 5: $staffs++; break;
			case 6: $shields++; break;
			case 7: $helm++; break;
			case 8: $armor++; break;
			case 9: $pants++; break;
			case 10: $gloves++; break;
			case 11: $boots++; break;
			case 12: 
                if ($item['id'] < 7){
				    $wings++;
			    }
				else{
					$bows++;
				}break;
			case 13:
			     $ring_ar    = array(8,9,20,21,22,23,24,25);
				 $pendan_ar  = array(12,13,26,27,28);
				 $cape_l_ar  = array(30);
                  if(in_array($item['id'],$ring_ar)){
					  $rings++;
				  }	
                  elseif(in_array($item['id'],$pendan_ar)){
					 $pendants++; 
				  }
                  elseif(in_array($item['id'],$cape_l_ar)){
					  $wings++; 
				  }			  
	
				else{
					$bows++;
				}break;
		}		
        $i++;
		     if($data[6] == "+Option"     && $data[0] == "Success")  { $opt++; $text = "+Option"    ;}
		     if($data[6] == "+Stamina"    && $data[0] == "Success")  { $sta++; $text = "+Stamina"   ;}
		     if($data[6] == "+Level"      && $data[0] == "Success")  { $lvl++; $text = "+Level"     ;}
		     if($data[6] == "+Skill"      && $data[0] == "Success")  { $ski++; $text = "+Skill"     ;}
			 if($data[6] == "+Luck"       && $data[0] == "Success")  { $luc++; $text = "+Luck"      ;}
		     if($data[6] == "+Excellent"  && $data[0] == "Success")  { $exl++; $text = "+Excellent" ;}
    	     if($data[0] == "Success" ) {$color = "rgba(239,255,191,0.2)";}else{$color = "rgba(255,191,207,0.2)";}
		echo  "
		<tbody>
	        <tr style='background:".$color."'>
			     <td>".$i."</td>
				 <td>".date("D-M-y H:i",$row['date'])."</td>
				 <td><div class=\"someClass\" title=\"".$item['overlib']."\"><img  height='30px' src='" . $item['thumb'] . "'/></div></td> 
		         <td><div class=\"someClass\" title=\"".$item1['overlib']."\"><img  height='30px' src='" . $item1['thumb'] . "'/></div></td>
			     <td>".number_format($data[2])."</td>
				 <td>".$data[3]."</td>
			     <td>".$data[6]."</td>
			     <td>".$data[5]."</td>
			     <td>".$data[0]."</td> 
			</tr>
		</tbody>
			
		";
	}
	
	echo "
	<script type='text/javascript'>

      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Option Attemt');
        data.addColumn('number', 'Succeeded');
        data.addRows([
          ['+ Opt',    ".$opt."],
          ['+ Anc',    ".$sta."],
          ['+ Lvl',    ".$lvl."],
          ['+ Skill',  ".$ski."],
          ['+ Luck',   ".$luc."],
		  ['+ Exl',    ".$exl."]
        ]);


        var options = {'width':320,'height':320};
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
</script>
<script>
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawRightY);

function drawRightY() {
    var data = google.visualization.arrayToDataTable([
        ['', 'Swords', 'Axes', 'Maces', 'Scepters','Spears', 'Bows','CrossBows','Staffs','Shields'],
        ['Total Upgrade Attemts', ".$sword.", ".$axes.", ".$maces.", ".$scepters.", ".$spears.", ".$bows.",".$crossbows.",".$staffs.",".$shields."],
  
 
      ]);

        var options_fullStacked = {
          isStacked: 'percent',
          height: 300,

          legend: {position: 'top', maxLines: 3},
          hAxis: {
            minValue: 0,
            ticks: [0, .3, .6, .9, 1]
          }
        };
    
      var materialChart = new google.charts.Bar(document.getElementById('chart_div1'));
      materialChart.draw(data, options_fullStacked);
    }
</script>
	
	<script>
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawRightY);

function drawRightY() {
    var data = google.visualization.arrayToDataTable([
        ['', 'Helms','Armors','Pants','Gloves','Boots','Wings','Rings','Pendants' ],
        ['Total Upgrade Attemts', ".$helm.", ".$armor.", ".$pants.", ".$gloves.", ".$boots.", ".$wings.",".$rings.",".$pendants."],
  
 
      ]);

        var options_fullStacked = {
          isStacked: 'percent',
          height: 300,

          legend: {position: 'top', maxLines: 3},
          hAxis: {
            minValue: 0,
            ticks: [0, .3, .6, .9, 1]
          }
        };
    
      var materialChart = new google.charts.Bar(document.getElementById('chart_div2'));
      materialChart.draw(data, options_fullStacked);
    }
</script>
	";
	echo 
	'
	     </table></div>
		   </div>
	   </div>	
	 </div>
	</div>
  </div>
  
  
	';
?>
