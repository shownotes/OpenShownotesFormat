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
    
    echo '<table><tr><td>Datei</td><td>plaintext</td><td>json</td><td>var_dump()</td><td>html</td><td>PSC&sup1;</td><td>OSF-Class</td></tr>';
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
            echo '<td><a href="?podcast='.$Podcastverzeichnis.$Podcast.'&mode=psc">link</a></td>';
            echo '<td><a href="?podcast='.$Podcastverzeichnis.$Podcast.'&mode=osfc">link</a></td>';
            echo '</tr>';
          }
      }
    //echo '<tr><th colspan="7"><form action="./?mode=textarea" method="POST"><textarea></textarea></th><br><input type="submit" value=" Absenden "></tr>';
    echo '</table><div id="info">mehr Informationen gibt es im zugeh&ouml;rigen <a href="https://github.com/SimonWaldherr/OpenShownotesFormat">GitHub Repo</a>. <br>&sup1;) PSC = Podlove Simple Chapters, mehr informationen dazu gibt es auf <a href="http://podlove.org/simple-chapters/">podlove.org</a>.';
  }
else
  {
    include "./osfregex.php";
    $Shownotedatei = $_GET['podcast'];
    $handle = fopen($Shownotedatei, "r");
    $content = fread($handle, filesize($Shownotedatei));
    fclose($handle);
    
    $starttime = microtime(1);
    $shownotes = osf_parser($content);
    $timer['osf_parser'] = microtime(1)-$starttime;
    if($_GET['mode'] == 'json')
      {
        echo '<textarea>'.json_encode($shownotes['export']).'</textarea>';
        $timer['json_encode'] = microtime(1)-$starttime;
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
        echo osf_get_chapter_html($shownotes['export']);
        $timer['osf_get_chapter_html'] = microtime(1)-$starttime;
      }
    elseif($_GET['mode'] == 'psc')
      {
        echo '<textarea>'.osf_export_psc($shownotes['export']).'</textarea>';
        $timer['osf_export_psc'] = microtime(1)-$starttime;
      }
    elseif($_GET['mode'] == 'osfc')
      {
        include "./OpenShownotesClass.php";
        $starttime_osc = microtime(1);
        $sn = new Shownotes($content);
        echo '<textarea>';
        print_r($sn->items);
        echo '</textarea>';
        $timer['osc'] = microtime(1)-$starttime_osc;
      }
    else
      {
        ob_start();
        var_dump($shownotes);
        $buffer = ob_get_clean();
        $buffer = nl2br(str_replace(' ', '&nbsp;', $buffer));
        echo $buffer;
        //echo nl2br($buffer);
        $timer['var_dump'] = microtime(1)-$starttime;
      }
    
    echo '<!--';
    var_dump($timer);
    echo '-->';
  }
?>

</body>
</html>
