=== Gallery ===
Contributors: bestwebsoft
Donate link: https://www.2checkout.com/checkout/purchase?sid=1430388&quantity=10&product_id=13
Tags: gallery, image, gallery image, album, foto, fotoalbum, website gallery, multiple pictures, pictures, photo, photoalbum, photogallery
Requires at least: 3.0
Tested up to: 3.4.2
Stable tag: 3.7

This plugin allows you to implement gallery page into your web site.

== Description ==

This plugin makes it possible to implement as many galleries as you want into your website. You can add multiple pictures and description for each gallery, show them all at one page, view each one separately. Moreover, it's possible to upload HQ pictures.

<a href="http://wordpress.org/extend/plugins/gallery-plugin/faq/" target="_blank">FAQ</a>
<a href="http://bestwebsoft.com/plugin/gallery-plugin/" target="_blank">Support</a>

= Features =

* Actions: Create any quantity of the albums in gallery.
* Description: Add description to each album.
* Actions: Possibility to set featured image as cover of the album.
* Actions: Possibility to load any number of photos to each album in the gallery.
* Actions: Possibility to add Single Gallery to your page or post with shortcode.
* Actions: Option to make the sorting settings of attachments in the admin panel.
* Caption: Add caption to each photo in the album.
* Display: You can select dimensions of the thumbnails for the cover of the album as well as for photos in the album.
* Display: A possibility to select a number of the photos for the separate page of album of the gallery which will be placed in one line.
* Slideshow: User can review all photos in album in full size and in slideshow.

= Translation =

* Brazilian Portuguese (pt_BR) (thanks to DJIO, www.djio.com.br)
* Czech (cs_CZ) (thanks to Josef Sukdol)
* Dutch (nl_NL) (thanks to <a href="ronald@hostingu.nl">HostingU, Ronald Verheul</a>)
* French (fr_FR) (thanks to Didier)
* Georgian (ka_GE) (thanks to Vako Patashuri)
* German (de_DE) (thanks to Thomas Bludau)
* Hebrew (he_IL) (thanks to Sagive SEO)
* Hungarian (hu_HU) (thanks to Mészöly Gábor) 
* Italian (it_IT) (thanks to Stefano Ferruggiara)
* Lituanian (lt_LT) (thanks to Naglis Jonaitis)
* Polish (pl_PL) (thanks to Janusz Janczy, Bezcennyczas.pl)
* Russian (ru_RU)
* Spanish (es) (thanks to Victor Garcia)
* Ukrainian (uk_UA)(thanks to Ted Mosby)

If you create your own language pack or update an existing one, you can send <a href="http://codex.wordpress.org/Translating_WordPress" target="_blank">the text in PO and MO files</a> for <a href="http://bestwebsoft.com/" target="_blank">BWS</a> and we'll add it to the plugin. You can download the latest version of the program for work with PO and MO files  <a href="http://www.poedit.net/download.php" target="_blank">Poedit</a>.

= Technical support =

Dear users, if you have any questions or propositions regarding our plugins (current options, new options, current issues) please feel free to contact us. Please note that we accept requests in English only. All messages on another languages wouldn't be accepted. 

Also, emails which are reporting about plugin's bugs are accepted for investigation and fixing. Your request must contain URL of the website, issues description and WordPress admin panel access. Plugin customization based on your Wordpress theme is a paid service (standard price is $10, but it could be higer and depends on the complexity of requested changes). We will analize existing issue and make necessary changes after 100% pre-payment.All these paid changes and modifications could be included to the next version of plugin and will be shared for all users like an integral part of the plugin. Free fixing services will be provided for user who send translation on their native language (this should be a new translation of a certain plugin, and you can check available translations on the official plugin page).

== Installation ==

1. Upload `Gallery` folder to the directory `/wp-content/plugins/`.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Please check if you have the `gallery-template.php` template file as well as `gallery-single-template.php` template file in your templates directory. If you are not able to find these files, then just copy it from `/wp-content/plugins/gallery/template/` directory to your templates directory.

== Frequently Asked Questions ==

= I cannot view my Gallery page =

1. First of all, you need to create your first Gallery page and choose 'Gallery' from the list of available templates (which will be used for displaying our gallery).
2. If you cannot find 'Gallery' in the list of available templates, then just copy it from `/wp-content/plugins/gallery-plugin/template/` directory to your templates directory.

= How to use plugin? =

1. Choose 'Add New' from the 'Galleries' menu and fill out your page.
2. Upload pictures by using an uploader in the bottom of the page. 
3. Save the page.

= How to add an image? =

