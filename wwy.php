<?php
/*
Plugin Name: WWY - Timeline map
Plugin URI: http://waitington.com
Description: WWY - is a plugin to support WWY - Where were you features
Version: 0.1
Author: Where were you, http://waitington.com
Author URI: http://waitington.com
*/

/*  Copyright 2014  WWY - Where were YOU?  (email : info@waitington.com)
   
*/
?><?php

// some definition we will use
define( 'WWY_PUGIN_NAME', 'WWY - Timeline map');
define( 'WWY_PLUGIN_DIRECTORY', 'wwy');
define( 'WWY_CURRENT_VERSION', '0.1' );
define( 'WWY_CURRENT_BUILD', '3' );
define( 'WWY_LOGPATH', str_replace('\\', '/', WP_CONTENT_DIR).'/wwy-logs/');
define( 'WWY_DEBUG', false);		# never use debug mode on productive systems
if (!defined('EMU2_I18N_DOMAIN')) define('EMU2_I18N_DOMAIN', 'wwy');

// how to handle log files, don't load them if you don't log
require_once('wwy_logfilehandling.php');

// load language files
function wwy_set_lang_file() {
	# set the language file
	$currentLocale = get_locale();
	if(!empty($currentLocale)) {
		$moFile = dirname(__FILE__) . "/lang/" . $currentLocale . ".mo";
		if (@file_exists($moFile) && is_readable($moFile)) {
			load_textdomain(EMU2_I18N_DOMAIN, $moFile);
		}

	}
}
wwy_set_lang_file();

// Require files within the plugin




//call register settings function


register_activation_hook(__FILE__, 'wwy_activate');
register_deactivation_hook(__FILE__, 'wwy_deactivate');
register_uninstall_hook(__FILE__, 'wwy_uninstall');

//allow redirection, even if my theme starts to send output to the browser
add_action('init', 'do_output_buffer');
function do_output_buffer() {
        ob_start();
}


//call register settings function
add_action( 'admin_init', 'wwy_register_settings' );
function wwy_register_settings() {
	//register settings
	register_setting( 'wwy-settings-group', 'wwy_pay' );
	register_setting( 'wwy-settings-group', 'wwy_paypal_email' );
	register_setting( 'wwy-settings-group', 'wwy_signinbar' );
	register_setting( 'wwy-settings-group', 'wwy_num' );	
	register_setting( 'wwy-settings-group', 'wwy_charge' );	
	register_setting( 'wwy-settings-group', 'wwy_sandbox' );	
	register_setting( 'wwy-settings-group', 'wwy_menu_in' );	
	register_setting( 'wwy-settings-group', 'wwy_jqueryon' );	
	register_setting( 'wwy-settings-group', 'wwy_credit' );		
}

register_activation_hook(__FILE__, 'wwy_activate');
register_deactivation_hook(__FILE__, 'wwy_deactivate');
register_uninstall_hook(__FILE__, 'wwy_uninstall');

// activating the default values
function wwy_activate() {
	add_option('wwy_menu_in', 1);
	add_option('wwy_signinbar', 1);
	add_option('wwy_jqueryon', 1);
	add_option('wwy_credit', 'WWY - Where were you map timeline.');
}

// deactivating
function wwy_deactivate() {
	// needed for proper deletion of every option	
	delete_option('wwy_pay');
	delete_option('wwy_paypal_email');
	delete_option('wwy_signinbar');
	delete_option('wwy_num');
	delete_option('wwy_charge');
	delete_option('wwy_charge_period');
	delete_option('wwy_sandbox');
	delete_option('wwy_menu_in');
	delete_option('wwy_jqueryon');
	delete_option('wwy_credit');
}

// uninstalling
function wwy_uninstall() {
	# delete all data stored	
	delete_option('wwy_pay');
	delete_option('wwy_paypal_email');
	delete_option('wwy_signinbar');
	delete_option('wwy_num');
	delete_option('wwy_charge');
	delete_option('wwy_charge_period');
	delete_option('wwy_sandbox');
	delete_option('wwy_menu_in');
	delete_option('wwy_jqueryon');
	delete_option('wwy_credit');
	// delete log files and folder only if needed
	if (function_exists('wwy_deleteLogFolder')) wwy_deleteLogFolder();
}

// create custom plugin settings menu
add_action( 'admin_menu', 'wwy_create_menu' );
function wwy_create_menu() {

	// create new top-level menu
	add_menu_page( 
	__('WWY System', EMU2_I18N_DOMAIN),
	__('WWY System', EMU2_I18N_DOMAIN),
	0,
	WWY_PLUGIN_DIRECTORY.'/admin_side/wwy_settings_page.php',
	'',
	plugins_url('/images/icon.png', __FILE__));
	
	
	add_submenu_page( 
	WWY_PLUGIN_DIRECTORY.'/admin_side/wwy_settings_page.php',
	__("Configuration", EMU2_I18N_DOMAIN),
	__("Configuration", EMU2_I18N_DOMAIN),
	0,
	WWY_PLUGIN_DIRECTORY.'/admin_side/wwy_settings_page.php'
	);	
		
	add_submenu_page( 
	WWY_PLUGIN_DIRECTORY.'/admin_side/wwy_settings_page.php',
	__("Invoices", EMU2_I18N_DOMAIN),
	__("Invoices", EMU2_I18N_DOMAIN),
	0,
	WWY_PLUGIN_DIRECTORY.'/admin_side/wwy_invoice.php'
	);	
	
	add_submenu_page( 
	WWY_PLUGIN_DIRECTORY.'/admin_side/wwy_settings_page.php',
	__("Credits", EMU2_I18N_DOMAIN),
	__("Credits", EMU2_I18N_DOMAIN),
	9,
	WWY_PLUGIN_DIRECTORY.'/admin_side/wwy_settings_page2.php'
	);	
	
}


