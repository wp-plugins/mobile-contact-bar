<?php


/**
 * Administration related class
 * 
 * @since 0.0.1
 * 
 * @package Mobile_Contact_Bar
 * @author Anna Bansaghi <anna.bansaghi@mamikon.net>
 * @license GPL-3.0
 * @link https://bansaghi.github.io/mobilecontactbar/
 * @copyright Anna Bansaghi
 */
final class MCB_Admin {

  public static $plugin_data = null;

  public static $settings    = null;
  public static $contacts    = null;

  public static $options     = null;




  /**
   * Inserts plugin's option into the Options table for the very first time
   * 
   * @since 0.0.1
   * 
   * @link https://codex.wordpress.org/Function_Reference/register_activation_hook
   */
  public static function on_activation() {
    if( ! current_user_can( 'activate_plugins' )) {
      return;
    }

    $plugin_data = get_plugin_data( MCB_PLUGIN_FILE );

    include_once( plugin_dir_path( MCB_PLUGIN_FILE ) . 'includes/class-mcb-settings.php' );

    add_option(
        'mcb_options',
        array(
            'version'  => $plugin_data['Version'],
            'settings' => array_map( function( $field ) { return $field['default']; }, MCB_Settings::settings() ),
        )
    );

    set_transient( 'mobile-contact-bar', '1', 120 );
  }





  /**
   * Adds WP admin hooks
   * 
   * @since 0.0.1
   * 
   * @link https://codex.wordpress.org/Plugin_API/Action_Reference/plugins_loaded
   */
  public static function plugins_loaded() {

    load_plugin_textdomain( 'mcb', false, dirname( plugin_basename( MCB_PLUGIN_FILE )) . '/languages' );

    // display wp-pointer after plugin activation
    if( get_transient( 'mobile-contact-bar' )) {
      add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts_wp_pointer' ));
      delete_transient( 'mobile-contact-bar' );
    }


    add_filter( 'plugin_action_links_' . plugin_basename( MCB_PLUGIN_FILE ), array( __CLASS__, 'plugin_action_links' ));

    add_action( 'init'                         , array( __CLASS__, 'init' ));
    add_action( 'admin_menu'                   , array( __CLASS__, 'admin_menu' ));
    add_action( 'admin_init'                   , array( __CLASS__, 'admin_init' ));
    add_action( 'admin_enqueue_scripts'        , array( __CLASS__, 'admin_enqueue_scripts' ));
    add_filter( 'pre_update_option_mcb_options', array( __CLASS__, 'pre_update_option' ), 10, 2 );
    add_action( 'admin_footer'                 , array( __CLASS__, 'admin_footer' ));
  }




  /**
   * Includes plugin classes
   * 
   * @since 0.0.1
   * 
   * @link https://codex.wordpress.org/Plugin_API/Action_Reference/init
   */
  public static function init() {

    include_once( plugin_dir_path( MCB_PLUGIN_FILE ) . 'includes/class-mcb-settings.php' );
    include_once( plugin_dir_path( MCB_PLUGIN_FILE ) . 'includes/class-mcb-contacts.php' );

    self::$settings = MCB_Settings::settings();
    self::$contacts = MCB_Contacts::contacts();
    self::$options  = get_option( 'mcb_options' );
  }




  /**
   * Loads CSS styles and JavaScript scripts for showing wp-pointer after plugin activation
   * 
   * @since 0.0.1
   * 
   * @param $hook string a specific admin page
   * 
   * @see MCB_Admin::admin_print_footer_scripts()
   * 
   * @link https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
   */
  public static function admin_enqueue_scripts_wp_pointer( $hook ) {

    // Display Feature Pointer on activation
    if( 'plugins.php' == $hook && ! is_plugin_active_for_network( plugin_basename( MCB_PLUGIN_FILE ))) {
      wp_enqueue_style(  'wp-pointer' );
      wp_enqueue_script( 'wp-pointer' );
      add_action( 'admin_print_footer_scripts', array( __CLASS__, 'admin_print_footer_scripts' ));
    }
  }




  /**
   * Adds 'Settings' link to plugins overview page
   * 
   * @since 0.0.1
   * 
   * @param $links array of links to display on the plugins page
   * @return array updated links
   * 
   * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_%28plugin_file_name%29
   */
  public static function plugin_action_links( $links ) {

    return array_merge(
        $links,
        array( 'settings' => '<a href="' . admin_url( 'options-general.php?page=' . 'mcb' ) . '">' . __( 'Settings' ) . '</a>' )
    );
  }




