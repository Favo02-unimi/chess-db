var header = document.getElementById("nav");
var main = document.getElementById("main");
var logo = document.getElementById("logo");
var sticky = header.offsetTop;

var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;

window.onscroll = function() {
  width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
  if (width > 1100) {
    headerScroll();
  }
};

function headerScroll() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
    main.classList.add("sticky");
    logo.classList.add("sticky");
  }
  else {
    header.classList.remove("sticky");
    main.classList.remove("sticky");
    logo.classList.remove("sticky");
  }
}

$(window).scroll(function() {
  $('.animation').each(function(){
      var imagePos = $(this).offset().top;
      var topOfWindow = $(window).scrollTop();
      var vh = Math.max(document.documentElement.clientHeight || 0, window.innerHeight || 0)
      if (imagePos < topOfWindow+vh) {
          $(this).addClass("slideRight");
      }
  });
});

function showMobileMenu() {
  $("nav ul").toggle("hidden");
}