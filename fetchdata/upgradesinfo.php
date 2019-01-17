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
$content = "";

$i= 0;
$rows  = mssql_query("Select * from r00tme_Upgrade_Item_Logs where username='".$_SESSION['username']."'");
$count = mssql_num_rows($rows);
    while($row = mssql_fetch_array($rows)){
    	$i++;
    	if($count > $i){
    		$fot = ",";
    	}
    	else{
    		$fot = "";
    	}
    	$content .= "['".date("d/m/y H:i",$row['date'])."',  'dfgdsfg', 'true']" . $fot . "</br>"; 
    
    }
	echo '
	{
  "cols": [
        {"id":"","label":"Topping","pattern":"","type":"string"},
        {"id":"","label":"Slices","pattern":"","type":"number"}
      ],
  "rows": [
        {"c":[{"v":"Mushrooms","f":null},{"v":3,"f":null}]},
        {"c":[{"v":"Onions","f":null},{"v":1,"f":null}]},
        {"c":[{"v":"Olives","f":null},{"v":1,"f":null}]},
        {"c":[{"v":"Zucchini","f":null},{"v":1,"f":null}]},
        {"c":[{"v":"Pepperoni","f":null},{"v":2,"f":null}]}
      ]
}
	
	
	
	
	';
 }
}
else{
	echo "<script> setTimeout(function(){location.href='/'} , '1');  </script>"; 
}
?>