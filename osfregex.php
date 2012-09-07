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
        
        
        if(preg_match($pattern['kaskade'], $zeile[0]))
          {
            $returnarray['export'][$lastroot]['subitems'][$kaskadei] = $newarray;
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

function osf_get_chapter_html($array, $full=false)
  {
    $returnstring = '<dl>';
    foreach($array as $item)
      {
        if(($item['chapter'])||(($full)&&($item['time'] != '')))
          {
            $filterpattern = array('(\s(#)(\S*))', '(\<((http(|s)://[\S#?-]{0,128})>))', '(\s+((http(|s)://[\S#?-]{0,128})\s))', '(^ *-*)');
            $text = preg_replace($filterpattern, '', $item['text']);
            if(strpos($item['time'], '.'))
              {
                $time = explode('.', $item['time']);
                $time = $time[0];
              }
            else
              {
                $time = $item['time'];
              }
            $returnstring .= '<dt data-time="'.osf_convert_time($time).'">'.$time.'</dt>'."\n".'<dd>';
            if(isset($item['urls'][0]))
              {
                $returnstring .= '<strong><a href="'.$item['urls'][0].'">'.$text.'</a></strong> '."\n";
              }
            else
              {
                $returnstring .= '<strong>'.$text.'</strong> '."\n";
              }
            if(isset($item['subitems']))
              {
                $subitemi = 0;
                foreach($item['subitems'] as $subitem)
                  {
                    //$filterpattern = array('((#)(\S*))', '(\<((http(|s)://\S{0,64})>))', '(\s+((http(|s)://\S{0,64})\s))');
                    $text = preg_replace($filterpattern, '', $subitem['text']);
                    if($subitemi)
                      {
                        $returnstring .= ', ';
                      }
                    else
                      {
                        $returnstring .= '<br>';
                      }
                    if(isset($subitem['urls'][0]))
                      {
                        $returnstring .= '<a href="'.$subitem['urls'][0].'">'.$text.'</a>'."\n";
                      }
                    else
                      {
                        $returnstring .= $text;
                      }
                    ++$subitemi;
                  }
              }
            $returnstring .= '</dd>';
          }
      }
    $returnstring .= '</dl>'."\n";
    return $returnstring;
  }

function osf_export_psc($array)
  {
    $returnstring = '<!-- specify chapter information -->'."\n".'<sc:chapters version="1.0">'."\n";
    foreach($array as $item)
      {
        if($item['chapter'])
          {
            $filterpattern = array('((#)(\S*))', '(\<((http(|s)://[\S#?-]{0,128})>))', '(\s+((http(|s)://[\S#?-]{0,128})\s))');
            $text = preg_replace($filterpattern, '', $item['text']);
            if(strpos($item['time'], '.'))
              {
                $time = $item['time'];
              }
            else
              {
                $time = $item['time'].'.000 ';
              }
            $returnstring .= '<sc:chapter start="'.$time.'" title="'.$text.'"';
            if(isset($item['urls'][0]))
              {
                $returnstring .= ' href="'.$item['urls'][0].'"';
              }
            $returnstring .= ' />'."\n";
          }
      }
    $returnstring .= '</sc:chapters>'."\n";
    $returnstring = preg_replace('(\s+")', '"', $returnstring);
    return $returnstring;
  }

function osf_export_chapterlist($array)
  {
    $returnstring = '';
    foreach($array as $item)
      {
        if($item['chapter'])
          {
            $filterpattern = array('((#)(\S*))', '(\<((http(|s)://\S{0,128})>))', '(\s+((http(|s)://\S{0,128})\s))');
            $text = preg_replace($filterpattern, '', $item['text']);

            if(strpos($item['time'], '.'))
              {
                $returnstring .= $item['time'].' '.$text."\n";
              }
            else
              {
                $returnstring .= $item['time'].'.000 '.$text."\n";
              }
          }
      }
    $returnstring = preg_replace('(\s+\n)', "\n", $returnstring);
    return $returnstring;
  }

?>