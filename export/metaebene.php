<?php

//HTML export im metaebene.me style

function osf_export_metaebene($array, $url='')
  {
    $starttime = '00:00:00';
    $returnstring = '<table rel="wp_pwp_1" class="pwp_chapters linked" style="display: table; "><caption>Podcast Chapters</caption><thead><tr><th scope="col">Timecode</th><th scope="col">Title</th></tr></thead><tbody>'."\n";
    foreach($array as $item)
      {
        if(($item['chapter'])&&($item['time'] != ''))
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
            
            if((isset($starttime))&&(isset($chaptitel)))
              {
                $returnstring .= '<tr data-start="'.osf_convert_time($starttime).'.0" data-end="'.osf_convert_time($time).'.0"><td class="timecode"><code>'.$starttime.'</code></td><td class="title"><a href="'.$url.'#t='.$starttime.','.$time.'">'.$chaptitel.'</a></td></tr><!-- Chapter length = '.(osf_convert_time($time)-osf_convert_time($starttime)).' -->'."\n";
              }
            
            $starttime = $time;
            $chaptitel = $text;
          }
      }
    $returnstring .= '</tbody></table>'."\n";
    return $returnstring;
  }

?>