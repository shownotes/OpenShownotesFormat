<?php

function osf_parser($shownotes)
  {

    $pattern['zeilen']    = '/((\d\d:\d\d:\d\d)(.\d\d\d)*)*(.+)/';
    $pattern['tags']      = '((#)(\S*))';
    $pattern['urls']      = '(\s+((http(|s)://\S{0,64})\s))';
    $pattern['urls2']     = '(\<((http(|s)://\S{0,64})>))';
    $pattern['kaskade']   = '/^(-+ )/';
    
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
        $newarray['tags'] = $tags[2];
        $newarray['urls'] = $urls;
        
        if(((in_array("Chapter", $newarray['tags']))||(in_array("chapter", $newarray['tags'])))&&($newarray['time'] != ''))
          {
            $newarray['chapter'] = true;
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

function osf_get_chapter_html($array)
  {
    $returnstring = '';
    foreach($array as $item)
      {
        if($item['chapter'])
          {
            $filterpattern = array('((#)(\S*))', '(\<((http(|s)://\S{0,64})>))', '(\s+((http(|s)://\S{0,64})\s))');
            $text = preg_replace($filterpattern, '', $item['text']);
            $returnstring .= '<span class="osf_time">'.$item['time'].'</span> '."\n";
            $returnstring .= '<span class="osf_text">'.$text.'</span> '."\n";
            $returnstring .= '<span class="osf_tags">';
            foreach($item['tags'] as $tag)
              {
                $returnstring .= '#'.$tag.' ';
              }
            $returnstring .= '</span> '."\n";
            $returnstring .= '<span class="osf_urls">';
            foreach($item['urls'] as $url)
              {
                $returnstring .= '<a href="'.$url.'">'.$url.'</a> ';
              }
            $returnstring .= '</span> '."\n";
          }
      }
    return $returnstring;
  }

function osf_export_psc($array)
  {
    $returnstring = '<!-- specify chapter information -->'."\n".'<sc:chapters version="1.0">'."\n";
    foreach($array as $item)
      {
        if($item['chapter'])
          {
            $filterpattern = array('((#)(\S*))', '(\<((http(|s)://\S{0,64})>))', '(\s+((http(|s)://\S{0,64})\s))');
            $text = preg_replace($filterpattern, '', $item['text']);
            $returnstring .= '<sc:chapter start="'.$item['time'].'" title="'.$text.'"';
            if(isset($item['urls'][0]))
              {
                $returnstring .= ' href="'.$item['urls'][0].'"';
              }
            $returnstring .= ' />'."\n";
          }
      }
    $returnstring .= '</sc:chapters>'."\n";
    return $returnstring;
  }

?>