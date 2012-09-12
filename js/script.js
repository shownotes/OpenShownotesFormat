function getpad(geturl)
{
  reqwest(
    {
        url: geturl
      , type: 'html'
      , method: 'get'
      , success: function (resp)
          {
            document.getElementById('textpadarea').value = resp
          }
    })
  }