<?php

function osf_parser($shownotes)
  {

    $pattern['zeilen'] = '/((\d\d:\d\d:\d\d)(.\d\d\d)*)*(.+)/';
    $pattern['tags']   = '((#)(\S*))';
    $pattern['urls']   = '(\s+((http(|s)://\S{0,64})\s))';
    preg_match_all($pattern['zeilen'], $shownotes, $zeilen, PREG_SET_ORDER);
    
    $i = 0;
    $returnarray['info']['zeilen'] = 0;
    foreach($zeilen as $zeile)
      {
        preg_match_all($pattern['tags'], $zeile[4], $tags, PREG_PATTERN_ORDER);
        preg_match_all($pattern['urls'], $zeile[4], $urls, PREG_PATTERN_ORDER);
        
        $returnarray[$i]['time'] = $zeile[1];
        $returnarray[$i]['text'] = $zeile[4];
        $returnarray[$i]['tags'] = $tags[2];
        $returnarray[$i]['urls'] = $urls[2];
        
        ++$i;
      }
    $returnarray['info']['zeilen'] = $i;
    $returnarray['info']['hash']   = md5($shownotes);
    
    return $returnarray;
  }

?>