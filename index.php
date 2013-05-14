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
  <link rel="stylesheet" href="./css/style.css?v=006" type="text/css" />
  
  <script src="http://selfcss.org/baf/js/baf.min.js"></script>
  <script type="text/javascript" src="http://cdn.simon.waldherr.eu/projects/reqwest/reqwest.js"></script>
  <style>
    * {
      font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; 
    }

    h2 {
      font-size: larger;
    }
    
    .baf.grey.w120.add-on {
      top: 0px;
      margin-top: 0px;
      vertical-align: top;
    }
    
    .content {
      max-width: 880px;
      min-width: 680px;
      margin: 30px auto 30px auto;
      width: 80%;
    }
    
    .box h4{
      max-width: 340px; margin: auto;
    }
    
    input[type="radio"]{
      margin: 3px;
    }
    #viewarea {
      visibility: visible;
    }
    input.input-grey {
      margin-left: -5px;
    }
    .textarea textarea {
      height: 160px;
    }
    #padlist {
      overflow-y: auto;
      max-height: 135px;
    }
    @media only screen 
    and (min-width : 480px) 
    and (max-width : 680px) {
      .content {
        zoom: 0.8;
      }
    }
  </style>
</head>
<body onload="baf_listenerInit();">
  <div class="content">
    <div class="header">
      <div class="title">
        <a href="#"><img src="http://cdn.shownot.es/img/logo.png" alt="Shownot.es Logo">Die Shownotes</a>
      </div>
    </div>
    <div class="box" id="main" style="text-align:center;">
      <h2><b>OSF-Parser-Suite!</b></h2>
      <br/><br/>
      <h4><i>Bitte entweder den Etherpad Namen im Feld &quot;<b>etherpad</b>&quot; oder die kompletten Shownotes im Feld &quot;<b>Shownotes</b>&quot; eingeben.<br/>Bitte bedenken Sie, dass es eine gewisse Zeit (mehrere Sekunden) dauern kann, bis die Padliste und die Shownotes via API abgerufen werden.</i></h4>
      <br/>
    <div class="input-prepend baf-input baf-group-x1">
      <label class="baf grey w120 add-on" for="search" id="searchlabel">
        <span class="baf-icomoon normal" aria-hidden="true" data-icon="&#xe04a;">&nbsp;
        </span> search
      </label>
      <input class="input-grey" id="search" name="text-search1" onkeyup="getPadslist();" maxlength="" size="16" type="text"/>
    </div>
    <div id="padlist">
    </div>
    <div class="input-prepend baf-input baf-group-x2">
      <label class="baf grey w120 add-on" for="etherpad" id="label-etherpad2">
        <span class="baf-icomoon normal" aria-hidden="true" data-icon="&#xe017;">&nbsp;
        </span> etherpad
      </label>
      <input class="input-grey" id="etherpad" name="text-etherpad2" maxlength="" size="16" type="text"/>
    </div>
    <a class="baf w175" id="loadbutton" onclick="getPadContentAndParse('load', false);">load Pad</a>
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
        <div style="">
        <input type="radio" name="select" value="anycast" <?php   if($exportmodul == 'anycast'){echo 'checked="checked"';} ?> /> Anycast<br/>
        <input type="radio" name="select" value="metastyle" <?php if($exportmodul == 'metastyle'){echo 'checked="checked"';} ?> /> Metaebene<br/>
        <input type="radio" name="select" value="metacast" <?php  if($exportmodul == 'metacast'){echo 'checked="checked"';} ?> /> Newstyle<br/>
        <input type="radio" name="select" value="wikigeeks" <?php if($exportmodul == 'wikigeeks'){echo 'checked="checked"';} ?> /> Wikigeeks<br/>
        <input type="radio" name="select" value="json" <?php      if($exportmodul == 'json'){echo 'checked="checked"';} ?> /> JSON<br/>
        <input type="radio" name="select" value="chapter" <?php   if($exportmodul == 'chapter'){echo 'checked="checked"';} ?> /> Chapter<br/>
        <input type="radio" name="select" value="glossary" <?php  if($exportmodul == 'glossary'){echo 'checked="checked"';} ?> /> Glossary<br/>
        <input type="radio" name="select" value="tagsalphabetical" <?php if($exportmodul == 'tagsalphabetical'){echo 'checked="checked"';} ?> /> Tags Alphabetical<br/>
        <input type="radio" name="select" value="print_r" <?php   if($exportmodul == 'print_r'){echo 'checked="checked"';} ?> /> print_r<br/><br/>
        </div><div style="position: relative;left: 130px;bottom: 210px;">
        <input type="radio" name="fullmode" value="off" <?php      if($exportfullmodul == false){echo 'checked="checked"';} ?> /> inclusion tags only<br/>
        <input type="radio" name="fullmode" value="on" <?php      if($exportfullmodul == true){echo 'checked="checked"';} ?> /> all tags<br/>
        </div>
      </form>
    </div>
    <a class="baf w175" id="showsource"  onclick="osf_export('source', getCheckedValue(document.forms['outputmode'].elements['select'], getCheckedValue(document.forms['outputmode'].elements['fullmode'])))">Show Source</a>
    <a class="baf w175" id="showpreview" onclick="osf_export('preview', getCheckedValue(document.forms['outputmode'].elements['select'], getCheckedValue(document.forms['outputmode'].elements['fullmode'])))">Preview</a>
    <a class="baf w175" id="download"    onclick="osf_export('download', getCheckedValue(document.forms['outputmode'].elements['select'], getCheckedValue(document.forms['outputmode'].elements['fullmode'])))">Download</a>
    <!--
    <div class="baf-container">
      <div class="baf-group">
        <a class="baf dropdown-toggle w175" id="parsebutton">
          parse with export modul
          <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li><a onclick="getShownotes('anycast', false);">Anycast</a></li>
          <li><a onclick="getShownotes('anycast-full', true);">Anycast Full</a></li>
          <li><a onclick="getShownotes('metastyle', false);">metastyle</a></li>
          <li><a onclick="getShownotes('metastyle-full', true);">metastyle Full</a></li>
          <li><a onclick="getShownotes('metacast', true);">Metacast-Full</a></li>
          <li><a onclick="getShownotes('wikigeeks', false);">Wikigeeks</a></li>
          <li><a onclick="getShownotes('wikigeeks-full', true);">Wikigeeks Full</a></li>
          <li><a onclick="getShownotes('json', false);">JSON</a></li>
          <li><a onclick="getShownotes('chapter', false);">Chapter</a></li>
          <li><a onclick="getShownotes('glossary', true);">Glossary</a></li>
          <li><a onclick="getShownotes('tagsalphabetical', true);">Tags Alphabetical</a></li>
          <li><a onclick="getShownotes('print_r', true);">Print_r</a></li>
        </ul>
      </div>
    </div>
    -->
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

