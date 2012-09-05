<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Open Shownotes Format</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    
</head>
<body>

<?php

if(!isset($_GET['podcast']))
  {
    $Podcastverzeichnis = './Beispiele/';
    $Podcastliste       = scandir($Podcastverzeichnis);
    
    echo '<table><tr><td>Datei</td><td>plaintext</td><td>json</td><td>var_dump()</td><td>html</td><td>OSF-Class</td></tr>';
    foreach($Podcastliste as $Podcast)
      {
        if(($Podcast != '.')&&($Podcast != '..'))
          {
            echo '<tr>';
            echo '<td>'.$Podcast.'</td>';
            echo '<td><a href="'.$Podcastverzeichnis.$Podcast.'">link</a></td>';
            echo '<td><a href="?podcast='.$Podcastverzeichnis.$Podcast.'&mode=json">link</a></td>';
            echo '<td><a href="?podcast='.$Podcastverzeichnis.$Podcast.'">link</a></td>';
            echo '<td><a href="?podcast='.$Podcastverzeichnis.$Podcast.'&mode=html">link</a></td>';
            echo '<td><a href="?podcast='.$Podcastverzeichnis.$Podcast.'&mode=osfc">link</a></td>';
            echo '</tr>';
          }
      }
    echo '</table>';
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
        echo json_encode($shownotes['export']);
      }
    elseif($_GET['mode'] == 'xml')
      {
        echo 'sorry, xml-export is currently not working';
        //$xml = new SimpleXMLElement('<root/>');
        //array_walk_recursive($shownotes, array ($xml, 'addChild'));
        //print $xml->asXML();
      }
    elseif($_GET['mode'] == 'html')
      {
        echo osf_get_chapter($shownotes['export']);
      }
    elseif($_GET['mode'] == 'osfc')
      {
        include "./OpenShownotesClass.php";
        $sn = new Shownotes($content);
        print_r($sn->items);
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