  /**
   * Outputs wp-pointer on activation
   * 
   * @since 0.0.1
   * 
   * @link https://codex.wordpress.org/Plugin_API/Action_Reference/admin_print_scripts
   */
  public static function admin_print_footer_scripts() {

    $content  = sprintf( '<h3>%s &rarr; %s</h3>', __( 'Settings' ), __( 'Mobile Contact Bar', 'mcb' ));
    $content .= sprintf( '<p>%s</p>', __( 'Start editing the settings of your contact bar.', 'mcb' ));

    ?>
    <script type="text/javascript">
      ;(function($) {

        $(document).ready(function() {
          $('#menu-settings').pointer({
            content: '<?php echo $content; ?>',
            position: {
              edge: 'left',
              align: 'center'
            }
          }).pointer('open');
        });

      })(jQuery);
    </script>
    <?php
  }




  /**
   * Adds plugin option page
   * 
   * @since 0.0.1
   * 
   * @see MCB_Admin::options_page()
   * 
   * @link https://codex.wordpress.org/Plugin_API/Action_Reference/admin_menu
   * @link https://codex.wordpress.org/Function_Reference/add_options_page
   */
  public static function admin_menu() {

    // Workaround: should be in method init
    self::$plugin_data = get_plugin_data( MCB_PLUGIN_FILE );

    add_options_page(
        __( 'Mobile Contact Bar', 'mcb' ),
        __( 'Mobile Contact Bar', 'mcb' ),
        'manage_options',
        'mcb',
        array( __CLASS__, 'options_page' ));
  }




  /**
   * Outputs the content for the plugin option page
   * 
   * @since 0.0.1
   */
  public static function options_page() {

    ?>
    <div class="wrap mcb-wrap">
      <h2><?php _e( 'Mobile Contact Bar', 'mcb' ) ?></h2>

      <form action="options.php" method="post">
        <?php

        settings_fields( 'mcb_option_group' );
        do_settings_sections( 'mcb' );

        submit_button();
        
        ?>
      </form>
    </div>
    <?php
  }




  /**
   * Adds sections and fields for the plugin option page
   * 
   * @since 0.0.1
   * 
   * @see MCB_Admin::sanitize_input()
   * @see MCB_Admin::setting_field_callback()
   * @see MCB_Admin::setting_contact_callback()
   * 
   * @link https://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
   * @link https://codex.wordpress.org/Function_Reference/register_setting
   * @link https://codex.wordpress.org/Function_Reference/add_settings_section
   * @link https://codex.wordpress.org/Function_Reference/add_settings_field
   */
  public static function admin_init() {

    register_setting( 'mcb_option_group', 'mcb_options', array( __CLASS__, 'sanitize_input' ));

    $section_bar  = array_filter( self::$settings, function( $field ) { return 'bar'  == $field['section']; });
    $section_icon = array_filter( self::$settings, function( $field ) { return 'icon' == $field['section']; });



    /* -------------------------------------------------------------------------- */
    /*                                Section Bar                                 */
    /* -------------------------------------------------------------------------- */
    add_settings_section(
        'section_bar',
        __( 'Bar Display Settings', 'mcb' ),
        false,
        'mcb'
    );

    foreach( $section_bar as $id => $field ) {
      add_settings_field(
          $id,
          $field['title'],
          array( __CLASS__, 'setting_field_callback' ),
          'mcb',
          'section_bar',
          array( 'id' => $id, 'field' => $field )
      ); 
    }



    /* -------------------------------------------------------------------------- */
    /*                                Section Icon                                */
    /* -------------------------------------------------------------------------- */
    add_settings_section(
        'section_icon',
        __( 'Icon Display Settings', 'mcb' ),
        false,
        'mcb'
    );

    foreach( $section_icon as $id => $field ) {
      add_settings_field(
          $id,
          $field['title'],
          array( __CLASS__, 'setting_field_callback' ),
          'mcb',
          'section_icon',
          array( 'id' => $id, 'field' => $field )
      ); 
    }



    /* -------------------------------------------------------------------------- */
    /*                               Section Contacts                             */
    /* -------------------------------------------------------------------------- */
    add_settings_section(
        'section_contacts',
        __( 'Contact Information', 'mcb' ),
        false,
        'mcb'
    );


    foreach( self::$contacts as $id => $contact ) {
      add_settings_field(
          $id,
          '<i class="fa fa-lg fa-' . $contact['icon'] . ' mcb-contact"></i>' . $contact['title'],
          array( __CLASS__, 'setting_contact_callback' ),
          'mcb',
          'section_contacts',
          array( 'id' => $id, 'contact' => $contact )
      ); 
    }
  }




