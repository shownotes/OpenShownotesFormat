#OSF in a Nutshell

[english](http://shownotes.github.io/OSF-in-a-Nutshell/OSF-in-a-Nutshell.en.html) - [deutsch](http://shownotes.github.io/OSF-in-a-Nutshell/OSF-in-a-Nutshell.de.html)

##Erklärung

Das Open Shownotes Format, oder auch kurz OSF ist ein Format welches die Erstellung von Shownotes für Podcasts erleichtert.

##Beispiele

Zeitangabe als HH:MM:SS gefolgt von Text und Chapter-Tag  
```00:00:00 Intro #c```

Zeitangabe als HH:MM:SS.ms gefolgt von Text und Link in spitzen Klammern  
```00:01:25.234 Shownot.es Projekt <http://shownot.es/>```

Zusätzliche Informationen mittels Glossar (Wikipedia-Link)  
```00:03:13 Geofencing <http://de.wikipedia.org/wiki/Geofencing> #g```

Zeitangabe als UNIX-Timestamp  
```1373135108 Sendungsbeginn #c```

##Grundlagen

Wichtige Grundlagen des Open Shownotes Format (OSF) sind:

* jede Zeile ist ein eigenes Item (d.h. zusammenhängende Informationen nicht durch ```\n``` trennen)
* leere Zeilen werden ignoriert
* jedes Item **kann** eine Zeitangabe enthalten
* Zeitangaben sind als UNIX-Timestamps oder im Format ```HH:MM:SS``` anzugeben (wenn das [ShowPad](http://pad.shownot.es/) zum Shownotes schreiben verwendet wird, können diese Zeitangaben per ```###``` und einem darauf folgendem Leerzeichen automatisch eingefügt werden)
* nach der Zeitangabe (oder am Anfang der Zeile wenn keine Zeit angegeben wurde) kann eine Angabe über die Hierarchieebene mittels ```-``` gemacht werden. Je mehr ```-```, desto tiefer verschachtelt ist das Item.
* mit Hierarchien **sollte** man nicht übertreiben
* jedes Item **muss** einen Text enthalten, dieser kann die meisten UTF-8 Zeichen enthalten, um Probleme zu vermeiden wäre es aber Sinnvoll, sich auf ISO-8859-15 zu beschränken
* Items **sollten** mit Großbuchstaben beginnen, es sei den es handelt sich um Subitems oder Halbsätze
* Satzzeichen am Ende von Items **sollten** vermieden werden
* Es sollten keine Sprachspezifischen Anführungszeichen, sondern nur ```"``` verwendet werden
* jedes Item **kann** **eine** URL enthalten, diese ist mit ```<``` am Anfang und ```>``` am Ende zu kennzeichnen
* jedes Item **kann** **mehrere** Tags enthalten, diese sind mit ```#``` zu beginnen
* ausserdem gibt es Tags mit vordefinierten Eigenschaften:
	* ```#chapter``` (```#c```) kennzeichnet ein Item als Beginn eines neuen Kapitels
	* ```#topic``` (```#t```) kennzeichnet ein Item als wichtigen Bestandteil der Shownotes
	* ```#video``` (```#v```), ```#audio``` (```#a```) und ```#image``` (```#i```) können verwendet werden, um Mediendateien zu referenzieren
	* ```#quote``` (```#q```) kennzeichnet Zitate, es sollte immer die Person die es gesagt hat angegeben werden
		* Alle in den Shownotes erwähnten Personen (egal ob bei Zitaten oder Erwähnungen) sollten auch im Header angegeben werden (FAMOUS)
	* mit ```#shopping``` können Links zur Onlineshops gekennzeichnet werden
	* ```#prediction``` wird verwendet, um Vorhersagen zu markieren, die später überprüft werden müssen
	* Links mit weiterführenden und beschreibenden Inhalten, auf die aber im Podcast nicht weiter eingegangen worden ist können mit ```#glossary``` gekennzeichnet werden
	* Links die direkt im Podcast erwähnt worden sind, sind mit ```#link``` zu kennzeichnen
	* unfertige Zeilen sollten mit ```#revision```(```#r```) gekennzeichnet werden, diese werden nicht exportiert
	* zusätzlich werden jedem Item, welches einen Link enthält die Top- und Second Level Domain als Tags beigefügt (z.B.: ```#googlede```)
	* weitere Tags können ebenfalls verwendet werden, haben jedoch vorerst keine Auswirkungen auf das Ergebnis (für einige Tags gibt es kleine Bildchen: [BitmapWebIcons](http://simonwaldherr.github.io/BitmapWebIcons/))

##Wortdefinition

Die folgende Erklärung muss wie in [RFC 2119](http://tools.ietf.org/html/rfc2119) definiert gelesen werden, wobei in der deutschen Übersetzung ```MUST```/```SHALL``` mit ```MUSS```, ```REQUIRED``` mit ```BENÖTIGT```,  ```MUST NOT```/```SHALL NOT``` mit ```DARF NICHT```, ```SHOULD``` mit ```SOLLTE```/```KANN```, ```SHOULD NOT``` mit ```SOLLTE NICHT``` und ```MAY``` mit ```KANN``` übersetzt wurde.

##Tools

* [ShowPad](https://github.com/shownotes/show-pad) ist eine Erweiterung von [Etherpad lite](https://github.com/ether/etherpad-lite) um Usermanagement, Zeitmanagement, ein schönes Interface sowie Im- und Export Funktionen
* [tinyOSF.js](https://github.com/shownotes/tinyOSF.js) ist eine Referenzimplementierung des OSF Parsers in JavaScript
* [OpenShownotesFormat](https://github.com/shownotes/OpenShownotesFormat) ist die erste Implementierung des OSF Standards (geschrieben in PHP). Eine frei verwendbare Installation dieses Tools ist auf [tools.shownot.es/parsersuite](http://tools.shownot.es/parsersuite/?configfile=shownotes) zu finden
* [wp-osf-shownotes](https://github.com/SimonWaldherr/wp-osf-shownotes) ([auf wordpress.org](http://wordpress.org/extend/plugins/shownotes/)) ist ein WordPress Plugin), welches die Umwandlung von OSF zu HTML direkt im Blog ermöglicht
* [OSF.php](https://github.com/SimonWaldherr/OSF.php) sind PHP Funktionen um OSF nach HTML (und andere Formate) zu wandeln (dieses Repo sollte als Submodul in Projekte eingefügt werden, welche OSF verarbeiten)
* [ep_insertTimestamp](https://github.com/shownotes/ep_insertTimestamp) erweitert EPL Installationen um eine automatisierte Datum/Zeit eingabe
* [EtherpadBookmarklets](https://github.com/shownotes/EtherpadBookmarklets) sind Bookmarklets die das [Shownot.es Team](http://shownot.es) vor dem Umstieg auf [Etherpad lite](https://github.com/ether/etherpad-lite) verwendete
* [ParseTime.js](https://github.com/SimonWaldherr/parseTime.js) wandelt Zeitangaben in den Shownotes in weiterverarbeitbare Zahlen um
* [XMPP Notification Service](https://github.com/Drake81/shownotes-message-service)
