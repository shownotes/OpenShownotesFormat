<?php

//Plaintext chapterlist export

function osf_export_chapterlist($array)
  {
    $returnstring = '';
    foreach($array as $item)
      {
        if($item['chapter'])
          {
            $filterpattern = array('((#)(\S*))', '(\<((http(|s)://\S{0,128})>))', '(\s+((http(|s)://\S{0,128})\s))');
            $text = preg_replace($filterpattern, '', $item['text']);

            if(strpos($item['time'], '.'))
              {
                $returnstring .= $item['time'].' '.$text."\n";
              }
            else
              {
                $returnstring .= $item['time'].'.000 '.$text."\n";
              }
          }
      }
    $returnstring = preg_replace('(\s+\n)', "\n", $returnstring);
    return $returnstring;
  }

?>