  /**
   * Outputs the content for the setting field
   * 
   * @since 0.0.1
   * 
   * @param $args array of field's arguments
   */
  public static function setting_field_callback( $args ) {

    extract( $args );

    switch( $field['type'] ) {

       case 'color-picker':
        printf(
            '<input type="text" id="%1$s" name="mcb_options[settings][%1$s]" class="cs-wp-color-picker" value="%2$s">',
            esc_attr( $id ),
            esc_attr( self::$options['settings'][$id] )
        );
        break;

      case 'select':
        printf(
            '<select id="%1$s" name="mcb_options[settings][%1$s]">',
            $id
        );
        foreach( $field['options'] as $value => $text ) {
          printf(
              '<option value="%s" %s>%s</option>',
              esc_attr( $value ),
              selected( $value, self::$options['settings'][$id], false ),
              esc_attr( $text )
          );
        }
        echo '</select>';
        break;


      case 'checkbox':
        printf(
            '<label for="%1$s"><input type="checkbox" id="%1$s" name="mcb_options[settings][%1$s]" %2$s value="1">%3$s</label>',
            esc_attr( $id ),
            checked( self::$options['settings'][$id], 1, false ),
            esc_attr( $field['label'] )
        );
        break;

      case 'number':
        printf(
            '<input type="number" id="%1$s" name="mcb_options[settings][%1$s]" class="small-text" min="%2$d" value="%3$d">
            <span>%4$s</span>',
            esc_attr( $id ),
            esc_attr( $field['min'] ),
            esc_attr( self::$options['settings'][$id] ),
            esc_attr( $field['postfix'] )
        );
        break;

       case 'slider':
        printf(
            '<div class="mcb-slider" value=""></div>
            <input type="text" id="%1$s" name="mcb_options[settings][%1$s]" class="small-text mcb-slider-input" value="%2$s" readonly="readonly">',
            esc_attr( $id ),
            esc_attr( self::$options['settings'][$id] )
        );
        break;

      case 'text-icon':
        printf(
            '<input type="text" id="%1$s" name="mcb_options[settings][%1$s]" class="small-text" value="%2$s">
            <i class="fa fa-lg fa-%2$s"></i>',
            esc_attr( $id ),
            esc_attr( self::$options['settings'][$id] )
        );
        break;
    }

    if( isset( $field['desc'] )) {
      printf(
          '<p class="description">%s</p>',
          esc_attr( $field['desc'] )
      );
    }
  }




  /**
   * Outputs the content for the setting field (a contact)
   * 
   * @since 0.0.1
   * 
   * @param $args array of contact's arguments
   */
  public static function setting_contact_callback( $args ) {

    extract( $args );

    printf(
        '<input type="text" id="%1$s" name="mcb_options[contacts][%1$s]" class="regular-text" value="%2$s" placeholder="%3$s">',
        esc_attr( $id ),
        isset( self::$options['contacts'][$id]['resource'] ) ? esc_attr( self::$options['contacts'][$id]['resource'] ) : '',
        esc_attr( $contact['placeholder'] )
    );
  }




