<?php

$getpad = $_GET['pad'];
$mode = $_GET['mode'];

if(($mode != '')&&($getpad != ''))
  {
    if(preg_match('(http(|s)://\S{0,128})', $getpad))
      {
        $pad = $getpad;
        $seccheck = 1;
      }
    else
      {
        $pad  = 'https://shownotes.piratenpad.de/ep/pad/export/'.$getpad.'/latest?format=txt';
      }
    
    include "./osfregex.php";
    ini_set('allow_url_fopen', '1');
    $handle = fopen($pad, "rb");
    $content = '';
    while (!feof($handle)) {
      $content .= fread($handle, 8192);
    }
    fclose($handle);

    $shownotes = osf_parser($content);

    if($_GET['mode'] == 'json')
      {
        header("Content-Type: application/json");
        echo json_encode($shownotes);
      }
    elseif($_GET['mode'] == 'html')
      {
        include "./export/anycast.php";
        header("Content-Type: text/html");
        echo osf_export_anycast($shownotes['export']);
      }
    elseif($_GET['mode'] == 'morehtml')
      {
        include "./export/anycast.php";
        header("Content-Type: text/html");
        echo osf_export_anycast($shownotes['export'], true);
      }
    elseif($_GET['mode'] == 'psc')
      {
        include "./export/psc.php";
        header("Content-Type: text/plain");
        echo osf_export_psc($shownotes['export']);
      }
    elseif($_GET['mode'] == 'chapter')
      {
        include "./export/chapterlist.php";
        header("Content-Type: text/plain");
        echo osf_export_chapterlist($shownotes['export']);
      }
    else
      {
        header("Content-Type: text/plain");
        print_r($shownotes);
      }
  }
elseif($mode == '')
  {
    header("Content-Type: text/html");
    echo '<p>please select export mode: ';
    echo '<a href="?mode=json&pad='.$getpad.'">JSON</a>, ';
    echo '<a href="?mode=html&pad='.$getpad.'">HTML</a>, ';
    echo '<a href="?mode=morehtml&pad='.$getpad.'">HTML (long)</a>, ';
    echo '<a href="?mode=psc&pad='.$getpad.'">PSC</a>, ';
    echo '<a href="?mode=chapter&pad='.$getpad.'">Chapterlist</a> or ';
    echo '<a href="?mode=PHP&pad='.$getpad.'">PHP</a>, ';
  }

?>