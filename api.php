<?php

function ctcs_header($type)
  {
    $contenttype = 'Content-Type: '.$type.'; ';
    if(strpos($_SERVER["HTTP_ACCEPT_CHARSET"], 'utf-8'))
      {
        $contenttype .= 'charset=utf-8';
      }
    elseif(strpos($_SERVER["HTTP_ACCEPT_CHARSET"], 'ISO-8859-15'))
      {
        $contenttype .= 'charset=ISO-8859-15';
      }
    else
      {
        $contenttype .= 'charset=ISO-8859-1';
      }
    header($contenttype);
  }

$getpad = $_GET['pad'];
$mode = $_GET['mode'];

if(isset($_GET['dl']))
  {
    $dl = true;
  }
else
  {
    $dl = false;
  }

if(isset($_GET['url']))
  {
    $url = $_GET['url'];
  }
else
  {
    $url = '';
  }

if(($mode != '')&&($getpad != ''))
  {
    if(preg_match('(http(|s)://\S{0,128})', $getpad))
      {
        $pad = $getpad;
        $seccheck = 1;
      }
    else
      {
        $pad = 'https://shownotes.piratenpad.de/ep/pad/export/'.$getpad.'/latest?format=txt';
      }
    
    include "./osfregex.php";
    ini_set('allow_url_fopen', '1');
    $handle = fopen($pad, "rb");
    $content = '';
    while (!feof($handle))
      {
        $content .= fread($handle, 8192);
      }
    fclose($handle);
    
    $shownotes = osf_parser($content);
    
    if($_GET['mode'] == 'json')
      {
        ctcs_header("application/json");
        if($dl){header("Content-Disposition: attachment; filename=\"shownotes.json\"");}
        echo json_encode($shownotes);
      }
    elseif($_GET['mode'] == 'html')
      {
        include "./export/anycast.php";
        ctcs_header("text/html");
        if($dl){header("Content-Disposition: attachment; filename=\"shownotes.html\"");}
        echo osf_export_anycast($shownotes['export']);
      }
    elseif($_GET['mode'] == 'morehtml')
      {
        include "./export/anycast.php";
        ctcs_header("text/html");
        if($dl){header("Content-Disposition: attachment; filename=\"shownotes.html\"");}
        echo osf_export_anycast($shownotes['export'], true);
      }
    elseif($_GET['mode'] == 'stylehtml')
      {
        include "./export/anycast.php";
        ctcs_header("text/html");
        if($dl){header("Content-Disposition: attachment; filename=\"shownotes.html\"");}
        echo '<html><head><meta charset="utf-8"><title>Shownotes - '.$pad.'</title><link href="./css/anycast.css" rel="stylesheet" type="text/css" /></head><body>';
        echo osf_export_anycast($shownotes['export'], true);
        echo '</body></html>';
      }
    elseif($_POST['mode'] == 'wikigeeks')
      {
        include "./export/wikigeeks.php";
        ctcs_header("text/html");
        if($dl){header("Content-Disposition: attachment; filename=\"shownotes.html\"");}
        echo '<html><head><meta charset="utf-8"><title>Shownotes - '.$pad.'</title><link href="./css/anycast.css" rel="stylesheet" type="text/css" /></head><body>';
        echo osf_export_wikigeeks($shownotes['export'], 1);
        echo '</body></html>';
      }
    elseif($_POST['mode'] == 'wikigeeks-full')
      {
        include "./export/wikigeeks.php";
        ctcs_header("text/html");
        if($dl){header("Content-Disposition: attachment; filename=\"shownotes.html\"");}
        echo '<html><head><meta charset="utf-8"><title>Shownotes - '.$pad.'</title><link href="./css/anycast.css" rel="stylesheet" type="text/css" /></head><body>';
        echo osf_export_wikigeeks($shownotes['export'], 2);
        echo '</body></html>';
      }
    elseif($_GET['mode'] == 'metaebene')
      {
        include "./export/metaebene.php";
        ctcs_header("text/html");
        if($dl){header("Content-Disposition: attachment; filename=\"shownotes.html\"");}
        echo osf_export_metaebene($shownotes['export'], $url);
      }
    elseif($_GET['mode'] == 'psc')
      {
        include "./export/psc.php";
        ctcs_header("text/plain");
        if($dl){header("Content-Disposition: attachment; filename=\"shownotes.txt\"");}
        echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
        echo osf_export_psc($shownotes['export']);
      }
    elseif($_GET['mode'] == 'chapter')
      {
        include "./export/chapterlist.php";
        ctcs_header("text/plain");
        if($dl){header("Content-Disposition: attachment; filename=\"shownotes.txt\"");}
        echo osf_export_chapterlist($shownotes['export']);
      }
    else
      {
        ctcs_header("text/plain");
        if($dl){header("Content-Disposition: attachment; filename=\"shownotes.txt\"");}
        print_r($shownotes);
      }
  }
elseif($mode == '')
  {
    ctcs_header("text/html");
    echo '<p>please select export mode: ';
    echo '<a href="?mode=json&pad='.$getpad.'">JSON</a> <a href="?mode=json&dl&pad='.$getpad.'">&lang;DL&rang;</a>, ';
    echo '<a href="?mode=html&pad='.$getpad.'">anyca.st</a> <a href="?mode=html&dl&pad='.$getpad.'">&lang;DL&rang;</a>, ';
    echo '<a href="?mode=morehtml&pad='.$getpad.'">anyca.st (long)</a> <a href="?mode=morehtml&dl&pad='.$getpad.'">&lang;DL&rang;</a>, ';
    echo '<a href="?mode=stylehtml&pad='.$getpad.'">anyca.st (style)</a> <a href="?mode=stylehtml&dl&pad='.$getpad.'">&lang;DL&rang;</a>, ';
    echo '<a href="?mode=metaebene&pad='.$getpad.'">metaebene.me (long)</a> <a href="?mode=metaebene&dl&pad='.$getpad.'">&lang;DL&rang;</a>, ';
    echo '<a href="?mode=psc&pad='.$getpad.'">PSC</a> <a href="?mode=psc&dl&pad='.$getpad.'">&lang;DL&rang;</a>, ';
    echo '<a href="?mode=chapter&pad='.$getpad.'">Chapterlist</a> <a href="?mode=chapter&dl&pad='.$getpad.'">&lang;DL&rang;</a> or ';
    echo '<a href="?mode=PHP&pad='.$getpad.'">PHP</a> <a href="?mode=PHP&dl&pad='.$getpad.'">&lang;DL&rang;</a>, ';
  }

?>