  /**
   * Sanitizes the option's value
   * 
   * @since 0.0.1
   * 
   * @param $input multidimensional array of settings and contacts
   * @return array of sanitized option
   * 
   * @see MCB_Admin::sanitize_hex_color()
   * @see MCB_Admin::sanitize_rgba_color()
   * @see MCB_Admin::sanitize_float()
   */
  public static function sanitize_input( $input ) {

    $in_settings  = $input['settings'];
    $in_contacts  = $input['contacts'];
    $out_settings = array();
    $out_contacts = null;


    /* -------------------------------------------------------------------------- */
    /*                                  Settings                                  */
    /* -------------------------------------------------------------------------- */
    // workaround empty checkboxes
    $in_settings = array_replace(
        array_map( function( $field ) { if( 'checkbox' == $field['type'] ) { return 0; }}, self::$settings ),
        $in_settings
    );

    // all settings will be saved, at least with their default values
    $in_settings = array_replace(
        array_map( function( $field ) { return $field['default']; }, self::$settings ),
        $in_settings
    );


    foreach( $in_settings as $id => $value ) {
      switch( $id ) {

        // Icon Size
        // Bar Position
        case 'icon_size':
        case 'bar_position':
          if( in_array( $value, array_keys( self::$settings[$id]['options'] ))) {
            $out_settings[$id] = $value;
          } else {
            $out_settings[$id] = self::$settings[$id]['default'];
          }
          break;


        // Icon Color
        // Icon Border Color
        // Bar Color
        // Toggle Color
        case 'icon_color':
        case 'icon_border_color':
        case 'bar_color':
        case 'bar_toggle_color':
          $color = self::sanitize_hex_color( $value );

          if( ! $color ) {
            $color = self::sanitize_rgba_color( $value );
          }
          if( ! $color ) {
            $color = self::$settings[$id]['default'];
          }

          $out_settings[$id] = $color;
          break;


        // Show / Hide Bar
        // Bar Fixed Position
        // Bar Height
        // Bar Toggle
        // Icon Border
        // Icon Border Width
        // Dock Max Width
        case 'bar_is_active':
        case 'bar_is_fixed':
        case 'bar_height':
        case 'bar_is_toggle':
        case 'icon_is_border':
        case 'icon_border_width':
        case 'icon_dock_max_width':
          $out_settings[$id] = absint( $value );
          break;



        // Bar Opacity
        case 'bar_opacity':
          $float = self::sanitize_float( $value );
          $out_settings[$id] = $float ? $float : self::$settings[$id]['default'];
          break;
      }
    }


    /* -------------------------------------------------------------------------- */
    /*                                 Contacts                                   */
    /* -------------------------------------------------------------------------- */
    if( array_filter( $in_contacts )) {

      $out_contacts = array();
    
      foreach( self::$contacts as $id => $contact ) {
        if( isset( $in_contacts[$id] ) && ! empty( $in_contacts[$id] )) {

          $resource = '';
          switch( $contact['protocol'] ) {

            case 'tel':
            case 'skype':
              $resource = sanitize_text_field( $in_contacts[$id] );
              break;

            case 'mailto':
              $resource = sanitize_email( $in_contacts[$id] );
              $resource = is_email( $resource ) ? $resource : '';
              break;

            case 'http':
            case 'https':
              $resource = esc_url_raw( $in_contacts[$id] );
              break;

            default:
              $resource = sanitize_text_field( $in_contacts[$id] );
              break;
          }
          if( ! empty( $resource )) {
            $out_contacts[$id] = array( 'icon' => $contact['icon'], 'protocol' => sanitize_key( $contact['protocol'] ), 'resource' => $resource );
          }
        }  
      }
    }


    return array_filter( array_replace(
        self::$options,
        array(
            'settings' => $out_settings,
            'contacts' => $out_contacts
        )
    ));
  }




  /**
   * Sanitizes hexadecimal color value
   * 
   * @since 0.0.1
   * 
   * @param $color string color value
   * @return string sanitized hexadecmial color
   */
  static function sanitize_hex_color( $color ) {
      if( '' === $color ) {
        return null;
      }
   
      if( preg_match('/^#([A-Fa-f0-9]{3}){1,2}$/', $color )) {
        return $color;
      }
   
      return null;
  }




  /**
   * Sanitizes RGBA color value
   * 
   * @since 0.0.1
   * 
   * @param $color string color value
   * @return string sanitized RGBA color
   */
  static function sanitize_rgba_color( $color ) {
    if( '' === $color ) {
      return null;
    }

    if( preg_match('/^rgba\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3}),\s*(\d*(?:\.\d+)?)\)$/', $color )) {
      return $color;
    }
 
    return null;
  }




  /**
   * Sanitizes float value
   * 
   * @since 0.0.1
   * 
   * @param $opacity string opacity value
   * @return string sanitized opacity
   */
  static function sanitize_float( $opacity ) {
    if( '' === $opacity ) {
      return null;
    }

    if( preg_match('/0|1|0\.\d\d/', $opacity )) {
      return $opacity;
    }

    return null;
  }




