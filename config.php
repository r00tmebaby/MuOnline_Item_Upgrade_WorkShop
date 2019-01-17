<?php

/////////////////////////////////////////////////////
///|-   Created for DarksTeam Users  ---|  //////////
///|-- Upgrade Items Up to Season 1 --| /////////////
///|---<   https://DarksTeam.net    > ---|///////////
//////////|-- Dev @r00tme 2017 --|///////////////////
/////////////////////////////////////////////////////
//<   AddLuck  AddSkill  AddLevel  AddDmg/Def  >/////
///////////<Add Exellent Options>////////////////////
/////////////////////////////////////////////////////

$options['sql_host']       = '';                    // Sql server host: 127.0.0.1,localhost,Your Computer Name, Instance
$options['sql_user']       = '';                    // Sql server user: sa
$options['sql_pass']       = '';                    // Sql server password
$options['sql_dbs']        = 'DarksTeam97d99i';     // Mu online database: default = MuOnline
$option['enc_key']         = 'r00tme@';             // Form Encryption
$option['web_address']	   = "http://localhost";    // Important for the Payment Systems API's
$option['web_name']        = "ItemWorkshop";        // Web Title
/////////////////////////////////////////////////////
$options['web_session']    = "dt_username";         // Web Session
$option['admin_acc']       = "r00tme";              // Administrator User
$options['fail_option']    = 1;                     // 0=Item disapears on upgrade failure, 1= Downgrade the item. Please note that if the item has no option or level to downgrade will be deleted permanently
 
 
/////////////////////////////////////////////////////                                                 
$options['credits_tbl']    = "memb_credits";        // Credits Table
$options['credits_col']    = "credits";             // Credits Column
$options['credits_usr']    = "memb___id";           // Credits User
$options['server_ver']     = 1;                     // Server Version 1= 97-99 | 2=Season2-12 | 3=IGCN

/* Not Implemented
// Take if from recatpcha2 Google 
//https://www.google.com/recaptcha/admin#list
$option['captcha_secret']         = "6Lfnk3UUAAAAAAuUKCE-aBPf7mtmTJK1KrD_zhiC";
$option['captcha_site']           = "6Lfnk3UUAAAAAPs9zNh6baibc0BQWhZsnfZsJmr5";
*/



//=================================================================================================	
    // Buy Credits
	
    $option['buycredits_methods']	= array( // Start-Stop Payment methods 1=On 0=Off
	  "PayPal"       => 1,
	  "Epay"         => 1,
	  "PayGol"       => 1,
	  "Mobio"        => 1,
	  "Fortumo"      => 1,
      "PaymentWall"  => 1
	);
////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
//=== Payment Methods Configuration
// This is the method key that will be used for all IPN's change it as every server must to have an unique one.
// *API's do not work locally, a real IP address or domain name is required 
//==================================================================================
// For example IPN's  should look like this if you do no change $option['unique_method_key'] value:
//
// http://IP or hostname /payment_proccess.php?dtweb_payment=paygol     
// http://IP or hostname /payment_proccess.php?dtweb_payment=paypal
// http://IP or hostname /payment_proccess.php?dtweb_payment=epaybg
//
/////////////////////////////////////////////////////////////////////////////////////////////

    $option['unique_method_key'] = "dtweb_payment"      ; // GET variable for the payment process *must be a string	

//PayPal Settings // ======================================================= 

	$option['paypal_email']    = "ludzspy@gmail.com"    ;   // PayPal Email Address
	$option['paypal_punish']   = 1                      ;   // ChargeBack Punishment -> Automatic Permanent Account Ban
	$option['PayPal_cr_bonus'] = "Bonus +50%"           ;   // Credit Bonus compared to other methods -> Some methods generate more profit than the others with this option you can persuade the players to pay via this method and give them more credits in return
	$option['paypal_currency'] = "EUR"                  ;   // All Available =>  https://developer.paypal.com/docs/classic/mass-pay/integration-guide/currency_codes/
	$option['ppal_debug']      = 0;                     ;   // Check whether you receive IPN from PayPAl
	$option['paypal_prices']   = array(
	  11100   =>  0.01 ,   // Credits => Price  //Use 0.01 euro for testing 
		200   =>  2    ,   // Credits => Price
		500   =>  3    ,   // Credits => Price
		1000  =>  10   ,   // Credits => Price
		3000  =>  12  	   // Credits => Price
	);
    
