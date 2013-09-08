<?php

include './wp-osf-shownotes/osf.php';

if (isset($_POST['amazon']) && $_POST['amazon'] != '') {
  $amazon = $_POST['amazon'];
} else {
  $amazon = 'shownot.es-21';
}
if (isset($_POST['thomann']) && $_POST['thomann'] != '') {
  $thomann = $_POST['thomann'];
} else {
  $thomann = '93439';
}
if (isset($_POST['tradedoubler']) && $_POST['tradedoubler'] != '') {
  $tradedoubler = $_POST['tradedoubler'];
} else {
  $tradedoubler = '16248286';
}
$fullmode = 'false';

$tags = $_POST['tags'];

if ($tags == '') {
  $fullmode = 'true';
  $fullint = 2;
  $tags = explode(' ', 'chapter section spoiler topic embed video audio image shopping glossary source app title quote link podcast news');
} else {
  $fullint = 1;
  $tags = explode(' ', $tags);
}
$data = array(
  'amazon'       => $_POST['amazon'],
  'thomann'      => $_POST['thomann'],
  'tradedoubler' => $_POST['tradedoubler'],
  'fullmode'     => $fullmode,
  'tagsmode'     => 'include',
  'tags'         => $tags
);

$encodedData = str_replace(' ','+',$_POST['pad']);
$shownotesString = str_replace("\n", " \n", "\n" . base64_decode($encodedData) . "\n");

$mode = $_POST['mainmode'];

if ($mode == 'block') {
  $mode = 'block style';
}
if ($mode == 'list') {
  $mode = 'list style';
}
if ($mode == 'osf') {
  $mode = 'clean osf';
}

function is_feed() {
  return false;
}
function get_the_ID() {
  return '1';
}

$shownotes_options['main_delimiter'] = '';
$shownotes_options['main_last_delimiter'] = '';
$osf_starttime = 0;

if ($mode == 'shownot.es') {
  $fullmode             = 'true';
  $fullint              = 2;
  $tags                 = explode(' ', 'chapter section spoiler topic embed video audio image shopping glossary source app title quote link podcast news');
  $data['tags']         = $tags;
  $data['fullmode']     = $fullmode;
  $data['amazon']       = 'shownot.es-21';
  $data['thomann']      = '93439';
  $data['tradedoubler'] = '16248286';
}

$shownotesArray = osf_parser($shownotesString, $data);

if ($mode == 'shownot.es') {
  $fullmode             = 'true';
  $fullint              = 2;
  $tags                 = explode(' ', 'chapter section spoiler topic embed video audio image shopping glossary source app title quote link podcast news');
  $data['tags']         = $tags;
  $data['fullmode']     = $fullmode;
  $data['amazon']       = 'shownot.es-21';
  $data['thomann']      = '93439';
  $data['tradedoubler'] = '16248286';
  $export = '<div class="info">  <div class="thispodcast">  <div class="podcastimg">  <img src="" alt="Logo">  </div> <?php  include "./../episodeselector.php"; insertselector();  ?>  </div>  <div class="episodeinfo">  <table>  <tr>  <td>Podcast</td><td><a href="#"></a></td>  </tr>  <tr>  <td>Episode</td><td><a href="#"></a></td>  </tr>  <tr>  <td>Sendung vom</td><td>'.date("j. M Y").'</td>  </tr>  <tr>  <td>Podcaster</td><td>'.osf_get_persons('podcaster', $shownotesArray['header']).'</td>  </tr>  <tr>  <td>Shownoter</td>  <td>'.osf_get_persons('shownoter', $shownotesArray['header']).'</td>  </tr>  </table>  </div> </div><br/><br/>'."\n\n";
  
  $export .= osf_export_block($shownotesArray['export'], 2, 'block style');
} elseif (($mode == 'block style') || ($mode == 'button style')) {
  $export = osf_export_block($shownotesArray['export'], $fullint, $mode);
} elseif ($mode == 'list style') {
  $export = osf_export_list($shownotesArray['export'], $fullint, $mode);
} elseif ($mode == 'clean osf') {
  $export = osf_export_osf($shownotesArray['export'], $fullint, $mode);
} elseif ($mode == 'glossary') {
  $export = osf_export_glossary($shownotesArray['export'], $fullint);
} elseif (($mode == 'shownoter') || ($mode == 'podcaster')) {
  if (isset($shownotesArray['header'])) {
    if ($mode == 'shownoter') {
      $export = osf_get_persons('shownoter', $shownotesArray['header']);
    } elseif ($mode == 'podcaster') {
      $export = osf_get_persons('podcaster', $shownotesArray['header']);
    }
  }
} elseif ($mode == 'JSON') {
  $export = json_encode($shownotesArray['export']);
} elseif ($mode == 'Chapter') {
  $export = osf_export_chapterlist($shownotesArray['export']);
} elseif ($mode == 'PSC') {
  $export = osf_export_psc($shownotesArray['export']);
}

echo $export;

?>