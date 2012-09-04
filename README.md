#OpenShownotesFormat

Das ```Open Shownotes Format``` oder ```Podlove Shownotes Format``` ist ein Standard, um maschinenlesbare Shownotes für Podcasts zu schreiben.

Dieses Repo enthält die Standard-Definition sowie eine Referenz-Implementierung in PHP.

---

##Einträge

Die Shownotes sollen leicht im Plaintext erfasst werden können, aber auch maschinenlesbar sein können.

Die Shownotes bestehen aus mehreren einzelnen Zeilen, "Items". Jedes Item enthält einen Eintrag der Shownotes, mit Ausnahme  dass eine Zeile mit optionalen Leerzeichen und unabdinglichem Gedankenstrich ODER ASCII Minus ***0x2D*** beginnt, in dem Fall gehört der Text zum vorherigen Item. Jeder Eintrag besteht aus:

- Zeitmarke (optional) [Format: ```hh.mm.ss[.ddd]```]
- Titel/Text, Rauten ```#``` ***0x23*** werden darin per vorgestelltem backslash ```\``` ***0x5C*** escaped
- Links (optional) [Format: ```<URL>``` oder ```[Beschreibung](URL)```] //Da Kapitelmarken in Mediadateien nur maximal einen Link pro Kapitel erlauben, wird hierzu nur der erste Link verwendet.
- Tags (optional) [Format: ```#tag``` ...]
- Zeitangaben ausserhalb der Sendung (optional) [Format: ```(dd.mm.yyyy[ - hh:mm[:ss]]```]

---

##Spezielle Tags

Die Tags geben dem Eintrag bestimmte Attribute. Im Prinzip können beliebige Tags verwendet werden, aber bestimmte Tags haben eine besondere Bedeutung.

```#chapter``` markiert eine "richtige" Kapitelmarke   
```#spoiler``` markiert Spoiler   
```#music``` markiert Musik (Intro, Outro, Pausenmusik)   

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

##Automatisierungen

- Podlove Simple Chapters
- Affiliate Links
- Links
- Webpage Generierung via Template
- Export als JSON
- Export als XML

---

#Info

Der gesammte Inhalt dieses Repos steht unter der MIT Lizenz zur Verfügung.  
Jeder ist eingeladen, bei diesem Projekt mitzuwirken.

OSF ist Teil des [Shownot.es Projekt](http://shownot.es/), sowie von [Podlove](http://podlove.org/)
