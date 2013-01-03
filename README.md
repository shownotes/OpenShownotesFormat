#OpenShownotesFormat

<img src="http://tools.shownot.es/parser/osf_file_icon.png">

Das ```Open Shownotes Format``` oder ```Podlove Shownotes Format``` ist ein Standard, um maschinenlesbare Shownotes für Podcasts zu schreiben.

#ACHTUNG

**Bitte keine alte Versionen verwenden! Die nächste Version enthält viele Verbesserungen und kommt demnächst.**

---

**Don't use previous versions! The next version is much better and is coming soon.**

##Exportmodule

* Podlove Simple Chapter
* Anycast (short, long, full)
* wikigeeks (short, long, full)
* metaebene
* JSON
* Plaintext Chapter
* PHP (print_r)

##Automatisierungen

* Podlove Simple Chapters
* Affiliate Links
* Links
* Webpage Generierung via Template
* Export als JSON
* Export als XML

---

#Info

Der gesammte Inhalt dieses Repos steht unter der MIT Lizenz zur Verfügung.  (ausgenommen die Shownote Beispiele)
Jeder ist eingeladen, bei diesem Projekt mitzuwirken.

OSF ist Teil des [Shownot.es Projekt](http://shownot.es/), sowie von [Podlove](http://podlove.org/)

Eine Demoversion ist unter <http://tools.shownot.es/parser/> zu finden.  
  
#API
Zur automatisierten Nutzung ist das Ganze auch via API erreichbar:
`GET` `http://tools.shownot.es/parser/api.php`  
Parameter:  
* `mode` – Art der Rückgabe, mögliche Werte:  
  * `json`: Ausgabe als JSON, zur weiteren Verwendung in z.B. JavaScript nützlich  
  * `html`: Ausgabe als HTML, nur Kapitel und Namen  
  * `morehtml`: Ausgabe als HTML, Kapitel, Namen sowie weitere Links  
  * `wikigeeks`: Ausgabe als HTML, Format: Kapitelname [Timestamp] und Liste mit Links  
  * `wikigeeks-full`: Ausgabe als HTML, Format: Kapitelname [Timestamp] und Liste mit Links und Teilüberschriften  
  * `metaebene`: Ausgabe als HTML, Format: Tabelle mit Timestamp und Kapiteltiel welcher auf den Timestamp linkt  
  * `chapter`: Ausgabe als Text, Format: Timestamp  Kapitelname  
  * `psc`: [Podlove Simple Chapter Format](http://podlove.org/simple-chapters/)  
  * `php`: Debug-Ausgabe (print_r)  
* `pad` – Entweder eine URL welche die zu parsenden Daten zurückgibt oder die [shownotes.piratenpad.de](http://shownotes.piratenpad.de) Pad-ID  
* `dl` – Wenn gesetzt wird das Ergebnis heruntergeladen und nicht direkt angezeigt. (Download-Header wird gesetzt)

  
Beispiel: <a href="http://tools.shownot.es/parser/api.php?mode=morehtml&pad=mm95" target="_blank">tools.shownot.es/parser/api.php?mode=<code>morehtml</code>&pad=<code>mm95</code></a>  
