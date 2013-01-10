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
    foreach($array as $item)
      {
        if(($item['chapter'])||(($full!=false)&&($item['time'] != '')))
          {
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
            
            if(($item['chapter'])&&($full!=false)&&($time != '')&&($time != '00:00:00'))
              {
                $returnstring .= ''; //add code, which should inserted between chapters
              }
            
            $returnstring .= '<dt data-time="'.osf_convert_time($time).'">'.$time.'</dt>'."\n".'<dd>';
            if(isset($item['urls'][0]))
              {
                $returnstring .= '<strong';
                if(($item['chapter'])&&($time != ''))
                  {
                    $returnstring .= ' class="osf_chapter"';
                  }
                $returnstring .= '><a href="'.$item['urls'][0].'">'.$text.'</a></strong><div class="osf_items"> '."\n";
              }
            else
              {
                $returnstring .= '<strong';
                if(($item['chapter'])&&($time != ''))
                  {
                    $returnstring .= ' class="osf_chapter"';
                  }
                $returnstring .= '>'.$text.'</strong><div class="osf_items"> '."\n";
              }
            if(isset($item['subitems']))
              {
                $subitemi = 0;
                foreach($item['subitems'] as $subitem)
                  {
                    if(((($full!=false)||(!$subitem['subtext']))&&((($full==1)&&(!osf_checktags($filtertags, $subitem['tags'])))||($full==2)))&&(strlen(trim($subitem['text']))>2))
                      {
                        if(($full==2)&&(osf_checktags($filtertags, $subitem['tags'])))
                          {
                            $tagtext = ' osf_spoiler';
                          }
                        else
                          {
                            $tagtext = '';
                          }
                        
                        foreach($subitem['tags'] as $tag)
                          {
                            $tagtext .= ' osf_'.$tag;
                          }
                        
                        $text = preg_replace($filterpattern, '', $subitem['text']);
                        $subtext = osf_metacast_textgen($subitem, $tagtext, $text);
                        
                        $subtext = trim($subtext);
                        $returnstring .= $subtext;
                        ++$subitemi;
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