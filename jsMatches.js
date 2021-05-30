function copyPGN(pgn) {
  navigator.clipboard.writeText(pgn).then(function() {
    alert('PGN Copied to clipboard successfully!');
  }, function(err) {
    alert('Copying error: ', err);
  });
}

function ajaxSearch(search, molt) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    document.getElementById("response").innerHTML = "<div class='flex'><img src='src/loading.gif' class='loading'></div>";
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("response").innerHTML = this.responseText;
        hoverHeartbroken();
      }
  };
  xmlhttp.open("GET", "getMatches.php?search=" + search + "&molt=" + molt, true);
  xmlhttp.send();
}

var molt = 1;
function showMore() {
  molt++;
  var search = document.getElementById("search").value;
  ajaxSearch(search, molt);
}