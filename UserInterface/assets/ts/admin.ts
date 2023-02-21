function renderCMD(lines: string[]) {
    let i = 0,
        box = $('.cmd-box'),
        handle = setInterval(function() {
            if (i >= lines.length) {
                clearInterval(handle);
                return;
            }
            box.append('<p>'+ lines[i++] +'</p>').scrollTop(box[0].scrollHeight);
        }, Math.floor(1 + Math.random() * 400));
}

$(document).ready(function() {
    $(".zd-tab .zd-tab-head .zd-tab-item").on('click',function() {
        let $this = $(this);
        $this.addClass("active").siblings().removeClass("active");
        $this.parents(".zd-tab").find(".zd-tab-body .zd-tab-item").eq($this.index()).addClass("active").siblings().removeClass("active");
    });
    $(".zd-tab .zd-tab-head .fa-close").on('click',function() {
        let li = $(this).parent();
        li.parents(".zd-tab").find(".zd-tab-body .zd-tab-item").eq(li.index()).remove();
        li.remove();
    });
    $('.table-toggle tr').on('click',function() {
        let $this = $(this);
        if ($this.hasClass('tr-child')) {
            return;
        }
        $this.next('.tr-child').toggle();
    });
});