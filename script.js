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
