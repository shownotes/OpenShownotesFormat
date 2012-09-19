<?php

function osf_convert_time($string)
  {
    $strarray = explode(':', $string);
    return (($strarray[0]*3600)+($strarray[1]*60)+$strarray[2]);
  }

function osf_parser($shownotes)
  {

    $pattern['zeilen']    = '/((\d\d:\d\d:\d\d)(\\.\d\d\d)?)*(.+)/';
    $pattern['tags']      = '((#)(\S*))';
    $pattern['urls']      = '(\s+((http(|s)://\S{0,128})\s))';
    $pattern['urls2']     = '(\<((http(|s)://\S{0,128})>))';
    $pattern['kaskade']   = '/^([\t ]*-+ )/';
    
    preg_match_all($pattern['zeilen'], $shownotes, $zeilen, PREG_SET_ORDER);
    
    $i = 0;
    $lastroot = 0;
    $kaskadei = 0;
    $returnarray['info']['zeilen'] = 0;
    foreach($zeilen as $zeile)
      {
        unset($newarray);
        
        $text = $zeile[4].' ';
        preg_match_all($pattern['tags'],  $text, $tags,  PREG_PATTERN_ORDER);
        preg_match_all($pattern['urls'],  $text, $urls,  PREG_PATTERN_ORDER);
        preg_match_all($pattern['urls2'], $text, $urls2, PREG_PATTERN_ORDER);
        
        $urls = array_merge($urls[2], $urls2[2]);
        
        $newarray['time'] = $zeile[1];
        $newarray['text'] = $zeile[4];
        if(@count($tags[2])>0)
          {
            $newarray['tags'] = $tags[2];
            if(((in_array("Chapter", $newarray['tags']))||(in_array("chapter", $newarray['tags'])))&&($newarray['time'] != ''))
              {
                $newarray['chapter'] = true;
              }
          }
        if(@count($urls)>0)
          {
            $newarray['urls'] = $urls;
          }
        
        
        if((preg_match($pattern['kaskade'], $zeile[0]))||(!preg_match('/(\d\d:\d\d:\d\d)/', $zeile[0])))
          {
            if(preg_match($pattern['kaskade'], $zeile[0]))
              {
                $returnarray['export'][$lastroot]['subitems'][$kaskadei] = $newarray;
              }
            ++$kaskadei;
          }
        else
          {
            $returnarray['export'][$i] = $newarray;
            $lastroot = $i;
            $kaskadei = 0;
          }
        ++$i;
      }
    $returnarray['info']['zeilen']  = $i;
    $returnarray['info']['zeichen'] = strlen($shownotes);
    $returnarray['info']['hash']    = md5($shownotes);
    
    return $returnarray;
  }

?>