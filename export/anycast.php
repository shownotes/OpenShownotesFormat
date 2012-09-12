<?php

//HTML export im anyca.st style

function osf_export_anycast($array, $full=false)
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
            
            if(($item['chapter'])&&($full)&&($time != '')&&($time != '00:00:00'))
              {
                $returnstring .= '<hr>';
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

?>