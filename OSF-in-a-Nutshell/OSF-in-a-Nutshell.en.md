#OSF in a Nutshell

[english](http://shownotes.github.io/OSF-in-a-Nutshell/OSF-in-a-Nutshell.en.html) - [deutsch](http://shownotes.github.io/OSF-in-a-Nutshell/OSF-in-a-Nutshell.de.html)

##Explanation

The Open Show Notes format, or short OSF is a format which simplifies the creation of show notes for podcasts. 

##Examples

time information as HH:MM:SS followed by text and Chapter-Tag  
```00:00:00 Intro #c```

time information as HH:MM:SS.ms followed by text and a url enclosed in angle brackets  
```00:01:25.234 Shownot.es Projekt <http://shownot.es/>```

additional information via glossary (Wikipedia-Link)  
```00:03:13 Geofencing <http://de.wikipedia.org/wiki/Geofencing> #g```

time information as UNIX-Timestamp  
```1373135108 Sendungsbeginn #c```

##Basics

Important basics of the Open Show Notes format (OSF) are:

* each line is its own separate item (related information **shall not** be separated by ```\n```)
* blank lines are ignored
* each item **may** contain a time specification
* Times are to specify as UNIX timestamps or in ```HH:MM:SS``` format (if the [Showpad](http://pad.shownot.es/) is used to write show notes, these times can be made by ```###``` followed by a whitespace)
* after the time (or at the beginning of the line if no time is specified), a level of hierarchy can be set with ```-```. The more ```-``` the deeper the item is nested.
* don't use to much hierarchies
* each item **must** contain a text, it can contain most of UTF-8 characters, but to avoid problems, it would make sense to limit themselves to ISO-8859-15
* Items **should** begin with a capital letter, unless it is a subitems or a half-sentences
* Punctuation marks at the end of items **should** be avoided
* Don't write language specific quotation marks, use ```"```, they will be converted automatically by the parser
* each item **may** contain **one** URL, this URL has to beginn with ```<``` and end with ```>```
* each item **may** contain **multiple** tags, they have to start with ```#```
* There are also tags with predefined properties:
	* ```#chapter``` (```#c```) identifies an item as the beginning of a new chapter
	* ```#topic``` (```#t```) identifies an item as an important part of the show notes
	* ```#video``` (```#v```), ```#audio``` (```#a```) and ```#image``` (```#i```) and can be used to refer to media files
	* ```#quote``` (```#q```) marks quotes, it should also be given the name of the person who said it
		* All persons mentioned in the show notes (whether in citations or references) should also be specified in the header (FAMOUS)
	* ```#shopping``` links can be marked for online shops
	* ```#prediction``` is used to highlight predictions that need to be checked later
	* Links with further and descriptive content, which was not directly discussed in the podcast can be marked with ```#glossary```
	* Links that have been directly mentioned in the podcast, have to be marked with ```#link```
	* unfinished items should be marked with ```#revision```(```#r```), so they wan't appear in the parsed output
	* in addition to any item that contains a link, the top and second level domain are attached as a tag (e.g.: ```#googlecom```)
	* more tags can also be used, but have no immediate impact on the result (for some tags exists icons: [BitmapWebIcons](http://simonwaldherr.github.io/BitmapWebIcons/))

##Word definition

The following statements has to be interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).

##Tools

* [ShowPad](https://github.com/shownotes/show-pad) is an extension to [Etherpad lite](https://github.com/ether/etherpad-lite) with user management, time management, a nice interface, and import and export functions
* [tinyOSF.js](https://github.com/shownotes/tinyOSF.js) is a reference implementation of the OSF parser in JavaScript
* [OpenShownotesFormat](https://github.com/shownotes/OpenShownotesFormat) is the first implementation of the OSF standards (written in PHP). A freely usable installation of this tool is available at [tools.shownot.es/parsersuite](http://tools.shownot.es/parsersuite/?configfile=shownotes)
* [wp-osf-shownotes](https://github.com/SimonWaldherr/wp-osf-shownotes) ([at wordpress.org](http://wordpress.org/extend/plugins/shownotes/)) is a WordPress Plugin), which allows the conversion of OSF to HTML directly in the blog
* [OSF.php](https://github.com/SimonWaldherr/OSF.php) are PHP functions to convert OSF to HTML (and other formats) (it's meant to be included as submodule in other projects)
* [ep_insertTimestamp](https://github.com/shownotes/ep_insertTimestamp) extends EPL installations by an automated date / time input
* [EtherpadBookmarklets](https://github.com/shownotes/EtherpadBookmarklets) are bookmarklets which were used by the [Shownot.es Team](http://shownot.es) before moving to [Etherpad Lite](https://github.com/ether/etherpad-lite)
* [ParseTime.js](https://github.com/SimonWaldherr/parseTime.js) can parse Timedefinitions in Show notes
* [XMPP Notification Service](https://github.com/Drake81/shownotes-message-service)
