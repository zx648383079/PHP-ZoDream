$(document).ready(function () {
    $("#weight .weight-grid").draggable({
        connectToSortable: ".weight-row",
        helper: "clone",
        opacity: .3,
        revert: "invalid",
        start: function() {
            $("#mainGrid").addClass("hover");
        },
        stop: function(event, target) {
            $("#mainGrid").removeClass("hover");
            let ele = target.helper,
                row = ele.parents('.weight-row');
            if (!row || row.length < 1) {
                // 没用拖放成功！
                return;
            }
            ele.width('auto');
            $.post('/template/weight/create', {
                page: PAGE_ID,
                weight: ele.attr('data-weight'),
                parent_id: row.attr('data-id')
            }, function(data) {
                if (data.code == 200) {
                    ele.attr('data-id', data.data.id);
                }
            }, 'json');
        }
    });
    $("#mainGrid .weight-row").sortable({
        connectWith: ".weight-row"
    });
    $(".panel .fa-close").click(function() {
        $(this).parents('.panel').parent().addClass("min");
    });

    $(".panel>.head>.title").click(function() {
        let panel = $(this).parents('.panel').parent();
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
        let ele = $(this).parent().parent();
        $.post('/template/weight/destroy?id=' + ele.attr('data-id'), {}, function(data) {
            if (data.code == 200) {
                ele.remove();
            }
        });
    });

    let currentElement;
    $("#mainGrid").on("click", ".edit", function(e) {
        e.stopPropagation();
        currentElement = $(this).parents('.weight-grid');
        $("#mainGrid .weight-grid").removeClass('weight-edit-mode');
        currentElement.addClass('weight-edit-mode');
        editProperty(currentElement);
    });

    let editProperty = function (element) {
            let id = element.attr('data-id');
            $.getJSON('/template/weight/config?id=' + id, function(data) {
                if (data.code != 200) {
                    return;
                }

            });
        },
        saveProperty = function () {

        },
        retsetProperty = function () {

        };

});