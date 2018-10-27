/// <reference path="MediumEditor.d.ts/>

function bindTab(typeId: number| string) {
    let typeInput = $(".wx-editor .type-input");
    $(".wx-editor .zd-tab-head .zd-tab-item").click(function() {
        typeInput.val($(this).attr('data-id'));
    }).each((i, item) => {
        let that = $(item);
        if (that.attr('data-id') == typeId) {
            that.trigger('click');
        }
    });
}

function bindEditor(tag: string) {
    let editor = UE.getEditor('container',{
        toolbars: [
            ['fullscreen', 'source', 'undo', 'redo', 'bold', 'italic', 'underline', 'customstyle', 'link','simpleupload', 'insertvideo']
        ],
    }),
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
        editor.execCommand('insertHtml', $(this).html());
    });
}