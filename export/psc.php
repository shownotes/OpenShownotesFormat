<?php

//Podlove Simple Chapter export

function osf_export_psc($array)
  {
    $returnstring = '<!-- specify chapter information -->'."\n".'<sc:chapters version="1.0">'."\n";
    foreach($array as $item)
      {
        if($item['chapter'])
          {
            $filterpattern = array('((#)(\S*))', '(\<((http(|s)://[\S#?-]{0,128})>))', '(\s+((http(|s)://[\S#?-]{0,128})\s))');
            $text = trim(preg_replace($filterpattern, '', $item['text']));
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

?>