//EpayBG Settings  // =======================================================
	$option['epay_test']     = 1;                    // 1=On/0=Off if you want to use Epay Demo mode for debugging; Keep it false for production   
	$option['Epay_cr_bonus'] = "Bonus +10%";         // Credit Bonus compared to other methods -> Some methods generate more profit than the others with this option you can persuade the players to pay via this method and give them more credits in return
 	$option['epay_client_nr']= "D262694200";         // After business account registration you will be provided with a client number. Type it here!
	$option['epay_email']    = "r00tme@abv.bg";      // Your Business Account Registration Email
	$option['epay_sec_key']  = "EWA17QPLK1WD5D1N3VO8PNE8IHN1798OW7BFZEQO89ADNI50MJYOCZ7ERDGMPJ2J";   // Your Secret key
	$option['epay_inv_exp']  = "+ 2 days";           // Invoice expiration time, minimum 1 day is recommended
	$option['epay_prices']   = array(
	    100   =>  4   ,  // Credits => Price
		200   =>  7   ,  // Credits => Price
		500   =>  13  ,  // Credits => Price
		1000  =>  22  ,  // Credits => Price
		3000  =>  40  	 // Credits => Price
	); 
	
//PayGol Settings //======================================================
	$option['PayGol_cr_bonus']  = "Bonus  +30%";                          // Credit Bonus compared to other methods -> Some methods generate more profit than the others with this option you can persuade the players to pay via this method and give them more credits in return
	$option['pgol_ser_id']      = "329062";                               // Service ID
	$option['pgol_sec_key']     = "648707e6-fc7e-1032-9c5e-e882d49e9b2d"; // Your Secret key
	$option['pgol_currency']    = "EUR";                                  // Default Account currency                                   
	$option['pgol_prices']   = array(
	    100   =>  5   ,  // Credits => Price
		200   =>  8   ,  // Credits => Price
		500   =>  15  ,  // Credits => Price
		1000  =>  25  ,  // Credits => Price
		3000  =>  50  	 // Credits => Price
	); 	
	
//Mobio Settings
	$option['Mobio_cr_bonus'] = "Bonus +10%";   // Credit Bonus compared to other methods -> Some methods generate more profit than the others with this option you can persuade the players to pay via this method and give them more credits in return
    $option['mobio_get_vars'] = 0;              // Use this to get prices values for new services and add them  in $option['mobio_services'] array
    $option['mobio_punish']   = 0;              // Ban Account if payment is failed 1=On/0=Off
	$option['mobio_get_ip']   = 0;              // Record Mobio New IPs=> check Mobio Log Folder
    $option['mobio_services'] = array(
	// You can add as much services you want, just keep the same construction or you will brake up the code!	
	  	array("BG"=>"Bulgaria",                                       // Service Country Code, Country Full
		    1 => array(28531,10  ,"dtweb", "1.20 лв", 1851)  ,        // Payment Amount => array(ServiceID,Credits to add),SMS text, Real Sum, Service Number   
		    2 => array(25561,40  ,"dtweb", "2.40 лв", 1092)  ,        // Payment Amount => array(ServiceID,Credits to add) 
		    4 => array(25562,150 ,"dtweb", "4.80 лв", 1094)  ,        // Payment Amount => array(ServiceID,Credits to add) 
		   12 => array(29883,400 ,"dtweb", "12 лв"  , 1096))          // Payment Amount => array(ServiceID,Credits to add) 			)
        ,
		array("RO"=>"Romania",                                        // Service Country Code, Country Full
		    1 => array(26781,10  ,"plati dtweb", "1.20 euro", 1351) , // Payment Amount => array(ServiceID,Credits to add),SMS text, Real Sum, Service Number   
		    2 => array(25580,40  ,"plati dtweb", "2.40 euro", 1352))  // Payment Amount => array(ServiceID,Credits to add) 
	 );	
	

//Fortumo Settings
	$option['Fortumo_cr_bonus'] = "Bonus  +40%";     // Credit Bonus compared to other methods -> Some methods generate more profit than the others with this option you can persuade the players to pay via this method and give them more credits in return
	$option["fortumo_secret"]   = "99ad5e42d56909776e7071b4a79c2e40"; // Secret Key 
	$option["fortumo_id"]       = "3dc65d738d97dce5ccdbb351b73ba701"; // Service ID  
	$option["fortumo_punish"]   = 0;                 // Ban account if transaction status is failed
	$option["fortumo_see_ips"]  = 0;                 // Log Fortumo IP addresses

