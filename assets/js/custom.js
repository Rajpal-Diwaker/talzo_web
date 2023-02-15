$(document).ready(function() {
    "use strict";

    //LEFT MOBILE MENU OPEN
    $(".atab-menu").on('click', function() {
        //$(".side_bar").css("left", "-350px");
        
        $('html').toggleClass('wrapper');
    });

    //LEFT MOBILE MENU CLOSE
    $(".atab-menumb").on('click', function() {
        $(".side_bar").css("left", "0");
        $(".btn-close-menu").css("display", "inline-block");
    });

    $(".btn-close-menu").on('click', function() {
        $(".side_bar").css("left", "-350px");
        $(".btn-close-menu").css("display", "none");
    });

});