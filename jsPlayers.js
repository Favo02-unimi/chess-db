function ajaxDate(date) {
    if (date.length == 0) {
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            document.getElementById("response").innerHTML = "<div class='flex'><img src='src/loading.gif' class='loading'></div>";
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("response").innerHTML = this.responseText;
                hoverHeartbroken();
            }
        };
        xmlhttp.open("GET", "getPlayers.php?date=" + date, true);
        xmlhttp.send();
    }
}