<?php

//HTML export im anyca.st style

function osf_export_anycast($array, $full=false)
  {
    $returnstring = '<dl>';
    $filterpattern = array('(\s(#)(\S*))', '(\<((http(|s)://[\S#?-]{0,128})>))', '(\s+((http(|s)://[\S#?-]{0,128})\s))', '(^ *-*)');
    foreach($array as $item)
      {
        if(($item['chapter'])||(($full)&&($item['time'] != '')))
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

            if(($item['chapter'])&&($full)&&($time != '')&&($time != '00:00:00'))
              {
                $returnstring .= '<hr>';
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
                    if(($full)||(!$subitem['subtext']))
                      {
                        $text = preg_replace($filterpattern, '', $subitem['text']);
                        if($subitemi)
                          {
                            $subtext = ', ';
                          }
                        else
                          {
                            $subtext = '<br>';
                          }
                        if(isset($subitem['urls'][0]))
                          {
                            $subtext .= '<a href="'.$subitem['urls'][0].'"';
                            if(strstr($subitem['urls'][0], 'wikipedia.org/wiki/'))
                              {
                                $subtext .= ' class="osf_wiki"';
                              }
                            elseif(strstr($subitem['urls'][0], 'www.amazon.'))
                              {
                                $subtext .= ' class="osf_amazon"';
                              }
                            elseif(strstr($subitem['urls'][0], 'www.youtube.com/'))
                              {
                                $subtext .= ' class="osf_youtube"';
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