  /**
   * Loads CSS styles and JavaScript scripts for plugin option page
   * 
   * @since 0.0.1
   * 
   * @param $hook string a specific admin page
   * 
   * @link https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
   */
  public static function admin_enqueue_scripts( $hook ) {

    if( 'settings_page_mcb' == $hook ) {

      wp_enqueue_style(  'wp-color-picker' );
      wp_enqueue_script( 'wp-color-picker' );


      wp_enqueue_style( 'cs-wp-color-picker',
          plugins_url( 'css/cs-wp-color-picker.min.css', MCB_PLUGIN_FILE ),
          array( 'wp-color-picker' ),
          '1.0.0',
          'all'
      );
      wp_enqueue_script( 'cs-wp-color-picker-js',
          plugins_url( 'js/cs-wp-color-picker.min.js', MCB_PLUGIN_FILE ),
          array( 'wp-color-picker' ),
          '1.0.0',
          false
      );


      wp_enqueue_style( 'fa',
          plugins_url( 'fonts/font-awesome/css/font-awesome.min.css', MCB_PLUGIN_FILE ),
          false,
          '4.3.0',
          'all'
      );


      wp_enqueue_style( 'mcb-admin',
          plugins_url( 'css/mcb-admin.css', MCB_PLUGIN_FILE ),
          array( 'cs-wp-color-picker', 'fa' ),
          '1.0.0',
          'all'
      );
      wp_enqueue_script( 'mcb-admin-js',
          plugins_url( 'js/mcb-admin.js', MCB_PLUGIN_FILE ),
          array( 'jquery', 'jquery-ui-slider', 'cs-wp-color-picker-js' ),
          '1.0.0',
          false
      );
    }
  }




  /**
   * Generates frontend CSS styles
   * 
   * @since 0.0.1
   * 
   * @param $new_value 
   * @param $old_value 
   * @return array of option
   * 
   * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/pre_update_option_%28option_name%29
   */
  public static function pre_update_option( $new_value, $old_value ) {

    $settings = $new_value['settings'];

    $styles = '';

    $position = 'fixed';
    if( ! $settings['bar_is_fixed'] ) {
      if( 'top' == $settings['bar_position'] ) {
        $position = 'absolute';
      } else {
        $position = 'relative';
      }
    }

    // #mcb-wrap
    $styles .= '#mcb-wrap{';
      $styles .= 'display:block;';
      $styles .= 'opacity:' . $settings['bar_opacity'] . ';';
      $styles .= 'position:' . $position . ';';
      $styles .=  'top' == $settings['bar_position'] ? 'top:0;' : 'bottom:0;';
      $styles .= 'text-align:center;';
      $styles .= 'width:100%;';
      $styles .= 'z-index:99999;';
    $styles .= '}';



    // #mcb-bar
    $styles .= '#mcb-bar{';
      $styles .= 'background-color:' . $settings['bar_color'] . ';';
      $styles .= 'height:' . $settings['bar_height'] . 'px;';
      $styles .= 'width:inherit;';
    $styles .= '}';


    // #mcb-bar ul
    $styles .= '#mcb-bar ul{';
      $styles .= 'display:table;';
      $styles .= 'height:inherit;';
      $styles .= 'list-style-type:none;';
      $styles .= 'margin:0 auto;';
      $styles .= 'max-width:' . $settings['icon_dock_max_width'] . '%;';
      $styles .= 'table-layout:fixed;';
      $styles .= 'width:inherit;';
    $styles .= '}';


    // #mcb-bar ul li
    $styles .= '#mcb-bar ul li{';
      $styles .= 'display:table-cell;';
      $styles .= 'vertical-align:middle;';
      $styles .= $settings['icon_is_border'] ? 'border-width:' . $settings['icon_border_width'] . 'px;' .
                'border-style:solid solid solid none;' .
                'border-color:' . $settings['icon_border_color'] . ';' : '';
    $styles .= '}';


    // #mcb-bar ul li:first-child
    $styles .= $settings['icon_is_border'] ? '#mcb-bar ul li:first-child{ border-left:' . $settings['icon_border_width'] . 'px solid ' . $settings['icon_border_color'] . ';}' : '';


    // #mcb-bar a
    $styles .= '#mcb-bar a{';
      $styles .= 'display:block;';
      $styles .= 'margin:0 auto;';
      $styles .= 'width:inherit;';
    $styles .= '}';


    // #mcb-bar .fa
    $styles .= '#mcb-bar .fa{';
      $styles .= 'color:'. $settings['icon_color'] . ';';
    $styles .= '}';


    if( $settings['bar_is_toggle'] ) {

      // #mcb-bar
      $styles .= '#mcb-bar{';
        $styles .= 'overflow:hidden;';
        $styles .= '-webkit-transition:height 1s ease;';
        $styles .= '-moz-transition:height 1s ease;';
        $styles .= '-o-transition:height 1s ease;';
        $styles .= 'transition:height 1s ease;';
      $styles .= '}';


      // #mcb-toggle-checkbox
      $styles .= '#mcb-toggle-checkbox{';
        $styles .= 'display:none;';
      $styles .= '}';


      // #mcb-toggle-checkbox:checked ~ #mcb-bar
      $styles .= '#mcb-toggle-checkbox:checked ~ #mcb-bar{';
        $styles .= 'height:0;';
      $styles .= '}';


      // .mcb-toggle
      $styles .= '#mcb-toggle{';
        $styles .= 'cursor:pointer;';
        $styles .= 'display:inline-block;';
        $styles .= 'margin:0 auto;';
        $styles .= 'padding:1.2em 2.8em;';
        $styles .= 'position:relative;';
        $styles .= 'vertical-align:bottom;';
      $styles .= '}';


      // .mcb-toggle:before
      $styles .= '#mcb-toggle:before{';
        $styles .= 'background-color:' . $settings['bar_toggle_color'] . ';';
        $styles .= 'top' == $settings['bar_position'] ? 'border-radius:0 0 10px 10px;' : 'border-radius:10px 10px 0 0;';
        $styles .= 'content:"";';
        $styles .= 'position:absolute;';
        $styles .= 'top' == $settings['bar_position'] ? 'transform: perspective(5px) rotateX(-2deg);' : 'transform:perspective(5px) rotateX(2deg);';
        $styles .= 'top:0;right:0;bottom:0;left:0;';
        $styles .= 'z-index:-1;';
      $styles .= '}';

    }

    return array_replace(
        $new_value,
        array( 'styles' => $styles )
    );
  }




