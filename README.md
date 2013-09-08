#OpenShownotesFormat

<img src="https://raw.github.com/shownotes/OpenShownotesFormat/master/img/osf_file_icon.png">

Das ```Open Shownotes Format``` ist ein Standard, um per Hand maschinenlesbare Shownotes für Podcasts zu schreiben.
Die Shownotes können ganz einfach im OSF geschrieben und dann zu diversen anderen Formaten umgewandelt werden:

* HTML
* MP4 Chaps
* PSC
* ...

Dieses Repo enthält die Referenz-Implementierung in PHP.
Der Code wurde mit wenig Rücksicht auf Geschwindigkeit oder Schönheit geschrieben, Benutzung auf eigene Gefahr

weitere Informationen:

* [OSF in a Nutshell](https://github.com/shownotes/OSF-in-a-Nutshell/blob/master/OSF-in-a-Nutshell.md#deutsch) ist eine kurze Beschreibung des OSF-"Standards" *(auf deutsch und englisch)*
* [tinyOSF.js](https://github.com/shownotes/tinyOSF.js) ist eine Referenzimplementierung des OSF Parsers in JavaScript
* [wp-osf-shownotes](https://github.com/SimonWaldherr/wp-osf-shownotes) ([auf wordpress.org](http://wordpress.org/extend/plugins/shownotes/)) ist ein WordPress Plugin (auch als [PPP Modul erhältlich](https://github.com/podlove/podlove-publisher/tree/module-shownotes)), welches die Umwandlung von OSF zu HTML direkt im Blog ermöglicht



---

##Einträge

Die Shownotes sollen leicht im Plaintext erfasst werden können, aber auch maschinenlesbar sein können.

Die Shownotes bestehen aus mehreren einzelnen Zeilen, "Items". Jedes Item enthält einen Eintrag der Shownotes, mit Ausnahme  dass eine Zeile mit optionalen Leerzeichen und unabdinglichem Gedankenstrich ODER ASCII Minus ***0x2D*** beginnt, in dem Fall gehört der Text zum vorherigen Item. Jeder Eintrag besteht aus:

- Zeitmarke (optional) [Format: ```hh.mm.ss[.ddd]```] seit 2013 auch [Format: ```1357704990```] (UNIX-Timestamp)
- Titel/Text, Rauten ```#``` ***0x23*** werden darin per vorgestelltem backslash ```\``` ***0x5C*** escaped
- Links (optional) [Format: ```<URL>``` oder ```[Beschreibung](URL)```] //Da Kapitelmarken in Mediadateien nur maximal einen Link pro Kapitel erlauben, wird hierzu nur der erste Link verwendet.
- Tags (optional) [Format: ```#tag``` ...]

---

##Spezielle Tags

Die Tags geben dem Eintrag bestimmte Attribute. Im Prinzip können beliebige Tags verwendet werden, aber bestimmte Tags haben eine besondere Bedeutung.

```#chapter``` markiert eine "richtige" Kapitelmarke  
```#topic``` wichtige Inhalte  
```#spoiler``` markiert Spoiler  
```#music``` markiert Musik (Intro, Outro, Pausenmusik)  

Weitere wichtige Tags sind: #section #embed #video #audio #image #shopping #glossary

---

##Kaskadierung

Wenn der Titel eines Eintrags mit ```- ``` ***0x2d 0x20*** beginnt, kann er als dem vorherigen Eintrag zugehörig interpretiert werden (Gruppierung/Kaskadierung).  
Zur stärkeren Kaskadierung erhöht man die Anzahl der Bindestriche ```-- ``` ```--- ``` ```---- ``` ...

---

##Beispiele

```00:00:00.000 Intro #chapter #music```  
```Swedish Child Orchestra spielt “Also sprach Zarathustra” <http://www.youtube.com/watch?v=5umEUBDXfU0> #video #youtube #funny```  
```Also Sprach Zarathustra <http://de.wikipedia.org/wiki/Also_sprach_Zarathustra_(Strauss)> #wikipedia #music```  
```00:04:30 Begrüßung: Pakete Statt Themen #chapter```  
```Einer flog über das Kuckucksnest #movie```  
```- DVD <http://www.amazon.de/Einer-flog-über-das-Kuckucksnest/dp/B00004RYD3/ref=sr_1_1?  ie=UTF8&qid=1346785262&sr=8-1&tag=aflattrcom390-21> #dvd #amazon```  
```- Blu-Ray <http://www.amazon.de/Einer-flog-über-Kuckucksnest-Blu-ray/dp/B003UF5WGY/ref=sr_1_2?  s=dvd&ie=UTF8&qid=1346785293&sr=1-2&tag=aflattrcom390-21> #bluray #amazon```  
```@Holgi fällt vor Lachen vom Stuhl #spoiler```  
```01:23:42 Kalender #chapter```  
```- MacOSX```  
```-- iCal```  
```-- Fantastical```  
```-- QuickCal```  
```- iOS```  
```-- miCal```  
```-- Calvetica Calendar```  
```04:42:05 Outliner #chapter```  
```- MacOSX```  
```-- 04:55:33 OmniOutliner```  

---


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

OSF ist Teil des [Shownot.es Projekt](http://shownot.es/)  

Eine Demoversion ist unter <http://tools.shownot.es/parsersuite/?configfile=shownotes> zu finden.  
