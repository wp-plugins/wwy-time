<?php
// insert into table 1  
function wwy_insert_intotb1(){
	global $current_user;
	get_currentuserinfo();
	global $wpdb;
	
	
	if(isset($_POST['submit'])) {
   $table_add_one = $wpdb->prefix . "wwy_data_one";    
   $user_id = $current_user->ID;
   $slide_number = wp_specialchars($_POST['slide_number']);
   $map_type = wp_specialchars($_POST['map_type']);
   $date = wp_specialchars($_POST['date']);
   $length = wp_specialchars($_POST['length']);
   $caption = wp_specialchars($_POST['caption']);
   
   $rows_affected_one = $wpdb->insert( $table_add_one, array( 'user_id' => $user_id, 'slide_number' => $slide_number, 'map_type' => $map_type, 'date' => $date, 'length' => $length, 'caption' => $caption ) );
      
   }
}

// insert into table 2   
function wwy_insert_intotb2(){  
	global $current_user;
	get_currentuserinfo();
	global $wpdb;
	
   if(isset($_POST['attach'])) {   
   $table_add_two = $wpdb->prefix . "wwy_data_two";   
   $user_id = $current_user->ID;
   $slide_number_option = wp_specialchars($_POST['slide_number_option']);
   $marker_name = wp_specialchars($_POST['marker_name']);
   $coordx = wp_specialchars($_POST['coordx']);	  
   $coordy = wp_specialchars($_POST['coordy']);    
   $color = wp_specialchars($_POST['color']);	
   $rows_affected_two = $wpdb->insert( $table_add_two, array('user_id' => $user_id, 'slide_number_option' => $slide_number_option, 'marker_name' => $marker_name, 'coordx' => $coordx, 'coordy' => $coordy, 'color' => $color ) );
   }
   }


// insert into table 2 duplicate markers   
function wwy_insert_dubplicatetb2(){  
	global $current_user;
	get_currentuserinfo();
	global $wpdb; 
   // duplicate marker
   if(isset($_POST['slide_number_option2'])) {
   $table_add_two = $wpdb->prefix . "wwy_data_two";	
   $user_id = $current_user->ID;	
   $slide_number_option = wp_specialchars($_POST['slide_number_option2']);
   $marker_name = wp_specialchars($_POST['marker_name']);
   $coordx = wp_specialchars($_POST['coordx']);	  
   $coordy = wp_specialchars($_POST['coordy']);		  
   $color = wp_specialchars($_POST['color']);
   $rows_affected_two = $wpdb->insert( $table_add_two, array('user_id' => $user_id, 'slide_number_option' => $slide_number_option, 'marker_name' => $marker_name, 'coordx' => $coordx, 'coordy' => $coordy, 'color' => $color ) );
   }}

// publishing
function wwy_publishing(){  
	global $current_user;
	get_currentuserinfo();
	global $wpdb;
	
   if(isset($_POST['wwy_publish'])) {
   $table_add_three = $wpdb->prefix . "wwy_data_three";      
   $user_id = $current_user->ID;
   $published = wp_specialchars($_POST['published']);   
   $pub_name = wp_specialchars($_POST['pub_name']);   
   $rows_affected_three = $wpdb->insert( $table_add_three, array('user_id' => $user_id, 'published' => $published, 'pub_name' => $pub_name ) );
   }
   }

// delete slide's row
function wwy_deleteslide(){
global $wpdb;
global $current_user;
get_currentuserinfo();
$user_id = $current_user->ID;
$table_add_one = $wpdb->prefix . "wwy_data_one";


if(isset($_POST['field_id'])) {
     $table_add_one = $wpdb->prefix . "wwy_data_one";	
     $the_value = $_POST['field_id'];	 
	 $rows_affected_one = $wpdb->delete( $table_add_one, array( 'field_id' => $the_value ));	 
	}	
}

// delete marker's row
function wwy_deletemarker(){ 
global $wpdb;
global $current_user;
get_currentuserinfo();
$user_id = $current_user->ID;
$table_add_two = $wpdb->prefix . "wwy_data_two";

	if(isset($_POST['marker_field_id'])) {
     $table_add_two = $wpdb->prefix . "wwy_data_two";	
	 $the_value = $_POST['marker_field_id'];
	 $rows_affected_two = $wpdb->delete( $table_add_two, array( 'marker_field_id' => $the_value ));
	}	
}

function wwy_paypal_btn(){
require_once('wp-blog-header.php');
$wwy_charge = (int)get_option('wwy_charge');  
$wwy_paypal_email = get_option('wwy_paypal_email');  
$return_url = home_url() . "/wwy-paid/";
$wwy_sandbox = get_option('wwy_sandbox'); 
if ($wwy_sandbox == 1){
    $url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
}
else{
	$url = "https://www.paypal.com/cgi-bin/webscr";
}

return '<form action="' . $url . '" method="post" target="_top">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="' . $wwy_paypal_email . '">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="item_name" value="Subscription">
<input type="hidden" name="amount" value="' . $wwy_charge . '">
<input type="hidden" name="return" value="' . $return_url . '">
<input name="notify_url" value="' . $return_url . '" type="hidden">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="button_subtype" value="services">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
<input title="Paypal" type="submit" value="Pay Now!" name="submit" alt="PayPal - The safer, easier way to pay online!" id="pay_btn">
</form>';
}


function wwy_process_pay(){  
	global $current_user;
	get_currentuserinfo();
	global $wpdb;
    require_once('wp-blog-header.php'); 
	$wwy_paypal_email = get_option('wwy_paypal_email');  
	echo $receiver_email = $_POST['receiver_email'];
	
   echo $payment_status = $_POST['payment_status'];
   if ($payment_status == "Completed" and $wwy_paypal_email == $receiver_email){   
   
   $table_add_four = $wpdb->prefix . "wwy_data_four";   
   $user_id = $current_user->ID;
   $payer_email = $_POST['payer_email'];
   $payment_status = 1; 
   $payment_date = $_POST['payment_date'];
   $mc_gross = $_POST['mc_gross'];
   
   $rows_affected_four = $wpdb->insert( $table_add_four, array('user_id' => $user_id, 'payer_email' => $payer_email, 'payment_status' => $payment_status, 'payment_date' => $payment_date, 'mc_gross' => $mc_gross ) );
   
   // redirect to my account
   
   wp_redirect( home_url() . "/wwy-account/");
   }
   if ($payment_status == "Canceled_Reversal" or $payment_status == "Created" or $payment_status == "Denied" or $payment_status == "Expired" or $payment_status == "Failed" or $payment_status == "Pending" or $payment_status == "Refunded" or $payment_status == "Reversed" or $payment_status == "Processed" or $payment_status == "Voided"){
   
   $table_add_four = $wpdb->prefix . "wwy_data_four";   
   $user_id = $current_user->ID;
   $payer_email = $_POST['payer_email'];
   $payment_status = 0; 
   $payment_date = $_POST['payment_date'];
   $mc_gross = $_POST['mc_gross'];
   
   $rows_affected_four = $wpdb->insert( $table_add_four, array('user_id' => $user_id, 'payer_email' => $payer_email, 'payment_status' => $payment_status, 'payment_date' => $payment_date, 'mc_gross' => $mc_gross ) );
   
   // redirect to my account
   
   wp_redirect( home_url() . "/wwy-account/");
   }
}







?>