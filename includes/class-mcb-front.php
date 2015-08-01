<?php

defined( 'ABSPATH' ) or exit;

/**
 * Frontend related class
 * 
 * @since 0.0.1
 * 
 * @package Mobile_Contact_Bar
 * @author Anna Bansaghi <anna.bansaghi@mamikon.net>
 * @license GPL-3.0
 * @link https://github.com/bansaghi/mobile-contact-bar/
 * @copyright Anna Bansaghi
 */
final class MCB_Front {

  public static $option = null;


  /**
   * Adds WP frontend hooks
   * 
   * @since 0.0.1
   * 
   * @link https://codex.wordpress.org/Plugin_API/Action_Reference/plugins_loaded
   */
  public static function plugins_loaded() {

    self::$option = get_option( 'mcb_option' );

    if( ! self::$option ) {
      $option = get_option( 'mcb_options' );
      self::$option = $option;
      update_option( 'mcb_option', self::$option );
      delete_option( 'mcb_options' );
    }

    if( ! empty( self::$option ) && isset( self::$option['contacts'] ) && isset( self::$option['styles'] ) && self::$option['settings']['bar_is_active'] ) {
      add_action( 'wp_head'            , array( __CLASS__, 'wp_head' ), 7 );
      add_action( 'wp_enqueue_scripts' , array( __CLASS__, 'wp_enqueue_scripts' ));
      add_action( 'wp_footer'          , array( __CLASS__, 'wp_footer' ));
    }
  }




  /**
   * Links Font Awesome icons related CSS styles
   * 
   * @since 0.0.1
   * 
   * @link https://codex.wordpress.org/Function_Reference/wp_enqueue_script
   * @link http://fontawesome.io/
   */
  public static function wp_enqueue_scripts() {
    wp_enqueue_style( 'fa',
        plugins_url( 'fonts/font-awesome/css/font-awesome.min.css', MCB_PLUGIN_FILE ),
        false,
        '4.4.0',
        'all'
    );
  }




  /**
   * Adds the plugin related CSS styles within the head section
   * 
   * @since 0.0.1
   * 
   * @link https://codex.wordpress.org/Plugin_API/Action_Reference/wp_head 
   */
  public static function wp_head() {
    ?>
    <style id="mobile-contact-bar-css" type="text/css" media="screen"><?php echo str_replace( '&quot;', '"', esc_html__( self::$option['styles'] )); ?></style>
    <?php
  }




  /**
   * Invokes mcb_front_render_html action only once
   * 
   * @since 0.0.1
   * 
   * @uses MCB_Front::render_html()
   * 
   * @link https://codex.wordpress.org/Plugin_API/Action_Reference/wp_footer
   */
  public static function wp_footer() {

    if( ! has_action( 'mcb_front_render_html' )) {
      add_action( 'mcb_front_render_html', array( __CLASS__, 'render_html' ), 10, 2 );
    }

    do_action( 'mcb_front_render_html', self::$option['contacts'], self::$option['settings'] );
  }




  /**
   * Echos output to the browser
   * 
   * @since 0.0.1
   * 
   * @param array $contacts Associative array of displayable contacts
   * @param array $settings Associative array of bar and icon settings
   */
  public static function render_html( $contacts, $settings ) {
    if( 1 === did_action( 'mcb_front_render_html' )) {

      $html = '';

      $html .= '<div id="mcb-wrap">';

        if( $settings['bar_is_toggle'] ) {
          $html .= '<input id="mcb-toggle-checkbox" name="mcb-toggle-checkbox" type="checkbox">';
        }

        if( $settings['bar_is_toggle'] && 'bottom' == $settings['bar_position'] ) {        
          $html .= '<label for="mcb-toggle-checkbox" id="mcb-toggle"></label>';
        }

        $html .= '<div id="mcb-bar">';

          $html .= '<ul>';

            foreach( $contacts as $id => $contact ) {

              $uri = '';
              switch( $contact['protocol'] ) {

                case 'tel':
                case 'mailto':
                case 'skype':
                  $uri = $contact['protocol'] . ':' . esc_attr( $contact['resource'] );
                  break;

                case 'http':
                case 'https':
                  $uri = esc_url( $contact['resource'] );
                  break;

                default:
                  $uri = apply_filters( 'mcb_front_escape_uri', '', $id, $contact['protocol'], $contact['resource'] );
                  if( ! $uri ) {
                    $uri = esc_attr( $contact['protocol'] . ':' . $contact['resource'] );
                  }
                  break;
              }
              
              $html .= sprintf( '<li><a data-rel="external" href="%s"><i class="fa %s fa-%s"></i></a></li>',
                  $uri,
                  '1x' == $settings['icon_size'] ? '' : 'fa-' . esc_attr( $settings['icon_size'] ),
                  esc_attr( $contact['icon'] )
              );
            }

          $html .= '</ul>';
        $html .= '</div>';

        if( $settings['bar_is_toggle'] && 'top' == $settings['bar_position'] ) {
          $html .= '<label for="mcb-toggle-checkbox" id="mcb-toggle"></label>';
        }


      $html .= '</div>';


      echo $html;
    }
  }

}

