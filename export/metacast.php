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
                    $returnstring .= ' class="chapter"';
                  }
                $returnstring .= '><a href="'.$item['urls'][0].'">'.$text.'</a></strong> '."\n";
              }
            else
              {
                $returnstring .= '<strong';
                if(($item['chapter'])&&($time != ''))
                  {
                    $returnstring .= ' class="chapter"';
                  }
                $returnstring .= '>'.$text.'</strong> '."\n";
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
                            $hide = ' osf_spoiler';
                          }
                        else
                          {
                            $hide = '';
                          }
                        
                        foreach($subitem['tags'] as $tag)
                          {
                            $hide .= ' osf_'.$tag;
                          }
                        
                        $text = preg_replace($filterpattern, '', $subitem['text']);
                        if($subitemi)
                          {
                            $subtext = '; ';
                          }
                        else
                          {
                            $subtext = '';
                          }
                          
                        if((isset($subitem['time']))&&(trim($subitem['time']) != ''))
                          {
                            $subtext .= '<a href="#t='.$subitem['time'].'" class="time_button"></a>';
                          }
                          
                        if(isset($subitem['urls'][0]))
                          {
                            $subtext .= '<a href="'.$subitem['urls'][0].'"';
                            if(strstr($subitem['urls'][0], 'wikipedia.org/wiki/'))
                              {
                                $subtext .= ' class="osf_wiki '.$hide.'"';
                              }
                            elseif(strstr($subitem['urls'][0], 'www.amazon.'))
                              {
                                $subtext .= ' class="osf_amazon '.$hide.'"';
                              }
                            elseif(strstr($subitem['urls'][0], 'www.youtube.com/')||($subitem['chapter'] == 'video'))
                              {
                                $subtext .= ' class="osf_youtube '.$hide.'"';
                              }
                            elseif(strstr($subitem['urls'][0], 'flattr.com/'))
                              {
                                $subtext .= ' class="osf_flattr '.$hide.'"';
                              }
                            elseif(strstr($subitem['urls'][0], 'twitter.com/'))
                              {
                                $subtext .= ' class="osf_twitter '.$hide.'"';
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
                            if($hide != '')
                              {
                                $subtext .= ' class="'.$hide.'"';
                              }
                            
                            if((isset($subitem['time']))&&(trim($subitem['time']) != ''))
                              {
                                $subtext .= ' data-tooltip="'.$subitem['time'].'"';
                              }
                            
                            $subtext .= '>'.trim($text).'</span> ';
                          }
                        //$subtext = str_replace("\n, ", ", ", $subtext);
                        $subtext = trim($subtext);
                        $returnstring .= $subtext;
                        ++$subitemi;
                      }
                  }
              }
            $returnstring .= '</dd>';
          }
      }

    $returnstring .= '</dl>'."\n";
    return str_replace(',</dd>', '</dd>', $returnstring);
  }

?>