var searchvalue = '';

function osf_export(mode, outputmodul, fullmode) {

var fullmode2 = exportmode2 = '';
  if(mode == 'source') {
    document.getElementById('showsource').className = 'baf w175 loading';
  }
  if(mode == 'preview') {
    document.getElementById('showpreview').className = 'baf w175 loading';
  }
  if(mode == 'download') {
    document.getElementById('download').className = 'baf w175 loading';
  }
  
  outputmode = mode;
  exportmode = outputmodul;
  if((fullmode == 'on')&&((outputmodul == 'anycast')||(outputmodul == 'metastyle')||(outputmodul == 'wikigeeks'))) {
    exportmode2 = exportmode+'-full';
  } else {
	exportmode2 = exportmode;
  }
  
  if(fullmode == 'on') {
    fullmode2 = true;
  }
  if(fullmode == 'off') {
    fullmode2 = false;
  }
  
  if((document.getElementById('defaulttextarea').value == ''))
    {
      if(mode == 'source') {
        document.getElementById('showsource').className = 'baf w175';
      }
      if(mode == 'preview') {
        document.getElementById('showpreview').className = 'baf w175';
      }
      if(mode == 'download') {
        document.getElementById('download').className = 'baf w175';
      }
      if(document.getElementById('etherpad').value == '')
        {
          alert('please choose an episode');
          return false;
        }
      else
        {
          getPadContentAndParse2(exportmode2, mode, fullmode);
          return false;
        }
    }
  
  var geturl = '';
  
  if(mode == 'download')
    {
      geturl = 'http://tools.shownot.es/parsersuite/export.php?mode=download';
    }
  else
    {
      geturl = 'http://tools.shownot.es/parsersuite/export.php?mode=getpad';
    }
    
    if(getCheckedValue(document.forms['outputmode'].elements['fullmode']) == 'on') {
    if(((outputmodul == 'anycast')||(outputmodul == 'metastyle')||(outputmodul == 'wikigeeks'))) {
      exportmode2 = exportmode+'-full';
      fullmode2 = true;
    }
    }
  
  reqwest(
            {
                url: geturl
              , type: 'html'
              , method: 'post'
              , data: { exportmode: exportmode2
                       ,shownotes: document.getElementById('defaulttextarea').value
                       ,tags: document.getElementById('tags').value
                       ,amazon: document.getElementById('amazon').value
                       ,thomann: document.getElementById('thomann').value
                       ,tradedoubler: document.getElementById('tradedoubler').value
                       ,fullmode: fullmode2
                      }
              , success: function (resp)
                  {
                    if(outputmode == 'download')
                      {
                        window.location = "http://tools.shownot.es/parsersuite/archive/download.php?mode=download&smode="+mode+"&get="+resp;
                      }
                    else if(outputmode == 'preview')
                      {
                        document.getElementById('outputsource').style.display = 'none';
                        document.getElementById('outputview').style.display = 'block';
                        if((exportmode == 'chapter')||(exportmode == 'print_r'))
                          {
                            resp = '<pre>'+resp+'</pre>';
                          }
                        style = '';
                        if((exportmode == 'metastyle')||(exportmode == 'metastyle-full'))
                        	{
                        	  style += ".osf_items a::after, .osf_items span::after {content: '';}";
                        	}
                        document.getElementById('viewarea').srcdoc = '<html><head><title>'+mode+' - Shownotes</title><link rel="stylesheet" href="http://cdn.shownot.es/include-shownotes/shownotes.css" type="text/css" media="screen"><link rel="stylesheet" href="http://tools.shownot.es/parsersuite/preview.css" type="text/css" media="screen"><style>'+style+'</style></head><body><div class="content"><div class="box">'+resp+'</div></div><div class="footer">&nbsp;<span>© 2013 <a href="/">shownot.es</a></span></div></body></html>';
                        
                      }
                    else if(outputmode == 'source')
                      {
                        document.getElementById('outputsource').style.display = 'block';
                        document.getElementById('outputview').style.display = 'none';
                        document.getElementById('sourcearea').value = resp;
                      }
                    if(mode == 'source') {
                      document.getElementById('showsource').className = 'baf w175';
                    }
                    if(mode == 'preview') {
                      document.getElementById('showpreview').className = 'baf w175';
                    }
                    if(mode == 'download') {
                      document.getElementById('download').className = 'baf w175';
                    }
                  }
            })
}
function getPadslist()
  {
    if(document.getElementById('search').value.length < 2)
      {
        return false;
      }
    if(searchvalue != document.getElementById('search').value)
      {
        searchvalue = document.getElementById('search').value;
        window.setTimeout(getPadslist(), 750);
        return false;
      }
    var item;
    var html = '';
    getPadsByName(document.getElementById('search').value, function(items)
      {
        for(var i in items)
          {
            item = items[i].split(';');
            html += '<li><a onclick="document.getElementById(\'etherpad\').value = \''+item[0]+'\'; document.getElementById(\'defaulttextarea\').innerHTML = \'\';">'+item[1]+'</a></li>';
          }
        document.getElementById('padlist').innerHTML = html
      });
  }

