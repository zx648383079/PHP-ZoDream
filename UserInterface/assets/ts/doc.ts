$(document).ready(function() {
    $(".zd-tab .zd-tab-head .zd-tab-item").click(function() {
        let $this = $(this);
        $this.addClass("active").siblings().removeClass("active");
        $this.parents(".zd-tab").find(".zd-tab-body .zd-tab-item").eq($this.index()).addClass("active").siblings().removeClass("active");
    });
    $("form[data-type=ajax]").submit(function() {
        let $this = $(this);
        $.getJSON($this.attr('action'), $this.serialize(), function(data) {
            if (data.code == 200) {
                Dialog.tip(data.message);
                return;
            }
            Dialog.tip(data.errors);
        });
        return false;
    });
    $(".page-tip .toggle").click(function() {
        $(this).parents('.page-tip').toggleClass('min');
    });
});


