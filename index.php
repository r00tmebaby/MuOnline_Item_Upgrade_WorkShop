<?php
ob_start();
session_start();
require("config.php");
require("funcs.php");
header('Cache-control: private');
if(!defined('access')) {
          header('HTTP/1.0 403 Forbidden');
          exit;
}
	
?>
<!DOCTYPE html>
<head>
    <title>Upgrade Item Merchant All Seasons</title>
    <meta charset="utf-8" />
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="favicon.ico" />   
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/itemupgrade.css" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" />
    <link href="css/material-design-iconic-font.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/bootstrap-select.css"/> 
	<link rel="stylesheet" href="css/style.css"/> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <script type="text/javascript">$(document).ready(function(){$("[title]").easyTooltip();});</script>
	<script type="text/javascript" src="js/easyTooltip.js"></script>
	<script type="text/javascript" src="js/overlib.js"></script>	
	<script src="js/bootstrap-select.js"></script>



<script>
$('.selectpicker').selectpicker({
  style: 'btn-info',
  size: 4
});
</script>



<?php 

if(!logged()){
 login();
echo'
<div id="loginm"></div>
   
<div class="row">
    <div class="col-lg-12">
	      <div class="col-lg-5"><div style="position:absolute;z-index:555555;"><img class="image_col"  src="imgs/main2.png"/></div></div>
	            <div class="col-lg-2" style="margin-top:200px">                
			        <div class="login">
                    	<img src="imgs/mu.png"/>
                        <form method="post" id="login">
                        	<input type="text" name="account" class="form-control" placeholder="Username" required="required" />
                            <input type="password" class="form-control" name="password" placeholder="Password" required="required" />
                            <input type="submit" id="login" onclick="functions(\'login\')" value="Let me in." class="btn btn-primary btn-block btn-large"/>
                        </form>
                    </div>
			    </div>
			<div class="col-lg-5">
			    <div style="position:absolute;z-index:555555;"><img  class="image_col1" src="imgs/main1.png"/></div></div>
		   </div>		
	</div>        

</div>
   <footer>
      <div class="col-md-12" style="bottom:0;position:fixed">
              <p class="alert alert-info">DTeam  - Item Upgrade Workshop v1.0  -  r00tme</p>
      </div>
	</footer>
<style>
@import url(https://fonts.googleapis.com/css?family=Open+Sans);
.btn { display: inline-block; *display: inline; *zoom: 1; padding: 4px 10px 4px; margin-bottom: 0; font-size: 13px; line-height: 18px; color: #333333; text-align: center;text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75); vertical-align: middle; background-color: #f5f5f5; background-image: -moz-linear-gradient(top, #ffffff, #e6e6e6); background-image: -ms-linear-gradient(top, #ffffff, #e6e6e6); background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), to(#e6e6e6)); background-image: -webkit-linear-gradient(top, #ffffff, #e6e6e6); background-image: -o-linear-gradient(top, #ffffff, #e6e6e6); background-image: linear-gradient(top, #ffffff, #e6e6e6); background-repeat: repeat-x; filter: progid:dximagetransform.microsoft.gradient(startColorstr=#ffffff, endColorstr=#e6e6e6, GradientType=0); border-color: #e6e6e6 #e6e6e6 #e6e6e6; border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25); border: 1px solid #e6e6e6; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px; -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05); -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05); box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05); cursor: pointer; *margin-left: .3em; }
.btn:hover, .btn:active, .btn.active, .btn.disabled, .btn[disabled] { background-color: #e6e6e6; }
.btn-large { padding: 9px 14px; font-size: 15px; line-height: normal; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; }
.btn:hover { color: #333333; text-decoration: none; background-color: #e6e6e6; background-position: 0 -15px; -webkit-transition: background-position 0.1s linear; -moz-transition: background-position 0.1s linear; -ms-transition: background-position 0.1s linear; -o-transition: background-position 0.1s linear; transition: background-position 0.1s linear; }
.btn-primary, .btn-primary:hover { text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); color: #ffffff; }
.btn-primary.active { color: rgba(255, 255, 255, 0.75); }
.btn-primary { background-color: #4a77d4; background-image: -moz-linear-gradient(top, #6eb6de, #4a77d4); background-image: -ms-linear-gradient(top, #6eb6de, #4a77d4); background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#6eb6de), to(#4a77d4)); background-image: -webkit-linear-gradient(top, #6eb6de, #4a77d4); background-image: -o-linear-gradient(top, #6eb6de, #4a77d4); background-image: linear-gradient(top, #6eb6de, #4a77d4); background-repeat: repeat-x; filter: progid:dximagetransform.microsoft.gradient(startColorstr=#6eb6de, endColorstr=#4a77d4, GradientType=0);  border: 1px solid #3762bc; text-shadow: 1px 1px 1px rgba(0,0,0,0.4); box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.5); }
.btn-primary:hover, .btn-primary:active, .btn-primary.active, .btn-primary.disabled, .btn-primary[disabled] { filter: none; background-color: #4a77d4; }
.btn-block { width: 100%; display:block; }

* { -webkit-box-sizing:border-box; -moz-box-sizing:border-box; -ms-box-sizing:border-box; -o-box-sizing:border-box; box-sizing:border-box; }

html { width: 100%; height:100%; overflow:hidden; }

.image_col{
	width:100%;
}
.image_col1{
	width:100%;
}
a:active{
	background:yellow;
}
@media (max-width: 1200px) and (min-width:200px){ 
.image_col1{
	   display:none;
   }

}
@media (max-width: 1200px) and (min-width:1000px){ 
.image_col{
	   width:50%;
	   float:left;
   }
}
@media (max-width: 1200px) and (min-width:1000px){ 
.image_col1{
	   width:50%;
	   float:right;
   }
}
@media (max-width: 1000px) and (min-width:200px){ 
.image_col{
	 display:none;
   }
}

body { 
	width: 100%;
	height:100%;
	font-family: \'Open Sans\', sans-serif;
	background: #092756;
	background: -moz-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%),-moz-linear-gradient(top,  rgba(57,173,219,.25) 0%, rgba(42,60,87,.4) 100%), -moz-linear-gradient(-45deg,  #670d10 0%, #092756 100%);
	background: -webkit-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), -webkit-linear-gradient(top,  rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), -webkit-linear-gradient(-45deg,  #670d10 0%,#092756 100%);
	background: -o-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), -o-linear-gradient(top,  rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), -o-linear-gradient(-45deg,  #670d10 0%,#092756 100%);
	background: -ms-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), -ms-linear-gradient(top,  rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), -ms-linear-gradient(-45deg,  #670d10 0%,#092756 100%);
	background: -webkit-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), linear-gradient(to bottom,  rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), linear-gradient(135deg,  #670d10 0%,#092756 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#3E1D6D\', endColorstr=\'#092756\',GradientType=1 );
}
.login { 
	position: absolute;
	top: 50%;
	left: 50%;
	margin: -150px 0 0 -150px;
	width:300px;
	height:300px;
}
.login h3 { color: #fff; text-shadow: 0 0 10px rgba(0,0,0,0.3); letter-spacing:1px; text-align:center; }

input { 
	width: 100%; 
	margin-bottom: 10px; 
	background: rgba(0,0,0,0.3);
	border: none;
	outline: none;
	padding: 10px;
	font-size: 13px;
	color: #fff;
	text-shadow: 1px 1px 1px rgba(0,0,0,0.3);
	border: 1px solid rgba(0,0,0,0.3);
	border-radius: 4px;
	box-shadow: inset 0 -5px 45px rgba(100,100,100,0.2), 0 1px 1px rgba(255,255,255,0.2);
	-webkit-transition: box-shadow .5s ease;
	-moz-transition: box-shadow .5s ease;
	-o-transition: box-shadow .5s ease;
	-ms-transition: box-shadow .5s ease;
	transition: box-shadow .5s ease;
}
input[type="text"],[type="password"] {
background-color:white !important

}
input:focus { background:white; box-shadow: inset 0 -5px 45px rgba(100,100,100,0.4), 0 1px 1px rgba(255,255,255,0.2); }
</style>';
}
else{
	
	logout();
	$admin = logged();
	if($admin[0] == 1){
		$add_menu = "<a class='navbar-brand' href='?p=666'>&nbsp; Admin Board";
	}
	else{
		$add_menu = "<a class='navbar-brand' href='/'>&nbsp;  Dashboard";
	}
	$cr  = mssql_fetch_array(mssql_query("Select [".$options['credits_col']."] from [".$options['credits_tbl']."] where [".$options['credits_usr']."]='".$_SESSION['username']."'"));
?>


<script>

function functions(func,itemid,opt,hex){
    var str = $('#'+func).serialize();
    $.ajax({
        url: 'jax.php?f='+func+'&item='+itemid+'&opt='+opt+'&itemhex='+hex,
        type: "POST",
        data: ''+str+'',
        success: function(msgc)
        {
            $('#'+func+'m').empty().append(""+msgc+"").hide().fadeIn("fast");
	
        }
    });
	
}
</script>

<body>
  <div class="wrapper">  
		<div class="sidebar" >
            	 <div class="logo">
                  <img id= "logo" src="imgs/mu.png"/> 				            			                        
               </div> 
            <div class="logo logo-mini">
                <a href="#" class="simple-text">
                  <i class="fa fa-level-up" ></i> 
                </a>
            </div>
            <div class="sidebar-wrapper">
                <ul class="nav" style='text-align:left'>
                    <li>
                        <a href="?p=1">
                            <i class="fa fa-cogs" ></i>
                            <p>My Shop</p>
                        </a>
                    </li>
	                    <li>
                        <a href="?p=5">
                            <i class="fa fa-delicious"></i>
                            <p>My Quests</p>
                        </a>
                    </li>
                    <li>
                        <a href="?p=6">
                            <i class="fa fa-dropbox"></i>
                            <p>My Gambling</p>
                        </a>
                    </li>					<li>
                        <a href="?p=2">
                            <i class="	fa fa-info-circle"></i>
                            <p>My Account</p>
                        </a>
                    </li>
					     <li>
                        <a href="?p=3">
                            <i class="fa fa-history" aria-hidden="true"></i>
                            <p>My Upgrades</p>
                        </a>
                    </li>
					   <li>
                        <a href="?p=4">
                           <i class="fa fa-google-wallet" aria-hidden="true"></i>
                            <p>My Wallet</p>
                        </a>
                    </li>
					   <li>
                        <a href="/">
                  <i class="material-icons">power</i>
				  <form method='post' >
                            <p><input type='submit' name='logout' style='border:none;background:none;' value='Logout'/></p>
				  </form>
                        </a>
                    </li>
					<li>
					 <img width="100%" style="margin-top:20px" src="imgs/wpmerchant.png"/>
					</li>
                </ul>
            </div>
        </div>
		
          <div class="main-panel">
		  
            <nav class="navbar navbar-default navbar-absolute" data-topbar-color="blue">
                <div class="container-fluid">
				
                    <div class="navbar-minimize">
                        <button id="minimizeSidebar" class="btn btn-round btn-white btn-fill btn-just-icon">
							<i class="material-icons visible-on-sidebar-regular f-26">keyboard_arrow_left</i>
                            <i class="material-icons visible-on-sidebar-mini f-26">keyboard_arrow_right</i>
                        </button>
                    </div>
                    <div class="navbar-header">
					
                        <button type="button" class="navbar-toggle" data-toggle="collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <?php echo $add_menu?> 
						
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="?p=2">
                                    <i class="material-icons">notifications</i>
                                    <span class="notification">
									<?php
									    $cat =  mssql_num_rows(mssql_query("Select * from [r00tme_Upgrade_Item_Messages] where recipient = '".$_SESSION['username']."'"));
								       echo $cat;
									?>
									</span>
                   
                                </a>
                            </li>
                            <li>
                                <a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">	
                                     <i class="material-icons">money</i> <span style='font-size:10pt;'><?php echo $cr[$options['credits_col']]?> </span>                                   
                                </a>
                            </li>
                            <li>
                                <a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">							
                                    <i class="material-icons">person</i><?php echo $_SESSION['username']?>                                  
                                </a>
                            </li>
                            <li class="separator hidden-lg hidden-md"></li>
                        </ul>
                    </div>
                </div>
            </nav>
				<div  class="background"></div>
            <div class="content">
	
                <div class="container-fluid">

				    <?php 				
                              if(isset($_GET['p'])){
					        	  $page = (int)$_GET['p'];
								  
					        	  if(!empty($page)){
					        		  switch($page){
					        			  case 1: upgrade_item($_SESSION['username'],3);break;
					        			    case 2: include("user/account_info.php");break;
					        			      case 3: include("user/upgrade_info.php");break;
					        			        case 4: include("user/wallet_info.php");break;
										          case 5: include("user/quests.php");break;
										            case 6: include("user/gambling.php");break;
										              case 666: 
										                    if($admin[0] == 1) {
										                      include("user/administrator.php");
										                      }
										                    else{
										                      header("Location:/");	  
										                      }
											          break;
                                            default:include("user/upgrade_info.php");												  
					        		  }

					        	  }
							  }
							 else{
								include("user/upgrade_info.php");
							}
					  ?>
                </div>
            </div>
        </div>
		
		    <footer style='background:#FFF;' class="footer">
                <div class="container-fluid clearfix">
                    <nav class="facebook-shits pull-left">
                        <ul>
                            <li>
                               <button class="btn btn-just-icon btn-twitter"><i class="fa fa-twitter"></i><div class="ripple-container"></div></button>
                            </li>
                            <li>
                               <button class="btn btn-just-icon btn-facebook"><i class="fa fa-facebook"> </i><div class="ripple-container"></div></button>
                            </li>
                            <li>
                               <button class="btn btn-just-icon  btn-google"><i class="fa fa-google"> </i><div class="ripple-container"></div></button>                   </li>
                            <li>
                               <button class="btn btn-just-icon  btn-linkedin"><i class="fa fa-linkedin"></i><div class="ripple-container"></div></button>                           </li>
                        </ul>
                    </nav>
                    <p style="text-shadow:1px 1px #000000;font-weight:900;color:#2196f3;" class="copyright pull-right">
                        &copy; Upgrade Item Merchant :: All Seasons :: @r00tme 2017
                    </p>
                </div>
            </footer>
			<?php } ?>
    </div>
</body>

<script>
function addbutton() {
    document.getElementById("chaos").setAttribute("class", "show"); 
}

</script>

<script src="js/jquery-ui.min.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<script src="js/material.min.js" type="text/javascript"></script>
<script src="js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
<script src="js/itemupgrade.js"></script>
</html>
              