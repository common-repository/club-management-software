=== Wordpress Membership SwiftCloud.io ===
Contributors: SwiftCloud
Donate link: http://SwiftCloud.io
Tags: membership, login, welcome popup
Requires at least: 4.5
Tested up to: 4.8
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily make any wordpress membership site integrated with http://SwiftCloud.io (free or paid accounts).

== Description ==

This plugin allows you to make any wordpress site into a membership site, using http://SwiftCloud.io

It uses Wordpress' native user profiles for the membership.

The basic operation requires a single line of php to be copied into your theme, which will split your main
website navigation menu into 2 separate navs - logged in, and logged out.

To set up the most basic membership, we recommend these 5 steps:

1. First, modify your theme to display [login_logout_nav] where your main navigation will display. You can CSS it as usual.
1. Next, go to Appearance >> Menus and create 2 menus: Main Menu Logged OUT and Main Menu Logged IN
1. Next, go to Appearance >> Menus >> Manage Locations and ensure the Logged In Menu and Logged Out menu are defined.
1. Next, create the following pages: Home Logged IN, Home Logged OUT, Signup, Expired, Banned
1. Finally, go to SwiftBooks Subscription (bottom of sidebar) and click Subscription Management and define the settings accordingly.

You should then be set up. You may wish to allow front-end signups via your theme, or you can use the regular wordpress functions.

Additional Features (Optional)

*   Optional Billing via SwiftBooks. If defined, the system will query SwiftBooks accounting via API each midnight to get an updated list of expired / paid accounts, and store locally.
*   Optionally, you can add all new incoming users / signups to a Swift Marketing list / autoresponder and thus drip content out, including timed to membership.

Need something else? Contact us at Swift Marketing.

This plugin is fully functional without having to buy anything from SwiftCloud assuming your membership is free, or paid via some other method.

== Installation ==

You probably know the routine by now, but here's more details.

First, get the plugin installed.

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('plugin_name_hook'); ?>` in your templates

To set up the most basic membership, we recommend these 5 steps:

1. First, modify your theme to display [login_logout_nav] where your main navigation will display. You can CSS it as usual.
1. Next, go to Appearance >> Menus and create 2 menus: Main Menu Logged OUT and Main Menu Logged IN
1. Next, go to Appearance >> Menus >> Manage Locations and ensure the Logged In Menu and Logged Out menu are defined.
1. Next, create the following pages: Home Logged IN, Home Logged OUT, Signup, Expired, Banned
1. Finally, go to SwiftBooks Subscription (bottom of sidebar) and click Subscription Management and define the settings accordingly.

== Frequently Asked Questions ==

= Do I have to buy anything? Is this 100% free? =

Yes, it's 100% free if you are collecting money some other system or your membership is free. If you want more advanced features, i.e. autoresponder to new accounts,
billing, multiple membership levels with variable access, affiliate controls and sub-accounts, pseudo-currency, or something else, it may require a paid SwiftCloud.io
account, but check it out - it's pretty affordable, and we're high level coders that can help you create a real e-business.

= What about foo bar? =

See https://en.wikipedia.org/wiki/Foobar - it's hacker / coder slang for a placeholder.

== Screenshots ==


== Changelog ==

= 1.0 =
- Logged in menu, Logged out menu.
- Auto generated pages for Sigup, Login , Lost Password, Reset Password, Change Password, Home Logged IN and Home Logged OUT.
- Shortcode for Sigup, Login , Lost Password, Reset Password and Change Password.
- Email template management
- Welcome wizard popup.