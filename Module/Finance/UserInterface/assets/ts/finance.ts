function ajaxForm($this) {
    $.post($this.attr('action'), $this.serialize(), function(data) {
        if (data.code != 200) {
            Dialog.tip(data.errors || '操作执行失败！');
            return;
        }
        Dialog.tip(data.messages || '操作执行完成！');
        if (data.data && data.data.refresh) {
            setTimeout(() => {
                window.location.reload();
            }, 500);
        }
        if (data.data && data.data.url) {
            setTimeout(() => {
                window.location.href = data.data.url;
            }, 500);
        }
    }, 'json');
}

$(document).ready(function() {
    $(".zd-tab .zd-tab-head .zd-tab-item").click(function() {
        let $this = $(this);
        $this.addClass("active").siblings().removeClass("active");
        $this.parents(".zd-tab").find(".zd-tab-body .zd-tab-item").eq($this.index()).addClass("active").siblings().removeClass("active");
    });
    $("form[data-type=ajax]").submit(function() {
        let $this = $(this);
        ajaxForm($this);
        return false;
    });
    $(".page-tip .toggle").click(function() {
        $(this).parents('.page-tip').toggleClass('min');
    });
});