  /**
   * Outputs plugin's colophon
   * 
   * @since 0.0.1
   * 
   * @link https://codex.wordpress.org/Plugin_API/Action_Reference/admin_footer
   */
  public static function admin_footer() {

    if( 'settings_page_mcb' == get_current_screen()->base ) {

      $site = sprintf(
          '<a href="%s" target="_blank"><i class="fa fa-lg fa-heart" title="%s"></i></a>',
          esc_url_raw( self::$plugin_data['PluginURI'], array( 'https' )),
          __( 'Official Site', 'mcb' )
      );

      $github = sprintf(
          '<a href="%s" target="_blank"><i class="fa fa-lg fa-github" title="%s"></i></a>',
          esc_url_raw( self::$plugin_data['AuthorURI'], array( 'https' )),
          __( 'Fork me on GitHub', 'mcb' )
      );

      $wordpress = sprintf(
          '<a href="%s" target="_blank"><i class="fa fa-lg fa-wordpress" title="%s"></i></a>',
          esc_url_raw( 'https://wordpress.org/support/view/plugin-reviews/mobile-contact-bar', array( 'https' )),
          __( 'Rate this plugin', 'mcb' )
      );

      $paypal = sprintf(
          '<a href="%s" target="_blank"><img src="%s" title="%s" alt="%s"/></a>',
          esc_url_raw( 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YXJAZ7Q5EJFUA', array( 'https' )),
          plugins_url( 'css/images/btn_donate_SM.gif', MCB_PLUGIN_FILE ),
          __( 'Donate via PayPal', 'mcb' ),
          __( 'Donation Button', 'mcb' )
      );

      ?>
      <div id="mcb-footer" role="content-info">
        <span class="mcb-footer-author">2015 &copy; Anna Bansaghi</span>
        <span class="mcb-footer-links"><?php echo $site, $github, $wordpress, $paypal; ?></span>
      </div>
      <?php
    }
  }

}

