<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Open Shownotes Format</title>
    <link href="./css/style.css" rel="stylesheet" type="text/css">
    <link href="./css/anycast.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="./reqwest/reqwest.js"></script>
    <script type="text/javascript" src="./js/script.js"></script>
</head>
<body>

<?php

if(!isset($_GET['podcast']))
  {
    $Podcastverzeichnis = './Beispiele/';
    $Podcastliste       = scandir($Podcastverzeichnis);
    
    echo '<div id="boxc"><h1>OSF Parser Demo</h1><p>Dies ist eine Testinstallation der <a href="https://github.com/SimonWaldherr/OpenShownotesFormat">Open Shownotes Format Parser</a> Referenzimplementation</p><p>Sie können eine der folgenden Testdateien verwenden (durch einen Klick auf den Dateinamen wird die Datei in das Textfeld geladen) oder selbst Text in das Textfeld schreiben/kopieren.</p><p>Anschliessend muss nur noch der Export-Modus gewählt und durch das Klicken des Absende-Buttons der Vorgang gestartet werden.</p><p>Der Modus PSC exportiert die Shownotes im <a href="http://podlove.org/simple-chapters/">Podlove Simple Chapter Format</a>, die beiden anycast Modi exportieren HTML im Format des <a href="http://anyca.st/">Anyca.st-Podcasts</a>, der Modus <a href="http://metaebene.me/">Metaebene</a> exportiert eine HTML-Tabelle, wie sie auch vom <a href="http://podlove.org/podlove-wordpress-plugin/">Podlove Wordpress Plugin</a> exportiert wird, JSON exportiert die gesammten Shownotes in <a href="http://www.json.org/">JSON</a> und der chapter Export-Modus exportiert eine Plaintextliste der Kapitel.</p><ul>';
    foreach($Podcastliste as $Podcast)
      {
        if(($Podcast != '.')&&($Podcast != '..'))
          {
            echo '<li><a href="#" onclick="getpad(\''.$Podcastverzeichnis.$Podcast.'\')">'.$Podcast.'</a></li>';
          }
      }
    echo '</ul><hr><form action="./form.php?mode=textarea" method="POST"><textarea name="shownote" style="min-height:100px;" id="textpadarea"></textarea><br><select name="mode">
  <option>PSC</option>
  <option>anycast</option>
  <option>anycast-long</option>
  <option>anycast-full</option>
  <option>metaebene</option>
  <option>JSON</option>
  <option>chapter</option>
  <option>PHP</option>
</select><input type="submit" value=" Absenden "></form></div>';

    echo '<div id="info">mehr Informationen gibt es im zugeh&ouml;rigen <a href="https://github.com/SimonWaldherr/OpenShownotesFormat">GitHub Repo</a>. <br>&sup1;) PSC = Podlove Simple Chapters, mehr informationen dazu gibt es auf <a href="http://podlove.org/simple-chapters/">podlove.org</a>.';
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
        include "./export/anycast.php";
        echo '<div class="anycaststyle">'.osf_export_anycast($shownotes['export']).'</div>';
        $timer['osf_get_chapter_html'] = microtime(1)-$starttime;
      }
    elseif($_GET['mode'] == 'morehtml')
    {
      include "./export/anycast.php";
      echo '<div class="anycaststyle">'.osf_export_anycast($shownotes['export'], 2).'</div>';
      $timer['osf_get_chapter_morehtml'] = microtime(1)-$starttime;
    }
    elseif($_GET['mode'] == 'psc')
      {
        include "./export/psc.php";
        echo '<textarea>'.osf_export_psc($shownotes['export']).'</textarea>';
        $timer['osf_export_psc'] = microtime(1)-$starttime;
      }
    elseif($_GET['mode'] == 'chapter')
      {
        include "./export/chapterlist.php";
        echo '<form action="./form.php?mode=download" method="POST"><textarea name="download" style="min-height:300px;">'.osf_export_chapterlist($shownotes['export']).'</textarea><input type="submit" value=" Download "></form>';
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
        echo '<textarea>';
        print_r($shownotes);
        echo '</textarea>';
        $timer['var_dump'] = microtime(1)-$starttime;
      }
    
    echo '<!--';
    var_dump($timer);
    echo '-->';
  }
?>
<script type='text/javascript'>

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-12565471-1']);
  _gaq.push(['_setDomainName', 'waldherr.eu']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = 'http://statistik.simon.waldherr.eu/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>
