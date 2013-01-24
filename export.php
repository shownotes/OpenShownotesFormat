<?php

if($_POST['shownotes'] == '')
  {
    include("../etherconnect/bootstrap.inc.php");
    include("../etherconnect/common.inc.php");
    include("../etherconnect/config.inc.php");
    include("../etherconnect/include.php");
    
    $pad = fetch_recent_pads($base, $email, $password, $check_public, $time);
  }
else
  {
    $pad = $_POST['shownotes'];
  }

$mode = $_POST['exportmode'];

$dl = false;

if($_GET['mode'] == 'download')
  {
    $dl = true;
  }


//print_r($pad);

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

if(($mode != '')&&($pad != ''))
  {
    include('./osfregex.php');
    
    $amazon       = $_POST['amazon'];
    $thomann      = $_POST['thomann'];
    $fullmode     = $_POST['fullmode'];
    $tradedoubler = $_POST['tradedoubler'];
    
    $shownotes = osf_parser($pad, array('amazon'       => $amazon
                                       ,'thomann'      => $thomann
                                       ,'tradedoubler' => $tradedoubler
                                       ,'fullmode'     => $fullmode
                                       ,'tags'         => explode(' ', $_POST['tags'])));
    
    ob_start();
    
    if($_POST['exportmode'] == 'json')
      {
        ctcs_header("application/json");
        if($dl){header("Content-Disposition: attachment; filename=\"shownotes.json\"");}
        echo json_encode($shownotes);
      }
    elseif($_POST['exportmode'] == 'anycast')
      {
        include "./export/anycast.php";
        ctcs_header("text/html");
        if($dl){header("Content-Disposition: attachment; filename=\"shownotes.html\"");}
        echo osf_export_anycast($shownotes['export'], 1);
      }
    elseif($_POST['exportmode'] == 'anycast-full')
      {
        include "./export/anycast.php";
        ctcs_header("text/html");
        if($dl){header("Content-Disposition: attachment; filename=\"shownotes.html\"");}
        echo osf_export_anycast($shownotes['export'], 2);
      }
    elseif($_POST['exportmode'] == 'metacast')
      {
        include "./export/metacast.php";
        ctcs_header("text/html");
        if($dl){header("Content-Disposition: attachment; filename=\"shownotes.html\"");}
        echo osf_export_anycast($shownotes['export'], 2);
      }
    elseif($_POST['exportmode'] == 'wikigeeks')
      {
        include "./export/wikigeeks.php";
        ctcs_header("text/html");
        if($dl){header("Content-Disposition: attachment; filename=\"shownotes.html\"");}
        echo '<html><head><meta charset="utf-8"><title>Shownotes - '.$pad.'</title><link href="./css/anycast.css" rel="stylesheet" type="text/css" /></head><body>';
        echo osf_export_wikigeeks($shownotes['export'], 2);
        echo '</body></html>';
      }
    elseif($_POST['exportmode'] == 'chapter')
      {
        include "./export/chapterlist.php";
        ctcs_header("text/plain");
        if($dl){header("Content-Disposition: attachment; filename=\"shownotes.txt\"");}
        echo osf_export_chapterlist($shownotes['export']);
      }
    elseif($_POST['exportmode'] == 'glossary')
      {
        include "./export/glossary.php";
        ctcs_header("text/plain");
        if($dl){header("Content-Disposition: attachment; filename=\"shownotes.txt\"");}
        echo osf_export_glossary($shownotes['export'], array(0=>'glossary'));
      }
    elseif($_POST['exportmode'] == 'print_r')
      {
        ctcs_header("text/plain");
        if($dl){header("Content-Disposition: attachment; filename=\"shownotes.txt\"");}
        echo print_r($shownotes);
      }
    else
      {
        ctcs_header("text/plain");
        if($dl){header("Content-Disposition: attachment; filename=\"shownotes.txt\"");}
        print_r($shownotes);
      }
  }

if($dl)
  {
    $fileid = hash("sha256", rand(0, 9999999).time().rand(0,9999));
    
    mkdir('./archive/'.$fileid.'/', $mode=0777);
    file_put_contents('./archive/'.$fileid.'/shownotes.txt', ob_get_clean());
    
    echo $fileid;
  }
else {
  echo ob_get_clean();
}

?>