function getPadContentAndParse2(exportmode, mode, fulloutput)
  {
    if(document.getElementById('etherpad').value.length < 2)
      {
        alert('please enter a padname first');
        return false;
      }
    document.getElementById('loadbutton').className = 'baf w175 loading';
    getPadcontentByName(document.getElementById('etherpad').value, function(padcontent)
      {
        document.getElementById('defaulttextarea').innerHTML = padcontent;
        if(mode != 'load')
          {
            osf_export(exportmode, mode, fulloutput);
          }
        document.getElementById('loadbutton').className = 'baf w175';
      });
  }

function getPadContentAndParse(mode, fulloutput)
  {
    if(document.getElementById('etherpad').value.length < 2)
      {
        alert('please enter a padname first');
        return false;
      }
    document.getElementById('loadbutton').className = 'baf w175 loading';
    getPadcontentByName(document.getElementById('etherpad').value, function(padcontent)
      {
        document.getElementById('defaulttextarea').innerHTML = padcontent;
        if(mode != 'load')
          {
            getShownotes(mode, fulloutput);
          }
        document.getElementById('loadbutton').className = 'baf w175';
      });
  }

function getCheckedValue(radioObj)
  {
    if(!radioObj)
      {
        return "";
      }
      
    var radioLength = radioObj.length;
    if(radioLength == undefined)
      if(radioObj.checked)
        {
          return radioObj.value;
        }
      else
        {
          return "";
        }
    for(var i = 0; i < radioLength; i++)
      {
        if(radioObj[i].checked)
          {
            return radioObj[i].value;
          }
      }
    return "";
  }

