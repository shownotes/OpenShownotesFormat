<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    
    <link href="style.css" rel="stylesheet" type="text/css">
    
</head>
<body>

<?php

if(!isset($_GET['podcast']))
  {
    $Podcastverzeichnis = './Beispiele/';
    $Podcastliste       = scandir($Podcastverzeichnis);
    
    echo '<ul>';
    foreach($Podcastliste as $Podcast)
      {
        if(($Podcast != '.')&&($Podcast != '..'))
          {
            echo '<li>open '.$Podcast.' as <a href="?podcast='.$Podcastverzeichnis.$Podcast.'&mode=json">json</a>, <a href="?podcast='.$Podcastverzeichnis.$Podcast.'&mode=xml">xml</a> or via <a href="?podcast='.$Podcastverzeichnis.$Podcast.'">php var_dump()</a></li>'."\n";
          }
      }
    echo '</ul>';
  }
else
  {
    include "./osfregex.php";
    $Shownotedatei = $_GET['podcast'];
    $handle = fopen($Shownotedatei, "r");
    $content = fread($handle, filesize($Shownotedatei));
    fclose($handle);
    $shownotes = osf_parser($content);
    
    if($_GET['mode'] == 'json')
      {
        echo json_encode($shownotes);
      }
    elseif($_GET['mode'] == 'xml')
      {
        echo 'sorry, xml-export is currently not working';
        //$xml = new SimpleXMLElement('<root/>');
        //array_walk_recursive($shownotes, array ($xml, 'addChild'));
        //print $xml->asXML();
      }
    else
      {
        ob_start();
        var_dump($shownotes);
        $buffer = ob_get_clean();
        $buffer = nl2br(str_replace(' ', '&nbsp;', $buffer));
        echo $buffer;
        //echo nl2br($buffer);
      }
  }
?>

</body>
</html>
