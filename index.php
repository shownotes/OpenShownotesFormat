<?php

$inclusion = array('chapter', 'section', 'spoiler', 'topic', 'embed', 'video', 'audio', 'image', 'shopping', 'glossary');
$amazonid  = '';
$thomannid = '';

if($_GET['configfile'] != '')
  {
    if((strpos($_GET['configfile'], '.') !== false)||(strpos($_GET['configfile'], '/') !== false))
      {

      }
    else
      {
        $configfile = $_GET['configfile'];
        @include_once('./config/config.'.$configfile.'.inc.php');
      }
  }

?><!DOCTYPE html>
<html lang="de"> 
<head>
  <meta charset="utf-8" />
  <title>OpenShownoteFormat-Parser</title>
  <meta name="viewport" content="width=980" />  
  <meta name="apple-mobile-web-app-capable" content="yes" />  
  <link rel="shortcut icon" type="image/x-icon" href="http://shownot.es/favicon.ico" />
  <link rel="icon" type="image/x-icon" href="http://shownot.es/favicon.ico" />
  <link rel="apple-touch-startup-image" href="http://cdn.shownot.es/img/iPhonePortrait.png" />
  <link rel="apple-touch-startup-image" sizes="768x1004" href="http://cdn.shownot.es/img/iPadPortait.png" />
  <link href="http://selfcss.org/baf/css/baf.css" media="screen" rel="stylesheet" type="text/css"/>
  <link href="http://selfcss.org/baf/css/icomoon.css" media="screen" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="./style.css?v=006" type="text/css" />
  <script src="http://selfcss.org/baf/js/baf.min.js"></script>
  <script src="http://simonwaldherr.github.io/majaX.js/majax.js"></script>
</head>
<body>
  <div class="content">
    <div class="header">
      <div class="title">
        <a href="http://shownot.es/"><img src="http://cdn.shownot.es/img/logo.png" alt="Shownot.es Logo">Die Shownotes</a>
      </div>
    </div>
    <div class="box" id="main" style="text-align:center;">
      <h2><b>OSF-Parser-Suite!</b></h2>
      <br/>
    <div class="input-prepend baf-input baf-group-x1">
      <label class="baf grey w120 add-on" for="search" id="searchlabel">
        <span class="baf-icomoon normal" aria-hidden="true" data-icon="&#xe04a;">&nbsp;
        </span> search
      </label>
      <input class="input-grey" id="search" name="text-search1" onkeyup="searchPadslist(this);" maxlength="" size="16" type="text"/>
    </div>
    <div id="padlist">
    </div>
    <div class="textarea" style="width: 640px; margin:auto;">
      <label class="baf add-on w90" for="defaulttextarea" id="defaultt1">Shownotes</label><br/>
      <textarea class="" id="defaulttextarea" name="defaulttextarea" onkeyup="" size="26" type="text"><?php echo $_POST['padcontent']; ?></textarea>
    </div>
    <br/>
    <div class="input-prepend baf-input baf-group-x3">
      <label class="baf grey w120 add-on" for="tags" id="label-inc. tags3">
        <span class="baf-icomoon normal" aria-hidden="true" data-icon="&#xe084;">&nbsp;
        </span> inc. tags
      </label>
      <input class="input-grey" id="tags" name="text-inc. tags3" maxlength="" size="16" value="<?php echo implode(' ', $inclusion); ?>" type="text"/>
    </div>
    <div class="input-prepend baf-input baf-group-x4">
      <label class="baf grey w120 add-on" for="amazon" id="label-amazon id4">amazon id</label>
      <input class="input-grey" id="amazon" name="text-amazon id4" onkeyup="" maxlength="" size="16" value="<?php echo $amazonid; ?>" type="text"/>
    </div>
    <div class="input-prepend baf-input baf-group-x5">
      <label class="baf grey w120 add-on" for="thomann" id="label-thomann id5">thomann id</label>
      <input class="input-grey" id="thomann" name="text-thomann id5" onkeyup="" maxlength="" size="16" value="<?php echo $thomannid; ?>" type="text"/>
    </div> 
    <div class="input-prepend baf-input baf-group-x6">
      <label class="baf grey w120 add-on" for="tradedoubler" id="label-tradedoubler id6">tradedoubler id</label>
      <input class="input-grey" id="tradedoubler" name="text-tradedoubler id6" onkeyup="" maxlength="" size="16" value="<?php echo $tradedoubler; ?>" type="text"/>
    </div> 
    <div style="">
      <form name="outputmode" method="get" action="" onsubmit="return false;" style="width: 225px; text-align: left; margin: 15px auto;">
        <!--<label class="baf grey w120 add-on" for="mainmode" id="label-mainmode id7">main mode</label>-->
        <select id="mainmode">
          <option>shownot.es</option>
          <option>block style</option>
          <option>button style</option>
          <option>list style</option>
          <option>clean osf</option>
          <option>glossary</option>
          <option>shownoter</option>
          <option>podcaster</option>
          <option>JSON</option>
          <option>Chapter</option>
          <option>PSC</option>
        </select>
      </form>
    </div>
    <a class="baf w175" id="showsource"  onclick="expmode = 'source';   parsePad(); return false;">Show Source</a>
    <a class="baf w175" id="showpreview" onclick="expmode = 'preview';  parsePad(); return false;">Preview</a>
    <a class="baf w175" id="download"    onclick="expmode = 'download'; parsePad(); return false;">Download</a>
    <hr/>
    <div id="outputsource" style="display:none;">
      <textarea id="sourcearea" style="height: 620px; width: 96%;"></textarea>
    </div>
    <div id="outputview" style="display:none;">
      <iframe id="viewarea" style="height: 620px; width: 96%;"></iframe>
    </div>
    <hr/>
    <a href="http://flattr.com/thing/1062678/SimonWaldherrOSF-Parser-Suite-on-GitHub" target="_blank">
    <img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a>
    </div>
    <div class="footer">&nbsp;<span>&copy; 2013 <a href="http://shownot.es/">shownot.es</a></span></div>
  </div>
