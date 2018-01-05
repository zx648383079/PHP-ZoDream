/// <reference path="../../typings/jquery/jquery.d.ts" />
$(document).ready(function () {
    $("#weight .grid").draggable({
        connectToSortable: ".row",
        helper: "clone",
        opacity: .3,
        revert: "invalid",
        start: function() {
            $("#mainGrid").addClass("hover");
        },
        stop: function() {
            $("#mainGrid").removeClass("hover");
        }
    });
    $("#mainGrid .row").sortable({
        connectWith: ".row"
    });
    $(".panel .fa-close").click(function() {
        $(this).parent().parent().addClass("min");
    });

    $(".panel>.head>.title").click(function() {
        let panel = $(this).parent().parent();
        if (panel.hasClass("min")) {
            panel.removeClass("min");
        }
    });

    $(".menu>li>.head").click(function() {
        $(this).parent().toggleClass("active");
    });

    $.htmlClean($("#mainGrid").html(), {
        format: true,
        allowedAttributes: [
            ["id"],
            ["class"],
            ["data-toggle"],
            ["data-target"],
            ["data-parent"],
            ["role"],
            ["data-dismiss"],
            ["aria-labelledby"],
            ["aria-hidden"],
            ["data-slide-to"],
            ["data-slide"]
        ]
    });
    $(".mobile-size li").click(function() {
        $(".mobile-size").parent().removeClass("open");
        let size = $(this).attr("data-size").split("*");
        let width = size[0] ? size[0] + 'px' : "100%";
        let height = size[1] ? size[1] + 'px' : "100%";
        $("#mainMobile").css({width: width, height: height});
    });
    $(".navbar>li>div").click(function() {
        $(this).parent().toggleClass("open");
    });
    $(".mobile-size li").click(function() {
        $(this).addClass("active").siblings().removeClass("active");
    });


    $(".mobile-rotate").click(function() {
        let mobile = $("#mainMobile");
        let width = mobile.height();
        let height = mobile.width();
        let padding = mobile.css("padding").replace(/(\d+\s?(px|rem|em)?)(.+)/, "$3 $1");
        mobile.css({width: width, height: height, padding: padding});
    });
    $(".expand>.head").click(function() {
        $(this).parent().toggleClass("open");
    });

    /** action */
    $("#mainGrid").on("click", ".del", function() {
        $(this).parent().parent().remove();
    });

    let currentElement;
    $("#mainGrid").on("click", ".edit", function() {
        currentElement = $(this).parent().parent();
        editProperty();
    });

    let editProperty = function () {

        },
        saveProperty = function () {

        },
        retsetProperty = function () {

        };

});