//PaymentWall Settings
	$option['PaymentWall_cr_bonus'] = "Bonus  0%";   // Credit Bonus compared to other methods -> Some methods generate more profit than the others with this option you can persuade the players to pay via this method and give them more credits in return
    $option['paymentwall_punish']   = 1;             // Ban account if transaction status is failed 
	$option["paymentwall_secret"]   = "d168d892228c24839d9f17bb57359a3b"; // Secret Key 
	$option["paymentwall_id"]       = "0dddc98c152d39c52eb80bb3e5e9d2b3"; // Service ID 
	$option["pwall_widget_width"]   = "370";         // Form With to adjust it for your template
	$option["pwall_widget_height"]  = "450";         // Form With to adjust it for your template
	$option["pwall_widget"]         = "p4_3";        // Widget type => _3 index calls the 3rd widget in your account
			
//================================================================================================
//================================================================================================
	
//////////////////////////////////////////////////// 
////////////////////////////////////////////////////
////////////////////////////////////////////////////
////   -- Do Not Touch Anything Below --   /////////
////   -- Do Not Touch Anything Below --   /////////
////   -- Do Not Touch Anything Below --   /////////
////////////////////////////////////////////////////

////>>>>> AS YOU MAY BRAKE UP THE CODE <<<</////////

////////////////////////////////////////////////////
////   -- Do Not Touch Anything Below --   /////////
////   -- Do Not Touch Anything Below --   /////////
////   -- Do Not Touch Anything Below --   /////////
////////////////////////////////////////////////////
//////////////////////////////////////////////////// 
////////////////////////////////////////////////////


if (!class_exists('mssqlQuery')) { 
    class mssqlQuery 
    { 
        private $data = array(); 
        private $rowsCount = 0; 
        private $fieldsCount = null; 

        public function __construct($resource) 
        { 
            if ($resource) { 
                while ($data = sqlsrv_fetch_array($resource)) { 
                    $this->addData($data); 
                } 

                sqlsrv_free_stmt($resource); 
            } 
        } 

        public function getRowsCount() 
        { 
            return $this->rowsCount; 
        } 

        public function getFieldsCount() 
        { 
            if ($this->fieldsCount === null) { 
                $this->fieldsCount = 0; 
                $data = reset($this->data); 

                if ($data) { 
                    foreach ($data as $key => $value) { 
                        if (is_numeric($key)) { 
                            $this->fieldsCount++; 
                        } 
                    } 
                } 
            } 

            return $this->fieldsCount; 
        } 

        private function addData($data) 
        { 
            $this->rowsCount++; 
            $this->data[] = $data; 
        } 

        public function getData() 
        { 
            return $this->data; 
        } 

        public function shiftData($resultType = SQLSRV_FETCH_BOTH) 
        { 
            $data = array_shift($this->data); 

            if (!$data) { 
                return false; 
            } 

            if ($resultType == SQLSRV_FETCH_NUMERIC) { 
                foreach ($data as $key => $value) { 
                    if (!is_numeric($key)) { 
                        unset($data[$key]); 
                    } 
                } 
            } else { 
                if ($resultType == SQLSRV_FETCH_ASSOC) { 
                    foreach ($data as $key => $value) { 
                        if (is_numeric($key)) { 
                            unset($data[$key]); 
                        } 
                    } 
                } 
            } 

            return $data; 
        } 
    } 
} 


if (!function_exists('mssql_connect')) { 
    function mssql_connect($servername, $username, $password, $newLink = false) 
    { 
        if (empty($GLOBALS['_sqlsrvConnection'])) { 
            $connectionInfo = array( 
                "CharacterSet" => "UTF-8", 
                "UID" => $username, 
                "PWD" => $password, 
                "ReturnDatesAsStrings" => true 
            ); 

            $GLOBALS['_sqlsrvConnection'] = sqlsrv_connect($servername, $connectionInfo); 

            if ($GLOBALS['_sqlsrvConnection'] === false) { 
                foreach (sqlsrv_errors() as $error) { 
                    echo "SQLSTATE: " . $error['SQLSTATE'] . "<br />"; 
                    echo "code: " . $error['code'] . "<br />"; 
                    echo "message: " . $error['message'] . "<br />"; 
                } 
            } 
        } 

        return $GLOBALS['_sqlsrvConnection']; 
    } 
} 

