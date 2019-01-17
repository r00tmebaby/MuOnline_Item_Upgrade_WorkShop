<?php
session_start();
require("../config.php");
require("../funcs.php");

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

if(logged() === false){
	exit();
}
else{
$account = $_SESSION['username'];

 }
}
else{
	echo "<script> setTimeout(function(){location.href='/'} , '1');  </script>"; 
}
?>