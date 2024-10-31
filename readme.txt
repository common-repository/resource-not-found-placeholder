=== Resource Not Found Placeholder | Prevent redirections due to not foud resources ===
Contributors: giuse
Tested up to: 6.5
Stable tag: 0.0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: performance, 404, redirections
Requires at least: 4.6
Requires PHP: 5.6



No more 404 errors and redirections due to missing resources. When a resource is not found, the plugin will replace it with an empty resource.


== Description ==

No more 404 errors and redirections due to missing resources. When a resource is not found, the plugin will replace it with an empty resource.

No settings are needed for this plugin. Just activate it, and it will replace all the missing resources with empty resources, preventing 404 errors and expensive redirections.

Many themes call https://yoursite.com/favicon.ico instead of the final favicon URL. 
Believe it or not, when a resource like a favicon is not found, WordPress loads all the plugins, the theme, and the core, and then it does a redirection to the final favicon image.
This means that before the redirection the PHP code of the entire WordPress ecosystem runs, and all the database queries take place.
This is really expensive in terms of performance. You may have already seen some WordPress websites where the favicon needs a long time before being displayed or long waiting times for a not found resource. Well, this plugin prevents this kind of issue.

In the case of the favicon, if it's not found in the main directory, the plugin will not replace it with an empty icon, but it will send to the browser the final favicon image without any redirections.
In all other cases, it will replace the missing resource with an empty resource.

It works for any kind of resource, but the resources with extensions .txt and .xml to avoid issues with the files which are generated on the fly like robots.txt and the sitemaps.


== Changelog ==

= 0.0.4 =
* Fix: sitemap not showing

= 0.0.3 =
* Fix: warning in the backend about the mu-plugin version

= 0.0.2 =
* Fix: plugin can't be activated on some servers

= 0.0.1 =
*Initial release
