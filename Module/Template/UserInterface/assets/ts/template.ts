class Panel {
    constructor(
        tag: string,
        public page: Page
    ) {
        this.element = $(tag);
        this._init();
    }

    public element: JQuery;

    private _init() {
        this._bindEvent();
    }

    private _bindEvent() {
        let that = this;
        this.element.find(".panel .head .fa-close").click(function() {
            that.element.addClass("min");
        });
        this.element.find(".menu>li>.head").click(function() {
            $(this).parent().toggleClass("active");
        });
    }

    public bindDrag() {
        let that = this;
        this.element.find(".weight-grid").draggable({
            connectToSortable: ".weight-row",
            helper: "clone",
            opacity: .3,
            revert: "invalid",
            start: function() {
                that.page.body.addClass("hover");
            },
            stop: function(event, target) {
                that.page.body.removeClass("hover");
                let ele = target.helper,
                    row = ele.closest('.weight-row');
                if (!row || row.length < 1) {
                    // 没用拖放成功！
                    return;
                }
                that.page.addWeight(ele, row);
            }
        });
    }
}

class Weight {
    constructor(
        public box: JQuery
    ) {
        
    }

    public toggle(state?: boolean) {
        this.box.toggleClass('weight-edit-mode', state);
        return this;
    }

    public toggleLoading(state?: boolean) {
        this.box.toggleClass('weight-loading', state);
        return this;
    }
}

class Page {
    constructor(
    ) {
        this.box = $("#page-box");
        this.element = $('#mainMobile');
        this.body = $('#mainGrid');
        this._init();
    }

    public box: JQuery;

    public element: JQuery;

    public body: JQuery;

    public weight: Weight;

    private _init() {
        this._bindEvent();
        this.resize();
    }

    private _bindEvent() {
        let that = this;
        $(window).resize(function() {
            that.resize();
        });
        this.body.find(".weight-row").sortable({
            connectWith: ".weight-row"
        });
        this.body.on("click", ".del", function() {
            let ele = $(this).parent().parent();
            $.post('/template/weight/destroy?id=' + ele.attr('data-id'), {}, function(data) {
                if (data.code == 200) {
                    ele.remove();
                }
            });
        }).on("click", ".edit", function(e) {
            e.stopPropagation();
            that.setWeight($(this).parents('.weight-grid'));
        });
    }

    public setWeight(element: JQuery): Weight {
        if (this.weight) {
            this.weight.toggle(false);
        }
        this.weight = new Weight(element);
        this.weight.toggle(true);
        return this.weight;
    }

    public addWeight(element: JQuery, parent: JQuery): Weight {
        let that = this;
        element.width('auto');
        let weight = this.setWeight(element).toggleLoading(true);
        $.post('/template/weight/create', {
            page: PAGE_ID,
            weight: element.attr('data-weight'),
            parent_id: parent.attr('data-id')
        }, function(data) {
            weight.toggleLoading(false);
            if (data.code == 200) {
                element.attr('data-id', data.data.id);
            }
        }, 'json');
    }

    public resize() {
        this.box.height($(window).height() - 57);
    }

    public html(): string {
        return $.htmlClean($("#mainGrid").html(), {
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
    }
}

$(document).ready(function () {
    let page = new Page(),
        weight = new Panel('#weight', page),
        property = new Panel('#property', page);
    weight.bindDrag();
    $(".mobile-size li").click(function() {
        $(".mobile-size").parent().removeClass("open");
        let size = $(this).attr("data-size").split("*");
        page.element.removeClass().addClass('mobile-' + size[0]);
    });
    $(".navbar>li>div").click(function() {
        $(this).parent().toggleClass("open");
    });
    $(".mobile-size li").click(function() {
        $(this).addClass("active").siblings().removeClass("active");
    });

    $(".mobile-rotate").click(function() {
        page.element.toggleClass('rotate');
    });
    $(".expand>.head").click(function() {
        $(this).parent().toggleClass("open");
    });
});