=== WAR Renown Rank ===
Contributors: Mike Roessing
Donate link: http://mike.roessing.ca
Tags: widget, php, links, information
Requires at least: 2.7
Tested up to: 2.9.1
Stable tag: 2.9.1

WAR Renown Rank shows a character's renown rank progress.

== Description ==

WAR Renown Rank is a plugin that will show a WAR character's renown rank progress.

Once installed, you can specify the title, character ID (by searching for your character at
http://realmwar.warhammeronline.com) and select the server your character is on.  Once that
is done, the magic is complete. :)

The widget will display your character's name, as a link to the character page at the
Realm War site, how much renown your character currently has, how much renown your character
needs to rank up, as well as a nice progress bar to show how far along your character is in
their current rank.

== Installation ==

1. Upload 'realm-rank.php' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add the 'WAR Renown Rank' widget to your sidebar and specify the Character ID and Server

== Frequently Asked Questions ==

= How do I find out my Character ID? =

Go to http://realmwar.warhammeronline.com and perform a Character Search for your Character.
When you find your character, click on his/her name to go to the character sheet.  In the URL,
the Character ID is the number directly after '?id='

ie. https://realmwar.warhammeronline.com/realmwar/CharacterInfo.war?id=1&server=122
The Character ID is '1'.

== Screenshots ==
None

== Changelog ==

= 0.1 =
* Plugin creation

= 0.2 =
* Gracefully handle the inability to connect to the Realm War page

== Upgrade Notice ==
None
