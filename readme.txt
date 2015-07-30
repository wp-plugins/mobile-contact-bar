=== Mobile Contact Bar ===
Contributors: anna.bansaghi
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YXJAZ7Q5EJFUA
Tags: communication, social media, contact, mobile, icon, mobile action
Requires at least: 3.5.2
Tested up to: 4.2.3
Stable tag: 0.0.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

Allow your visitors to contact you via phone, email, or Social Media


== Description ==

Mobile Contact Bar allows your visitors to contact you directly via phone, email, Social Media, or any custom links.

Display settings for bar, icons and contacts are editable under the WordPress Admin &rarr; Settings &rarr; Mobile Contact Bar submenu.

**Website**

https://bansaghi.github.io/mobilecontactbar/

**Please Vote and Enjoy**

Your votes really make a difference! Thanks.



== Installation ==

1. Upload `mobile-contact-bar` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Click on the new submenu item 'Mobile Contact Bar' under 'Settings' menu, and customize your contact bar
4. Check your contact bar representation on frontend pages



== Frequently Asked Questions ==

= I have activated the plugin, and there is no bar on my frontend pages =

You should fill in at least one contact information on the MCB settings page.


= OK, I have filled in some contact information, still nothing on my screen =

The contact bar will appear on devices having width less than 960 pixel.


= I want to display contacts which are not listed on the MCB settings page, e.g. SlideShare, Reddit or Steam. Can I do that? =

Yes, you can. The `mcb_admin_update_contacts` filter allows you to modify the contact list. You can add a new item to that list, and it will appear at the bottom of the MCB settings page. For icon names check the [Font Awesome Icons](http://fontawesome.io/icons/). E.g. include the following code snippet to your `functions.php` file:

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

= 0.0.1 =
* Initial release


== Upgrade Notice ==

= 0.0.1 =
* Initial release
