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

function osf_export_wikigeeks($array, $full=false, $filtertags=array(0 => 'spoiler'))
  {
    $filtertags = array('spoiler', 'trash');
    $returnstring = '<div class="osf_wikigeeks">';
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
            
            if(isset($item['urls'][0]))
              {
                $returnstring .= '<div><h1><a href="'.$item['urls'][0].'">'.$text.'</a> ['.$time.']</h1></div><ul>';
              }
            else
              {
                $returnstring .= '<div><h1>'.$text.' ['.$time.']</h1></div><ul>';
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
                        
                        $text = preg_replace($filterpattern, '', $subitem['text']);
                        if($subitemi)
                          {
                            $subtext = '';
                          }
                        else
                          {
                            $subtext = '';
                          }
                        if(isset($subitem['urls'][0]))
                          {
                            $subtext .= '<li><a href="'.$subitem['urls'][0].'"';
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
                            $subtext .= '>'.trim($text).'</a></li>'." ";
                          }
                        else
                          {
                            $subtext .= '<li><span';
                            if($hide != '')
                              {
                                $subtext .= ' class="'.$hide.'"';
                              }
                            if((isset($subitem['time']))&&(trim($subitem['time']) != ''))
                              {
                                $subtext .= ' data-tooltip="'.$subitem['time'].'"';
                              }
                            $subtext .= '>'.trim($text).'</span></li> ';
                          }
                        //$subtext = str_replace("\n, ", ", ", $subtext);
                        $subtext = trim($subtext);
                        $returnstring .= $subtext;
                        ++$subitemi;
                      }
                  }
              }
            $returnstring .= '</ul>';
          }
      }

    $returnstring .= '</div>'."\n";
    return $returnstring;
  }

?>