<?php

if($_GET['mode'] == 'download')
  {
    header("Content-Type: text/plain");
    header("Content-Disposition: attachment; filename=\"shownotes.txt\"");
    echo $_POST['download'];
    die();
  }

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Open Shownotes Format</title>
    <link href="./css/style.css" rel="stylesheet" type="text/css">
    <link href="./css/anycast.css" rel="stylesheet" type="text/css">

</head>
<body>

<?php

echo '<form action="./form.php?mode=textarea" method="POST"><textarea name="shownote" style="min-height:300px;">'.$_POST['shownote'].'</textarea><br><select name="mode">
  <option>PSC</option>
  <option>anycast</option>
  <option>anycast-long</option>
  <option>metaebene</option>
  <option>JSON</option>
  <option>chapter</option>
</select><input type="submit" value=" Absenden "></form><br><br><hr><br><br>';

if((!isset($_GET['mode']))||(!isset($_POST['shownote']))||(!isset($_POST['mode'])))
  {
    echo 'error';
    die('error');
  }

include "./osfregex.php";

$content = $_POST['shownote'];

$shownotes = osf_parser($content);

if($_POST['mode'] == 'JSON')
  {
    echo '<textarea>'.json_encode($shownotes['export']).'</textarea>';
  }
elseif($_POST['mode'] == 'XML')
  {
    echo 'sorry, xml-export is currently not working';
  }
elseif($_POST['mode'] == 'anycast')
  {
    include "./export/anycast.php";
    echo '<div class="anycaststyle">'.osf_export_anycast($shownotes['export']).'</div>';
  }
elseif($_POST['mode'] == 'anycast-long')
  {
    include "./export/anycast.php";
    echo '<div class="anycaststyle">'.osf_export_anycast($shownotes['export'], true).'</div>';
  }
elseif($_POST['mode'] == 'PSC')
  {
    include "./export/psc.php";
    echo '<textarea>'.osf_export_psc($shownotes['export']).'</textarea>';
  }
elseif($_POST['mode'] == 'metaebene')
  {
    include "./export/metaebene.php";
    echo osf_export_metaebene($shownotes['export']);
  }
elseif($_POST['mode'] == 'chapter')
  {
    include "./export/chapterlist.php";
    echo '<form action="./form.php?mode=download" method="POST"><textarea name="download" style="min-height:300px;">'.osf_export_chapterlist($shownotes['export']).'</textarea><input type="submit" value=" Download "></form>';
  }
else
  {
    echo '<form action="./form.php?mode=download" method="POST"><textarea name="download" style="min-height:300px;">'.print_r($shownotes['export']).'</textarea><input type="submit" value=" Download "></form>';
  }

?>
<script type='text/javascript'>
    QueryLoader.init();

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