var outputmode;
var exportmode = '';
var style = '';

function getShownotes(mode, fulloutput)
  {
    document.getElementById('parsebutton').className = 'baf dropdown-toggle w175 loading';
    outputmode = getCheckedValue(document.forms['outputmode'].elements['select']);
    exportmode = mode;
    
    if((document.getElementById('defaulttextarea').value == ''))
      {
        document.getElementById('parsebutton').className = 'baf dropdown-toggle w175';
        if(document.getElementById('etherpad').value == '')
          {
            alert('please choose an episode');
            return false;
          }
        else
          {
            getPadContentAndParse(mode, fulloutput);
            return false;
          }
      }
    
    var geturl = '';
    
    if(outputmode == 'Download')
      {
        geturl = 'http://tools.shownot.es/parsersuite/export.php?mode=download';
      }
    else
      {
        geturl = 'http://tools.shownot.es/parsersuite/export.php?mode=getpad';
      }
    
    reqwest(
              {
                  url: geturl
                , type: 'html'
                , method: 'post'
                , data: { exportmode: exportmode
                         ,shownotes: document.getElementById('defaulttextarea').value
                         ,tags: document.getElementById('tags').value
                         ,amazon: document.getElementById('amazon').value
                         ,thomann: document.getElementById('thomann').value
                         ,tradedoubler: document.getElementById('tradedoubler').value
                         ,fullmode: fulloutput
                        }
                , success: function (resp)
                    {
                      if(outputmode == 'Download')
                        {
                          window.location = "http://tools.shownot.es/parsersuite/archive/download.php?mode=download&smode="+mode+"&get="+resp;
                        }
                      else if(outputmode == 'Preview')
                        {
                          document.getElementById('outputsource').style.display = 'none';
                          document.getElementById('outputview').style.display = 'block';
                          if((exportmode == 'chapter')||(exportmode == 'print_r'))
                            {
                              resp = '<pre>'+resp+'</pre>';
                            }
                          style = '';
                          if((exportmode == 'metastyle')||(exportmode == 'metastyle-full'))
                          	{
                          	  style += ".osf_items a::after, .osf_items span::after {content: '';}";
                          	}
                          document.getElementById('viewarea').srcdoc = '<html><head><title>'+mode+' - Shownotes</title><link rel="stylesheet" href="http://cdn.shownot.es/include-shownotes/shownotes.css" type="text/css" media="screen"><link rel="stylesheet" href="http://tools.shownot.es/parsersuite/preview.css" type="text/css" media="screen"><style>'+style+'</style></head><body><div class="content"><div class="box">'+resp+'</div></div><div class="footer">&nbsp;<span>© 2013 <a href="/">shownot.es</a></span></div></body></html>';
                          
                        }
                      else if(outputmode == 'ShowSource')
                        {
                          document.getElementById('outputsource').style.display = 'block';
                          document.getElementById('outputview').style.display = 'none';
                          document.getElementById('sourcearea').value = resp;
                        }
                      document.getElementById('parsebutton').className = 'baf dropdown-toggle w175';
                    }
              })
  }

var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-34667234-1']);
_gaq.push(['_trackPageview']);
(function() {
  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
  ga.src = 'http://statistik.simon.waldherr.eu/ga.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();

</script>
<script src="http://api.shownot.es/pad/ep/script.js"></script>
</body>
</html>