<?php

/**
 * Plugin Name: Mobile Contact Bar
 * Plugin URI:  https://bansaghi.github.io/mobilecontactbar/
 * Description: Allow your visitors to contact you directly via phone, email, or Social Media
 * Version:     1.0.0
 * Author:      Anna Bansaghi
 * Author URI:  https://github.com/bansaghi/
 * License:     GPL-3.0
 * License URI: http://www.gnu.org/licenses/gpl.html
 * Copyright:   Anna Bansaghi
 * Text Domain: mcb
 */


defined( 'ABSPATH' ) or exit;


/**
 * Main plugin file
 * 
 * @since 0.0.1
 * 
 * @package Mobile_Contact_Bar
 * @author Anna Bansaghi <anna.bansaghi@mamikon.net>
 * @license GPL-3.0
 * @link https://github.com/bansaghi/mobile-contact-bar/
 * @copyright Anna Bansaghi
 */


define( 'MCB_PLUGIN_FILE', __FILE__ );


/* -------------------------------------------------------------------------- */
/*                        Administration functionality                        */
/* -------------------------------------------------------------------------- */
if( is_admin() ) {

  include_once( plugin_dir_path( __FILE__ ) . 'includes/class-mcb-admin.php' );
  register_activation_hook( __FILE__, array( 'MCB_Admin', 'on_activation' ));
  add_action( 'plugins_loaded', array( 'MCB_Admin', 'plugins_loaded' ));




/* -------------------------------------------------------------------------- */
/*                           Frontend functionality                           */
/* -------------------------------------------------------------------------- */
} else {

  include_once( plugin_dir_path( __FILE__ ) . 'includes/class-mcb-front.php' );
  add_action( 'plugins_loaded', array( 'MCB_Front', 'plugins_loaded' ));
}

