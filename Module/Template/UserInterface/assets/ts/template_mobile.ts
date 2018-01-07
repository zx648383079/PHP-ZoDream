$(document).ready(function () {
    $("#weight .grid").draggable({
        connectToSortable: ".row",
        helper: "clone",
        opacity: .3,
        revert: "invalid",
        start: function() {
            $("#mainGrid").addClass("hover");
        },
        stop: function(event, target) {
            target.helper.width('auto');
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
        $("#mainMobile").removeClass().addClass('mobile-' + size[0]);
    });
    $(".navbar>li>div").click(function() {
        $(this).parent().toggleClass("open");
    });
    $(".mobile-size li").click(function() {
        $(this).addClass("active").siblings().removeClass("active");
    });

    $(".mobile-rotate").click(function() {
        $("#mainMobile").toggleClass('rotate');
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