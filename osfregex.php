<?php

function osf_convert_time($string)
  {
    // Diese Funktion wandelt Zeitangaben vom Format 01:23:45 (H:i:s) in Sekundenangaben um
    $strarray = explode(':', $string);
    return (($strarray[0]*3600)+($strarray[1]*60)+$strarray[2]);
  }

function osf_parser($shownotes)
  {
    // Diese Funktion ist das Herzstück des OSF-Parsers
    // zuerst werden die regex-Definitionen zum erkennen von Zeilen, Tags, URLs und subitems definiert
    $pattern['zeilen']    = '/((\d\d:\d\d:\d\d)(\\.\d\d\d)?)*(.+)/';
    $pattern['tags']      = '((#)(\S*))';
    $pattern['urls']      = '(\s+((http(|s)://\S{0,128})\s))';
    $pattern['urls2']     = '(\<((http(|s)://\S{0,128})>))';
    $pattern['kaskade']   = '/^([\t ]*-+ )/';
    
    // danach werden mittels des zeilen-Patterns die Shownotes in Zeilen/items geteilt
    preg_match_all($pattern['zeilen'], $shownotes, $zeilen, PREG_SET_ORDER);
    
    // Zählvariablen definieren
    // i = item, lastroot = Nummer des letzten Hauptitems, kaskadei = Verschachtelungstiefe
    $i = 0;
    $lastroot = 0;
    $kaskadei = 0;
    $returnarray['info']['zeilen'] = 0;
    
    // Zeile für Zeile durch die Shownotes gehen
    foreach($zeilen as $zeile)
      {
        // Alle Daten der letzten Zeile verwerfen
        unset($newarray);
        
        // Text der Zeile in Variable abspeichern und abschließendes Leerzeichen anhängen
        $text = $zeile[4].' ';
        
        // Mittels regex tags und urls extrahieren
        preg_match_all($pattern['tags'],  $text, $tags,  PREG_PATTERN_ORDER);
        preg_match_all($pattern['urls'],  $text, $urls,  PREG_PATTERN_ORDER);
        preg_match_all($pattern['urls2'], $text, $urls2, PREG_PATTERN_ORDER);
        
        // array mit URLs im format <url> mit array mit URLs im format  url  zusammenführen
        $urls = array_merge($urls[2], $urls2[2]);
        
        // Zeit und Text in Array zur weitergabe speichern
        $newarray['time'] = $zeile[1];
        $newarray['text'] = $zeile[4];
        
        // Wenn Tags vorhanden sind, diese ebenfalls im Array speichern
        if(@count($tags[2])>0)
          {
            $newarray['tags'] = $tags[2];
            if(((in_array("Chapter", $newarray['tags']))||(in_array("chapter", $newarray['tags'])))&&($newarray['time'] != ''))
              {
                $newarray['chapter'] = true;
              }
          }
        
        // Wenn URLs vorhanden sind, auch diese im Array speichern
        if(@count($urls)>0)
          {
            $purls = array();
            foreach($urls as $url)
              {
                
                if(strstr($url, 'www.amazon.de/')&&strstr($url, 'p/'))
                  {
                    if(strstr($url,"dp/")){$pid = substr(strstr($url,"dp/"),3,10);}
                    elseif(strstr($url,"gp/product/")){$pid = substr(strstr($url,"gp/product/"),11,10);}
                    else{$pid='';}
                    $aid = '?ie=UTF8&linkCode=as2&tag=shownot.es-21';
                    $purls[] = 'http://www.amazon.de/gp/product/'.$pid.'/'.$aid;
                  }
                elseif(strstr($url, 'www.amazon.com/')&&strstr($url, 'p/'))
                {
                  if(strstr($url,"dp/")){$pid = substr(strstr($url,"dp/"),3,10);}
                  elseif(strstr($url,"gp/product/")){$pid = substr(strstr($url,"gp/product/"),11,10);}
                  else{$pid='';}
                  $aid = '?ie=UTF8&linkCode=as2&tag=shownot.es-21';
                  $purls[] = 'http://www.amazon.com/gp/product/'.$pid.'/'.$aid;
                }
                else
                  {
                    $purls[] = $url;
                  }
              }
            $newarray['urls'] = $purls;
          }
        
        // Wenn Zeile mit "- " beginnt im Ausgabe-Array verschachteln
        if((preg_match($pattern['kaskade'], $zeile[0]))||(!preg_match('/(\d\d:\d\d:\d\d)/', $zeile[0]))||(!$newarray['chapter']))
          {
            if(preg_match($pattern['kaskade'], $zeile[0]))
              {
                $returnarray['export'][$lastroot]['subitems'][$kaskadei] = $newarray;
              }
            else
              {
                $newarray['subtext'] = true;
                $returnarray['export'][$lastroot]['subitems'][$kaskadei] = $newarray;
              }
            
            // Verschachtelungstiefe hochzählen
            ++$kaskadei;
          }
        
        // Wenn die Zeile keine Verschachtelung darstellt
        else
          {
            // Daten auf oberster ebene einfügen
            $returnarray['export'][$i] = $newarray;
            
            // Nummer des letzten Objekts auf oberster ebene auf akutelle Item Nummer setzen
            $lastroot = $i;
            
            // Verschachtelungstiefe auf 0 setzen
            $kaskadei = 0;
          }
        
        // Item Nummer hochzählen
        ++$i;
      }
    
    // Zusatzinformationen im Array abspeichern (Zeilenzahl, Zeichenlänge und Hash der Shownotes)
    $returnarray['info']['zeilen']  = $i;
    $returnarray['info']['zeichen'] = strlen($shownotes);
    $returnarray['info']['hash']    = md5($shownotes);
    
    // Rückgabe der geparsten Daten
    return $returnarray;
  }

?>