- Choose the necessary gallery from the list on the Galleries page in admin section (or create a new gallery - choose 'Add New' from the 'Galleries' menu). 
- Use the option 'Upload a file' available in the uploader, choose the necessary pictures and click 'Open'
- The files uploading process will start.
- Once all pictures are uploaded, please save the page.
- If you see the message 'Please enable JavaScript to use the file uploader.', you should enable JavaScript in your browser.

= How to add many image? =

The multiple files upload is supported by all modern browsers except Internet Explorer. 

= I'm getting the following error: Fatal error: Call to undefined function get_post_thumbnail_id(). What am I to do? ? =

This error says that your theme doesn't support thumbnail option, in order to add this option please find 'functions.php' file in your theme and add the following strings to this file:

`add_action( 'after_setup_theme', 'theme_setup' );

function theme_setup() {
    add_theme_support( 'post-thumbnails' );
}`

After that your theme will support thumbnail option and the error won't display again.

= How to change image order on single gallery page? =

1. Please open the menu "Galleries" and choose random gallery from the list. You should be redirected to the gallery editing page. 
Please use drag and drop function to change the order of the output of images and do not forget to save post.
Please do not forget to select `Attachments order by` -> `attachments order` in the settings of the plugin (page http://your_domain/wp-admin/admin.php?page=gallery-plugin.php) 

2. Please open the menu "Galleries" and choose random gallery from the list. You should be redirected to the gallery editing page. 
There will be one or several media upload icons between the title and content adding blocks. Please choose any icon. 
After that you'll see a popup window with three or four tabs. 
Choose gallery tab and there'll be displayed attached files which are related to this gallery. 
You can change their order using drag'n'drop method. 
Just setup a necessary order and click 'Save' button.

== Screenshots ==

1. Gallery Admin page.
2. Galleries albums page on frontend.
3. Gallery Options page in admin panel.
4. Single gallery page.
5. PrettyPhoto pop-up window with images from the album.

== Changelog ==

= V3.7 - 23.10.2012 =
* NEW : Added link url field - clicking on image open the link in new window.

= V3.6 - 03.10.2012 =
* NEW : Added function to display 'Download High resolution image' link in lightbox on gallery page
* NEW : Added setting for 'Download High resolution image' link

= V3.5 - 27.07.2012 =
* NEW : Lituanian language file is added to the plugin.
* NEW : Added drag and drop function to change the order of the output of images
* NEW : Added a shortcode for displaying short gallery type (like [print_gllr id=211 display=short])

= V3.4 - 24.07.2012 =
* Bugfix : Cross Site Request Forgery bug was fixed. 

= V3.3 - 12.07.2012 =
* NEW : Brazilian Portuguese and Hebrew language files are added to the plugin.
* Update : We updated Italian language file.
* Update : We updated all functionality for wordpress 3.4.1.

= V3.2 - 27.06.2012 =
* Update : We updated all functionality for wordpress 3.4.

= V3.1.2 - 15.06.2012 =
* Bugfix : The bug with gallery uploader (undefined x undefined) was fixed.

= V3.1.1 - 13.06.2012 =
* Bugfix : The bug with gallery uploader was fixed.

= V3.1 - 11.06.2012 =
* New : Metabox with shortcode has been added on Edit Gallery Page to add it on your page or post.
* Bugfix : The bug with gallery shortcode was fixed.

= V3.06 - 01.06.2012 =
* Bugfix : The bug with gallery appears above text content was fixed.

= V3.05 - 25.05.2012 =
* NEW : Added shortcode for display Single Gallery on your page or post.
* NEW : Added attachment order.
* NEW : Added 'Return to all albums' link for Single Gallery page.
* NEW : Spanish language file are added to the plugin.

= V3.04 - 27.04.2012 =
* NEW : Added slideshow for lightbox on single gallery page.

= V3.03 - 19.04.2012 =
* Bugfix : The bug related with the upload of the photos on the multisite network was fixed.

= V3.02 - 12.04.2012 =
* Bugfix : The bug related with the display of the photo on the single page of the gallery was fixed.

= V3.01 - 12.04.2012 =
* NEW : Czech, Hungarian and German language files are added to the plugin.
* NEW : Possibility to set featured image as cover of the album.
* Change: Replace prettyPhoto library to fancybox library.
* Change: Code that is used to display a lightbox for images in `gallery-single-template.php` template file is changed.

= V2.12 - 27.03.2012 =
* NEW : Italian language files are added to the plugin.

= V2.11 - 26.03.2012 =
* Bugfix : The bug related with the indication of the menu item on the single page of the gallery was fixed.

= V2.10 - 20.03.2012 =
* NEW : Polish language files are added to the plugin.

= V2.09 - 12.03.2012 =
* Changed : BWS plugins section. 

= V2.08 - 24.02.2012 =
* Change : Code that is used to connect styles and scripts is added to the plugin for correct SSL verification.
* Bugfix : The bug with style for image block on admin page was fixed.

= V2.07 - 17.02.2012 =
* NEW : Ukrainian language files are added to the plugin.
* Bugfix : Problem with copying files gallery-single-template.php to theme was fixed.

= V2.06 - 14.02.2012 =
* NEW : Dutch language files are added to the plugin.

= V2.05 - 18.01.2012 =
* NEW : A link to the plugin's settings page is added.
* Change : Revised Georgian language files are added to the plugin.

= V2.04 - 13.01.2012 =
* NEW : French language files are added to the plugin.

= V2.03 - 12.01.2012 =
* Bugfix : Position to display images on a Gallery single page was fixed.

= V2.02 - 11.01.2012 =
* NEW : Georgian language files are added to the plugin.

= V2.01 - 03.01.2012 =
* NEW : Adding of the caption to each photo in the album.
* NEW : A possibility to select the dimensions of the thumbnails for the cover of the album and for photos in album is added.
* NEW : A possibility to select a number of the photos for a separate page of the album in the gallery which will be placed in one line is added.
* Change : PrettyPhoto library was updated up to version 3.1.3.
* Bugfix : Button 'Sluiten' is replaced with a 'Close' button.

= V1.02 - 13.10.2011 =
* noConflict for jQuery is added.  

= V1.01 - 23.09.2011 =
*The file uploader is added to the Galleries page in admin section. 

== Upgrade Notice ==

= V3.7 =
Added link url field - clicking on image open the link in new window.

= V3.6 =
Added function to display 'Download High resolution image' link in lightbox on gallery page. Added setting for 'Download High resolution image' link.

= V3.5 =
Lituanian language file is added to the plugin. Added drag and drop function to change the order of the output of images. Added a shortcode for displaying short gallery type (like [print_gllr id=211 display=short])

= V3.4 =
Cross Site Request Forgery bug was fixed. 

= V3.3 =
Brazilian Portuguese and Hebrew language files are added to the plugin. We updated Italian language file. We updated all functionality for wordpress 3.4.1.

= V3.2 =
We updated all functionality for wordpress 3.4.

= V3.1.2 =
The bug with gallery uploader (undefined x undefined) was fixed.

= V3.1.1 =
The bug with gallery uploader was fixed.

= V3.1 =
Metabox with shortcode has been added on Edit Gallery Page to add it on your page or post. The bug with gallery shortcode was fixed.

= V3.06 =
The bug with gallery appears above text content was fixed.

= V3.05 =
Added shortcode for display Single Gallery on your page or post. Added attachment order. Added 'Return to all albums' link for Single Gallery page. Spanish language file are added to the plugin.

= V3.04 =
Added slideshow for lightbox on single gallery page.

= V3.03 =
The bug related with the upload of the photos on the multisite network was fixed.

= V3.02 =
The bug related with the display of the photo on the single page of the gallery was fixed.

= V3.01 =
Czech, Hungarian and German language files are added to the plugin. Possibility to set featured image as cover of the album is added. Replace prettyPhoto library to fancybox library. Code that is used to display a lightbox for images in `gallery-single-template.php` template file is changed.

= V2.12 =
Italian language files are added to the plugin.

= V2.11 =
The bug related with the indication of the menu item on the single page of the gallery was fixed.

= V2.10 =
Polish language files are added to the plugin.

= V2.09 - 07.03.2012 =
BWS plugins section has been changed. 

= V2.08 =
Code that is used to connect styles and scripts is added to the plugin for correct SSL verification. The bug with a style for an image block on admin page was fixed.

= V2.07 =
Ukrainian language files are added to the plugin. Problem with copying files gallery-single-template.php to the theme was fixed.

= V2.06 =
Dutch language files are added to the plugin.

= V2.05 =
A link to the plugin's settings page is added. Revised Georgian language files are added to the plugin.

= V2.04 =
French language files are added to the plugin.

= V2.03 =
Position to display images on a single page of the Gallery was fixed. Please upgrade the Gallery plugin. Thank you.

= V2.02 =
Georgian language files are added to the plugin.

= V2.01 =
A possibility to add a caption to each photo of the album is added. A possibility to select dimensions of the thumbnails for the cover of the album and for photos in album is added. A possibility to select a number of the photos for a separate page of the album in the gallery which will be placed in one line is added. PrettyPhoto library was updated. Button 'Sluiten' is replaced with a 'Close' button. Please upgrade the Gallery plugin immediately. Thank you.

= V1.02 =
noConflict for jQuery is added.

= V1.01 =
The file uploader is added to the Galleries page in admin section.
