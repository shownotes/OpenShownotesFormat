<?php

function osf_glossarysort($a, $b)
  {
    $ax = str_split(strtolower(trim($a['text'])));
    $bx = str_split(strtolower(trim($b['text'])));
    
    if(count($ax) < count($bx))
      {
        for($i = 0; $i <= count($bx); $i++)
          {
            if(ord($ax[$i]) != ord($bx[$i]))
              {
                return (ord($ax[$i]) < ord($bx[$i])) ? -1 : 1;
              }
          }
      }
    else
      {
        for($i = 0; $i <= count($ax); $i++)
          {
            if(ord($ax[$i]) != ord($bx[$i]))
              {
                return (ord($ax[$i]) < ord($bx[$i])) ? -1 : 1;
              }
          }
      }
    return 0;
  }

//HTML export as glossary
function osf_export_glossary($array, $showtags = array(0 => ''))
  {
    $linksbytag = array();
    
    $filterpattern = array('(\s(#)(\S*))', '(\<((http(|s)://[\S#?-]{0,128})>))', '(\s+((http(|s)://[\S#?-]{0,128})\s))', '(^ *-*)');
    $arraykeys = array_keys($array);
    for($i = 0; $i <= count($array); $i++)
      {
        if(($array[$arraykeys[$i]]['chapter'])||(($full!=false)&&($array[$arraykeys[$i]]['time'] != '')))
          {
            if(isset($array[$arraykeys[$i]]['subitems']))
              {
                for($ii = 0; $ii <= count($array[$arraykeys[$i]]['subitems']); $ii++)
                  {
                    if(($array[$arraykeys[$i]]['subitems'][$ii]['urls'][0] != '')&&($array[$arraykeys[$i]]['subitems'][$ii]['text'] != ''))
                      {
                        foreach($array[$arraykeys[$i]]['subitems'][$ii]['tags'] as $tag)
                          {
                            if(($showtags[0] == '')||(array_search($tag, $showtags) !== false))
                              {
                                $linksbytag[$tag][$ii]['url'] = $array[$arraykeys[$i]]['subitems'][$ii]['urls'][0];
                                $linksbytag[$tag][$ii]['text'] = $array[$arraykeys[$i]]['subitems'][$ii]['text'];
                              }
                          }
                      }
                  }
              }
          }
      }
    
    $return = '';
    
    foreach($linksbytag as $tagname => $content)
      {
        $return .= '<h1>'.$tagname.'</h1>';
        $return .= '<ol>';
        usort($content, "osf_glossarysort");
        foreach($content as $item)
          {
            $return .= '<li><a href="'.$item['url'].'">'.$item['text'].'</a></li>';
          }
        $return .= '</ol>';
      }

    return $return;
  }

?>