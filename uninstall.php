<?php

defined( 'ABSPATH' ) and defined( 'WP_UNINSTALL_PLUGIN' ) or exit;
current_user_can( 'activate_plugins' ) or exit;

/**
 * Cleaning process
 * 
 * @since 0.0.1
 * 
 * @package Mobile_Contact_Bar
 * @author Anna Bansaghi <anna.bansaghi@mamikon.net>
 * @license GPL-3.0
 * @link https://github.com/bansaghi/mobile-contact-bar/
 * @copyright Anna Bansaghi
 */


if( function_exists( 'is_multisite' ) && is_multisite() ) {

  global $wpdb;

  $blogs = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

  foreach( $blogs as $blog_id ) {
    delete_blog_option( $blog_id, 'mcb_option' );
    delete_blog_option( $blog_id, 'mcb_options' );
  }

} else {
  delete_option( 'mcb_option' );
  delete_option( 'mcb_options' );
}

?>
