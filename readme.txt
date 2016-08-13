=== oik-zip ===
Contributors: bobbingwide, vsgloik
Donate link: http://www.oik-plugins.com/oik/oik-donate/
Tags: zip, 7-zip, plugins, package, oik-batch, CLI
Requires at least: 4.3
Tested up to: 4.6-RC2
Stable tag: 0.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

oik-zip.php packages the source files for a WordPress plugin into a .zip file ready for release to the general public.

The packaging process ensures up to date versions are released

* update the theme's style.css
* update the theme's readme.txt
* build a new version of the README.md file for GitHub

What it does not do:

* Create minimised versions of .css and .js files
* run Unit Tests
* perform translation 
* update the API reference


== Installation ==
1. Upload the contents of the oik-tip plugin to the `/wp-content/plugins/oik-tip' directory
1. Create a batch file called zip.bat to invoke the oik-zip routine, through oikwp.php from oik-batch

```
php c:\apache\htdocs\wordpress\wp-content\plugins\oik-batch\oik-wp.php c:\apache\htdocs\wordpress\wp-content\plugins\oik-zip\oik-zip.php %*

```

== Frequently Asked Questions ==

= How does it work? =

Read the code

= What are the dependencies? = 

* 7-ZIP
* an editor
* t2m - convert a readme.txt file to README.md ( github.com/bobbingwide/txt2md )
* oik-batch ( github.com/bobbingwide/oik-batch )

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
= 0.0.2 =
Attempts to ensure assets are present. Tested with WordPress 4.6-RC2

= 0.0.1 =
Tested with WordPress 4.5-RC1

= 0.0.0 =
Finally put under version control. 
First version of the plugin, available from GitHub and oik-plugins.

== Changelog == 
= 0.0.2 = 
* Added: Logic to copy assets files - used for GitHub
* Changed: Assets files are not included in the .zip file

= 0.0.1 = 
* Fixed: Copies oik-activation.php from oik/libs rather than oik/admin [github bobbingwide oik-zip issues 2]
* Tested: With WordPress 4.5-RC1

= 0.0.0 =
* Added: First version on GitHub

