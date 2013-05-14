<?php

function osf_checktags($needles, $haystack)
  {
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

function osf_metacast_textgen($subitem, $tagtext, $text)
  {
    $subtext = '';
    if(isset($subitem['urls'][0]))
      {
        $tagtext .= ' osf_link';
        $url = parse_url($subitem['urls'][0]);
        $url = explode('.', $url['host']);
        $tagtext .= ' osf_'.$url[count($url)-2].$url[count($url)-1];
        $subtext .= '<a href="'.$subitem['urls'][0].'"';
        if(strstr($subitem['urls'][0], 'wikipedia.org/wiki/'))
          {
            $subtext .= ' class="osf_wiki '.$tagtext.'"';
          }
        elseif(strstr($subitem['urls'][0], 'www.amazon.'))
          {
            $subtext .= ' class="osf_amazon '.$tagtext.'"';
          }
        elseif(strstr($subitem['urls'][0], 'www.youtube.com/')||($subitem['chapter'] == 'video'))
          {
            $subtext .= ' class="osf_youtube '.$tagtext.'"';
          }
        elseif(strstr($subitem['urls'][0], 'flattr.com/'))
          {
            $subtext .= ' class="osf_flattr '.$tagtext.'"';
          }
        elseif(strstr($subitem['urls'][0], 'twitter.com/'))
          {
            $subtext .= ' class="osf_twitter '.$tagtext.'"';
          }
        else
          {
            $subtext .= ' class="'.$tagtext.'"';
          }
        
        if((isset($subitem['time']))&&(trim($subitem['time']) != ''))
          {
            $subtext .= ' data-tooltip="'.$subitem['time'].'"';
          }
        $subtext .= '>'.trim($text).'</a>'." ";
      }
    else
      {
        $subtext .= '<span';
        if($tagtext != '')
          {
            $subtext .= ' class="'.$tagtext.'"';
          }
        if((isset($subitem['time']))&&(trim($subitem['time']) != ''))
          {
            $subtext .= ' data-tooltip="'.$subitem['time'].'"';
          }
        $subtext .= '>'.trim($text).'</span> ';
      }
    return $subtext;
  }

//HTML export im anyca.st style

function osf_export_anycast($array, $full=false, $filtertags=array(0 => 'spoiler'))
  {
    $returnstring = '<dl>';
    $filterpattern = array('(\s(#)(\S*))', '(\<((http(|s)://[\S#?-]{0,128})>))', '(\s+((http(|s)://[\S#?-]{0,128})\s))', '(^ *-*)');
    $arraykeys = array_keys($array);
    for($i = 0; $i <= count($array); $i++)
      {
        if(($array[$arraykeys[$i]]['chapter'])||(($full!=false)&&($array[$arraykeys[$i]]['time'] != '')))
          {
            $text = preg_replace($filterpattern, '', $array[$arraykeys[$i]]['text']);
            if(strpos($array[$arraykeys[$i]]['time'], '.'))
              {
                $time = explode('.', $array[$arraykeys[$i]]['time']);
                $time = $time[0];
              }
            else
              {
                $time = $array[$arraykeys[$i]]['time'];
              }
            
            if(($array[$arraykeys[$i]]['chapter'])&&($full!=false)&&($time != '')&&($time != '00:00:00'))
              {
                $returnstring .= ''; //add code, which should inserted between chapters
              }
            
            $returnstring .= '<dt data-time="'.osf_convert_time($time).'">'.$time.'</dt>'."\n".'<dd>';
            if(isset($array[$arraykeys[$i]]['urls'][0]))
              {
                $returnstring .= '<strong';
                if(($array[$arraykeys[$i]]['chapter'])&&($time != ''))
                  {
                    $returnstring .= ' class="osf_chapter"';
                  }
                $returnstring .= '><a href="'.$array[$arraykeys[$i]]['urls'][0].'">'.$text.'</a></strong><div class="osf_items"> '."\n";
              }
            else
              {
                $returnstring .= '<strong';
                if(($array[$arraykeys[$i]]['chapter'])&&($time != ''))
                  {
                    $returnstring .= ' class="osf_chapter"';
                  }
                $returnstring .= '>'.$text.'</strong><div class="osf_items"> '."\n";
              }
            if(isset($array[$arraykeys[$i]]['subitems']))
              {
                for($ii = 0; $ii <= count($array[$arraykeys[$i]]['subitems']); $ii++)
                  {
                    if(((($full!=false)||(!$array[$arraykeys[$i]]['subitems'][$ii]['subtext']))&&((($full==1)&&(!osf_checktags($filtertags, $array[$arraykeys[$i]]['subitems'][$ii]['tags'])))||($full==2)))&&(strlen(trim($array[$arraykeys[$i]]['subitems'][$ii]['text']))>2))
                      {
                        if(($full==2)&&(osf_checktags($filtertags, $array[$arraykeys[$i]]['subitems'][$ii]['tags'])))
                          {
                            $tagtext = ' osf_spoiler';
                          }
                        else
                          {
                            $tagtext = '';
                          }
                        
                        if($array[$arraykeys[$i]]['subitems'][$ii]['subtext'])
                          {
                            if(!$array[$arraykeys[$i]]['subitems'][$ii-1]['subtext'])
                              {
                                $tagtext .= ' osf_substart';
                              }
                            if(!$array[$arraykeys[$i]]['subitems'][$ii+1]['subtext'])
                              {
                                $tagtext .= ' osf_subend';
                              }
                          }
                        
                        foreach($array[$arraykeys[$i]]['subitems'][$ii]['tags'] as $tag)
                          {
                            $tagtext .= ' osf_'.$tag;
                          }
                        
                        $text = preg_replace($filterpattern, '', $array[$arraykeys[$i]]['subitems'][$ii]['text']);
                        $subtext = osf_metacast_textgen($array[$arraykeys[$i]]['subitems'][$ii], $tagtext, $text);
                        
                        $subtext = trim($subtext);
                        $returnstring .= $subtext;
                        //++$array[$arraykeys[$i]]['subitems'][$ii]i;
                      }
                  }
              }
            $returnstring .= '</div></dd>';
          }
      }
    
    $returnstring .= '</dl>'."\n";
    return str_replace(',</dd>', '</dd>', $returnstring);
  }

?>