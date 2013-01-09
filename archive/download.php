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


ctcs_header("text/plain");
header("Content-Disposition: attachment; filename=\"shownotes-".$_GET['spad'].'-'.$_GET['smode'].".txt\"");
$file = $_GET['get'];
readfile('./'.$file.'/shownotes.txt');
?>