/// <reference path="../../../../../typings/jquery/jquery.d.ts" />
;define(["jquery"], function() {
    $("#addCategory").click(function() {
       $("#category").show(); 
    });

    $("#addWeb").click(function() {
        $("#web").show();
    })
    $("#category").click(function() {
        $(this).hide();
    });
    $("#web").click(function() {
        $(this).hide();
    });
});