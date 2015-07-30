<?php

/**
 * Mobile Contacts
 * 
 * @since 0.0.1
 * 
 * @package Mobile_Contact_Bar
 * @author Anna Bansaghi <anna.bansaghi@mamikon.net>
 * @license GPL-3.0
 * @link https://bansaghi.github.io/mobilecontactbar/
 * @copyright Anna Bansaghi
 */
final class MCB_Contacts {



  /**
   * Defines the list of mobile contacts
   * 
   * @since 0.0.1
   * 
   * @return array of contacts
   */
  public static function contacts() {

    return apply_filters( 'mcb_admin_update_contacts', array(

        'phone' => array(
            'icon'        => 'phone',
            'title'       => __( 'Phone Number', 'mcb' ),
            'protocol'    => 'tel',
            'placeholder' => '+15417543010',
        ),
        'email' => array(
            'icon'        => 'envelope',
            'title'       => __( 'Email Address', 'mcb' ),
            'protocol'    => 'mailto',
            'placeholder' => 'username@example.com',
        ),
        'skype' => array(
            'icon'        => 'skype',
            'title'       => 'Skype',
            'protocol'    => 'skype',
            'placeholder' => 'skypename',
        ),
        'address' => array(
            'icon'        => 'map-marker',
            'title'       => 'Google Maps',
            'protocol'    => 'https',
            'placeholder' => 'https://google.com/maps/place/Dacre+St,+London+UK/',
        ),
        'facebook' => array(
            'icon'        => 'facebook-official',
            'title'       => 'Facebook',
            'protocol'    => 'https',
            'placeholder' => 'https://www.facebook.com/username',
        ),
        'twitter' => array(
            'icon'        => 'twitter',
            'title'       => 'Twitter',
            'protocol'    => 'https',
            'placeholder' => 'https://twitter.com/username',
        ),
        'googleplus' => array(
            'icon'        => 'google-plus',
            'title'       => 'Google+',
            'protocol'    => 'https',
            'placeholder' => 'https://plus.google.com/username',
        ),
        'youtube' => array(
            'icon'        => 'youtube-play',
            'title'       => 'YouTube',
            'protocol'    => 'https',
            'placeholder' => 'https://www.youtube.com/user/username',
        ),
        'pinterest' => array(
            'icon'        => 'pinterest-p',
            'title'       => 'Pinterest',
            'protocol'    => 'http',
            'placeholder' => 'http://pinterest.com/username',
        ),
        'tumblr' => array(
            'icon'        => 'tumblr',
            'title'       => 'Tumblr',
            'protocol'    => 'http',
            'placeholder' => 'http://username.tumblr.com',
        ),
        'linkedin' => array(
            'icon'        => 'linkedin',
            'title'       => 'LinkedIn',
            'protocol'    => 'http',
            'placeholder' => 'http://www.linkedin.com/in/username',
        ),
        'vimeo' => array(
            'icon'        => 'vimeo-square',
            'title'       => 'Vimeo',
            'protocol'    => 'https',
            'placeholder' => 'https://vimeo.com/username',
        ),
        'flickr' => array(
            'icon'        => 'flickr',
            'title'       => 'Flickr',
            'protocol'    => 'http',
            'placeholder' => 'http://www.flickr.com/people/username',
        ),
    ));
  }

}
