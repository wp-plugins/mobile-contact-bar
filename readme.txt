=== Mobile Contact Bar ===
Contributors: anna.bansaghi
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YXJAZ7Q5EJFUA
Tags: communication, social media, contact, mobile, icon, mobile action
Requires at least: 3.5.2
Tested up to: 4.2.3
Stable tag: 1.0.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

Allow your visitors to contact you via phone, email, or Social Media


== Description ==

Mobile Contact Bar allows your visitors to contact you directly via phone, email, Social Media, or any custom links.

Display settings for bar, icons and contacts are editable under the *Settings &rarr; Mobile Contact Bar* menu on WordPress Admin.

The contact bar will appear if at least one contact is set, and on those screens where the width is below of the Maximum Screen Width setting.

**Website**

[bansaghi.github.io/mobilecontactbar](https://bansaghi.github.io/mobilecontactbar/)

**Please Vote and Enjoy**

Your votes really make a difference! Thanks.



== Installation ==

1. Upload `mobile-contact-bar` to the `/wp-content/plugins/` directory
2. Activate the plugin through the *Plugins* menu in WordPress
3. Click on the new submenu item *Mobile Contact Bar* under the *Settings* menu, and customize your contact bar
4. Check your contact bar appearance on frontend pages



== Frequently Asked Questions ==

= I want to display contacts which are not listed on the MCB settings page, e.g. SlideShare, Reddit or Steam. Can I do that? =

Yes, you can. The `mcb_admin_update_contacts` filter allows you to modify the contact list. You can add a new item to that list, and it will appear at the bottom of the MCB settings page. For icon names check the [Font Awesome Icons](http://fontawesome.io/icons/).

E.g. include the following code snippet to your `functions.php` file:

    function my_admin_update_contacts( $contacts ) {
        $contacts['slideshare'] = array(
            'icon'        => 'slideshare',   // Font Awesome Icon name
            'title'       => 'SlideShare',
            'protocol'    => 'http',
            'placeholder' => 'http://www.slideshare.net/username'
        );
        return $contacts;
    }
    add_filter( 'mcb_admin_update_contacts', 'my_admin_update_contacts' );


== Screenshots ==

1. WordPress Admin &rarr; Settings &rarr; Mobile Contact Bar


== Changelog ==

= 1.0.0 =
* Official release

= 0.0.2 =
* Fixed Add options issue during network activation

= 0.0.1 =
* Initial release


== Upgrade Notice ==

= 1.0.0 =
* Official release

= 0.0.1 =
* Initial release
