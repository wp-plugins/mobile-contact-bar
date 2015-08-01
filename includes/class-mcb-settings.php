<?php

defined( 'ABSPATH' ) or exit;

/**
 * Bar and Icon settings
 * 
 * @since 0.0.1
 * 
 * @package Mobile_Contact_Bar
 * @author Anna Bansaghi <anna.bansaghi@mamikon.net>
 * @license GPL-3.0
 * @link https://github.com/bansaghi/mobile-contact-bar/
 * @copyright Anna Bansaghi
 */
final class MCB_Settings {


  /**
   * Defines the array of Setting Fields
   * 
   * @since 0.0.1
   * 
   * @return array Associative array of MCB setting fields, divided into two sections (Bar and Icon)
   */
  public static function settings() {

    return apply_filters( 'mcb_admin_update_settings', array(

        // Section Bar
        'bar_is_active' => array(
            'section' => 'bar',
            'type'    => 'checkbox',
            'default' => 1,
            'title'   => __( 'Show / Hide Bar', 'mcb' ),
            'label'   => __( 'Show contact bar', 'mcb' ),
            'desc'    => __( 'The bar will be shown if at least one contact information is set.', 'mcb' ),
        ),
        'bar_max_screen_width' => array(
            'section' => 'bar',
            'type'    => 'number',
            'default' => 960,
            'title'   => __( 'Max Screen Width', 'mcb' ),
            'postfix' => 'px',
            'min'     => 200,
            'desc'    => __( 'The bar will be shown on those screens where the width is below of this value.', 'mcb' ),
        ),
        'bar_color' => array(
            'section' => 'bar',
            'type'    => 'color-picker',
            'default' => '#666666',
            'title'   => __( 'Bar Color', 'mcb' ),
        ),
        'bar_opacity' => array(
            'section' => 'bar',
            'type'    => 'slider',
            'default' => 1,
            'title'   => __( 'Bar Opacity', 'mcb' ),
        ),
        'bar_position' => array(
            'section' => 'bar',
            'type'    => 'select',
            'default' => 'bottom',
            'title'   => __( 'Bar Position', 'mcb' ),
            'options' => array( 'bottom' => __( 'at the bottom', 'mcb' ), 'top' => __( 'at the top', 'mcb' )),
        ),
        'bar_is_fixed' => array(
            'section' => 'bar',
            'type'    => 'checkbox',
            'default' => 0,
            'title'   => __( 'Fixed Position', 'mcb' ),
            'label'   => __( 'Fix bar at its position', 'mcb' ),
        ),
        'bar_height' => array(
            'section' => 'bar',
            'type'    => 'number',
            'default' => 60,
            'title'   => __( 'Bar Height', 'mcb' ),
            'postfix' => 'px',
            'min'     => 0,
        ),
        'bar_is_toggle' => array(
            'section' => 'bar',
            'type'    => 'checkbox',
            'default' => 0,
            'title'   => __( 'Bar Toggle', 'mcb' ),
            'label'   => __( 'Activate toggle', 'mcb' ),
        ),
        'bar_toggle_color' => array(
            'section' => 'bar',
            'type'    => 'color-picker',
            'default' => '#333333',
            'title'   => '<em>' . __( 'Toggle Color', 'mcb' ) . '</em>',
        ),

        // Section Icon
        'icon_size' => array(
            'section' => 'icon',
            'type'    => 'select',
            'default' => '2x',
            'title'   => __( 'Icon Size', 'mcb' ),
            'options' => array( '1x' => '1x', 'lg' => '1.33x', '2x' => '2x', '3x' => '3x', '4x' => '4x', '5x' => '5x' ),
        ),
        'icon_color' => array(
            'section' => 'icon',
            'type'    => 'color-picker',
            'default' => '#ffffff',
            'title'   => __( 'Icon Color', 'mcb' ),
        ),
        'icon_is_border' => array(
            'section' => 'icon',
            'type'    => 'checkbox',
            'default' => 0,
            'title'   => __( 'Icon Border', 'mcb' ),
            'label'   => __( 'Draw border around icons', 'mcb' ),
        ),
        'icon_border_color' => array(
            'section' => 'icon',
            'type'    => 'color-picker',
            'default' => '#eeeeee',
            'title'   => '<em>' . __( 'Border Color', 'mcb' ) . '</em>',
        ),
        'icon_border_width' => array(
            'section' => 'icon',
            'type'    => 'number',
            'default' => 1,
            'title'   => '<em>' . __( 'Border Width', 'mcb' ) . '</em>',
            'postfix' => 'px',
            'min'     => 1,
        ),
        'icon_max_panel_width' => array(
            'section' => 'icon',
            'type'    => 'number',
            'default' => 100,
            'title'   => __( 'Max Icon Panel Width', 'mcb' ),
            'postfix' => '%',
            'min'     => 10,
            'max'     => 100,
            'desc'    => __( 'The icon panel will be squeezed at this percent.', 'mcb' ),
        ),
    ));
  }

}