if (!function_exists('mssql_pconnect')) { 
    function mssql_pconnect($servername, $username, $password, $newLink = false) 
    { 
        if (empty($GLOBALS['_sqlsrvConnection'])) { 
            $connectionInfo = array( 
                "CharacterSet" => "UTF-8", 
                "UID" => $username, 
                "PWD" => $password, 
                "ReturnDatesAsStrings" => true 
            ); 

            $GLOBALS['_sqlsrvConnection'] = sqlsrv_connect($servername, $connectionInfo); 

            if ($GLOBALS['_sqlsrvConnection'] === false) { 
                foreach (sqlsrv_errors() as $error) { 
                    echo "SQLSTATE: " . $error['SQLSTATE'] . "<br />"; 
                    echo "code: " . $error['code'] . "<br />"; 
                    echo "message: " . $error['message'] . "<br />"; 
                } 
            } 
        } 

        return $GLOBALS['_sqlsrvConnection']; 
    } 
} 

if (!function_exists('mssql_close')) { 
    function mssql_close($linkIdentifier = null) 
    { 
        sqlsrv_close($GLOBALS['_sqlsrvConnection']); 
        $GLOBALS['_sqlsrvConnection'] = null; 
    } 
} 

if (!function_exists('mssql_select_db')) { 
    function mssql_select_db($databaseName, $linkIdentifier = null) 
    { 
        $query = "USE " . $databaseName; 

        $resource = sqlsrv_query($GLOBALS['_sqlsrvConnection'], $query); 

        if ($resource === false) { 
            if (($errors = sqlsrv_errors()) != null) { 
                foreach ($errors as $error) { 
                    echo "SQLSTATE: " . $error['SQLSTATE'] . "<br />"; 
                    echo "code: " . $error['code'] . "<br />"; 
                    echo "message: " . $error['message'] . "<br />"; 
                } 
            } 
        } 

        return $resource; 
    } 
} 

if (!function_exists('mssql_query')) { 
    function mssql_query($query, $linkIdentifier = null, $batchSize = 0) 
    { 
        if (preg_match('/^\s*exec/i', $query)) { 
            $query = 'SET NOCOUNT ON;' . $query; 
        } 

        $resource = sqlsrv_query($GLOBALS['_sqlsrvConnection'], $query); 

        if ($resource === false) { 
            if (($errors = sqlsrv_errors()) != null) { 
                foreach ($errors as $error) { 
                    echo "SQLSTATE: " . $error['SQLSTATE'] . "<br />"; 
                    echo "code: " . $error['code'] . "<br />"; 
                    echo "message: " . $error['message'] . "<br />"; 
                } 
            } 
        } 

        return new mssqlQuery($resource); 
    } 
} 

if (!function_exists('mssql_fetch_array')) { 
    function mssql_fetch_array($mssqlQuery, $resultType = SQLSRV_FETCH_BOTH) 
    { 
        if (!$mssqlQuery instanceof mssqlQuery) { 
            return null; 
        } 

        switch ($resultType) { 
            case 'MSSQL_BOTH' : 
                $resultType = SQLSRV_FETCH_BOTH; 
                break; 
            case 'MSSQL_NUM': 
                $resultType = SQLSRV_FETCH_NUMERIC; 
                break; 
            case 'MSSQL_ASSOC': 
                $resultType = SQLSRV_FETCH_ASSOC; 
                break; 
        } 

        return $mssqlQuery->shiftData($resultType); 
    } 
} 

if (!function_exists('mssql_num_rows')) { 
    function mssql_num_rows($mssqlQuery) 
    { 
        if (!$mssqlQuery instanceof mssqlQuery) { 
            return null; 
        } 

        return $mssqlQuery->getRowsCount(); 
    } 
} 


if (!function_exists('mssql_get_last_message')) { 
    function mssql_get_last_message() 
    { 
        preg_match('/^\[Microsoft.*SQL.*Server\](.*)$/i', sqlsrv_errors(SQLSRV_ERR_ALL), $matches); 
        return $matches[1]; 
    } 
} 


mssql_connect($options['sql_host'], $options['sql_user'], $options['sql_pass']);
mssql_select_db($options['sql_dbs']);

?>                                                                                                                                                                                                                                                                                                                                        