// check if debug is activated
function wwy_debug() {
	# only run debug on localhost
	if ($_SERVER["HTTP_HOST"]=="localhost" && defined('EPS_DEBUG') && EPS_DEBUG==true) return true;
}

// adding styles
function wwy_method() {
	wp_register_style( 'custom-style', plugins_url( '/css/wwy.css', __FILE__ ), array(), '20120208', 'all' );  
	wp_register_style( 'custom-style2', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css');  
	wp_enqueue_style( 'custom-style' );
	wp_enqueue_style( 'custom-style2' );
   	
}

add_action( 'wp_enqueue_scripts', 'wwy_method' );

function wwy_jscss(){

wp_register_style( 'style' , plugins_url( '/css/validetta.css' , __FILE__ ) );
wp_register_style( 'style1' , '//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css'); 
wp_register_style( 'style2' , plugins_url( '/colorpicker/css/colorpicker.css' , __FILE__ ) );
wp_register_style( 'style3' , plugins_url( '/css/jquery-jvectormap-1.2.2.css' , __FILE__ ) );
wp_register_style( 'style4' , plugins_url( '/css/jquery-jvectormap.css' , __FILE__ ) );


wp_register_script('custom-script1' , plugins_url( '/js/jquery.timeliner.min.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script2' , plugins_url( '/js/validetta-min.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script3' , '//code.jquery.com/ui/1.10.3/jquery-ui.js', array("jquery"));
wp_register_script('custom-script4' , plugins_url( '/colorpicker/js/colorpicker.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script5' , plugins_url( '/colorpicker/js/eye.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script6' , plugins_url( '/colorpicker/js/utils.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script7' , plugins_url( '/colorpicker/js/layout.js?ver=1.0.2' , __FILE__ ), array("jquery") );
wp_register_script('custom-script8' , plugins_url( '/js/jquery-jvectormap-1.2.2.min.js' , __FILE__ ), array("jquery") );  
wp_register_script('custom-script9' , plugins_url( '/js/jquery-jvectormap.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script10' , plugins_url( '/js/jquery-mousewheel.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script11' , plugins_url( '/lib/jvectormap.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script12' , plugins_url( '/lib/abstract-element.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script13' , plugins_url( '/lib/abstract-canvas-element.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script14' , plugins_url( '/lib/abstract-shape-element.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script15' , plugins_url( '/lib/svg-element.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script16' , plugins_url( '/lib/svg-group-element.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script17' , plugins_url( '/lib/svg-canvas-element.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script18' , plugins_url( '/lib/svg-shape-element.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script19' , plugins_url( '/lib/svg-path-element.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script20' , plugins_url( '/lib/svg-circle-element.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script21' , plugins_url( '/lib/vml-element.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script22' , plugins_url( '/lib/vml-group-element.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script23' , plugins_url( '/lib/vml-canvas-element.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script24' , plugins_url( '/lib/vml-shape-element.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script25' , plugins_url( '/lib/vml-path-element.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script26' , plugins_url( '/lib/vml-circle-element.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script27' , plugins_url( '/lib/vector-canvas.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script28' , plugins_url( '/lib/simple-scale.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script29' , plugins_url( '/lib/numeric-scale.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script30' , plugins_url( '/lib/ordinal-scale.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script31' , plugins_url( '/lib/color-scale.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script32' , plugins_url( '/lib/data-series.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script33' , plugins_url( '/lib/proj.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script34' , plugins_url( '/lib/world-map.js' , __FILE__ ), array("jquery") );
wp_register_script('custom-script35' , plugins_url( '/js/jquery-jvectormap-world-mill-en.js' , __FILE__ ), array("jquery") );

wp_enqueue_style( 'style');
wp_enqueue_style( 'style1');
wp_enqueue_style( 'style2');
wp_enqueue_style( 'style3');
wp_enqueue_style( 'style4');

wp_enqueue_script( 'custom-script1' );
wp_enqueue_script( 'custom-script2' );
wp_enqueue_script( 'custom-script3' );
wp_enqueue_script( 'custom-script4' );
wp_enqueue_script( 'custom-script5' );
wp_enqueue_script( 'custom-script6' );
wp_enqueue_script( 'custom-script7' );
wp_enqueue_script( 'custom-script8' );
wp_enqueue_script( 'custom-script9' );
wp_enqueue_script( 'custom-script10' );
wp_enqueue_script( 'custom-script11' );
wp_enqueue_script( 'custom-script12' );
wp_enqueue_script( 'custom-script13' );
wp_enqueue_script( 'custom-script14' );
wp_enqueue_script( 'custom-script15' );
wp_enqueue_script( 'custom-script16' );
wp_enqueue_script( 'custom-script17' );
wp_enqueue_script( 'custom-script18' );
wp_enqueue_script( 'custom-script19' );
wp_enqueue_script( 'custom-script20' );
wp_enqueue_script( 'custom-script21' );
wp_enqueue_script( 'custom-script22' );
wp_enqueue_script( 'custom-script23' );
wp_enqueue_script( 'custom-script24' );
wp_enqueue_script( 'custom-script25' );
wp_enqueue_script( 'custom-script26' );
wp_enqueue_script( 'custom-script27' );
wp_enqueue_script( 'custom-script28' );
wp_enqueue_script( 'custom-script29' );
wp_enqueue_script( 'custom-script30' );
wp_enqueue_script( 'custom-script31' );
wp_enqueue_script( 'custom-script32' );
wp_enqueue_script( 'custom-script33' );
wp_enqueue_script( 'custom-script34' );
wp_enqueue_script( 'custom-script35' );

}
	  

// fix the issue with different version of jQuerry
if (get_option('wwy_jqueryon') == 1){
if (!is_admin()) add_action("wp_enqueue_scripts", "wwy_jquery_enqueue", 11);
}
function wwy_jquery_enqueue() {
   wp_deregister_script('jquery');
   wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js", false, null);
   wp_enqueue_script('jquery');
}

// display profile My Account page
function wwy_account() { 
     global $wpdb;
     global $current_user;
     get_currentuserinfo();
	 $user_id = $current_user->ID;
	 $wwy_charge = (int)get_option('wwy_charge'); 
	if ( is_user_logged_in() ) {	  
	  echo '<div class="my_account"><ul>';
	  echo '<li class="heading">Name</li>';
	  echo '<li>Username: ' . $current_user->user_login . "</li>";
      echo '<li>First Name: ' . $current_user->first_name . "</li>";         
      echo '<li>Last Name: ' . $current_user->last_name . "</li>";         
      echo '<li>Email: ' . $current_user->user_email . "</li>";         
      echo '<li class="heading">Contact Information</li>';    
       echo '<li>Website: ' . $current_user->user_url . "</li>"; 	  
       echo '<li>Facebook: ' . $current_user->user_fb . "</li>"; 	  
       echo '<li>Twitter: ' . $current_user->user_tw . "</li>"; 	  
       echo '<li>Google: ' . $current_user->google_profile . "</li>"; 	  
       echo '<li class="heading">About Yourself';  	  
       echo '<li>Description: ' . $current_user->description . "</li>";  	  
      //echo 'User ID: ' . $current_user->ID . "<br />";
	  echo '<li class="link"><a class="wwy-edit-profile" href="'.home_url().'/wwy-edit-profile/">Edit Profile</a></li>';
	   echo '</ul></div>';	
						
	$row = $wpdb->get_results( "SELECT * FROM wp_wwy_data_four WHERE user_id=$user_id");
	foreach ( $row as $row ) 
	{
	if ($row->payment_status == 1){
    echo '<div class="pay_note">Thank you for your payment. 
	<span>Time & Date: ' . $row->payment_date . '</span><span> Paid: $'.$row->mc_gross.'</span></div>';		
	}
	if ($row->payment_status == 0){
    echo '<div class="pay_note1"><span>Your payment is pending. </span>
	Please contact support if the status will not change within the next 48 hours.</div>';		
	}
	}   
	} else {
		echo 'Please <a href="'.home_url().'/wwy-sign-in/">Sign In</a>!';
	}     
}  
    add_shortcode('wwy_account_space', 'wwy_account');  

// front page
function wwy_front() {        
       echo "<div class='front'><a href='" . home_url(). "/wwy-published/?pub_id=2'><img src='" . plugins_url('/images/front.png', __FILE__) ."' alt=''/></a></div>";
}  
    add_shortcode('wwy_front_space', 'wwy_front');  

// redirect in footer	
function wwy_footer_sc() {
    if ( is_front_page() ) {
  // Default homepage
echo '<div style="width:100%"><div style="text-align:center"><b>Video Tutorial:</b><br /><br /> <iframe width="560" height="315" src="//www.youtube.com/embed/HCS39XORfQU?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe></div></div><br /><br /> ';

   
  //echo "<script>    setTimeout( function() {if (window.confirm('You can also check our new system that helps you to avoid a wait time')) {        window.location.href='http://waitington.com';    }}, 5000); </script>";
} 
}
add_action('wp_footer', 'wwy_footer_sc');

// paypal callback
function wwy_paid() { 
	if (is_user_logged_in() ) {
       include "ipnlistener.php";
	}
	else{
	echo 'Please <a href="'.home_url().'/wwy-sign-in/">Sign In</a>!';
	}
}  
    add_shortcode('wwy_paid_space', 'wwy_paid');  
	
	
// edit profile
function wwy_show_form( $user_id = null ) {
			if (is_user_logged_in() ) {
	   global $userdata, $wp_http_referer;
        get_currentuserinfo();

        if ( !(function_exists( 'get_user_to_edit' )) ) {
            require_once(ABSPATH . '/wp-admin/includes/user.php');
        }

        if ( !(function_exists( '_wp_get_user_contactmethods' )) ) {
            require_once(ABSPATH . '/wp-includes/registration.php');
        }

        if ( !$user_id ) {
            $current_user = wp_get_current_user();
            $user_id = $user_ID = $current_user->ID;
        }

        if ( isset( $_POST['submit'] ) ) {
            check_admin_referer( 'update-profile_' . $user_id );
            $errors = edit_user( $user_id );
            if ( is_wp_error( $errors ) ) {
                $message = $errors->get_error_message();
                $style = 'error';
            } else {
                $message = __( '<strong>Success</strong>: Profile updated', 'wwy' );
                $style = 'success';
                do_action( 'personal_options_update', $user_id );
            }
        }

        $profileuser = get_user_to_edit( $user_id );

        if ( isset( $message ) ) {
            echo '<div class="' . $style . '">' . $message . '</div>';
        }
        ?>
        <div class="wwy-profile">
            <form name="profile" id="your-profile" action="" method="post">
                <?php wp_nonce_field( 'update-profile_' . $user_id ) ?>
                <?php if ( $wp_http_referer ) : ?>
                    <input type="hidden" name="wp_http_referer" value="<?php echo esc_url( $wp_http_referer ); ?>" />
                <?php endif; ?>
                <input type="hidden" name="from" value="profile" />
                <input type="hidden" name="checkuser_id" value="<?php echo $user_id; ?>" />
                <table class="wwy-table">
                    <?php do_action( 'personal_options', $profileuser ); ?>
                </table>
                <?php do_action( 'profile_personal_options', $profileuser ); ?>

                <fieldset>
                    <legend><?php _e( 'Name' ) ?></legend>

                    <table class="wwy-table">
                        <tr>
                            <th><label for="user_login1"><?php _e( 'Username' ); ?></label></th>
                            <td><input type="text" name="user_login" id="user_login1" value="<?php echo esc_attr( $profileuser->user_login ); ?>" disabled="disabled" class="regular-text" /><br /><em><span class="description"><?php _e( 'Usernames cannot be changed.' ); ?></span></em></td>
                        </tr>
                        <tr>
                            <th><label for="first_name"><?php _e( 'First Name' ) ?></label></th>
                            <td><input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $profileuser->first_name ) ?>" class="regular-text" /></td>
                        </tr>

                        <tr>
                            <th><label for="last_name"><?php _e( 'Last Name' ) ?></label></th>
                            <td><input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $profileuser->last_name ) ?>" class="regular-text" /></td>
                        </tr>
                      
                    </table>
                </fieldset>

                <fieldset>
                    <legend><?php _e( 'Contact Info' ) ?></legend>

                    <table class="wwy-table">
                        <tr>
                            <th><label for="email"><?php _e( 'E-mail' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label></th>
                            <td><input type="text" name="email" id="email" value="<?php echo esc_attr( $profileuser->user_email ) ?>" class="regular-text" /> </td>
                        </tr>

                        <tr>
                            <th><label for="url"><?php _e( 'Website' ) ?></label></th>
                            <td><input type="text" name="url" id="url" value="<?php echo esc_attr( $profileuser->user_url ) ?>" class="regular-text code" /></td>
                        </tr>

                        <?php
                        foreach (_wp_get_user_contactmethods() as $name => $desc) {
                            ?>
                            <tr>
                                <th><label for="<?php echo $name; ?>"><?php echo apply_filters( 'user_' . $name . '_label', $desc ); ?></label></th>
                                <td><input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo esc_attr( $profileuser->$name ) ?>" class="regular-text" /></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </fieldset>

                <fieldset>
                    <legend><?php _e( 'About Yourself' ); ?></legend>

                    <table class="wwy-table">
                        <tr>
                            <th><label for="description"><?php _e( 'Biographical Info', 'wwy' ); ?></label></th>
                            <td><textarea name="description" id="description" rows="5" cols="30"><?php echo esc_html( $profileuser->description ); ?></textarea><br />
                                <span class="description"><?php _e( 'Share a little biographical information to fill out your profile. This may be shown publicly.' ); ?></span></td>
                        </tr>
                        <tr id="password">
                            <th><label for="pass1"><?php _e( 'New Password', 'wwy' ); ?></label></th>
                            <td>
                                <input type="password" name="pass1" id="pass1" size="16" value="" autocomplete="off" /><br /><br />
                            </td>
                        </tr>
                        <tr>
                            <th><label><?php _e( 'Confirm Password', 'wwy' ); ?></label></th>
                            <td>
                                <input type="password" name="pass2" id="pass2" size="16" value="" autocomplete="off" />&nbsp;<em><span class="description"><?php _e( "Type your new password again." ); ?></span></em>
                            </td>
                        </tr>                       
                    </table>
                </fieldset>

                <?php do_action( 'show_user_profile', $profileuser ); ?>

                <p class="submit">
                    <input type="hidden" name="action" value="update" />
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( $user_id ); ?>" />
                    <input type="submit" class="wwy-submit" value="<?php _e( 'Update Profile', 'wwy' ); ?>" name="submit" />
                </p>
            </form>
        </div>
		<?php
    }else{ echo 'You need to login before your start exploring the site! <br /> <a href="'.home_url().'/wwy-sign-in/">Sign In</a>'; 
	}
	}
    add_shortcode('wwy_edit_profile', 'wwy_show_form'); 
	
function wwy_editor() { 
	
     if ( is_user_logged_in() ) {	
	  wwy_jscss();
	  require_once('wwy_timeline.php');      
	} else {
		echo 'You need to login before your start exploring the site! <br /> <a href="'.home_url().'/wwy-sign-in/">Sign In</a>'; 
	}     
}  
    add_shortcode('wwy_editor_space', 'wwy_editor');  

// shortcode for publish page
function wwy_publish() {   
	  wwy_jscss();
	  require_once('wwy_published.php');	  
}  
    add_shortcode('wwy_published', 'wwy_publish'); 
	
// create shortcode for sign-in
function wwy_sign_in() {     	 
     if (! is_user_logged_in() ) {
		wp_login_form(); 
		echo '<a href="' . wp_lostpassword_url( $redirect ) . '">Lost Password?</a> ';
        echo 'or ------------- <i class="fa fa-magic"></i> <a class="wwy_reg" href="../wwy-register/">Register</a>';		
	}else{ wp_redirect( home_url());}	
}  
    add_shortcode('wwy_sign_in_space', 'wwy_sign_in'); 
	

// hide dashboard
function remove_admin_bar() {
	if ( ! current_user_can ( 'administrator' ) && !is_admin()) {
	  show_admin_bar(false);
	}	
	}
add_action('after_setup_theme', 'remove_admin_bar');
	
//restrict user getting to the admin area
function wwy_restrict_admin() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_redirect( home_url() );
    }
}
add_action( 'admin_init', 'wwy_restrict_admin', 1 );

// adding sign in link in the header if not logged in
function add_sign_in (){
global $current_user;
      get_currentuserinfo();	
    $signinbar = get_option('wwy_signinbar');
	if($signinbar == 1) {
	if (!is_user_logged_in() ) {
	echo "<div id='topbar'><a class='wwy-sign-in' href='wwy-sign-in'>Sign In</a> | <a class='wwy-sign-in' href='wwy-register'>Register</a></div>";
}else{
	echo "<div id='topbar'>You are logged in as <span>" . $current_user->user_login . "</span> | <a href='" .  wp_logout_url( home_url() ) . "'>Sign Out</a></div>";
}
}
}
add_action('wp_head', 'add_sign_in');
//register form

function wwy_register_form(){ 
	if ( ! is_user_logged_in() ) {	
?>



<div id="tab1_login" class="tab_content_login" style="margin-left: 560px;width: 130px;margin-top: -20px;"><a href="../wwy-sign-in/">Back to login</a></div>


<p>Sign up now! It's free.</p>

<form method="post" action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>" class="wp-user-form" data-abide>
<div class="username">
<label for="user_login">Username: </label>
<input type="text" name="user_login" value="" size="20" id="user_login" tabindex="101" />
</div>

<div class="email">
<label for="user_email">Your Email: </label>
<input type="text" name="user_email" value="" size="25" id="user_email" tabindex="102" />
</div>

			<div class="login_fields">			
<p class="login-submit">
<input type="submit" name="user-submit" id="wp-submit" value="Sign up" class="user-submit" tabindex="103" />
</p>
<input type="hidden" name="redirect_to" value="wwy-thanks" />
<input type="hidden" name="user-cookie" value="1" />
			</div>
</form>


<?php } 
	else {
	echo 'You need to login before your start exploring the site! <br /> <a href="'.home_url().'/wwy-sign-in/">Sign In</a>'; 
	}

}
add_shortcode('wwy_register','wwy_register_form');

function wwy_thank_you(){ ?>
    <p>Thank you for registration. Please check your email and confirm your registration!</p>
	<a href='<?php echo home_url(); ?>'>Continue</a>
	
<?php }
add_shortcode('wwy_thanks','wwy_thank_you');

//hide nav menu if not signed inet_ntop
function my_wp_nav_menu_args( $args = '' ) {
	
	if ( is_user_logged_in() ) {
	$args[''] = false;
	return $args;
}}
$main_menu = get_option('wwy_menu_in');
	if($main_menu == "") {
add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );
}
// Create new table to store data of the map
global $table_db_version;
$table_db_version = "1.0";

function wwy_table_add(){

	global $wpdb;
	global $table_db_version;

	$table_add_one = $wpdb->prefix . "wwy_data_one";
   	$table_add_two = $wpdb->prefix . "wwy_data_two";
   	$table_add_three = $wpdb->prefix . "wwy_data_three";
   	$table_add_four = $wpdb->prefix . "wwy_data_four";
	//-----------------------table_one-----------------------------------
        if($wpdb->get_var('SHOW TABLES LIKE ' . $table_add_one) != $table_add_one){
	  $sql_one = 'CREATE TABLE ' . $table_add_one . '(		  
		  field_id BIGINT(20) UNSIGNED AUTO_INCREMENT,
	      user_id BIGINT(20) UNSIGNED,
		  slide_number int (11) NOT NULL,
          map_type VARCHAR(255),		  
		  date VARCHAR (255),
		  length int (11) NOT NULL,
		  caption VARCHAR(255),
		  PRIMARY KEY  (field_id) )';

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql_one);
	}

	//-----------------------table_two-----------------------------------
        if($wpdb->get_var('SHOW TABLES LIKE ' . $table_add_two) != $table_add_two){
	  $sql_two = 'CREATE TABLE ' . $table_add_two . '(
	      marker_field_id BIGINT(20) UNSIGNED AUTO_INCREMENT,
	      user_id BIGINT(20) UNSIGNED,		
		  slide_number_option int (11) NOT NULL,
		  marker_name VARCHAR(255),
		  coordx VARCHAR (255),		  
		  coordy VARCHAR (255), 		  		  	  
		  color VARCHAR (10),		  	  
		  PRIMARY KEY  (marker_field_id) )';

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql_two);
	}
	//-----------------------table_three-----------------------------------
        if($wpdb->get_var('SHOW TABLES LIKE ' . $table_add_three) != $table_add_three){
	  $sql_three = 'CREATE TABLE ' . $table_add_three . '(
	      table_id BIGINT(20) UNSIGNED AUTO_INCREMENT,
	      user_id BIGINT(20) UNSIGNED,		
		  published int (11) NOT NULL,		  		  	  	  
		  pub_name VARCHAR (255),		  	  	  
		  PRIMARY KEY  (table_id) )';

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql_three);
	}
    //-----------------------table_four-----------------------------------
        if($wpdb->get_var('SHOW TABLES LIKE ' . $table_add_four) != $table_add_four){
	  $sql_four = 'CREATE TABLE ' . $table_add_four . '(
	      admtable_id BIGINT(20) UNSIGNED AUTO_INCREMENT,
	      user_id BIGINT(20) UNSIGNED,		
		  payer_email VARCHAR (255),		  		  	  	  
		  payment_status int (11),		  	  	  
		  payment_date VARCHAR (255),		  	  	  
		  mc_gross FLOAT (11),		  	  	  
		  PRIMARY KEY  (admtable_id) )';

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql_four);
	}
    add_option("table_db_version", $table_db_version);
}
register_activation_hook(__FILE__,'wwy_table_add');


function wwy_update_db_check() {
    global $table_db_version;
    if (get_site_option('table_db_version') != $table_db_version) {
        wwy_table_add();
    }
}
add_action('plugins_loaded', 'wwy_update_db_check');

// Drop table after deactivation

function pluginUninstall_wwy_datas() {

        global $wpdb;
        $table_add_one = $wpdb->prefix . "wwy_data_one";
   	    $table_add_two = $wpdb->prefix . "wwy_data_two";    
   	    $table_add_three = $wpdb->prefix . "wwy_data_three";    
   	    $table_add_four = $wpdb->prefix . "wwy_data_four";    

	$wpdb->query("DROP TABLE IF EXISTS $table_add_one");
	$wpdb->query("DROP TABLE IF EXISTS $table_add_two");
	$wpdb->query("DROP TABLE IF EXISTS $table_add_three");
	$wpdb->query("DROP TABLE IF EXISTS $table_add_four");
}
// register_deactivation_hook( __FILE__, 'pluginUninstall_wwy_datas' );  //TO DO Enable, DO you want to remove these tables?

// Create pages (Sign In)
function wwy_signin_install() {

    global $wpdb;

    $the_page_title = 'WWY Sign In';
    $the_page_name = 'Sign In';

    // the menu entry...
    delete_option("wwy_signin_page_title");
    add_option("wwy_signin_page_title", $the_page_title, '', 'yes');
    // the slug...
    delete_option("wwy_signin_page_name");
    add_option("wwy_signin_page_name", $the_page_name, '', 'yes');
    // the id...
    delete_option("wwy_signin_page_id");
    add_option("wwy_signin_page_id", '0', '', 'yes');

    $the_page = get_page_by_title( $the_page_title );

    if ( ! $the_page ) {

        // Create post object
        $_p = array();
        $_p['post_title'] = $the_page_title;
        $_p['post_content'] = "[wwy_sign_in_space]";
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
        $_p['post_category'] = array(1); // the default 'Uncatrgorised'
        

        // Insert the post into the database
        $the_page_id = wp_insert_post( $_p );

    }
    else {
        // the plugin may have been previously active and the page may just be trashed...

        $the_page_id = $the_page->ID;

        //make sure the page is not trashed...
        $the_page->post_status = 'publish';
        $the_page_id = wp_update_post( $the_page );

    }

    delete_option( 'wwy_signin_page_id' );
    add_option( 'wwy_signin_page_id', $the_page_id );

}

function wwy_signin_remove() {

    global $wpdb;

    $the_page_title = get_option( "wwy_signin_page_title" );
    $the_page_name = get_option( "wwy_signin_page_name" );

    //  the id of our page...
    $the_page_id = get_option( 'wwy_signin_page_id' );
    if( $the_page_id ) {

        wp_delete_post( $the_page_id ); // this will trash, not delete

    }

    delete_option("wwy_signin_page_title");
    delete_option("wwy_signin_page_name");
    delete_option("wwy_signin_page_id");

}

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'wwy_signin_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'wwy_signin_remove' );

// Create pages (Register)
function wwy_register_install() {

    global $wpdb;

    $the_page_title = 'WWY Register';
    $the_page_name = 'Register';

    // the menu entry...
    delete_option("wwy_register_page_title");
    add_option("wwy_register_page_title", $the_page_title, '', 'yes');
    // the slug...
    delete_option("wwy_register_page_name");
    add_option("wwy_register_page_name", $the_page_name, '', 'yes');
    // the id...
    delete_option("wwy_register_page_id");
    add_option("wwy_register_page_id", '0', '', 'yes');

    $the_page = get_page_by_title( $the_page_title );

    if ( ! $the_page ) {

        // Create post object
        $_p = array();
        $_p['post_title'] = $the_page_title;
        $_p['post_content'] = "[wwy_register]";
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
        $_p['post_category'] = array(1); // the default 'Uncatrgorised'
        

        // Insert the post into the database
        $the_page_id = wp_insert_post( $_p );

    }
    else {
        // the plugin may have been previously active and the page may just be trashed...

        $the_page_id = $the_page->ID;

        //make sure the page is not trashed...
        $the_page->post_status = 'publish';
        $the_page_id = wp_update_post( $the_page );

    }

    delete_option( 'wwy_register_page_id' );
    add_option( 'wwy_register_page_id', $the_page_id );

}

function wwy_register_remove() {

    global $wpdb;

    $the_page_title = get_option( "wwy_register_page_title" );
    $the_page_name = get_option( "wwy_register_page_name" );

    //  the id of our page...
    $the_page_id = get_option( 'wwy_register_page_id' );
    if( $the_page_id ) {

        wp_delete_post( $the_page_id ); // this will trash, not delete

    }

    delete_option("wwy_register_page_title");
    delete_option("wwy_register_page_name");
    delete_option("wwy_register_page_id");

}

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'wwy_register_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'wwy_register_remove' );


function wwy_thanks_install() {

    global $wpdb;

    $the_page_title = 'WWY Thanks';
    $the_page_name = 'Thanks';

    // the menu entry...
    delete_option("wwy_thanks_page_title");
    add_option("wwy_thanks_page_title", $the_page_title, '', 'yes');
    // the slug...
    delete_option("wwy_thanks_page_name");
    add_option("wwy_thanks_page_name", $the_page_name, '', 'yes');
    // the id...
    delete_option("wwy_thanks_page_id");
    add_option("wwy_thanks_page_id", '0', '', 'yes');

    $the_page = get_page_by_title( $the_page_title );

    if ( ! $the_page ) {

        // Create post object
        $_p = array();
        $_p['post_title'] = $the_page_title;
        $_p['post_content'] = "[wwy_thanks]";
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
        $_p['post_category'] = array(1); // the default 'Uncatrgorised'
        

        // Insert the post into the database
        $the_page_id = wp_insert_post( $_p );

    }
    else {
        // the plugin may have been previously active and the page may just be trashed...

        $the_page_id = $the_page->ID;

        //make sure the page is not trashed...
        $the_page->post_status = 'publish';
        $the_page_id = wp_update_post( $the_page );

    }

    delete_option( 'wwy_thanks_page_id' );
    add_option( 'wwy_thanks_page_id', $the_page_id );

}

function wwy_thanks_remove() {

    global $wpdb;

    $the_page_title = get_option( "wwy_thanks_page_title" );
    $the_page_name = get_option( "wwy_thanks_page_name" );

    //  the id of our page...
    $the_page_id = get_option( 'wwy_thanks_page_id' );
    if( $the_page_id ) {

        wp_delete_post( $the_page_id ); // this will trash, not delete

    }

    delete_option("wwy_thanks_page_title");
    delete_option("wwy_thanks_page_name");
    delete_option("wwy_thanks_page_id");

}

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'wwy_thanks_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'wwy_thanks_remove' );

// new page (account)

function wwy_account_install() {

    global $wpdb;

    $the_page_title = 'WWY Account';
    $the_page_name = 'Account';

    // the menu entry...
    delete_option("wwy_account_page_title");
    add_option("wwy_account_page_title", $the_page_title, '', 'yes');
    // the slug...
    delete_option("wwy_account_page_name");
    add_option("wwy_account_page_name", $the_page_name, '', 'yes');
    // the id...
    delete_option("wwy_account_page_id");
    add_option("wwy_account_page_id", '0', '', 'yes');

    $the_page = get_page_by_title( $the_page_title );

    if ( ! $the_page ) {

        // Create post object
        $_p = array();
        $_p['post_title'] = $the_page_title;
        $_p['post_content'] = "[wwy_account_space]";
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
        $_p['post_category'] = array(1); // the default 'Uncatrgorised'
        

        // Insert the post into the database
        $the_page_id = wp_insert_post( $_p );

    }
    else {
        // the plugin may have been previously active and the page may just be trashed...

        $the_page_id = $the_page->ID;

        //make sure the page is not trashed...
        $the_page->post_status = 'publish';
        $the_page_id = wp_update_post( $the_page );

    }

    delete_option( 'wwy_account_page_id' );
    add_option( 'wwy_account_page_id', $the_page_id );

}

function wwy_account_remove() {

    global $wpdb;

    $the_page_title = get_option( "wwy_account_page_title" );
    $the_page_name = get_option( "wwy_account_page_name" );

    //  the id of our page...
    $the_page_id = get_option( 'wwy_account_page_id' );
    if( $the_page_id ) {

        wp_delete_post( $the_page_id ); // this will trash, not delete

    }

    delete_option("wwy_account_page_title");
    delete_option("wwy_account_page_name");
    delete_option("wwy_account_page_id");

}

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'wwy_account_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'wwy_account_remove' );

// new page (edit profile) 

function wwy_editprofile_install() {

    global $wpdb;

    $the_page_title = 'WWY Edit Profile';
    $the_page_name = 'Edit Profile';

    // the menu entry...
    delete_option("wwy_editprofile_page_title");
    add_option("wwy_editprofile_page_title", $the_page_title, '', 'yes');
    // the slug...
    delete_option("wwy_editprofile_page_name");
    add_option("wwy_editprofile_page_name", $the_page_name, '', 'yes');
    // the id...
    delete_option("wwy_editprofile_page_id");
    add_option("wwy_editprofile_page_id", '0', '', 'yes');

    $the_page = get_page_by_title( $the_page_title );

    if ( ! $the_page ) {

        // Create post object
        $_p = array();
        $_p['post_title'] = $the_page_title;
        $_p['post_content'] = "[wwy_edit_profile]";
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
        $_p['post_category'] = array(1); // the default 'Uncatrgorised'
        

        // Insert the post into the database
        $the_page_id = wp_insert_post( $_p );

    }
    else {
        // the plugin may have been previously active and the page may just be trashed...

        $the_page_id = $the_page->ID;

        //make sure the page is not trashed...
        $the_page->post_status = 'publish';
        $the_page_id = wp_update_post( $the_page );

    }

    delete_option( 'wwy_editprofile_page_id' );
    add_option( 'wwy_editprofile_page_id', $the_page_id );

}

function wwy_editprofile_remove() {

    global $wpdb;

    $the_page_title = get_option( "wwy_editprofile_page_title" );
    $the_page_name = get_option( "wwy_editprofile_page_name" );

    //  the id of our page...
    $the_page_id = get_option( 'wwy_editprofile_page_id' );
    if( $the_page_id ) {

        wp_delete_post( $the_page_id ); // this will trash, not delete

    }

    delete_option("wwy_editprofile_page_title");
    delete_option("wwy_editprofile_page_name");
    delete_option("wwy_editprofile_page_id");

}

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'wwy_editprofile_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'wwy_editprofile_remove' );

// new page (editor_space)
function wwy_creation_install() {

    global $wpdb;

    $the_page_title = 'WWY Your Creation';
    $the_page_name = 'Your Creation';

    // the menu entry...
    delete_option("wwy_creation_page_title");
    add_option("wwy_creation_page_title", $the_page_title, '', 'yes');
    // the slug...
    delete_option("wwy_creation_page_name");
    add_option("wwy_creation_page_name", $the_page_name, '', 'yes');
    // the id...
    delete_option("wwy_creation_page_id");
    add_option("wwy_creation_page_id", '0', '', 'yes');

    $the_page = get_page_by_title( $the_page_title );

    if ( ! $the_page ) {

        // Create post object
        $_p = array();
        $_p['post_title'] = $the_page_title;
        $_p['post_content'] = "[wwy_editor_space]";
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
        $_p['post_category'] = array(1); // the default 'Uncatrgorised'
        

        // Insert the post into the database
        $the_page_id = wp_insert_post( $_p );

    }
    else {
        // the plugin may have been previously active and the page may just be trashed...

        $the_page_id = $the_page->ID;

        //make sure the page is not trashed...
        $the_page->post_status = 'publish';
        $the_page_id = wp_update_post( $the_page );

    }

    delete_option( 'wwy_creation_page_id' );
    add_option( 'wwy_creation_page_id', $the_page_id );

}

function wwy_creation_remove() {

    global $wpdb;

    $the_page_title = get_option( "wwy_creation_page_title" );
    $the_page_name = get_option( "wwy_creation_page_name" );

    //  the id of our page...
    $the_page_id = get_option( 'wwy_creation_page_id' );
    if( $the_page_id ) {

        wp_delete_post( $the_page_id ); // this will trash, not delete

    }

    delete_option("wwy_creation_page_title");
    delete_option("wwy_creation_page_name");
    delete_option("wwy_creation_page_id");

}

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'wwy_creation_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'wwy_creation_remove' );

// new page (published page)
function wwy_publish_install() {

    global $wpdb;

    $the_page_title = 'WWY Published';
    $the_page_name = 'Published Maps';

    // the menu entry...
    delete_option("wwy_publish_page_title");
    add_option("wwy_publish_page_title", $the_page_title, '', 'yes');
    // the slug...
    delete_option("wwy_publish_page_name");
    add_option("wwy_publish_page_name", $the_page_name, '', 'yes');
    // the id...
    delete_option("wwy_publish_page_id");
    add_option("wwy_publish_page_id", '0', '', 'yes');

    $the_page = get_page_by_title( $the_page_title );

    if ( ! $the_page ) {

        // Create post object
        $_p = array();
        $_p['post_title'] = $the_page_title;
        $_p['post_content'] = "[wwy_published]";
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
        $_p['post_category'] = array(1); // the default 'Uncatrgorised'
        

        // Insert the post into the database
        $the_page_id = wp_insert_post( $_p );

    }
    else {
        // the plugin may have been previously active and the page may just be trashed...

        $the_page_id = $the_page->ID;

        //make sure the page is not trashed...
        $the_page->post_status = 'publish';
        $the_page_id = wp_update_post( $the_page );

    }

    delete_option( 'wwy_publish_page_id' );
    add_option( 'wwy_publish_page_id', $the_page_id );

}

function wwy_publish_remove() {

    global $wpdb;

    $the_page_title = get_option( "wwy_publish_page_title" );
    $the_page_name = get_option( "wwy_publish_page_name" );

    //  the id of our page...
    $the_page_id = get_option( 'wwy_publish_page_id' );
    if( $the_page_id ) {

        wp_delete_post( $the_page_id ); // this will trash, not delete

    }

    delete_option("wwy_publish_page_title");
    delete_option("wwy_publish_page_name");
    delete_option("wwy_publish_page_id");

}

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'wwy_publish_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'wwy_publish_remove' );

// new page (front page)
function wwy_front_install() {

    global $wpdb;

    $the_page_title = 'WWY Home';
    $the_page_name = 'Home';

    // the menu entry...
    delete_option("wwy_front_page_title");
    add_option("wwy_front_page_title", $the_page_title, '', 'yes');
    // the slug...
    delete_option("wwy_front_page_name");
    add_option("wwy_front_page_name", $the_page_name, '', 'yes');
    // the id...
    delete_option("wwy_front_page_id");
    add_option("wwy_front_page_id", '0', '', 'yes');

    $the_page = get_page_by_title( $the_page_title );

    if ( ! $the_page ) {

        // Create post object
        $_p = array();
        $_p['post_title'] = $the_page_title;
        $_p['post_content'] = "[wwy_front_space]";
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
        $_p['post_category'] = array(1); // the default 'Uncatrgorised'
        

        // Insert the post into the database
        $the_page_id = wp_insert_post( $_p );

    }
    else {
        // the plugin may have been previously active and the page may just be trashed...

        $the_page_id = $the_page->ID;

        //make sure the page is not trashed...
        $the_page->post_status = 'publish';
        $the_page_id = wp_update_post( $the_page );

    }

    delete_option( 'wwy_front_page_id' );
    add_option( 'wwy_front_page_id', $the_page_id );

}

function wwy_front_remove() {

    global $wpdb;

    $the_page_title = get_option( "wwy_front_page_title" );
    $the_page_name = get_option( "wwy_front_page_name" );

    //  the id of our page...
    $the_page_id = get_option( 'wwy_front_page_id' );
    if( $the_page_id ) {

        wp_delete_post( $the_page_id ); // this will trash, not delete

    }

    delete_option("wwy_front_page_title");
    delete_option("wwy_front_page_name");
    delete_option("wwy_front_page_id");

}

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'wwy_front_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'wwy_front_remove' );

// new page (front page)
function wwy_paid_install() {

    global $wpdb;

    $the_page_title = 'WWY Paid';
    $the_page_name = 'WWY Thank you for your purchase';

    // the menu entry...
    delete_option("wwy_paid_page_title");
    add_option("wwy_paid_page_title", $the_page_title, '', 'yes');
    // the slug...
    delete_option("wwy_paid_page_name");
    add_option("wwy_paid_page_name", $the_page_name, '', 'yes');
    // the id...
    delete_option("wwy_paid_page_id");
    add_option("wwy_paid_page_id", '0', '', 'yes');

    $the_page = get_page_by_title( $the_page_title );

    if ( ! $the_page ) {

        // Create post object
        $_p = array();
        $_p['post_title'] = $the_page_title;
        $_p['post_content'] = "[wwy_paid_space]";
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
        $_p['post_category'] = array(1); // the default 'Uncatrgorised'
        

        // Insert the post into the database
        $the_page_id = wp_insert_post( $_p );

    }
    else {
        // the plugin may have been previously active and the page may just be trashed...

        $the_page_id = $the_page->ID;

        //make sure the page is not trashed...
        $the_page->post_status = 'publish';
        $the_page_id = wp_update_post( $the_page );

    }

    delete_option( 'wwy_paid_page_id' );
    add_option( 'wwy_paid_page_id', $the_page_id );

}

function wwy_paid_remove() {

    global $wpdb;

    $the_page_title = get_option( "wwy_paid_page_title" );
    $the_page_name = get_option( "wwy_paid_page_name" );

    //  the id of our page...
    $the_page_id = get_option( 'wwy_paid_page_id' );
    if( $the_page_id ) {

        wp_delete_post( $the_page_id ); // this will trash, not delete

    }

    delete_option("wwy_paid_page_title");
    delete_option("wwy_paid_page_name");
    delete_option("wwy_paid_page_id");

}

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'wwy_paid_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'wwy_paid_remove' );

// Add user's id's
add_filter('manage_users_columns', 'wwy_add_user_id_column');
function wwy_add_user_id_column($columns) {
    $columns['user_id'] = 'User ID';
    return $columns;
}
 
add_action('manage_users_custom_column',  'wwy_show_user_id_column_content', 10, 3);
function wwy_show_user_id_column_content($value, $column_name, $user_id) {
    $user = get_userdata( $user_id );
	if ( 'user_id' == $column_name )
		return $user_id;
    return $value;
}


?>