function addPlayerToFav(button, id) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            button.classList.toggle("far");
            button.classList.toggle("fas");
            hoverHeartbroken();
        }
    };
    xmlhttp.open("GET", "addPlayerToFav.php?id=" + id, true);
    xmlhttp.send();
}

function addMatchToFav(button, id) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            button.classList.toggle("far");
            button.classList.toggle("fas");
            hoverHeartbroken();
        }
    };
    xmlhttp.open("GET", "addMatchToFav.php?id=" + id, true);
    xmlhttp.send();
}

function hoverHeartbroken() {
    $('i.fa-heart').hover(
        function(){
            if ($(this).hasClass('fas')) {
                $(this).addClass('fa-heart-broken');      
            }
        },
        function(){
            $(this).removeClass('fa-heart-broken');
    });
}