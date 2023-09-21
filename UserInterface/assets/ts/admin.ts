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

$(function() {
    $(document).on('click', ".tab-box .tab-header .fa-close", function() {
        let li = $(this).parent();
        li.closest(".tab-box").find(".tab-body .tab-item").eq(li.index()).remove();
        li.remove();
    }).on('click', '.table-toggle tr', function() {
        let $this = $(this);
        if ($this.hasClass('tr-child')) {
            return;
        }
        $this.next('.tr-child').toggle();
    });
});