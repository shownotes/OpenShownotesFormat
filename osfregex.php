<?php

$osf_starttime = 0;

function osf_specialtags($needles, $haystack)
  {
    // Eine Funktion um Tags zu filtern
    $return = false;
    foreach($needles as $needle)
      {
        if(array_search($needle, $haystack) !== false)
          {
            $return = true;
          }
      }
    return $return;
  }

function osf_affiliate_generator($url, $data)
  {
    // Diese Funktion wandelt Links zu Amazon, Thomann und iTunes in Affiliate Links um
    $amazon       = $data['amazon'];
    $thomann      = $data['thomann'];
    $tradedoubler = $data['tradedoubler'];
    
    if((strstr($url, 'www.amazon.de/')&&strstr($url, 'p/'))&&($amazon != ''))
      {
        if(strstr($url,"dp/")){$pid = substr(strstr($url,"dp/"),3,10);}
        elseif(strstr($url,"gp/product/")){$pid = substr(strstr($url,"gp/product/"),11,10);}
        else{$pid='';}
        $aid = '?ie=UTF8&linkCode=as2&tag='.$amazon;
        $purl = 'http://www.amazon.de/gp/product/'.$pid.'/'.$aid;
      }
    elseif((strstr($url, 'www.amazon.com/')&&strstr($url, 'p/'))&&($amazon != ''))
      {
        if(strstr($url,"dp/")){$pid = substr(strstr($url,"dp/"),3,10);}
        elseif(strstr($url,"gp/product/")){$pid = substr(strstr($url,"gp/product/"),11,10);}
        else{$pid='';}
        $aid = '?ie=UTF8&linkCode=as2&tag='.$amazon;
        $purl = 'http://www.amazon.com/gp/product/'.$pid.'/'.$aid;
      }
    elseif((strstr($url, 'thomann.de/de/'))&&($thomann != ''))
      {
        $thomannurl = explode('.de/', $url);
        $purl = 'http://www.thomann.de/index.html?partner_id='.$thomann.'&page=/'.$thomannurl[1];
      }
    elseif((strstr($url, 'itunes.apple.com/de'))&&($tradedoubler != ''))
      {
        if(strstr($url, '?'))
          {
            $purl = 'http://clkde.Tradedoubler.com/click?p=23761&a='.$tradedoubler.'&url='.urlencode($url.'&partnerId=2003');
          }
        else
          {
            $purl = 'http://clkde.Tradedoubler.com/click?p=23761&a='.$tradedoubler.'&url='.urlencode($url.'?partnerId=2003');
          }
      }
    else
      {
        $purl = $url;
      }
    
    return $purl;
  }

function osf_convert_time($string)
  {
    // Diese Funktion wandelt Zeitangaben vom Format 01:23:45 (H:i:s) in Sekundenangaben um
    $strarray = explode(':', $string);
    return (($strarray[0]*3600)+($strarray[1]*60)+$strarray[2]);
  }

function osf_time_from_timestamp($utimestamp)
  {
    // Diese Funktion wandelt Zeitangaben im UNIX-Timestamp Format in relative Zeitangaben im Format 01:23:45 um
    global $osf_starttime;
    $duration = $utimestamp-$osf_starttime;
    $sec = $duration%60;
    if($sec < 10)
      {
        $sec = '0'.$sec;
      }
    $min = $duration/60%60;
    if($min < 10)
      {
        $min = '0'.$min;
      }
    $hour = $duration/3600%24;
    if($hour < 10)
      {
        $hour = '0'.$hour;
      }
    return "\n".$hour.':'.$min.':'.$sec;
  }

function osf_replace_timestamps($shownotes)
  {
    // Durchsucht die Shownotes nach Zeitangaben (UNIX-Timestamp) und übergibt sie an die Funktion osf_time_from_timestamp()
    global $osf_starttime;
    preg_match_all('/\n[0-9]{9,15}/', $shownotes, $unixtimestamps);
    $osf_starttime = $unixtimestamps[0][0];
    $regexTS = array('/\n[0-9]{9,15}/e', 'osf_time_from_timestamp(\'\\0\')');
    return preg_replace($regexTS[0], $regexTS[1], $shownotes);
  }

function osf_parser($shownotes, $data)
  {
    // Diese Funktion ist das Herzstück des OSF-Parsers
    $specialtags = $data['tags'];
    $exportall = $data['fullmode'];
    
    // entferne alle Angaben vorm und im Header
    $shownotes = explode('/HEADER', $shownotes);
    if(strlen($shownotes[1])>42)
      {
        $shownotes = $shownotes[1];
      }
    else
      {
        $shownotes = $shownotes[0];
      }
    
    $shownotes = explode('/HEAD', $shownotes);
    if(strlen($shownotes[1])>42)
      {
        $shownotes = $shownotes[1];
      }
    else
      {
        $shownotes = $shownotes[0];
      }
    
    // wandle Zeitangaben im UNIX-Timestamp Format in relative Zeitangaben im Format 01:23:45 um
    $shownotes = osf_replace_timestamps($shownotes);
    
    // zuerst werden die regex-Definitionen zum erkennen von Zeilen, Tags, URLs und subitems definiert
    $pattern['zeilen']    = '/((\d\d:\d\d:\d\d)(\\.\d\d\d)?)*(.+)/';
    $pattern['tags']      = '((\s#)(\S*))';
    $pattern['urls']      = '(\s+((http(|s)://\S{0,256})\s))';
    $pattern['urls2']     = '(\<((http(|s)://\S{0,256})>))';
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
                $purls[] = osf_affiliate_generator($url, $data);
              }
            $newarray['urls'] = $purls;
          }
        
        // Wenn Zeile mit "- " beginnt im Ausgabe-Array verschachteln
        if((preg_match($pattern['kaskade'], $zeile[0]))||(!preg_match('/(\d\d:\d\d:\d\d)/', $zeile[0]))||(!$newarray['chapter']))
          {
            if((osf_specialtags($newarray['tags'], $specialtags))||($exportall == 'true'))
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
              }
            else
              {
                unset($newarray);
              }
            
            // Verschachtelungstiefe hochzählen
            ++$kaskadei;
          }
        
        // Wenn die Zeile keine Verschachtelung darstellt
        else
          {
            if((osf_specialtags($newarray['tags'], $specialtags))||($exportall == 'true'))
            //if((osf_specialtags($newarray['tags'], $specialtags)))
              {
                // Daten auf oberster ebene einfügen
                $returnarray['export'][$i] = $newarray;
                
                // Nummer des letzten Objekts auf oberster ebene auf akutelle Item Nummer setzen
                $lastroot = $i;
                
                // Verschachtelungstiefe auf 0 setzen
                $kaskadei = 0;
              }
            else
              {
                unset($newarray);
              }
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