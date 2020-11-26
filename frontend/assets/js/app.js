$(document).ready(function() {
  var backendUrl = 'https://backend.dev.local/index.php';

  /**
     * Frontend CORS With Session
     * 
     * jQuery Example
     */
  $.ajax({
    url: backendUrl,
    method: 'GET',
    datatype: 'json',
    // Setting CORS XHR Credentials - for Cookie support
    xhrFields: {
      withCredentials: true,
    },
  }).done(function(res) {
    $('.backend-session-id').text(res.session_id);
    console.log(res);
  });

  // open backend button

  $('.open-backend').click(function() {
    window.open(backendUrl);
  });
});
