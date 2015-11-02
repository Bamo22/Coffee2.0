function executeQuery() {
  $.ajax({
    url: 'script.ajaxRefresh.php',
    success: function(data) {
      // do something with the return value here if you like
      $("#status")
    }
  });
  setTimeout(executeQuery, 2000); // you could choose not to continue on failure...
}

$(document).ready(function() {
  // run the first time; all subsequent calls will take care of themselves
  setTimeout(executeQuery, 2000);
});