<script type="text/javascript">

var expmode = false, options = {};

function getOptions() {
  options = {
    'tags'         : document.getElementById('tags').value,
    'amazon'       : document.getElementById('amazon').value,
    'thomann'      : document.getElementById('thomann').value,
    'tradedoubler' : document.getElementById('tradedoubler').value,
    'mainmode'     : document.getElementById('mainmode').value,
    'expmode'      : expmode
  }
  return options;
}

window.onload = function () {
  majaX({
    'url': 'http://cdn.simon.waldherr.eu/projects/showpad-api/getList/',
    'type': 'json'
  }, function (json) {
    var i, pads = '';
    for (i = 0; i < json.length; i++) {
      pads += '<span onclick="loadPad(\''+json[i].docname+'\');" class="baf bluehover">'+json[i].docname+'</span>';
    }
    document.getElementById('padlist').innerHTML = pads;
  });
};

function searchPadslist(e) {
  var i, pads = document.getElementById('padlist').getElementsByTagName('span');
  for (i = 0; i < pads.length; i++) {
    if(pads[i].innerHTML.indexOf(e.value) !== -1) {
      pads[i].style.display = 'block';
    } else {
      pads[i].style.display = 'none';
    }
  }
}

function loadPad(name) {
  majaX({
    'url': 'http://cdn.simon.waldherr.eu/projects/showpad-api/getPad/?id='+name,
    'type': 'txt'
  }, function (txt) {
    document.getElementById('defaulttextarea').innerHTML = txt;
  });
}

function parsePad() {
  var padContent = window.btoa(unescape(encodeURIComponent( document.getElementById('defaulttextarea').value )));
  getOptions();
  majaX({
    'url': './api.php',
    'method': 'POST',
    'type': 'txt',
    'data': {
      'pad': padContent,
      'tags': options.tags,
      'amazon': options.amazon,
      'thomann': options.thomann,
      'tradedoubler': options.tradedoubler,
      'mainmode': options.mainmode,
      'expmode': options.expmode
    }
  }, function (txt) {
    style = '';
    
    document.getElementById('viewarea').srcdoc = '<html><head><title>'+options.mainmode+' - Shownotes</title><link rel="stylesheet" href="http://cdn.shownot.es/include-shownotes/shownotes.css" type="text/css" media="screen"><link rel="stylesheet" href="http://tools.shownot.es/parsersuite/preview.css" type="text/css" media="screen"><style>'+style+'</style></head><body><div class="content"><div class="box">'+txt+'</div></div><div class="footer">&nbsp;<span>© 2013 <a href="/">shownot.es</a></span></div></body></html>';
    document.getElementById('sourcearea').innerHTML = txt;
    
    if(expmode === 'preview') {
      document.getElementById('outputview').style.display   = 'block';
      document.getElementById('outputsource').style.display = 'none';
    } else if(expmode === 'source') {
      document.getElementById('outputview').style.display   = 'none';
      document.getElementById('outputsource').style.display = 'block';
    } else if(expmode === 'download') {
      document.getElementById('outputview').style.display   = 'none';
      document.getElementById('outputsource').style.display = 'none';
    }
  });
}

</script>
</body>
</html>
