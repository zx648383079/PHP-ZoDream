/// <reference path="MediumEditor.d.ts/>
$(function () {
    $(".wx-editor .zd-tab-head .zd-tab-item").click(function() {
        let $this = $(this);
        let index = $this.index();
        let parent = $this.parents('.zd-tab');
        parent.find('.type-input').val(index);
    });
});

function bindEditor(tag: string) {
    let editor = new MediumEditor(tag),
        tplURI: string,
        tplPage = 1,
        tplBox = $(".template-box .templdate-list"),
        loadTpl = function() {
            if (tplPage < 2) {
                tplBox.empty();
            }
            $.post(tplURI, {
                page: tplPage
            }, function(html) {
                tplBox.append(html);
            });
        };
    $(".template-menu li").click(function() {
        $(this).addClass('active').siblings().removeClass('active');
        tplPage = 1;
        tplURI = $(this).attr('data-url');
        loadTpl();
    }).eq(0).trigger('click');
    tplBox.on('click', '.rich_media_content', function() {
        // 为解决保存上次焦点问题
        editor.restoreSelection();
        editor.pasteHTML($(this).html());
    });
}