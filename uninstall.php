<?php

defined( 'ABSPATH' ) and defined( 'WP_UNINSTALL_PLUGIN' ) or exit;
current_user_can( 'activate_plugins' ) or exit;


if( function_exists( 'is_multisite' ) && is_multisite() ) {

    global $wpdb;

    $blogs = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

    foreach( $blogs as $blog ) {
        switch_to_blog( $blog );

        delete_option( 'mcb_options' );
    }
    restore_current_blog();

} else {
    delete_option( 'mcb_options' );
}

?>
