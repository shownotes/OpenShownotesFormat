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
  <option>JSON</option>
  <option>HTML</option>
  <option>PSC</option>
  <option>chapter</option>
</select><input type="submit" value=" Absenden "></form><br><br><hr><br><br>';

if((!isset($_GET['mode']))||(!isset($_POST['shownote']))||(!isset($_POST['mode'])))
  {
    echo 'error';
    die('error');
  }

include "./osfregex.php";

$content = $_POST['shownote'];


$starttime = microtime(1);
$shownotes = osf_parser($content);
$timer['osf_parser'] = microtime(1)-$starttime;
if($_POST['mode'] == 'JSON')
  {
    echo '<textarea>'.json_encode($shownotes['export']).'</textarea>';
    $timer['json_encode'] = microtime(1)-$starttime;
  }
elseif($_POST['mode'] == 'XML')
  {
    echo 'sorry, xml-export is currently not working';
  }
elseif($_POST['mode'] == 'HTML')
  {
    echo osf_get_chapter_html($shownotes['export']);
    $timer['osf_get_chapter_html'] = microtime(1)-$starttime;
  }
elseif($_POST['mode'] == 'PSC')
  {
    echo '<textarea>'.osf_export_psc($shownotes['export']).'</textarea>';
    $timer['osf_export_psc'] = microtime(1)-$starttime;
  }
elseif($_POST['mode'] == 'chapter')
  {
    echo '<form action="./form.php?mode=download" method="POST"><textarea name="download" style="min-height:300px;">'.osf_export_chapterlist($shownotes['export']).'</textarea><input type="submit" value=" Download "></form>';
  }
?>

</body>
</html>