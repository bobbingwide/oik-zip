=== oik-zip ===
Contributors: bobbingwide
Donate link: http://www.oik-plugins.com/oik/oik-donate/
Tags: shortcodes, smart, lazy
Requires at least: 4.3
Tested up to: 4.4.1
Stable tag: 0.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

oik-zip.php packages the source files for a plugin into a .zip file ready for release to the general public.

The packaging process ensures up to date versions are released

* update the root plugin file
* update the plugin's readme.txt
* build a new version of the README.md file for GitHub
* update language files, if applicable
* reconcile shared library files
* update the "oik-activation" logic

What it does not do:
* Create minimised versions of .css and .js files
* run Unit Tests
* perform translation
* update the API reference


== Installation ==
1. Upload the contents of the oik-zip plugin to the `/wp-content/plugins/oik-zip' directory
1. Create a batch file called zip.bat to invoke the oik-zip routine, through oikwp.php from oik-batch

```
php c:\apache\htdocs\wordpress\wp-content\plugins\oik-batch\oik-wp.php c:\apache\htdocs\wordpress\wp-content\plugins\oik-zip\oik-zip.php %*
```

== Frequently Asked Questions ==

= How does it work? =

Read the code

= What are the dependencies? = 

* oik-batch
* 7-ZIP 

= Does it use Composer? =

No. But it may be enabled for use with Composer

= Why not WP-CLI? = 

Now that I need most of WordPress to do all the things I'm working towards using WP-CLI
primarily to handle command line parameters.

= Is it integrated with Git? = 

It will be, when I've made more progress with the oik-git shared library.

= Is it integrated with SVN? =

No. Updating the SVN version is currently a manual process performed after creating the .zip and updating GitHub.



== Screenshots ==
1. oik-zip in action

== Upgrade Notice ==
= 0.0.0 =
Finally put under version control. 
First version of the plugin, available from GitHub and oik-plugins.

== Changelog == 
= 0.0.0 =
* Added: First version GitHub

